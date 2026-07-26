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

/* libtommath is routed through the Zend allocator, so its allocations count
 * against memory_limit and are reclaimed from the request heap. libtommath has
 * no runtime allocator setter; it is redirected at compile time by the
 * -DMP_MALLOC/MP_REALLOC/MP_CALLOC/MP_FREE flags on its sources, which make
 * every call site reference the functions here. Their realloc/free signatures
 * carry the block size, which the Zend allocator does not need. This file
 * intentionally does not include any libtommath header, so the MP_* macros
 * never rewrite these definitions. */

#include "zend.h"

void *zend_mp_malloc(size_t size);
void *zend_mp_realloc(void *mem, size_t oldsize, size_t newsize);
void *zend_mp_calloc(size_t nmemb, size_t size);
void zend_mp_free(void *mem, size_t size);

void *zend_mp_malloc(size_t size)
{
	return emalloc(size);
}

void *zend_mp_realloc(void *mem, size_t oldsize, size_t newsize)
{
	(void) oldsize;
	return erealloc(mem, newsize);
}

void *zend_mp_calloc(size_t nmemb, size_t size)
{
	return ecalloc(nmemb, size);
}

void zend_mp_free(void *mem, size_t size)
{
	(void) size;
	efree(mem);
}
