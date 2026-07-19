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

/* Wraps a backend integer in an IS_BIGINT box and stores it in result. Takes
 * ownership of b. */
ZEND_API void zend_int_from_bigint(zval *result, zend_bigint *b);

END_EXTERN_C()

#endif
