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
#include "zend_smart_str.h"

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

ZEND_API bool zend_int_fits_long(const zval *zv)
{
	ZEND_ASSERT(Z_IS_INT_P(zv));

	if (Z_TYPE_P(zv) == IS_LONG) {
		return true;
	}

	return zend_bigint_fits_long(Z_BIG_P(zv));
}

ZEND_API bool zend_int_get_long(const zval *zv, zend_long *out)
{
	ZEND_ASSERT(Z_IS_INT_P(zv));

	if (Z_TYPE_P(zv) == IS_LONG) {
		*out = Z_LVAL_P(zv);
		return true;
	}

	if (!zend_bigint_fits_long(Z_BIG_P(zv))) {
		return false;
	}

	*out = zend_bigint_to_long(Z_BIG_P(zv));
	return true;
}

ZEND_API zend_string *zend_int_debug_str(const zval *zv, size_t max_digits)
{
	ZEND_ASSERT(Z_IS_INT_P(zv));

	if (Z_TYPE_P(zv) == IS_LONG) {
		return zend_long_to_str(Z_LVAL_P(zv));
	}

	zend_bigint *b = Z_BIG_P(zv);
	zend_string *full = zend_bigint_to_str(b);

	if (!zend_bigint_exceeds_digits(b, (zend_long) max_digits)) {
		return full;
	}

	size_t lead = MIN(max_digits, ZSTR_LEN(full));
	smart_str buf = {0};
	smart_str_appendl(&buf, ZSTR_VAL(full), lead);
	smart_str_appends(&buf, "...(");
	smart_str_append_long(&buf, (zend_long) ZSTR_LEN(full));
	smart_str_appends(&buf, " digits)");

	zend_string_release(full);

	return smart_str_extract(&buf);
}
