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

#ifndef ZEND_BIGINT_H
#define ZEND_BIGINT_H

#include "zend_types.h"

BEGIN_EXTERN_C()

/* One-time process startup hook. */
ZEND_API void zend_startup_bigint(void);

/* Name of the active arbitrary-precision backend (e.g. "libtommath"). Stable
 * identifier for diagnostics and for tests that assert backend-specific limits. */
ZEND_API const char *zend_bigint_backend_name(void);

/* Lifecycle */
ZEND_API zend_bigint *zend_bigint_init(void);
ZEND_API zend_bigint *zend_bigint_init_from_long(zend_long value);
ZEND_API zend_bigint *zend_bigint_init_from_unsigned_long(zend_ulong value);
ZEND_API zend_bigint *zend_bigint_init_from_string_length(const char *str, size_t len, int base);
ZEND_API zend_bigint *zend_bigint_dup(const zend_bigint *src);
ZEND_API void         zend_bigint_free(zend_bigint *big);
ZEND_API void         zend_bigint_release(zend_bigint *big);

/* Operations */
ZEND_API void zend_bigint_add(zend_bigint *out, const zend_bigint *op1, const zend_bigint *op2);
ZEND_API void zend_bigint_add_long(zend_bigint *out, const zend_bigint *op1, zend_long op2);
ZEND_API void zend_bigint_long_add_long(zend_bigint *out, zend_long op1, zend_long op2);
ZEND_API void zend_bigint_sub(zend_bigint *out, const zend_bigint *op1, const zend_bigint *op2);
ZEND_API void zend_bigint_sub_long(zend_bigint *out, const zend_bigint *op1, zend_long op2);
ZEND_API void zend_bigint_long_sub(zend_bigint *out, zend_long op1, const zend_bigint *op2);
ZEND_API void zend_bigint_long_sub_long(zend_bigint *out, zend_long op1, zend_long op2);
ZEND_API void zend_bigint_mul(zend_bigint *out, const zend_bigint *op1, const zend_bigint *op2);
ZEND_API void zend_bigint_mul_long(zend_bigint *out, const zend_bigint *op1, zend_long op2);
ZEND_API void zend_bigint_long_mul_long(zend_bigint *out, zend_long op1, zend_long op2);

/* Truncated division: quotient = op1 / op2, remainder = op1 % op2 (remainder
 * takes the sign of the dividend). The remainder lets callers detect an exact
 * division (zero remainder) so an integer result can be preserved. */
ZEND_API void zend_bigint_divmod(zend_bigint *quot, zend_bigint *rem, const zend_bigint *op1, const zend_bigint *op2);
ZEND_API void zend_bigint_divmod_long(zend_bigint *quot, zend_bigint *rem, const zend_bigint *op1, zend_long op2);
ZEND_API void zend_bigint_long_divmod(zend_bigint *quot, zend_bigint *rem, zend_long op1, const zend_bigint *op2);

/* Truncated remainder = op1 % op2, taking the sign of the dividend (matching
 * PHP's "%", i.e., C truncated division). */
ZEND_API void zend_bigint_mod(zend_bigint *out, const zend_bigint *op1, const zend_bigint *op2);
ZEND_API void zend_bigint_mod_long(zend_bigint *out, const zend_bigint *op1, zend_long op2);
ZEND_API void zend_bigint_long_mod(zend_bigint *out, zend_long op1, const zend_bigint *op2);

/* Bitwise complement: out = ~op = -op - 1 (infinite-precision two's complement). */
ZEND_API void zend_bigint_complement(zend_bigint *out, const zend_bigint *op);

/* Bitwise shift left (out = op << bits) for a non-negative bit count. Returns
 * true on success. If the active backend cannot perform the shift (e.g. a bit
 * count beyond the backend's reach), it leaves out untouched and returns false.
 * When returning false, it should throw an ArithmeticError describing the
 * backend's limit. */
ZEND_API bool zend_bigint_shift_left(zend_bigint *out, const zend_bigint *op, zend_long bits);
ZEND_API bool zend_bigint_long_shift_left(zend_bigint *out, zend_long op, zend_long bits);

/* Whether the active backend can shift left by the given non-negative bit count.
 * The compiler consults this to avoid constant-folding a shift that would throw,
 * so the error stays catchable at runtime instead of aborting compilation. */
ZEND_API bool zend_bigint_can_shift_left(zend_long bits);

/* Arithmetic (sign-propagating, floored) shift right: out = op >> bits, for a
 * non-negative bit count the backend can represent. A count too large to
 * represent would shift past every bit, so the caller should produce that result
 * directly (0 for a non-negative op, -1 for a negative op) instead of calling
 * this. */
ZEND_API void zend_bigint_shift_right(zend_bigint *out, const zend_bigint *op, zend_long bits);

/* Bitwise AND in infinite-precision two's complement. The *_long variant takes a
 * long operand (AND is commutative, so it covers both bigint&long and long&bigint). */
ZEND_API void zend_bigint_and(zend_bigint *out, const zend_bigint *op1, const zend_bigint *op2);
ZEND_API void zend_bigint_and_long(zend_bigint *out, const zend_bigint *op1, zend_long op2);

