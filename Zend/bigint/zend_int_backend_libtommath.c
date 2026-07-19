/*
   +----------------------------------------------------------------------+
   | Copyright © The PHP Group and Contributors.                          |
   +----------------------------------------------------------------------+
   | This source file is subject to the Modified BSD License that is      |
   | bundled with this package in the file LICENSE, and is available      |
   | through the World Wide Web at <https://www.php.net/license/>.        |
   |                                                                      |
   | SPDX-License-Identifier: BSD-3-Clause                                |
   +----------------------------------------------------------------------+
   | Author: Ben Ramsey <ramsey@php.net>                                  |
   +----------------------------------------------------------------------+
*/

#include "zend.h"
#include "zend_int_backend_libtommath.h"

#include <string.h>

/* The libtommath calls below that can only fail on allocation are asserted
 * MP_OKAY rather than checked: the zend_mp_* allocator (see
 * zend_int_backend_libtommath_alloc.c) routes through emalloc/erealloc/ecalloc,
 * which bail out on OOM instead of returning NULL. */

ZEND_API zend_bigint *zend_bigint_from_long(zend_long v)
{
	zend_bigint *b = emalloc(sizeof(zend_bigint));
	mp_err err = mp_init_i64(&b->mp, (int64_t) v);
	ZEND_ASSERT(err == MP_OKAY);
	(void) err;
	return b;
}

/* mp_read_radix reads a NUL-terminated string and silently stops at the
 * first character it can't map to a digit, even mid-string. Validate the
 * whole span here first so trailing garbage is rejected instead of
 * truncated. */
static int zend_bigint_digit_value(char ch, int base)
{
	static const char digits[] =
		"0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz+/";
	const char *p;
	int val;

	if (base <= 36 && ch >= 'a' && ch <= 'z') {
		ch = (char) (ch - 'a' + 'A');
	}

	p = memchr(digits, ch, base <= 36 ? 36 : sizeof(digits) - 1);
	if (p == NULL) {
		return -1;
	}

	val = (int) (p - digits);
	return val < base ? val : -1;
}

ZEND_API zend_bigint *zend_bigint_from_string(const char *s, size_t len, int base)
{
	size_t start, i, copy_off, copy_len;
	char *buf;
	zend_bigint *b;

	if (len == 0) {
		return NULL;
	}

	start = (s[0] == '+' || s[0] == '-') ? 1 : 0;
	if (start == len) {
		return NULL;
	}

	for (i = start; i < len; i++) {
		if (zend_bigint_digit_value(s[i], base) < 0) {
			return NULL;
		}
	}

	/* mp_read_radix understands a leading '-' but not '+'; drop a '+'. */
	copy_off = (s[0] == '+') ? 1 : 0;
	copy_len = len - copy_off;
	buf = emalloc(copy_len + 1);
	memcpy(buf, s + copy_off, copy_len);
	buf[copy_len] = '\0';

	b = emalloc(sizeof(zend_bigint));
	mp_err err = mp_init(&b->mp);
	ZEND_ASSERT(err == MP_OKAY);
	(void) err;
	if (mp_read_radix(&b->mp, buf, base) != MP_OKAY) {
		mp_clear(&b->mp);
		efree(b);
		efree(buf);
		return NULL;
	}

	efree(buf);
	return b;
}

ZEND_API zend_bigint *zend_bigint_dup(const zend_bigint *b)
{
	zend_bigint *dup = emalloc(sizeof(zend_bigint));
	mp_err err = mp_init_copy(&dup->mp, &b->mp);
	ZEND_ASSERT(err == MP_OKAY);
	(void) err;
	return dup;
}

ZEND_API void zend_bigint_free(zend_bigint *b)
{
	mp_clear(&b->mp);
	efree(b);
}

ZEND_API bool zend_bigint_fits_long(const zend_bigint *b)
{
	int bits = mp_count_bits(&b->mp);

	if (bits <= 63) {
		return true;
	}

	/* ZEND_LONG_MIN's magnitude is 2**63: 64 bits with only the top bit set. */
	return bits == 64 && mp_isneg(&b->mp) && mp_cnt_lsb(&b->mp) == 63;
}

ZEND_API zend_long zend_bigint_to_long(const zend_bigint *b)
{
	return (zend_long) mp_get_i64(&b->mp);
}

ZEND_API int zend_bigint_sign(const zend_bigint *b)
{
	if (mp_iszero(&b->mp)) {
		return 0;
	}
	return mp_isneg(&b->mp) ? -1 : 1;
}

ZEND_API int zend_bigint_cmp(const zend_bigint *a, const zend_bigint *b)
{
	return (int) mp_cmp(&a->mp, &b->mp);
}

ZEND_API uint64_t zend_bigint_bit_length(const zend_bigint *b)
{
	return (uint64_t) mp_count_bits(&b->mp);
}

ZEND_API zend_string *zend_bigint_to_str(const zend_bigint *b)
{
	int size;
	size_t written;
	zend_string *str;
	mp_err err;

	err = mp_radix_size(&b->mp, 10, &size);
	ZEND_ASSERT(err == MP_OKAY);
	(void) err;

	str = zend_string_alloc((size_t) size - 1, 0);
	err = mp_to_radix(&b->mp, ZSTR_VAL(str), (size_t) size, &written, 10);
	ZEND_ASSERT(err == MP_OKAY);
	(void) err;
	ZSTR_LEN(str) = written - 1;
	ZSTR_VAL(str)[ZSTR_LEN(str)] = '\0';

	return str;
}

ZEND_API bool zend_bigint_radix_is_linear(int base)
{
	return base >= 2 && (base & (base - 1)) == 0;
}

ZEND_API bool zend_bigint_exceeds_digits(const zend_bigint *b, zend_long max_digits)
{
	uint64_t bits = zend_bigint_bit_length(b);
	uint64_t digits = bits * 30103 / 100000 + 1;

	return digits > (uint64_t) max_digits;
}

ZEND_API const char *zend_bigint_backend_name(void)
{
	return "libtommath";
}
