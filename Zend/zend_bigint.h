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

/* Information / conversion */
ZEND_API int       zend_bigint_sign(const zend_bigint *big);
ZEND_API bool      zend_bigint_can_fit_long(const zend_bigint *big);
ZEND_API zend_long zend_bigint_to_long(const zend_bigint *big);
ZEND_API int       zend_bigint_cmp(const zend_bigint *a, const zend_bigint *b);
ZEND_API int       zend_bigint_cmp_long(const zend_bigint *a, zend_long b);
ZEND_API char     *zend_bigint_to_string(const zend_bigint *big, size_t *len);

END_EXTERN_C()

#endif /* ZEND_BIGINT_H */
