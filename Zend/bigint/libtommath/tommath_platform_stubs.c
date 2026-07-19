/* Stub definitions for libtommath platform-specific RNG functions that are
 * declared (with external linkage) in tommath_amalgam.c but only defined as
 * static functions inside platform-specific #if guards. On macOS/ARM64 none
 * of those guards fire, so the linker can't find the symbols.
 *
 * These stubs return MP_ERR, which causes libtommath to fall back to the next
 * available entropy source (s_read_urandom, which IS compiled on macOS).
 *
 * This file MUST NOT define LTM_ALL or include tommath_private.h; it only
 * provides the missing external symbols.
 *
 * TODO: Remove this after figuring out how to fix the issue with the linker and
 * the platform-specific #if guards.
 */

#include <stddef.h>

typedef int mp_err;
#define MP_ERR (-1)

mp_err s_read_arc4random(void *p, size_t n) { (void)p; (void)n; return MP_ERR; }
mp_err s_read_wincsp(void *p, size_t n)     { (void)p; (void)n; return MP_ERR; }
mp_err s_read_getrandom(void *p, size_t n)  { (void)p; (void)n; return MP_ERR; }
mp_err s_read_ltm_rng(void *p, size_t n)    { (void)p; (void)n; return MP_ERR; }
