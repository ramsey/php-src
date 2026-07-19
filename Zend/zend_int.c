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