/* Bitwise OR in infinite-precision two's complement. The *_long variant takes a
 * long operand (OR is commutative, so it covers both bigint|long and long|bigint). */
ZEND_API void zend_bigint_or(zend_bigint *out, const zend_bigint *op1, const zend_bigint *op2);
ZEND_API void zend_bigint_or_long(zend_bigint *out, const zend_bigint *op1, zend_long op2);

/* Bitwise XOR in infinite-precision two's complement. The *_long variant takes a
 * long operand (XOR is commutative, so it covers both bigint^long and long^bigint). */
ZEND_API void zend_bigint_xor(zend_bigint *out, const zend_bigint *op1, const zend_bigint *op2);
ZEND_API void zend_bigint_xor_long(zend_bigint *out, const zend_bigint *op1, zend_long op2);

/* Exponentiation: out = base ** exp, for a non-negative exp. Returns true on
 * success. If the active backend cannot compute the power (e.g. an exponent
 * beyond the backend's reach), it leaves out untouched and returns false. When
 * returning false, it should throw an ArithmeticError describing the backend's
 * limit. */
ZEND_API bool zend_bigint_pow_long(zend_bigint *out, const zend_bigint *base, zend_long exp);
ZEND_API bool zend_bigint_long_pow_long(zend_bigint *out, zend_long base, zend_long exp);

/* Whether the active backend can raise to the given non-negative exponent. The
 * compiler consults this to avoid constant-folding a power that would throw, so
 * the error stays catchable at runtime instead of aborting compilation. */
ZEND_API bool zend_bigint_can_pow_exponent(zend_long exp);

/* Information / conversion */
ZEND_API int       zend_bigint_sign(const zend_bigint *big);
ZEND_API bool      zend_bigint_can_fit_long(const zend_bigint *big);
ZEND_API zend_long zend_bigint_to_long(const zend_bigint *big);
ZEND_API double    zend_bigint_to_double(const zend_bigint *big);
ZEND_API int       zend_bigint_cmp(const zend_bigint *a, const zend_bigint *b);
ZEND_API int       zend_bigint_cmp_long(const zend_bigint *a, zend_long b);
ZEND_API char     *zend_bigint_to_string(const zend_bigint *big, size_t *len);

/* Like zend_bigint_to_string(), but returns an owned (refcount-1) zend_string
 * holding the canonical decimal. Does not apply any display digit limit. */
ZEND_API zend_string *zend_bigint_to_str(const zend_bigint *big);

/* Like zend_bigint_to_string(), but emits the value in an arbitrary radix
 * (2-36), signed, and with lowercase a-z digits. Returns an owned C string
 * (caller must efree()); *len receives strlen.
 *
 * This function does not apply a digit limit. Callers must gate calls with
 * zend_bigint_radix_conversion_is_linear() and zend_bigint_string_exceeds_digits(). */
ZEND_API char *zend_bigint_to_string_base(const zend_bigint *big, int base, size_t *len);

/* Whether the active backend converts to/from the given radix in linear time.
 * The engine uses this to decide whether the zend.int_string_max_digits gate
 * is necessary. A linear radix is cheap and may skip the gate. */
ZEND_API bool zend_bigint_radix_conversion_is_linear(int base);

/* True if the decimal representation of |big| has more than max_digits digits
 * (the sign is not counted). */
ZEND_API bool zend_bigint_string_exceeds_digits(const zend_bigint *big, zend_long max_digits);

/* Store a bigint result, demoting to IS_LONG when it fits. */
static zend_always_inline void zend_bigint_result(zval *result, zend_bigint *big)
{
	if (zend_bigint_can_fit_long(big)) {
		zend_long l = zend_bigint_to_long(big);
		zend_bigint_release(big);
		ZVAL_LONG(result, l);
	} else {
		ZVAL_BIGINT(result, big);
	}
}

/* Reads a logical int (an IS_LONG or IS_BIGINT zval, e.g. from Z_PARAM_INT) as a
 * zend_long. Writes *out and returns true if the value fits a zend_long; returns
 * false, WITHOUT throwing, if an IS_BIGINT is too large to fit.
 *
 * The no-throw result is for "bounded" builtins, those whose int argument is a
 * count/length/index they already cap. On false they throw their own limit error,
 * so an oversized integer is rejected just like an oversized IS_LONG, never leaking
 * that a long has a maximum. (Builtins whose *result* is an unbounded integer, like
 * intdiv()/abs(), instead keep the bigint and store it with zend_bigint_result().) */
static zend_always_inline bool zend_logical_int_to_long(const zval *op, zend_long *out)
{
	if (EXPECTED(Z_TYPE_P(op) == IS_LONG)) {
		*out = Z_LVAL_P(op);
		return true;
	}
	ZEND_ASSERT(Z_TYPE_P(op) == IS_BIGINT);
	if (EXPECTED(zend_bigint_can_fit_long(Z_BIG_P(op)))) {
		*out = zend_bigint_to_long(Z_BIG_P(op));
		return true;
	}
	return false;
}

END_EXTERN_C()

#endif /* ZEND_BIGINT_H */
