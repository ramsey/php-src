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
ZEND_API void zend_int_and_slow(zval *result, const zval *op1, const zval *op2);
ZEND_API void zend_int_or_slow(zval *result, const zval *op1, const zval *op2);
ZEND_API void zend_int_xor_slow(zval *result, const zval *op1, const zval *op2);
ZEND_API zend_result zend_int_shift_left_slow(zval *result, const zval *op1, const zval *op2);
ZEND_API void zend_int_shift_right_slow(zval *result, const zval *op1, const zval *op2);
ZEND_API zend_result zend_int_pow_slow(zval *result, const zval *op1, const zval *op2);
ZEND_API int zend_int_cmp_slow(const zval *op1, const zval *op2);

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

static zend_always_inline void zend_int_and(zval *result, const zval *op1, const zval *op2)
{
	ZEND_ASSERT(Z_IS_INT_P(op1) && Z_IS_INT_P(op2));
	if (EXPECTED(Z_TYPE_INFO_P(op1) == IS_LONG) && EXPECTED(Z_TYPE_INFO_P(op2) == IS_LONG)) {
		ZVAL_LONG(result, Z_LVAL_P(op1) & Z_LVAL_P(op2));
		return;
	}
	zend_int_and_slow(result, op1, op2);
}

static zend_always_inline void zend_int_or(zval *result, const zval *op1, const zval *op2)
{
	ZEND_ASSERT(Z_IS_INT_P(op1) && Z_IS_INT_P(op2));
	if (EXPECTED(Z_TYPE_INFO_P(op1) == IS_LONG) && EXPECTED(Z_TYPE_INFO_P(op2) == IS_LONG)) {
		ZVAL_LONG(result, Z_LVAL_P(op1) | Z_LVAL_P(op2));
		return;
	}
	zend_int_or_slow(result, op1, op2);
}

static zend_always_inline void zend_int_xor(zval *result, const zval *op1, const zval *op2)
{
	ZEND_ASSERT(Z_IS_INT_P(op1) && Z_IS_INT_P(op2));
	if (EXPECTED(Z_TYPE_INFO_P(op1) == IS_LONG) && EXPECTED(Z_TYPE_INFO_P(op2) == IS_LONG)) {
		ZVAL_LONG(result, Z_LVAL_P(op1) ^ Z_LVAL_P(op2));
		return;
	}
	zend_int_xor_slow(result, op1, op2);
}

static zend_always_inline void zend_int_not(zval *result, const zval *op1)
{
	ZEND_ASSERT(Z_IS_INT_P(op1));
	if (EXPECTED(Z_TYPE_INFO_P(op1) == IS_LONG)) {
		ZVAL_LONG(result, ~Z_LVAL_P(op1));
		return;
	}
	zend_int_from_bigint(result, zend_bigint_not(Z_BIG_P(op1)));
}

/* shift_left returns a zend_result and fails when the backend's over-reach
 * ArithmeticError has been thrown; shift_right instead saturates to 0 or -1
 * once the count exceeds the backend's reach. Both require a non-negative
 * count (debug-asserted). */

static zend_always_inline zend_result zend_int_shift_left(zval *result, const zval *op1, const zval *op2)
{
	ZEND_ASSERT(Z_IS_INT_P(op1) && Z_IS_INT_P(op2));
	ZEND_ASSERT(Z_TYPE_INFO_P(op2) == IS_LONG ? Z_LVAL_P(op2) >= 0 : zend_bigint_sign(Z_BIG_P(op2)) >= 0);
	if (EXPECTED(Z_TYPE_INFO_P(op1) == IS_LONG) && EXPECTED(Z_TYPE_INFO_P(op2) == IS_LONG)) {
		zend_long n = Z_LVAL_P(op2);
		if (EXPECTED((zend_ulong) n < (zend_ulong) (SIZEOF_ZEND_LONG * 8 - 1))
			&& EXPECTED((Z_LVAL_P(op1) >> (SIZEOF_ZEND_LONG * 8 - 1 - n)) == (Z_LVAL_P(op1) >> (SIZEOF_ZEND_LONG * 8 - 1)))) {
			ZVAL_LONG(result, Z_LVAL_P(op1) << n);
			return SUCCESS;
		}
	}
	return zend_int_shift_left_slow(result, op1, op2);
}

