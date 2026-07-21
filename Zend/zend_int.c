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
#include "zend_int.h"
#include "zend_smart_str.h"

/* A zend_bigint is opaque here on purpose; this file never sees the backend
 * struct layout. The cross-backend contract guarantees a zend_bigint begins
 * with its zend_refcounted_h, so GC headers are reached through
 * zend_refcounted casts. */

ZEND_API void zend_int_from_bigint(zval *result, zend_bigint *b)
{
	zend_refcounted *ref;

	if (zend_bigint_fits_long(b)) {
		ZVAL_LONG(result, zend_bigint_to_long(b));
		zend_bigint_free(b);
		return;
	}

	ref = (zend_refcounted *) b;
	GC_SET_REFCOUNT(ref, 1);
	GC_TYPE_INFO(ref) = GC_BIGINT;

	ZVAL_BIGINT(result, b);
}

ZEND_API bool zend_int_fits_long(const zval *zv)
{
	ZEND_ASSERT(Z_IS_INT_P(zv));

	if (Z_TYPE_P(zv) == IS_LONG) {
		return true;
	}

	return zend_bigint_fits_long(Z_BIG_P(zv));
}

ZEND_API bool zend_int_get_long(const zval *zv, zend_long *out)
{
	ZEND_ASSERT(Z_IS_INT_P(zv));

	if (Z_TYPE_P(zv) == IS_LONG) {
		*out = Z_LVAL_P(zv);
		return true;
	}

	if (!zend_bigint_fits_long(Z_BIG_P(zv))) {
		return false;
	}

	*out = zend_bigint_to_long(Z_BIG_P(zv));
	return true;
}

ZEND_API zend_string *zend_int_debug_str(const zval *zv, size_t max_digits)
{
	ZEND_ASSERT(Z_IS_INT_P(zv));

	if (Z_TYPE_P(zv) == IS_LONG) {
		return zend_long_to_str(Z_LVAL_P(zv));
	}

	zend_bigint *b = Z_BIG_P(zv);
	zend_string *full = zend_bigint_to_str(b);

	if (!zend_bigint_exceeds_digits(b, (zend_long) max_digits)) {
		return full;
	}

	size_t lead = MIN(max_digits, ZSTR_LEN(full));
	smart_str buf = {0};
	smart_str_appendl(&buf, ZSTR_VAL(full), lead);
	smart_str_appends(&buf, "...(");
	smart_str_append_long(&buf, (zend_long) ZSTR_LEN(full));
	smart_str_appends(&buf, " digits)");

	zend_string_release(full);

	return smart_str_extract(&buf);
}

ZEND_API void zend_int_add_slow(zval *result, const zval *op1, const zval *op2)
{
	zend_bigint *b;
	if (Z_TYPE_P(op1) == IS_LONG) {
		if (Z_TYPE_P(op2) == IS_LONG) {
			b = zend_bigint_long_add_long(Z_LVAL_P(op1), Z_LVAL_P(op2));
		} else {
			b = zend_bigint_add_long(Z_BIG_P(op2), Z_LVAL_P(op1));
		}
	} else if (Z_TYPE_P(op2) == IS_LONG) {
		b = zend_bigint_add_long(Z_BIG_P(op1), Z_LVAL_P(op2));
	} else {
		b = zend_bigint_add(Z_BIG_P(op1), Z_BIG_P(op2));
	}
	zend_int_from_bigint(result, b);
}

ZEND_API void zend_int_sub_slow(zval *result, const zval *op1, const zval *op2)
{
	zend_bigint *b;
	if (Z_TYPE_P(op1) == IS_LONG) {
		if (Z_TYPE_P(op2) == IS_LONG) {
			b = zend_bigint_long_sub_long(Z_LVAL_P(op1), Z_LVAL_P(op2));
		} else {
			b = zend_bigint_long_sub(Z_LVAL_P(op1), Z_BIG_P(op2));
		}
	} else if (Z_TYPE_P(op2) == IS_LONG) {
		b = zend_bigint_sub_long(Z_BIG_P(op1), Z_LVAL_P(op2));
	} else {
		b = zend_bigint_sub(Z_BIG_P(op1), Z_BIG_P(op2));
	}
	zend_int_from_bigint(result, b);
}

