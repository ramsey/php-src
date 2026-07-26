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

typedef struct _zend_bigint zend_bigint;

BEGIN_EXTERN_C()

ZEND_API zend_bigint *zend_bigint_from_long(zend_long v);
ZEND_API zend_bigint *zend_bigint_from_string(const char *s, size_t len, int base);
ZEND_API zend_bigint *zend_bigint_dup(const zend_bigint *b);
ZEND_API void         zend_bigint_free(zend_bigint *b);
ZEND_API bool         zend_bigint_fits_long(const zend_bigint *b);
ZEND_API zend_long    zend_bigint_to_long(const zend_bigint *b);
ZEND_API int          zend_bigint_sign(const zend_bigint *b);
ZEND_API int          zend_bigint_cmp(const zend_bigint *a, const zend_bigint *b);
ZEND_API int          zend_bigint_cmp_long(const zend_bigint *a, zend_long b);
ZEND_API bool         zend_bigint_is_odd(const zend_bigint *b);
ZEND_API uint64_t     zend_bigint_bit_length(const zend_bigint *b);
ZEND_API double       zend_bigint_to_double(const zend_bigint *b);
ZEND_API zend_bigint *zend_bigint_from_double(double d);
ZEND_API zend_string *zend_bigint_to_str(const zend_bigint *b);
ZEND_API bool         zend_bigint_radix_is_linear(int base);
ZEND_API bool         zend_bigint_exceeds_digits(const zend_bigint *b, zend_long max_digits);
ZEND_API const char  *zend_bigint_backend_name(void);

ZEND_API zend_bigint *zend_bigint_add(const zend_bigint *a, const zend_bigint *b);
ZEND_API zend_bigint *zend_bigint_add_long(const zend_bigint *a, zend_long b);
ZEND_API zend_bigint *zend_bigint_long_add_long(zend_long a, zend_long b);
ZEND_API zend_bigint *zend_bigint_sub(const zend_bigint *a, const zend_bigint *b);
ZEND_API zend_bigint *zend_bigint_sub_long(const zend_bigint *a, zend_long b);
ZEND_API zend_bigint *zend_bigint_long_sub(zend_long a, const zend_bigint *b);
ZEND_API zend_bigint *zend_bigint_long_sub_long(zend_long a, zend_long b);
ZEND_API zend_bigint *zend_bigint_mul(const zend_bigint *a, const zend_bigint *b);
ZEND_API zend_bigint *zend_bigint_mul_long(const zend_bigint *a, zend_long b);
ZEND_API zend_bigint *zend_bigint_long_mul_long(zend_long a, zend_long b);
ZEND_API zend_bigint *zend_bigint_neg(const zend_bigint *a);
ZEND_API zend_bigint *zend_bigint_abs(const zend_bigint *a);

ZEND_API void zend_bigint_divmod(const zend_bigint *a, const zend_bigint *b, zend_bigint **quot, zend_bigint **rem);
ZEND_API void zend_bigint_divmod_long(const zend_bigint *a, zend_long b, zend_bigint **quot, zend_bigint **rem);
ZEND_API void zend_bigint_long_divmod(zend_long a, const zend_bigint *b, zend_bigint **quot, zend_bigint **rem);
ZEND_API zend_bigint *zend_bigint_mod(const zend_bigint *a, const zend_bigint *b);
ZEND_API zend_bigint *zend_bigint_mod_long(const zend_bigint *a, zend_long b);
ZEND_API zend_bigint *zend_bigint_long_mod(zend_long a, const zend_bigint *b);

ZEND_API zend_bigint *zend_bigint_not(const zend_bigint *a);
ZEND_API zend_bigint *zend_bigint_and(const zend_bigint *a, const zend_bigint *b);
ZEND_API zend_bigint *zend_bigint_and_long(const zend_bigint *a, zend_long b);
ZEND_API zend_bigint *zend_bigint_or(const zend_bigint *a, const zend_bigint *b);
ZEND_API zend_bigint *zend_bigint_or_long(const zend_bigint *a, zend_long b);
ZEND_API zend_bigint *zend_bigint_xor(const zend_bigint *a, const zend_bigint *b);
ZEND_API zend_bigint *zend_bigint_xor_long(const zend_bigint *a, zend_long b);

ZEND_API bool zend_bigint_can_shift_left(zend_long bits);
ZEND_API bool zend_bigint_shift_left(const zend_bigint *a, zend_long bits, const zend_bigint *bits_big, zend_bigint **out);
ZEND_API bool zend_bigint_long_shift_left(zend_long a, zend_long bits, const zend_bigint *bits_big, zend_bigint **out);
ZEND_API zend_bigint *zend_bigint_shift_right(const zend_bigint *a, zend_long bits, const zend_bigint *bits_big);

ZEND_API bool zend_bigint_can_pow(zend_long exp);
ZEND_API bool zend_bigint_pow(const zend_bigint *base, zend_long exp, const zend_bigint *exp_big, zend_bigint **out);

/* These functions let opcache keep a box in shared memory and in its file
 * cache. A regular box allocates its digit buffer separately on the request
 * heap. persist_size reports the size of a copy that holds everything in
 * one contiguous block, and persist_copy writes such a copy, marked
 * immutable, into caller memory of that size. The copy contains one pointer
 * to its own digits. persist_detach clears that pointer before the block is
 * saved to disk; persist_relink restores it after the block is loaded at a
 * new address. */

ZEND_API size_t       zend_bigint_persist_size(const zend_bigint *b);
ZEND_API zend_bigint *zend_bigint_persist_copy(void *dst, const zend_bigint *b);
ZEND_API void         zend_bigint_persist_detach(zend_bigint *b);
ZEND_API void         zend_bigint_persist_relink(zend_bigint *b);

END_EXTERN_C()

#endif
