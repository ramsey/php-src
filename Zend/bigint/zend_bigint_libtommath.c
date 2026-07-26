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
#include "zend_exceptions.h"
#include "zend_bigint_libtommath.h"

#include <limits.h>
#include <string.h>

/* The libtommath calls below that can only fail on allocation are asserted
 * MP_OKAY rather than checked. The zend_mp_* allocator (see
 * zend_bigint_libtommath_alloc.c) routes through emalloc/erealloc/ecalloc,
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
	int max_bits = SIZEOF_ZEND_LONG * 8 - 1;

	if (bits <= max_bits) {
		return true;
	}

	/* One bit past the positive range fits only ZEND_LONG_MIN, whose magnitude
	 * is 2**max_bits with that top bit set alone. */
	return bits == max_bits + 1 && mp_isneg(&b->mp) && mp_cnt_lsb(&b->mp) == max_bits;
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

ZEND_API int zend_bigint_cmp_long(const zend_bigint *a, zend_long b)
{
	mp_int t;
	mp_err err = mp_init(&t);
	ZEND_ASSERT(err == MP_OKAY);
	mp_set_i64(&t, (int64_t) b);
	int r = (int) mp_cmp(&a->mp, &t);
	mp_clear(&t);
	(void) err;
	return r;
}

ZEND_API bool zend_bigint_is_odd(const zend_bigint *b)
{
	return mp_isodd(&b->mp) == MP_YES;
}

ZEND_API uint64_t zend_bigint_bit_length(const zend_bigint *b)
{
	return (uint64_t) mp_count_bits(&b->mp);
}

