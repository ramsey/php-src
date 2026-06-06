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
#include "libtommath/tommath.h"

ZEND_API zend_bigint *zend_bigint_init(void)
{
	/* not yet implemented */
	ZEND_UNREACHABLE();
	return NULL;
}

ZEND_API zend_bigint *zend_bigint_init_from_long(zend_long value)
{
	/* not yet implemented */
	ZEND_UNREACHABLE();
	return NULL;
}

ZEND_API zend_bigint *zend_bigint_init_from_string_length(const char *str, size_t len, int base)
{
	/* not yet implemented */
	ZEND_UNREACHABLE();
	return NULL;
}

ZEND_API zend_bigint *zend_bigint_dup(const zend_bigint *src)
{
	/* not yet implemented */
	ZEND_UNREACHABLE();
	return NULL;
}

ZEND_API void zend_bigint_free(zend_bigint *big)
{
	/* not yet implemented */
	ZEND_UNREACHABLE();
}

ZEND_API void zend_bigint_add(zend_bigint *out, const zend_bigint *op1, const zend_bigint *op2)
{
	/* not yet implemented */
	ZEND_UNREACHABLE();
}

ZEND_API void zend_bigint_add_long(zend_bigint *out, const zend_bigint *op1, zend_long op2)
{
	/* not yet implemented */
	ZEND_UNREACHABLE();
}

ZEND_API void zend_bigint_long_add_long(zend_bigint *out, zend_long op1, zend_long op2)
{
	/* not yet implemented */
	ZEND_UNREACHABLE();
}

ZEND_API int zend_bigint_sign(const zend_bigint *big)
{
	/* not yet implemented */
	ZEND_UNREACHABLE();
	return 0;
}

ZEND_API bool zend_bigint_can_fit_long(const zend_bigint *big)
{
	/* not yet implemented */
	ZEND_UNREACHABLE();
	return false;
}

ZEND_API zend_long zend_bigint_to_long(const zend_bigint *big)
{
	/* not yet implemented */
	ZEND_UNREACHABLE();
	return 0;
}

ZEND_API int zend_bigint_cmp(const zend_bigint *a, const zend_bigint *b)
{
	/* not yet implemented */
	ZEND_UNREACHABLE();
	return 0;
}

ZEND_API int zend_bigint_cmp_long(const zend_bigint *a, zend_long b)
{
	/* not yet implemented */
	ZEND_UNREACHABLE();
	return 0;
}

ZEND_API char *zend_bigint_to_string(const zend_bigint *big, size_t *len)
{
	/* not yet implemented */
	ZEND_UNREACHABLE();
	return NULL;
}
