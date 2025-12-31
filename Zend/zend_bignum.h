/*
   +----------------------------------------------------------------------+
   | Copyright (c) The PHP Group                                          |
   +----------------------------------------------------------------------+
   | This source file is subject to version 3.01 of the PHP license,      |
   | that is bundled with this package in the file LICENSE, and is        |
   | available through the world-wide-web at the following url:           |
   | https://www.php.net/license/3_01.txt                                 |
   | If you did not receive a copy of the PHP license and are unable to   |
   | obtain it through the world-wide-web, please send a note to          |
   | license@php.net so we can mail you a copy immediately.               |
   +----------------------------------------------------------------------+
   | Author: Ben Ramsey <ramsey@php.net>                                  |
   |         Andrea Faulds <ajf@ajf.me>                                   |
   +----------------------------------------------------------------------+
*/

#ifndef ZEND_BIGNUM_H
#define ZEND_BIGNUM_H

#include "zend.h"
#include "zend_types.h"

/*** INTERNAL FUNCTIONS ***/

/* Called by zend_startup */
void zend_startup_bignum(void);

/*** INITIALISERS ***/

/* HERE BE DRAGONS: Memory allocated internally to hold bignum data is always
 * non-persistent - even if the zend_bignum struct itself is persistent,
 * the data it points is not.
 */

/* Allocates and initializes ("creates") a bignum, returns a pointer */
ZEND_API zend_bignum* zend_bignum_init(void);

/* Creates a bignum from a string with the specified base (in range 2-36)
 * Returns NULL on failure (if the string is not entirely numeric)
 */
ZEND_API zend_bignum* zend_bignum_init_from_string(const char *str, int base);

/* Creates a bignum from a string with the specified base (in range 2-36)
 * Returns NULL on failure */
ZEND_API zend_bignum* zend_bignum_init_from_string_length(const char *str, size_t length, int base);

/* Creates a bignum from a C-string with the specified base (10 or 16)
 * If endptr is not NULL, it is set to point to first character after number
 * If base is zero, it shall be detected from the prefix:
 * 0x/0X for 16, 0 for 8, else 10
 * Leading whitespace is ignored, will take as many valid characters as possible
 * Stops at first non-valid character, else null byte
 * If there are no valid characters, the bignum is initialized to zero
 * This behavior is supposed to match that of strtol but may have differences
 * in practice
 */
ZEND_API zend_bignum* zend_bignum_init_strtol(const char *str, char** endptr, int base);

/* Creates a bignum from a long */
ZEND_API zend_bignum* zend_bignum_init_from_long(zend_long value);

/* Creates a bignum from a double */
ZEND_API zend_bignum* zend_bignum_init_from_double(double value);

/* Creates a bignum and from another bignum, duplicating its value */
ZEND_API zend_bignum* zend_bignum_dup(const zend_bignum *source);

/* Decreases the refcount of a bignum and, if <= 0, destroys and frees it */
ZEND_API void zend_bignum_release(zend_bignum *big);

/* Destroys and frees a bignum */
ZEND_API void zend_bignum_free(zend_bignum *big);

/*** INFORMATION ***/

/* Returns true if bignum can fit into type without truncation
   ulong is zend_ulong, long is zend_long */
ZEND_API bool zend_bignum_can_fit_ulong(const zend_bignum *big);
ZEND_API bool zend_bignum_can_fit_long(const zend_bignum *big);

/* Returns sign of bignum (-1 for negative, 0 for zero or 1 for positive) */
ZEND_API int zend_bignum_sign(const zend_bignum *big);

/* Returns true if two integers divisible (i.e. num mod divisor == 0) */
ZEND_API bool zend_bignum_divisible(const zend_bignum *num, const zend_bignum *divisor);
ZEND_API bool zend_bignum_divisible_long(const zend_bignum *num, zend_long divisor);
ZEND_API bool zend_bignum_long_divisible(zend_long num, const zend_bignum *divisor);

/*** CONVERTORS ***/

/* Converts to long; if it doesn't fit, wraps around (like zend_dval_to_lval) */
ZEND_API zend_long zend_bignum_to_long(const zend_bignum *big);

/* Converts to long; if it doesn't fit, saturates (caps at ZEND_LONG_MAX/_MIN) */
ZEND_API zend_long zend_bignum_to_long_saturate(const zend_bignum *big);

/* Converts to long; if it doesn't fit, may return garbage
 * If it didn't fit, sets overflow to 1, else to 0 */
ZEND_API zend_long zend_bignum_to_long_ex(const zend_bignum *big, bool *overflow);

/* Converts to unsigned long; this will cap at the max value of an unsigned long */
ZEND_API zend_ulong zend_bignum_to_ulong(const zend_bignum *big);

/* Converts to bool */
ZEND_API bool zend_bignum_to_bool(const zend_bignum *big);

/* Converts to double; this will lose precision beyond a certain point */
ZEND_API double zend_bignum_to_double(const zend_bignum *big);

/* Converts to decimal C string
 * HERE BE DRAGONS: String allocated is non-persistent */
ZEND_API char* zend_bignum_to_string(const zend_bignum *big);

/* Convenience function: Converts to zend string */
ZEND_API zend_string* zend_bignum_to_zend_string(const zend_bignum *big, int persistent);

/* Converts to C string of arbitrary base */
ZEND_API char* zend_bignum_to_string_base(const zend_bignum *big, int base);

/* Convenience function: Converts to zend string of arbitrary base */
ZEND_API zend_string* zend_bignum_to_zend_string_base(const zend_bignum *big, int base, int persistent);

/*** OPERATIONS **/

