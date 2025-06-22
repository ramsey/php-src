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

#ifndef ZEND_BIGINT_H
#define ZEND_BIGINT_H

#include "zend.h"
#include "zend_types.h"

void zend_startup_bigint(void);

/**
 * Allocates and initializes ("creates") a bigint and returns a pointer.
 */
zend_bigint* ZEND_FASTCALL zend_bigint_init(void);

/**
 * Creates a bigint from a string with the specified base (in range 2-36).
 *
 * Returns NULL on failure (if the string is not entirely numeric).
 */
zend_bigint* zend_bigint_init_from_string(const char *str, int base);

/**
 * Creates a bigint from a string with the specified base (in range 2-36).
 *
 * Returns NULL on failure.
 */
zend_bigint* zend_bigint_init_from_string_length(const char *str, size_t length, int base);

/**
 * Creates a bigint from a C-string with the specified base (10 or 16).
 *
 * If endptr is not NULL, it will point to the first character after the number.
 * If the base is zero, it will be detected from the prefix:
 * 0x/0X for 16, 0 for 8, else 10
 *
 * Leading whitespace is ignored; it will take as many valid characters as
 * possible and will stop at the first non-valid character or null byte.
 * If there are no valid characters, the bigint is initialized to zero.
 * This behavior is intended to match that of strtol() but may have differences
 * in practice.
 */
zend_bigint* zend_bigint_init_strtol(const char *str, char** endptr, int base);

/**
 * Creates a bigint from a long value.
 */
zend_bigint* zend_bigint_init_from_long(zend_long value);

/**
 * Creates a bigint from a double value.
 */
zend_bigint* zend_bigint_init_from_double(double value);

/**
 * Creates a bigint and from another bigint, duplicating its value.
 */
zend_bigint* zend_bigint_dup(const zend_bigint *source);

/**
 * Decreases the refcount of a bigint and, if <= 0, destroys and frees it.
 */
void zend_bigint_release(zend_bigint *big);

/**
 * Destroys and frees a bigint
 */
void zend_bigint_free(zend_bigint *big);

#endif /* ZEND_BIGINT_H */