static zend_always_inline void zend_int_shift_right(zval *result, const zval *op1, const zval *op2)
{
	ZEND_ASSERT(Z_IS_INT_P(op1) && Z_IS_INT_P(op2));
	ZEND_ASSERT(Z_TYPE_INFO_P(op2) == IS_LONG ? Z_LVAL_P(op2) >= 0 : zend_bigint_sign(Z_BIG_P(op2)) >= 0);
	if (EXPECTED(Z_TYPE_INFO_P(op1) == IS_LONG) && EXPECTED(Z_TYPE_INFO_P(op2) == IS_LONG)) {
		zend_long n = Z_LVAL_P(op2);
		ZVAL_LONG(result, Z_LVAL_P(op1) >> MIN(n, SIZEOF_ZEND_LONG * 8 - 1));
		return;
	}
	zend_int_shift_right_slow(result, op1, op2);
}

/* pow returns a zend_result and fails when the backend's over-reach
 * ArithmeticError has been thrown. The exponent must be non-negative
 * (debug-asserted). */
static zend_always_inline zend_result zend_int_pow(zval *result, const zval *op1, const zval *op2)
{
	ZEND_ASSERT(Z_IS_INT_P(op1) && Z_IS_INT_P(op2));
	ZEND_ASSERT(Z_TYPE_INFO_P(op2) == IS_LONG ? Z_LVAL_P(op2) >= 0 : zend_bigint_sign(Z_BIG_P(op2)) >= 0);
	if (Z_TYPE_INFO_P(op2) == IS_LONG && Z_LVAL_P(op2) == 0) {
		ZVAL_LONG(result, 1);
		return SUCCESS;
	}
	return zend_int_pow_slow(result, op1, op2);
}

/* Queries about a logical integer operand, returning a value rather than
 * writing to a result zval. Operands must be logical integers (debug-asserted). */

static zend_always_inline int zend_int_cmp(const zval *op1, const zval *op2)
{
	ZEND_ASSERT(Z_IS_INT_P(op1) && Z_IS_INT_P(op2));
	if (EXPECTED(Z_TYPE_INFO_P(op1) == IS_LONG) && EXPECTED(Z_TYPE_INFO_P(op2) == IS_LONG)) {
		return Z_LVAL_P(op1) < Z_LVAL_P(op2) ? -1 : (Z_LVAL_P(op1) > Z_LVAL_P(op2) ? 1 : 0);
	}
	return zend_int_cmp_slow(op1, op2);
}

static zend_always_inline int zend_int_cmp_long(const zval *op, zend_long n)
{
	ZEND_ASSERT(Z_IS_INT_P(op));
	if (EXPECTED(Z_TYPE_INFO_P(op) == IS_LONG)) {
		return Z_LVAL_P(op) < n ? -1 : (Z_LVAL_P(op) > n ? 1 : 0);
	}
	return zend_bigint_cmp_long(Z_BIG_P(op), n);
}

static zend_always_inline int zend_int_sign(const zval *op)
{
	ZEND_ASSERT(Z_IS_INT_P(op));
	if (EXPECTED(Z_TYPE_INFO_P(op) == IS_LONG)) {
		return (Z_LVAL_P(op) > 0) - (Z_LVAL_P(op) < 0);
	}
	return zend_bigint_sign(Z_BIG_P(op));
}

static zend_always_inline bool zend_int_is_odd(const zval *op)
{
	ZEND_ASSERT(Z_IS_INT_P(op));
	if (EXPECTED(Z_TYPE_INFO_P(op) == IS_LONG)) {
		return Z_LVAL_P(op) & 1;
	}
	return zend_bigint_is_odd(Z_BIG_P(op));
}

static zend_always_inline uint64_t zend_int_bit_length(const zval *op)
{
	ZEND_ASSERT(Z_IS_INT_P(op));
	if (EXPECTED(Z_TYPE_INFO_P(op) == IS_LONG)) {
		zend_ulong magnitude = Z_LVAL_P(op) < 0
			? (zend_ulong) -(zend_ulong) Z_LVAL_P(op)
			: (zend_ulong) Z_LVAL_P(op);
		uint64_t bits = 0;
		while (magnitude != 0) {
			bits++;
			magnitude >>= 1;
		}
		return bits;
	}
	return zend_bigint_bit_length(Z_BIG_P(op));
}

static zend_always_inline double zend_int_to_double(const zval *op)
{
	ZEND_ASSERT(Z_IS_INT_P(op));
	if (EXPECTED(Z_TYPE_INFO_P(op) == IS_LONG)) {
		return (double) Z_LVAL_P(op);
	}
	return zend_bigint_to_double(Z_BIG_P(op));
}

/* Converts a finite double to a canonical integer, truncating toward zero,
 * and stores it in result. The caller must ensure d is finite. */
ZEND_API void zend_int_from_double(zval *result, double d);

END_EXTERN_C()

#endif
