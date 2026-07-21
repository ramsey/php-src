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

#ifndef ZEND_INT_H
#define ZEND_INT_H

#include "zend_int_backend.h"
#include "zend_multiply.h"

BEGIN_EXTERN_C()

/* Stores b in result as a canonical integer, demoting to an IS_LONG when the
 * value fits zend_long and wrapping it in an IS_BIGINT box otherwise. Takes
 * ownership of b either way. */
ZEND_API void zend_int_from_bigint(zval *result, zend_bigint *b);

/* Reports whether the integer zval holds a value that fits zend_long. Always
 * true for an IS_LONG; a boxed value is canonically too large. */
ZEND_API bool zend_int_fits_long(const zval *zv);

/* Writes the integer zval into out and returns true when it fits zend_long.
 * Returns false without throwing when a boxed value is too large to represent. */
ZEND_API bool zend_int_get_long(const zval *zv, zend_long *out);

/* Renders the integer zval as a decimal string. A value within max_digits
 * renders in full; a larger boxed value renders its leading max_digits
 * characters followed by "...(N digits)", where N is the full length. */
ZEND_API zend_string *zend_int_debug_str(const zval *zv, size_t max_digits);

/* Returns true if a + b overflows zend_long. Writes the wrapped result to
 * *r regardless; it is meaningful only when this returns false. */
static zend_always_inline bool zend_long_add_overflows(zend_long a, zend_long b, zend_long *r)
{
#if defined(PHP_HAVE_BUILTIN_SADDL_OVERFLOW) && SIZEOF_LONG == SIZEOF_ZEND_LONG
	long lr;
	bool ov = __builtin_saddl_overflow((long) a, (long) b, &lr);
	*r = (zend_long) lr;
	return ov;
#elif defined(PHP_HAVE_BUILTIN_SADDLL_OVERFLOW) && SIZEOF_LONG_LONG == SIZEOF_ZEND_LONG
	long long llr;
	bool ov = __builtin_saddll_overflow((long long) a, (long long) b, &llr);
	*r = (zend_long) llr;
	return ov;
#else
	zend_long s = (zend_long) ((zend_ulong) a + (zend_ulong) b);
	*r = s;
	return ((a ^ s) & (b ^ s)) < 0;
#endif
}

/* Returns true if a - b overflows zend_long. Writes the wrapped result to
 * *r regardless; it is meaningful only when this returns false. */
static zend_always_inline bool zend_long_sub_overflows(zend_long a, zend_long b, zend_long *r)
{
#if defined(PHP_HAVE_BUILTIN_SSUBL_OVERFLOW) && SIZEOF_LONG == SIZEOF_ZEND_LONG
	long lr;
	bool ov = __builtin_ssubl_overflow((long) a, (long) b, &lr);
	*r = (zend_long) lr;
	return ov;
#elif defined(PHP_HAVE_BUILTIN_SSUBLL_OVERFLOW) && SIZEOF_LONG_LONG == SIZEOF_ZEND_LONG
	long long llr;
	bool ov = __builtin_ssubll_overflow((long long) a, (long long) b, &llr);
	*r = (zend_long) llr;
	return ov;
#else
	zend_long s = (zend_long) ((zend_ulong) a - (zend_ulong) b);
	*r = s;
	return ((a ^ b) & (a ^ s)) < 0;
#endif
}

/* Cold out-of-line halves of zend_int_add/zend_int_sub below; the overflow
 * and boxed cases are handled here, not called directly. */
ZEND_API void zend_int_add_slow(zval *result, const zval *op1, const zval *op2);
ZEND_API void zend_int_sub_slow(zval *result, const zval *op1, const zval *op2);
ZEND_API void zend_int_mul_slow(zval *result, const zval *op1, const zval *op2);
ZEND_API void zend_int_neg_slow(zval *result, const zval *op1);
ZEND_API void zend_int_abs_slow(zval *result, const zval *op1);
ZEND_API void zend_int_div_trunc_slow(zval *result, const zval *op1, const zval *op2);
ZEND_API void zend_int_mod_slow(zval *result, const zval *op1, const zval *op2);

/* Value-op arithmetic on logical integers. Each stores a canonical integer
 * in result, an IS_LONG when the value fits zend_long and an IS_BIGINT box
 * otherwise; result may alias an operand. Operands must be logical integers
 * (debug-asserted). */
