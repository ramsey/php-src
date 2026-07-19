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

#ifndef ZEND_INT_BACKEND_LIBTOMMATH_H
#define ZEND_INT_BACKEND_LIBTOMMATH_H

#include "zend_int_backend.h"
#include "Zend/bigint/libtommath/tommath.h"

struct _zend_bigint {
	zend_refcounted_h gc;
	mp_int            mp;
};

#endif
