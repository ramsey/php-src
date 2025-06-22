/*
   +----------------------------------------------------------------------+
   | Zend Engine                                                          |
   +----------------------------------------------------------------------+
   | Copyright (c) Zend Technologies Ltd. (http://www.zend.com)           |
   +----------------------------------------------------------------------+
   | This source file is subject to version 2.00 of the Zend license,     |
   | that is bundled with this package in the file LICENSE, and is        |
   | available through the world-wide-web at the following url:           |
   | http://www.zend.com/license/2_00.txt.                                |
   | If you did not receive a copy of the Zend license and are unable to  |
   | obtain it through the world-wide-web, please send a note to          |
   | license@zend.com so we can mail you a copy immediately.              |
   +----------------------------------------------------------------------+
   | Authors: Andrea Faulds <ajf@ajf.me>                                  |
   |          Ben Ramsey <ramsey@php.net>                                 |
   +----------------------------------------------------------------------+
*/

/*
 * This file is a placeholder implementation of zend_bigint.h
 *
 * This placeholder implementation of zend_bigint.h DOES NOT perform MP arithmetic.
 * It only exists for development purposes and should be removed and replaced
 * with a proper implementation (i.e., GNU MP, LibTomMath, etc.) before merging
 * this feature.
 *
 * See zend_bigint.c
 */

#include "zend.h"

#ifdef ZEND_BIGINT_USE_PLACEHOLDER

#include <ctype.h>

#include "zend_types.h"
#include "zend_bigint.h"
#include "zend_string.h"

struct _zend_bigint {
	zend_refcounted_h   gc;
	zend_long           zl;
};

void zend_startup_bigint(void) {}

zend_bigint* zend_bigint_init(void) {
	zend_bigint *big = (zend_bigint *)emalloc(sizeof(zend_bigint));

	GC_SET_REFCOUNT(big, 1);
	GC_TYPE_INFO(big) = IS_BIGINT;
	big->zl = 0;

	return big;
}

zend_bigint* zend_bigint_init_from_string(const char *str, const int base) {
	zend_bigint *big = zend_bigint_init();

	errno = 0;
	const zend_long value = ZEND_STRTOL(str, NULL, base);

	if (errno == ERANGE) {
		zend_bigint_release(big);

		return NULL;
	}

	big->zl = value;

	return big;
}

zend_bigint* zend_bigint_init_from_string_length(const char *str, const size_t length, const int base) {
	zend_bigint *big = zend_bigint_init();
	char *temp_str = estrndup(str, length);

	errno = 0;
	const zend_long value = ZEND_STRTOL(temp_str, NULL, base);

	if (errno == ERANGE) {
		zend_bigint_release(big);
		efree(temp_str);

		return NULL;
	}

	efree(temp_str);
	big->zl = value;

	return big;
}

zend_bigint* zend_bigint_init_strtol(const char *str, char** endptr, int base) {
	zend_bigint *big = NULL;
	size_t len = 0;

	/* Skip leading whitespace */
	while (isspace(*str)) {
		str++;
	}

	/* A single sign is valid */
	if (str[0] == '+' || str[0] == '-') {
		len += 1;
	}

	/* detect prefix */
	if (base == 0) {
		if (str[0] == '0' && (str[1] == 'x' || str[1] == 'X')) {
			base = 16;
			str += 2;
		} else if (str[0] == '0') {
			base = 8;
			str++;
		} else {
			base = 10;
		}
	}

	if (base == 10) {
		while (isdigit(str[len])) {
			len++;
		}
	} else if (base == 16) {
		while (isxdigit(str[len])) {
			len++;
		}
	} else if (base == 8) {
		while (isdigit(str[len]) && str[len] != '8' && str[len] != '9') {
			len++;
		}
	}

	if (len) {
		big = zend_bigint_init_from_string_length(str, len, base);
	}

	if (endptr) {
		*endptr = (char*)(str + len);
	}

	if (big == NULL) {
		big = zend_bigint_init();
	}

	return big;
}

zend_bigint* zend_bigint_init_from_long(const zend_long value) {
	zend_bigint *big = zend_bigint_init();
	big->zl = value;

	return big;
}

zend_bigint* zend_bigint_init_from_double(const double value) {
	zend_bigint *big = zend_bigint_init();

	if (zend_finite(value) && !zend_isnan(value)) {
		big->zl = zend_dval_to_lval_safe(value);
	}

	return big;
}

zend_bigint* zend_bigint_dup(const zend_bigint *source) {
	zend_bigint *big = zend_bigint_init();
	big->zl = source->zl;

	return big;
}

void zend_bigint_release(zend_bigint *big) {
	if (GC_DELREF(big) <= 0) {
		efree(big);
	}
}

void zend_bigint_free(zend_bigint *big) {
	efree(big);
}

#endif /* ZEND_BIGINT_USE_PLACEHOLDER */
