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
#include "zend_bigint.h"
#include "zend_exceptions.h"
#include "libtommath/tommath.h"

ZEND_API const char *zend_bigint_backend_name(void)
{
	return "libtommath";
}

ZEND_API zend_bigint *zend_bigint_init(void)
{
	zend_bigint *b = emalloc(sizeof(zend_bigint));
	GC_SET_REFCOUNT(b, 1);
	GC_TYPE_INFO(b) = GC_BIGINT;
	b->mp = emalloc(sizeof(mp_int));
	if (mp_init((mp_int *) b->mp) != MP_OKAY) {
		efree(b->mp);
		efree(b);
		zend_error_noreturn(E_ERROR, "Could not initialize bigint: mp_init failed");
	}
	return b;
}

ZEND_API zend_bigint *zend_bigint_init_from_long(zend_long value)
{
	zend_bigint *b = zend_bigint_init();
	mp_set_i64((mp_int *) b->mp, (int64_t) value);
	return b;
}

ZEND_API zend_bigint *zend_bigint_init_from_string_length(const char *str, size_t len, int base)
{
	/* mp_read_radix requires a NUL-terminated string */
	char *tmp = estrndup(str, len);
	zend_bigint *b = zend_bigint_init();
	mp_err err = mp_read_radix((mp_int *) b->mp, tmp, base);
	efree(tmp);
	if (err != MP_OKAY) {
		zend_bigint_free(b);
		return NULL;
	}
	return b;
}

ZEND_API zend_bigint *zend_bigint_dup(const zend_bigint *src)
{
	zend_bigint *b = zend_bigint_init();
	mp_copy((const mp_int *) src->mp, (mp_int *) b->mp);
	return b;
}

ZEND_API void zend_bigint_free(zend_bigint *big)
{
	mp_clear((mp_int *) big->mp);
	efree(big->mp);
	efree(big);
}

ZEND_API void zend_bigint_add(zend_bigint *out, const zend_bigint *op1, const zend_bigint *op2)
{
	mp_add((const mp_int *) op1->mp, (const mp_int *) op2->mp, (mp_int *) out->mp);
}

ZEND_API void zend_bigint_add_long(zend_bigint *out, const zend_bigint *op1, zend_long op2)
{
	mp_int tmp;
	mp_init(&tmp);
	mp_set_i64(&tmp, (int64_t) op2);
	mp_add((const mp_int *) op1->mp, &tmp, (mp_int *) out->mp);
	mp_clear(&tmp);
}

ZEND_API void zend_bigint_long_add_long(zend_bigint *out, zend_long op1, zend_long op2)
{
	mp_set_i64((mp_int *) out->mp, (int64_t) op1);
	zend_bigint_add_long(out, out, op2);
}

ZEND_API void zend_bigint_sub(zend_bigint *out, const zend_bigint *op1, const zend_bigint *op2)
{
	mp_sub((const mp_int *) op1->mp, (const mp_int *) op2->mp, (mp_int *) out->mp);
}

ZEND_API void zend_bigint_sub_long(zend_bigint *out, const zend_bigint *op1, zend_long op2)
{
	mp_int tmp;
	mp_init(&tmp);
	mp_set_i64(&tmp, (int64_t) op2);
	mp_sub((const mp_int *) op1->mp, &tmp, (mp_int *) out->mp);
	mp_clear(&tmp);
}

ZEND_API void zend_bigint_long_sub(zend_bigint *out, zend_long op1, const zend_bigint *op2)
{
	mp_int tmp;
	mp_init(&tmp);
	mp_set_i64(&tmp, (int64_t) op1);
	mp_sub(&tmp, (const mp_int *) op2->mp, (mp_int *) out->mp);
	mp_clear(&tmp);
}

ZEND_API void zend_bigint_long_sub_long(zend_bigint *out, zend_long op1, zend_long op2)
{
	mp_set_i64((mp_int *) out->mp, (int64_t) op1);
	zend_bigint_sub_long(out, out, op2);
}

ZEND_API void zend_bigint_mul(zend_bigint *out, const zend_bigint *op1, const zend_bigint *op2)
{
	mp_mul((const mp_int *) op1->mp, (const mp_int *) op2->mp, (mp_int *) out->mp);
}

ZEND_API void zend_bigint_mul_long(zend_bigint *out, const zend_bigint *op1, zend_long op2)
{
	mp_int tmp;
	mp_init(&tmp);
	mp_set_i64(&tmp, (int64_t) op2);
	mp_mul((const mp_int *) op1->mp, &tmp, (mp_int *) out->mp);
	mp_clear(&tmp);
}

ZEND_API void zend_bigint_long_mul_long(zend_bigint *out, zend_long op1, zend_long op2)
{
	mp_set_i64((mp_int *) out->mp, (int64_t) op1);
	zend_bigint_mul_long(out, out, op2);
}

ZEND_API void zend_bigint_divmod(zend_bigint *quot, zend_bigint *rem, const zend_bigint *op1, const zend_bigint *op2)
{
	mp_div((const mp_int *) op1->mp, (const mp_int *) op2->mp, (mp_int *) quot->mp, (mp_int *) rem->mp);
}

ZEND_API void zend_bigint_divmod_long(zend_bigint *quot, zend_bigint *rem, const zend_bigint *op1, zend_long op2)
{
	mp_int tmp;
	mp_init(&tmp);
	mp_set_i64(&tmp, (int64_t) op2);
	mp_div((const mp_int *) op1->mp, &tmp, (mp_int *) quot->mp, (mp_int *) rem->mp);
	mp_clear(&tmp);
}