ZEND_API void zend_int_mul_slow(zval *result, const zval *op1, const zval *op2)
{
	zend_bigint *b;
	if (Z_TYPE_P(op1) == IS_LONG) {
		if (Z_TYPE_P(op2) == IS_LONG) {
			b = zend_bigint_long_mul_long(Z_LVAL_P(op1), Z_LVAL_P(op2));
		} else {
			b = zend_bigint_mul_long(Z_BIG_P(op2), Z_LVAL_P(op1));
		}
	} else if (Z_TYPE_P(op2) == IS_LONG) {
		b = zend_bigint_mul_long(Z_BIG_P(op1), Z_LVAL_P(op2));
	} else {
		b = zend_bigint_mul(Z_BIG_P(op1), Z_BIG_P(op2));
	}
	zend_int_from_bigint(result, b);
}

ZEND_API void zend_int_neg_slow(zval *result, const zval *op1)
{
	zend_bigint *b;
	if (Z_TYPE_P(op1) == IS_LONG) {
		zend_bigint *tmp = zend_bigint_from_long(Z_LVAL_P(op1));
		b = zend_bigint_neg(tmp);
		zend_bigint_free(tmp);
	} else {
		b = zend_bigint_neg(Z_BIG_P(op1));
	}
	zend_int_from_bigint(result, b);
}

ZEND_API void zend_int_abs_slow(zval *result, const zval *op1)
{
	zend_bigint *b;
	if (Z_TYPE_P(op1) == IS_LONG) {
		zend_bigint *tmp = zend_bigint_from_long(Z_LVAL_P(op1));
		b = zend_bigint_abs(tmp);
		zend_bigint_free(tmp);
	} else {
		b = zend_bigint_abs(Z_BIG_P(op1));
	}
	zend_int_from_bigint(result, b);
}

ZEND_API void zend_int_div_trunc_slow(zval *result, const zval *op1, const zval *op2)
{
	zend_bigint *quot, *rem;
	if (Z_TYPE_P(op1) == IS_LONG) {
		if (Z_TYPE_P(op2) == IS_LONG) {
			zend_bigint *tmp = zend_bigint_from_long(Z_LVAL_P(op1));
			zend_bigint_divmod_long(tmp, Z_LVAL_P(op2), &quot, &rem);
			zend_bigint_free(tmp);
		} else {
			zend_bigint_long_divmod(Z_LVAL_P(op1), Z_BIG_P(op2), &quot, &rem);
		}
	} else if (Z_TYPE_P(op2) == IS_LONG) {
		zend_bigint_divmod_long(Z_BIG_P(op1), Z_LVAL_P(op2), &quot, &rem);
	} else {
		zend_bigint_divmod(Z_BIG_P(op1), Z_BIG_P(op2), &quot, &rem);
	}
	zend_bigint_free(rem);
	zend_int_from_bigint(result, quot);
}

ZEND_API void zend_int_mod_slow(zval *result, const zval *op1, const zval *op2)
{
	zend_bigint *b;
	if (Z_TYPE_P(op1) == IS_LONG) {
		if (Z_TYPE_P(op2) == IS_LONG) {
			zend_bigint *tmp = zend_bigint_from_long(Z_LVAL_P(op1));
			b = zend_bigint_mod_long(tmp, Z_LVAL_P(op2));
			zend_bigint_free(tmp);
		} else {
			b = zend_bigint_long_mod(Z_LVAL_P(op1), Z_BIG_P(op2));
		}
	} else if (Z_TYPE_P(op2) == IS_LONG) {
		b = zend_bigint_mod_long(Z_BIG_P(op1), Z_LVAL_P(op2));
	} else {
		b = zend_bigint_mod(Z_BIG_P(op1), Z_BIG_P(op2));
	}
	zend_int_from_bigint(result, b);
}

ZEND_API void zend_int_and_slow(zval *result, const zval *op1, const zval *op2)
{
	zend_bigint *b;
	if (Z_TYPE_P(op1) == IS_LONG) {
		b = zend_bigint_and_long(Z_BIG_P(op2), Z_LVAL_P(op1));
	} else if (Z_TYPE_P(op2) == IS_LONG) {
		b = zend_bigint_and_long(Z_BIG_P(op1), Z_LVAL_P(op2));
	} else {
		b = zend_bigint_and(Z_BIG_P(op1), Z_BIG_P(op2));
	}
	zend_int_from_bigint(result, b);
}