/* By the way, in case you're wondering, you can indeed use something as both
 * output and operand. For example, zend_bignum_add_long(foo, foo, 1) is
 * perfectly valid for incrementing foo. This is because gmp supports it, and
 * zend_bignum is (at the time of writing, at least) merely a thin wrapper
 * around gmp. This is not advisable, however, because bignums are reference-
 * counted and should be copy-on-write so far as userland PHP code cares. Do
 * it sparingly, and never to bignums which have been exposed to userland. With
 * great power comes great responsibility.
 */

/* Adds two integers */
ZEND_API void zend_bignum_add(zend_bignum *out, const zend_bignum *op1, const zend_bignum *op2);
ZEND_API void zend_bignum_add_long(zend_bignum *out, const zend_bignum *op1, zend_long op2);
ZEND_API void zend_bignum_long_add_long(zend_bignum *out, zend_long op1, zend_long op2);

/* Subtracts two integers */
ZEND_API void zend_bignum_subtract(zend_bignum *out, const zend_bignum *op1, const zend_bignum *op2);
ZEND_API void zend_bignum_subtract_long(zend_bignum *out, const zend_bignum *op1, zend_long op2);
ZEND_API void zend_bignum_long_subtract(zend_bignum *out, zend_long op1, const zend_bignum *op2);
ZEND_API void zend_bignum_long_subtract_long(zend_bignum *out, zend_long op1, zend_long op2);

/* Multiplies two integers */
ZEND_API void zend_bignum_multiply(zend_bignum *out, const zend_bignum *op1, const zend_bignum *op2);
ZEND_API void zend_bignum_multiply_long(zend_bignum *out, const zend_bignum *op1, zend_long op2);
ZEND_API void zend_bignum_long_multiply_long(zend_bignum *out, zend_long op1, zend_long op2);

/* Raises an integer base to an integer power */
ZEND_API void zend_bignum_pow_ulong(zend_bignum *out, const zend_bignum *base, zend_ulong power);
ZEND_API void zend_bignum_long_pow_ulong(zend_bignum *out, zend_long base, zend_ulong power);

/* Divides an integer by an integer
 * _as_double functions may provide better precision than converting to double
 * and doing a floating-point division would
 */
ZEND_API void zend_bignum_divide(zend_bignum *out, const zend_bignum *big, const zend_bignum *divisor);
ZEND_API double zend_bignum_divide_as_double(const zend_bignum *num, const zend_bignum *divisor);
ZEND_API void zend_bignum_divide_long(zend_bignum *out, const zend_bignum *big, zend_long divisor);
ZEND_API double zend_bignum_divide_long_as_double(const zend_bignum *num, zend_long divisor);
ZEND_API void zend_bignum_long_divide(zend_bignum *out, zend_long big, const zend_bignum *divisor);
ZEND_API double zend_bignum_long_divide_as_double(zend_long num, const zend_bignum *divisor);

/* Finds the remainder of the division of two integers
 * The result always has the sign of the divisor, like in C and PHP
 */
ZEND_API void zend_bignum_modulus(zend_bignum *out, const zend_bignum *num, const zend_bignum *divisor);
ZEND_API void zend_bignum_modulus_long(zend_bignum *out, const zend_bignum *num, zend_long divisor);
ZEND_API void zend_bignum_long_modulus(zend_bignum *out, zend_long num, const zend_bignum *divisor);

/* Finds the one's complement of an integer
 * If you're used to two's-complement arithmetic, this is equivalent to a
 * bitwise NOT on a two's-complement signed integer
 */
ZEND_API void zend_bignum_ones_complement(zend_bignum *out, const zend_bignum *op);

/* Finds the bitwise OR of two integers
 * Uses two's-complement arithmetic for negative numbers */
ZEND_API void zend_bignum_or(zend_bignum *out, const zend_bignum *op1, const zend_bignum *op2);
ZEND_API void zend_bignum_or_long(zend_bignum *out, const zend_bignum *op1, zend_long op2);

/* Finds the bitwise AND of two integers
 * Uses two's-complement arithmetic for negative numbers */
ZEND_API void zend_bignum_and(zend_bignum *out, const zend_bignum *op1, const zend_bignum *op2);
ZEND_API void zend_bignum_and_long(zend_bignum *out, const zend_bignum *op1, zend_long op2);

/* Finds the bitwise XOR of two integers
 * Uses two's-complement arithmetic for negative numbers */
ZEND_API void zend_bignum_xor(zend_bignum *out, const zend_bignum *op1, const zend_bignum *op2);
ZEND_API void zend_bignum_xor_long(zend_bignum *out, const zend_bignum *op1, zend_long op2);

/* Shifts an integer left by an integer number of bits
 * This is a logical left shift, like C
 */
ZEND_API void zend_bignum_shift_left_ulong(zend_bignum *out, const zend_bignum *num, zend_ulong shift);
ZEND_API void zend_bignum_long_shift_left_ulong(zend_bignum *out, zend_long num, zend_ulong shift);

/* Shifts an integer right by an integer number of bits
 * This is an arithmetic (sign-extending) right shift, like C in most compilers
 * While bignums are not usually represented as two's complement, it acts the
 * same as a two's-complement arithmetic right shift
 */
ZEND_API void zend_bignum_shift_right_ulong(zend_bignum *out, const zend_bignum *num, zend_ulong shift);

/* Compares two numbers
 * Result is negative if op1 > op2, zero if op1 == op2, positive if op1 < op2)
 */
ZEND_API int zend_bignum_cmp(const zend_bignum *op1, const zend_bignum *op2);
ZEND_API int zend_bignum_cmp_long(const zend_bignum *op1, zend_long op2);
ZEND_API int zend_bignum_cmp_double(const zend_bignum *op1, double op2);

/* Finds the absolute value of an integer */
ZEND_API void zend_bignum_abs(zend_bignum *out, const zend_bignum *big);

#endif /* ZEND_BIGNUM_H */