ZEND_API void zend_bigint_long_divmod(zend_bigint *quot, zend_bigint *rem, zend_long op1, const zend_bigint *op2)
{
	mp_int tmp;
	mp_init(&tmp);
	mp_set_i64(&tmp, (int64_t) op1);
	mp_div(&tmp, (const mp_int *) op2->mp, (mp_int *) quot->mp, (mp_int *) rem->mp);
	mp_clear(&tmp);
}

/* PHP's "%" is C truncated division: the remainder takes the sign of the
 * dividend. This is the same as mp_div's remainder; mp_mod would instead floor
 * the result toward the divisor's sign. */
ZEND_API void zend_bigint_mod(zend_bigint *out, const zend_bigint *op1, const zend_bigint *op2)
{
	mp_div((const mp_int *) op1->mp, (const mp_int *) op2->mp, NULL, (mp_int *) out->mp);
}

ZEND_API void zend_bigint_mod_long(zend_bigint *out, const zend_bigint *op1, zend_long op2)
{
	mp_int tmp;
	mp_init(&tmp);
	mp_set_i64(&tmp, (int64_t) op2);
	mp_div((const mp_int *) op1->mp, &tmp, NULL, (mp_int *) out->mp);
	mp_clear(&tmp);
}

ZEND_API void zend_bigint_long_mod(zend_bigint *out, zend_long op1, const zend_bigint *op2)
{
	mp_int tmp;
	mp_init(&tmp);
	mp_set_i64(&tmp, (int64_t) op1);
	mp_div(&tmp, (const mp_int *) op2->mp, NULL, (mp_int *) out->mp);
	mp_clear(&tmp);
}

ZEND_API bool zend_bigint_can_pow_exponent(zend_long exp)
{
	ZEND_ASSERT(exp >= 0);
	/* mp_expt_n takes an int exponent */
	return exp <= INT_MAX;
}

/* out = base ** exp, for a non-negative exp. On success returns true with the
 * power in out. libtommath's mp_expt_n takes an int exponent, and an exponent
 * above INT_MAX is beyond this backend's reach (and the result would be far too
 * large to hold in memory); in that case it throws an ArithmeticError naming
 * the backend limit, leaves out untouched, and returns false. */
ZEND_API bool zend_bigint_pow_long(zend_bigint *out, const zend_bigint *base, zend_long exp)
{
	if (!zend_bigint_can_pow_exponent(exp)) {
		zend_throw_error(zend_ce_arithmetic_error,
			"The libtommath bigint backend cannot raise to an exponent greater than %d", INT_MAX);
		return false;
	}
	mp_expt_n((const mp_int *) base->mp, (int) exp, (mp_int *) out->mp);
	return true;
}

ZEND_API bool zend_bigint_long_pow_long(zend_bigint *out, zend_long base, zend_long exp)
{
	mp_set_i64((mp_int *) out->mp, (int64_t) base);
	return zend_bigint_pow_long(out, out, exp);
}

ZEND_API int zend_bigint_sign(const zend_bigint *big)
{
	if (mp_iszero((const mp_int *) big->mp)) {
		return 0;
	}
	return mp_isneg((const mp_int *) big->mp) ? -1 : 1;
}

ZEND_API bool zend_bigint_can_fit_long(const zend_bigint *big)
{
	/* fits if within [ZEND_LONG_MIN, ZEND_LONG_MAX] */
	mp_int min, max;
	bool ok;
	mp_init_multi(&min, &max, NULL);
	mp_set_i64(&min, (int64_t) ZEND_LONG_MIN);
	mp_set_i64(&max, (int64_t) ZEND_LONG_MAX);
	ok = (mp_cmp((const mp_int *) big->mp, &min) != MP_LT)
		&& (mp_cmp((const mp_int *) big->mp, &max) != MP_GT);
	mp_clear_multi(&min, &max, NULL);
	return ok;
}

ZEND_API zend_long zend_bigint_to_long(const zend_bigint *big)
{
	return (zend_long) mp_get_i64((const mp_int *) big->mp);
}

ZEND_API double zend_bigint_to_double(const zend_bigint *big)
{
	return mp_get_double((const mp_int *) big->mp);
}

ZEND_API int zend_bigint_cmp(const zend_bigint *a, const zend_bigint *b)
{
	mp_ord o = mp_cmp((const mp_int *) a->mp, (const mp_int *) b->mp);
	return o == MP_LT ? -1 : (o == MP_GT ? 1 : 0);
}

ZEND_API int zend_bigint_cmp_long(const zend_bigint *a, zend_long b)
{
	mp_int t;
	int r;
	mp_init(&t);
	mp_set_i64(&t, (int64_t) b);
	mp_ord o = mp_cmp((const mp_int *) a->mp, &t);
	r = o == MP_LT ? -1 : (o == MP_GT ? 1 : 0);
	mp_clear(&t);
	return r;
}

ZEND_API char *zend_bigint_to_string(const zend_bigint *big, size_t *len)
{
	int size = 0;
	mp_radix_size((const mp_int *) big->mp, 10, &size);
	/* size includes the NUL terminator */
	char *out = emalloc((size_t) size);
	size_t written = 0;
	mp_to_radix((const mp_int *) big->mp, out, (size_t) size, &written, 10);
	/* written includes NUL; use strlen as a version-robust alternative */
	*len = strlen(out);
	return out;
}
