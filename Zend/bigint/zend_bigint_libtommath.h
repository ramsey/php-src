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

#ifndef ZEND_BIGINT_LIBTOMMATH_H
#define ZEND_BIGINT_LIBTOMMATH_H

#include "zend_bigint.h"
#include "Zend/bigint/libtommath/tommath.h"

struct _zend_bigint {
	zend_refcounted_h gc;
	mp_int            mp;
};

/* The engine reaches the GC header through a zend_refcounted cast, which relies
 * on the box beginning with its refcounted header. */
ZEND_STATIC_ASSERT(offsetof(struct _zend_bigint, gc) == 0,
	"zend_bigint must begin with its GC header");

#endif
