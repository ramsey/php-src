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

#include "zend_bigint.h"

/*
 * There are two bigint backends: GMP and libtommath.
 *
 * - libtommath is enabled and built by default, with its source bundled with PHP.
 *   Its implementation of zend_bigint.h is in bigint/zend_bigint_libtommath.c
 * - GMP can be used instead with the --with-bigint-gmp configure option.
 *   Its implementation of zend_bigint.h is in bigint/zend_bigint_gmp.c
 *
 * One or the other actually implement zend_bigint.h, this file isn't used.
 */

#if defined(ZEND_BIGINT_USE_LIBTOMMATH)
#	include "bigint/zend_bigint_libtommath.c"
#elif defined(ZEND_BIGINT_USE_GMP)
#	include "bigint/zend_bigint_gmp.c"
#elif defined(ZEND_BIGINT_USE_PLACEHOLDER)
#	include "bigint/zend_bigint_placeholder.c"
#else
#	error Neither LibTomMath nor GMP available
#endif
