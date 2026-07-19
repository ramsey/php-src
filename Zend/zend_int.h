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

END_EXTERN_C()

#endif