ZEND_API double zend_bigint_to_double(const zend_bigint *b)
{
	return mp_get_double(&b->mp);
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

static zend_always_inline zend_bigint *zend_bigint_alloc(void)
{
	zend_bigint *b = emalloc(sizeof(zend_bigint));
	mp_err err = mp_init(&b->mp);
	ZEND_ASSERT(err == MP_OKAY);
	(void) err;
	return b;
}

/* The input is finite by precondition; mp_set_double truncates toward zero
 * and requires IEEE-754. */
ZEND_API zend_bigint *zend_bigint_from_double(double d)
{
	zend_bigint *out = zend_bigint_alloc();
	mp_err err = mp_set_double(&out->mp, d);
	ZEND_ASSERT(err == MP_OKAY);
	(void) err;
	return out;
}

ZEND_API zend_bigint *zend_bigint_add(const zend_bigint *a, const zend_bigint *b)
{
	zend_bigint *out = zend_bigint_alloc();
	mp_err err = mp_add(&a->mp, &b->mp, &out->mp);
	ZEND_ASSERT(err == MP_OKAY);
	(void) err;
	return out;
}

ZEND_API zend_bigint *zend_bigint_add_long(const zend_bigint *a, zend_long b)
{
	zend_bigint *out = zend_bigint_alloc();
	mp_int t;
	mp_err err = mp_init(&t);
	ZEND_ASSERT(err == MP_OKAY);
	mp_set_i64(&t, (int64_t) b);
	err = mp_add(&a->mp, &t, &out->mp);
	ZEND_ASSERT(err == MP_OKAY);
	mp_clear(&t);
	(void) err;
	return out;
}

ZEND_API zend_bigint *zend_bigint_long_add_long(zend_long a, zend_long b)
{
	zend_bigint *out = zend_bigint_alloc();
	mp_set_i64(&out->mp, (int64_t) a);

	mp_int t;
	mp_err err = mp_init(&t);
	ZEND_ASSERT(err == MP_OKAY);
	mp_set_i64(&t, (int64_t) b);
	err = mp_add(&out->mp, &t, &out->mp);
	ZEND_ASSERT(err == MP_OKAY);
	mp_clear(&t);
	(void) err;
	return out;
}

ZEND_API zend_bigint *zend_bigint_sub(const zend_bigint *a, const zend_bigint *b)
{
	zend_bigint *out = zend_bigint_alloc();
	mp_err err = mp_sub(&a->mp, &b->mp, &out->mp);
	ZEND_ASSERT(err == MP_OKAY);
	(void) err;
	return out;
}

ZEND_API zend_bigint *zend_bigint_sub_long(const zend_bigint *a, zend_long b)
{
	zend_bigint *out = zend_bigint_alloc();
	mp_int t;
	mp_err err = mp_init(&t);
	ZEND_ASSERT(err == MP_OKAY);
	mp_set_i64(&t, (int64_t) b);
	err = mp_sub(&a->mp, &t, &out->mp);
	ZEND_ASSERT(err == MP_OKAY);
	mp_clear(&t);
	(void) err;
	return out;
}

ZEND_API zend_bigint *zend_bigint_long_sub(zend_long a, const zend_bigint *b)
{
	zend_bigint *out = zend_bigint_alloc();
	mp_int t;
	mp_err err = mp_init(&t);
	ZEND_ASSERT(err == MP_OKAY);
	mp_set_i64(&t, (int64_t) a);
	err = mp_sub(&t, &b->mp, &out->mp);
	ZEND_ASSERT(err == MP_OKAY);
	mp_clear(&t);
	(void) err;
	return out;
}

ZEND_API zend_bigint *zend_bigint_long_sub_long(zend_long a, zend_long b)
{
	zend_bigint *out = zend_bigint_alloc();
	mp_int ta, tb;
	mp_err err = mp_init(&ta);
	ZEND_ASSERT(err == MP_OKAY);
	err = mp_init(&tb);
	ZEND_ASSERT(err == MP_OKAY);
	mp_set_i64(&ta, (int64_t) a);
	mp_set_i64(&tb, (int64_t) b);
	err = mp_sub(&ta, &tb, &out->mp);
	ZEND_ASSERT(err == MP_OKAY);
	mp_clear(&ta);
	mp_clear(&tb);
	(void) err;
	return out;
}

ZEND_API zend_bigint *zend_bigint_mul(const zend_bigint *a, const zend_bigint *b)
{
	zend_bigint *out = zend_bigint_alloc();
	mp_err err = mp_mul(&a->mp, &b->mp, &out->mp);
	ZEND_ASSERT(err == MP_OKAY);
	(void) err;
	return out;
}

ZEND_API zend_bigint *zend_bigint_mul_long(const zend_bigint *a, zend_long b)
{
	zend_bigint *out = zend_bigint_alloc();
	mp_int t;
	mp_err err = mp_init(&t);
	ZEND_ASSERT(err == MP_OKAY);
	mp_set_i64(&t, (int64_t) b);
	err = mp_mul(&a->mp, &t, &out->mp);
	ZEND_ASSERT(err == MP_OKAY);
	mp_clear(&t);
	(void) err;
	return out;
}

ZEND_API zend_bigint *zend_bigint_long_mul_long(zend_long a, zend_long b)
{
	zend_bigint *out = zend_bigint_alloc();
	mp_set_i64(&out->mp, (int64_t) a);

	mp_int t;
	mp_err err = mp_init(&t);
	ZEND_ASSERT(err == MP_OKAY);
	mp_set_i64(&t, (int64_t) b);
	err = mp_mul(&out->mp, &t, &out->mp);
	ZEND_ASSERT(err == MP_OKAY);
	mp_clear(&t);
	(void) err;
	return out;
}

ZEND_API zend_bigint *zend_bigint_neg(const zend_bigint *a)
{
	zend_bigint *out = zend_bigint_alloc();
	mp_err err = mp_neg(&a->mp, &out->mp);
	ZEND_ASSERT(err == MP_OKAY);
	(void) err;
	return out;
}

ZEND_API zend_bigint *zend_bigint_abs(const zend_bigint *a)
{
	zend_bigint *out = zend_bigint_alloc();
	mp_err err = mp_abs(&a->mp, &out->mp);
	ZEND_ASSERT(err == MP_OKAY);
	(void) err;
	return out;
}

/* PHP's "%" is C truncated division: the remainder takes the sign of the
 * dividend. This is the same as mp_div's remainder; mp_mod would instead
 * floor the result toward the divisor's sign. */
ZEND_API void zend_bigint_divmod(const zend_bigint *a, const zend_bigint *b, zend_bigint **quot, zend_bigint **rem)
{
	*quot = zend_bigint_alloc();
	*rem = zend_bigint_alloc();
	mp_err err = mp_div(&a->mp, &b->mp, &(*quot)->mp, &(*rem)->mp);
	ZEND_ASSERT(err == MP_OKAY);
	(void) err;
}

ZEND_API void zend_bigint_divmod_long(const zend_bigint *a, zend_long b, zend_bigint **quot, zend_bigint **rem)
{
	*quot = zend_bigint_alloc();
	*rem = zend_bigint_alloc();
	mp_int t;
	mp_err err = mp_init(&t);
	ZEND_ASSERT(err == MP_OKAY);
	mp_set_i64(&t, (int64_t) b);
	err = mp_div(&a->mp, &t, &(*quot)->mp, &(*rem)->mp);
	ZEND_ASSERT(err == MP_OKAY);
	mp_clear(&t);
	(void) err;
}

ZEND_API void zend_bigint_long_divmod(zend_long a, const zend_bigint *b, zend_bigint **quot, zend_bigint **rem)
{
	*quot = zend_bigint_alloc();
	*rem = zend_bigint_alloc();
	mp_int t;
	mp_err err = mp_init(&t);
	ZEND_ASSERT(err == MP_OKAY);
	mp_set_i64(&t, (int64_t) a);
	err = mp_div(&t, &b->mp, &(*quot)->mp, &(*rem)->mp);
	ZEND_ASSERT(err == MP_OKAY);
	mp_clear(&t);
	(void) err;
}

ZEND_API zend_bigint *zend_bigint_mod(const zend_bigint *a, const zend_bigint *b)
{
	zend_bigint *out = zend_bigint_alloc();
	mp_err err = mp_div(&a->mp, &b->mp, NULL, &out->mp);
	ZEND_ASSERT(err == MP_OKAY);
	(void) err;
	return out;
}

ZEND_API zend_bigint *zend_bigint_mod_long(const zend_bigint *a, zend_long b)
{
	zend_bigint *out = zend_bigint_alloc();
	mp_int t;
	mp_err err = mp_init(&t);
	ZEND_ASSERT(err == MP_OKAY);
	mp_set_i64(&t, (int64_t) b);
	err = mp_div(&a->mp, &t, NULL, &out->mp);
	ZEND_ASSERT(err == MP_OKAY);
	mp_clear(&t);
	(void) err;
	return out;
}

ZEND_API zend_bigint *zend_bigint_long_mod(zend_long a, const zend_bigint *b)
{
	zend_bigint *out = zend_bigint_alloc();
	mp_int t;
	mp_err err = mp_init(&t);
	ZEND_ASSERT(err == MP_OKAY);
	mp_set_i64(&t, (int64_t) a);
	err = mp_div(&t, &b->mp, NULL, &out->mp);
	ZEND_ASSERT(err == MP_OKAY);
	mp_clear(&t);
	(void) err;
	return out;
}

/* Infinite two's-complement: ~x == -(x + 1), matching mp_complement's own
 * definition rather than a fixed-width bitwise flip. */
ZEND_API zend_bigint *zend_bigint_not(const zend_bigint *a)
{
	zend_bigint *out = zend_bigint_alloc();
	mp_err err = mp_complement(&a->mp, &out->mp);
	ZEND_ASSERT(err == MP_OKAY);
	(void) err;
	return out;
}

ZEND_API zend_bigint *zend_bigint_and(const zend_bigint *a, const zend_bigint *b)
{
	zend_bigint *out = zend_bigint_alloc();
	mp_err err = mp_and(&a->mp, &b->mp, &out->mp);
	ZEND_ASSERT(err == MP_OKAY);
	(void) err;
	return out;
}

ZEND_API zend_bigint *zend_bigint_and_long(const zend_bigint *a, zend_long b)
{
	zend_bigint *out = zend_bigint_alloc();
	mp_int t;
	mp_err err = mp_init(&t);
	ZEND_ASSERT(err == MP_OKAY);
	mp_set_i64(&t, (int64_t) b);
	err = mp_and(&a->mp, &t, &out->mp);
	ZEND_ASSERT(err == MP_OKAY);
	mp_clear(&t);
	(void) err;
	return out;
}

ZEND_API zend_bigint *zend_bigint_or(const zend_bigint *a, const zend_bigint *b)
{
	zend_bigint *out = zend_bigint_alloc();
	mp_err err = mp_or(&a->mp, &b->mp, &out->mp);
	ZEND_ASSERT(err == MP_OKAY);
	(void) err;
	return out;
}

ZEND_API zend_bigint *zend_bigint_or_long(const zend_bigint *a, zend_long b)
{
	zend_bigint *out = zend_bigint_alloc();
	mp_int t;
	mp_err err = mp_init(&t);
	ZEND_ASSERT(err == MP_OKAY);
	mp_set_i64(&t, (int64_t) b);
	err = mp_or(&a->mp, &t, &out->mp);
	ZEND_ASSERT(err == MP_OKAY);
	mp_clear(&t);
	(void) err;
	return out;
}

ZEND_API zend_bigint *zend_bigint_xor(const zend_bigint *a, const zend_bigint *b)
{
	zend_bigint *out = zend_bigint_alloc();
	mp_err err = mp_xor(&a->mp, &b->mp, &out->mp);
	ZEND_ASSERT(err == MP_OKAY);
	(void) err;
	return out;
}

ZEND_API zend_bigint *zend_bigint_xor_long(const zend_bigint *a, zend_long b)
{
	zend_bigint *out = zend_bigint_alloc();
	mp_int t;
	mp_err err = mp_init(&t);
	ZEND_ASSERT(err == MP_OKAY);
	mp_set_i64(&t, (int64_t) b);
	err = mp_xor(&a->mp, &t, &out->mp);
	ZEND_ASSERT(err == MP_OKAY);
	mp_clear(&t);
	(void) err;
	return out;
}

ZEND_API bool zend_bigint_can_shift_left(zend_long bits)
{
	ZEND_ASSERT(bits >= 0);
	/* mp_mul_2d takes an int bit count. */
	return bits <= INT_MAX;
}

/* Throws and returns false when bit size is out of the backend's reach. */
static bool zend_bigint_shift_left_reach_check(zend_long bits, const zend_bigint *bits_big)
{
	if (bits_big != NULL || !zend_bigint_can_shift_left(bits)) {
		zend_throw_error(zend_ce_arithmetic_error,
			"The libtommath bigint backend cannot shift left by more than %d bits", INT_MAX);
		return false;
	}
	return true;
}

ZEND_API bool zend_bigint_shift_left(const zend_bigint *a, zend_long bits, const zend_bigint *bits_big, zend_bigint **out)
{
	if (!zend_bigint_shift_left_reach_check(bits, bits_big)) {
		return false;
	}
	*out = zend_bigint_alloc();
	mp_err err = mp_mul_2d(&a->mp, (int) bits, &(*out)->mp);
	ZEND_ASSERT(err == MP_OKAY);
	(void) err;
	return true;
}

ZEND_API bool zend_bigint_long_shift_left(zend_long a, zend_long bits, const zend_bigint *bits_big, zend_bigint **out)
{
	if (!zend_bigint_shift_left_reach_check(bits, bits_big)) {
		return false;
	}
	*out = zend_bigint_alloc();
	mp_set_i64(&(*out)->mp, (int64_t) a);
	mp_err err = mp_mul_2d(&(*out)->mp, (int) bits, &(*out)->mp);
	ZEND_ASSERT(err == MP_OKAY);
	(void) err;
	return true;
}

ZEND_API zend_bigint *zend_bigint_shift_right(const zend_bigint *a, zend_long bits, const zend_bigint *bits_big)
{
	zend_bigint *out = zend_bigint_alloc();

	if (bits_big != NULL || bits > INT_MAX) {
		/* The count shifts past every bit. An arithmetic right shift saturates
		 * to 0 for a non-negative operand, -1 for a negative one. */
		if (mp_isneg(&a->mp)) {
			mp_set_i64(&out->mp, -1);
		} else {
			mp_zero(&out->mp);
		}
		return out;
	}

	ZEND_ASSERT(bits >= 0);
	mp_err err = mp_signed_rsh(&a->mp, (int) bits, &out->mp);
	ZEND_ASSERT(err == MP_OKAY);
	(void) err;
	return out;
}

ZEND_API bool zend_bigint_can_pow(zend_long exp)
{
	ZEND_ASSERT(exp >= 0);
	/* mp_expt_n takes an int exponent. */
	return exp <= INT_MAX;
}

/* out = base ** exp, for a non-negative exponent (exp_big when non-NULL, else
 * exp). Throws and returns false when the exponent is out of the backend's
 * reach. */
ZEND_API bool zend_bigint_pow(const zend_bigint *base, zend_long exp, const zend_bigint *exp_big, zend_bigint **out)
{
	if (exp_big != NULL || !zend_bigint_can_pow(exp)) {
		zend_throw_error(zend_ce_arithmetic_error,
			"The libtommath bigint backend cannot raise to an exponent greater than %d", INT_MAX);
		return false;
	}
	*out = zend_bigint_alloc();
	mp_err err = mp_expt_n(&base->mp, (int) exp, &(*out)->mp);
	ZEND_ASSERT(err == MP_OKAY);
	(void) err;
	return true;
}

/* The persist form is a flat, self-contained blob: the box struct followed by
 * its used digits inline. The digit pointer lands at a fixed offset just past
 * the struct, so zend_bigint_persist_relink can recompute it from the box
 * address alone after the blob is remapped. */

ZEND_API size_t zend_bigint_persist_size(const zend_bigint *b)
{
	return sizeof(zend_bigint) + (size_t) b->mp.used * sizeof(mp_digit);
}

ZEND_API zend_bigint *zend_bigint_persist_copy(void *dst, const zend_bigint *b)
{
	zend_bigint *copy = (zend_bigint *) dst;
	mp_digit *digits = (mp_digit *) (copy + 1);
	int used = b->mp.used;

	copy->mp = b->mp;
	copy->mp.dp = digits;
	copy->mp.alloc = used;
	memcpy(digits, b->mp.dp, (size_t) used * sizeof(mp_digit));

	GC_SET_REFCOUNT(copy, 2);
	GC_TYPE_INFO(copy) = GC_BIGINT | (GC_IMMUTABLE << GC_FLAGS_SHIFT);

	return copy;
}

ZEND_API void zend_bigint_persist_detach(zend_bigint *b)
{
	b->mp.dp = NULL;
}

ZEND_API void zend_bigint_persist_relink(zend_bigint *b)
{
	b->mp.dp = (mp_digit *) (b + 1);
}