static zend_always_inline void zend_int_add(zval *result, const zval *op1, const zval *op2)
{
	ZEND_ASSERT(Z_IS_INT_P(op1) && Z_IS_INT_P(op2));
	if (EXPECTED(Z_TYPE_INFO_P(op1) == IS_LONG) && EXPECTED(Z_TYPE_INFO_P(op2) == IS_LONG)) {
		zend_long r;
		if (EXPECTED(!zend_long_add_overflows(Z_LVAL_P(op1), Z_LVAL_P(op2), &r))) {
			ZVAL_LONG(result, r);
			return;
		}
	}
	zend_int_add_slow(result, op1, op2);
}

static zend_always_inline void zend_int_sub(zval *result, const zval *op1, const zval *op2)
{
	ZEND_ASSERT(Z_IS_INT_P(op1) && Z_IS_INT_P(op2));
	if (EXPECTED(Z_TYPE_INFO_P(op1) == IS_LONG) && EXPECTED(Z_TYPE_INFO_P(op2) == IS_LONG)) {
		zend_long r;
		if (EXPECTED(!zend_long_sub_overflows(Z_LVAL_P(op1), Z_LVAL_P(op2), &r))) {
			ZVAL_LONG(result, r);
			return;
		}
	}
	zend_int_sub_slow(result, op1, op2);
}

static zend_always_inline void zend_int_mul(zval *result, const zval *op1, const zval *op2)
{
	ZEND_ASSERT(Z_IS_INT_P(op1) && Z_IS_INT_P(op2));
	if (EXPECTED(Z_TYPE_INFO_P(op1) == IS_LONG) && EXPECTED(Z_TYPE_INFO_P(op2) == IS_LONG)) {
		zend_long r, overflow;
		double d;
		ZEND_SIGNED_MULTIPLY_LONG(Z_LVAL_P(op1), Z_LVAL_P(op2), r, d, overflow);
		if (EXPECTED(!overflow)) {
			ZVAL_LONG(result, r);
			return;
		}
		(void) d;
	}
	zend_int_mul_slow(result, op1, op2);
}

static zend_always_inline void zend_int_neg(zval *result, const zval *op1)
{
	ZEND_ASSERT(Z_IS_INT_P(op1));
	if (EXPECTED(Z_TYPE_INFO_P(op1) == IS_LONG)) {
		if (EXPECTED(Z_LVAL_P(op1) != ZEND_LONG_MIN)) {
			ZVAL_LONG(result, -Z_LVAL_P(op1));
			return;
		}
	}
	zend_int_neg_slow(result, op1);
}

static zend_always_inline void zend_int_abs(zval *result, const zval *op1)
{
	ZEND_ASSERT(Z_IS_INT_P(op1));
	if (EXPECTED(Z_TYPE_INFO_P(op1) == IS_LONG)) {
		if (EXPECTED(Z_LVAL_P(op1) != ZEND_LONG_MIN)) {
			ZVAL_LONG(result, Z_LVAL_P(op1) < 0 ? -Z_LVAL_P(op1) : Z_LVAL_P(op1));
			return;
		}
	}
	zend_int_abs_slow(result, op1);
}

static zend_always_inline void zend_int_div_trunc(zval *result, const zval *op1, const zval *op2)
{
	ZEND_ASSERT(Z_IS_INT_P(op1) && Z_IS_INT_P(op2));
	if (EXPECTED(Z_TYPE_INFO_P(op1) == IS_LONG) && EXPECTED(Z_TYPE_INFO_P(op2) == IS_LONG)) {
		ZEND_ASSERT(Z_LVAL_P(op2) != 0);
		if (EXPECTED(!(Z_LVAL_P(op1) == ZEND_LONG_MIN && Z_LVAL_P(op2) == -1))) {
			ZVAL_LONG(result, Z_LVAL_P(op1) / Z_LVAL_P(op2));
			return;
		}
	}
	zend_int_div_trunc_slow(result, op1, op2);
}

static zend_always_inline void zend_int_mod(zval *result, const zval *op1, const zval *op2)
{
	ZEND_ASSERT(Z_IS_INT_P(op1) && Z_IS_INT_P(op2));
	if (EXPECTED(Z_TYPE_INFO_P(op1) == IS_LONG) && EXPECTED(Z_TYPE_INFO_P(op2) == IS_LONG)) {
		ZEND_ASSERT(Z_LVAL_P(op2) != 0);
		if (EXPECTED(Z_LVAL_P(op2) != -1)) {
			ZVAL_LONG(result, Z_LVAL_P(op1) % Z_LVAL_P(op2));
		} else {
			ZVAL_LONG(result, 0);
		}
		return;
	}
	zend_int_mod_slow(result, op1, op2);
}

END_EXTERN_C()

#endif
