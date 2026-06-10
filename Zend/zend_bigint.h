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

END_EXTERN_C()

#endif /* ZEND_BIGINT_H */