ZEND_API void zend_int_or_slow(zval *result, const zval *op1, const zval *op2)
{
	zend_bigint *b;
	if (Z_TYPE_P(op1) == IS_LONG) {
		b = zend_bigint_or_long(Z_BIG_P(op2), Z_LVAL_P(op1));
	} else if (Z_TYPE_P(op2) == IS_LONG) {
		b = zend_bigint_or_long(Z_BIG_P(op1), Z_LVAL_P(op2));
	} else {
		b = zend_bigint_or(Z_BIG_P(op1), Z_BIG_P(op2));
	}
	zend_int_from_bigint(result, b);
}

ZEND_API void zend_int_xor_slow(zval *result, const zval *op1, const zval *op2)
{
	zend_bigint *b;
	if (Z_TYPE_P(op1) == IS_LONG) {
		b = zend_bigint_xor_long(Z_BIG_P(op2), Z_LVAL_P(op1));
	} else if (Z_TYPE_P(op2) == IS_LONG) {
		b = zend_bigint_xor_long(Z_BIG_P(op1), Z_LVAL_P(op2));
	} else {
		b = zend_bigint_xor(Z_BIG_P(op1), Z_BIG_P(op2));
	}
	zend_int_from_bigint(result, b);
}

ZEND_API zend_result zend_int_shift_left_slow(zval *result, const zval *op1, const zval *op2)
{
	zend_long bits = 0;
	const zend_bigint *bits_big = NULL;
	if (Z_TYPE_P(op2) == IS_LONG) {
		bits = Z_LVAL_P(op2);
	} else {
		bits_big = Z_BIG_P(op2);
	}

	zend_bigint *out;
	bool ok;
	if (Z_TYPE_P(op1) == IS_LONG) {
		ok = zend_bigint_long_shift_left(Z_LVAL_P(op1), bits, bits_big, &out);
	} else {
		ok = zend_bigint_shift_left(Z_BIG_P(op1), bits, bits_big, &out);
	}
	if (!ok) {
		return FAILURE;
	}
	zend_int_from_bigint(result, out);
	return SUCCESS;
}

ZEND_API void zend_int_shift_right_slow(zval *result, const zval *op1, const zval *op2)
{
	zend_long bits = 0;
	const zend_bigint *bits_big = NULL;
	if (Z_TYPE_P(op2) == IS_LONG) {
		bits = Z_LVAL_P(op2);
	} else {
		bits_big = Z_BIG_P(op2);
	}

	zend_bigint *b;
	if (Z_TYPE_P(op1) == IS_LONG) {
		zend_bigint *tmp = zend_bigint_from_long(Z_LVAL_P(op1));
		b = zend_bigint_shift_right(tmp, bits, bits_big);
		zend_bigint_free(tmp);
	} else {
		b = zend_bigint_shift_right(Z_BIG_P(op1), bits, bits_big);
	}
	zend_int_from_bigint(result, b);
}

/* A boxed operand cannot equal a long operand. A value that fits zend_long is
 * never boxed. So long-vs.-box and box-vs.-long reduce to zend_bigint_cmp_long
 * (negated for long-vs.-box), and box-vs.-box reduces to zend_bigint_cmp. */
ZEND_API int zend_int_cmp_slow(const zval *op1, const zval *op2)
{
	if (Z_TYPE_P(op1) == IS_LONG) {
		return -zend_bigint_cmp_long(Z_BIG_P(op2), Z_LVAL_P(op1));
	}
	if (Z_TYPE_P(op2) == IS_LONG) {
		return zend_bigint_cmp_long(Z_BIG_P(op1), Z_LVAL_P(op2));
	}
	return zend_bigint_cmp(Z_BIG_P(op1), Z_BIG_P(op2));
}

ZEND_API void zend_int_from_double(zval *result, double d)
{
	ZEND_ASSERT(zend_finite(d));
	zend_int_from_bigint(result, zend_bigint_from_double(d));
}

ZEND_API zend_result zend_int_pow_slow(zval *result, const zval *op1, const zval *op2)
{
	zend_bigint *base = Z_TYPE_P(op1) == IS_LONG
		? zend_bigint_from_long(Z_LVAL_P(op1))
		: zend_bigint_dup(Z_BIG_P(op1));

	zend_long exp = 0;
	const zend_bigint *exp_big = NULL;
	if (Z_TYPE_P(op2) == IS_LONG) {
		exp = Z_LVAL_P(op2);
	} else {
		exp_big = Z_BIG_P(op2);
	}

	zend_bigint *out;
	if (!zend_bigint_pow(base, exp, exp_big, &out)) {
		zend_bigint_free(base);
		return FAILURE;
	}
	zend_bigint_free(base);
	zend_int_from_bigint(result, out);
	return SUCCESS;
}
