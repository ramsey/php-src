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
#include "zend_bigint.h"

ZEND_API void zend_startup_bigint(void)
{
	/* reserved for future allocator/memory hook setup */
}

ZEND_API void zend_bigint_release(zend_bigint *big)
{
	if (GC_DELREF(big) == 0) {
		zend_bigint_free(big);
	}
}
