# libtommath (bundled)

PHP bundles libtommath as the default backend for arbitrary-precision integers.

- Upstream: https://github.com/libtom/libtommath
- Version: v1.3.0
- License: The Unlicense (see LICENSE)
- Build flags applied by PHP: -DMP_NO_FILE -DMP_PREC=8 -DMP_MALLOC=zend_mp_malloc
  -DMP_REALLOC=zend_mp_realloc -DMP_CALLOC=zend_mp_calloc -DMP_FREE=zend_mp_free
  (see configure.ac). No -DMP_64BIT/-DMP_32BIT: libtommath detects the digit
  width from the platform on its own.
- Local modifications: none to the vendored files (all configuration is via
  compile flags). The MP_MALLOC/MP_REALLOC/MP_CALLOC/MP_FREE flags route every
  allocation to `zend_mp_malloc`/`zend_mp_realloc`/`zend_mp_calloc`/`zend_mp_free`
  in `../zend_bigint_libtommath_alloc.c`, which implement them over the
  Zend allocator (emalloc/erealloc/ecalloc/efree).

## Vendored files

| File                       | Origin                                                                                                                                                                                                                                                                                                                                                  |
|----------------------------|---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------|
| `tommath_amalgam.c`        | Generated from the source tree by running `perl gen.pl` in the upstream root, which concatenates all `bn*.c` sources into a single file. The upstream repository calls this `mpi.c`; we rename it `tommath_amalgam.c` for clarity and since this is the name used in the newer `develop` branch when running `make pre_gen` (see notes below).          |
| `tommath.h`                | Copied verbatim from the upstream root.                                                                                                                                                                                                                                                                                                                 |
| `tommath_private.h`        | Private header required by `tommath_amalgam.c`; includes `tommath.h` and `tommath_class.h`.                                                                                                                                                                                                                                                             |
| `tommath_class.h`          | Private header that maps function aliases; includes `tommath_superclass.h`.                                                                                                                                                                                                                                                                             |
| `tommath_cutoffs.h`        | Private header defining algorithm-selection thresholds; required by `tommath_amalgam.c`.                                                                                                                                                                                                                                                                |
| `tommath_superclass.h`     | Private header defining the superclass feature-selection macros; required by `tommath_class.h`.                                                                                                                                                                                                                                                         |
| `LICENSE`                  | Upstream Unlicense file, copied verbatim.                                                                                                                                                                                                                                                                                                               |
| `tommath_platform_stubs.c` | Not from upstream. Stubs four RNG symbols (`s_read_arc4random`, `s_read_wincsp`, `s_read_getrandom`, `s_read_ltm_rng`) that `tommath_amalgam.c` declares with external linkage but only defines inside platform-specific `#if` guards; on platforms where none of the guards fire, the linker otherwise fails. See the comment in the file for details. |

## Updating

To update to a newer tagged release:

1. Download the release tarball from https://github.com/libtom/libtommath/releases.
2. In the extracted source root, run `perl gen.pl` to produce `mpi.c`.
   - The 1.3.0 tagged release used `perl gen.pl` to generate `mpi.c`. However, the `develop` branch uses `make pre_gen` to generate `pre_gen/tommath_amalgam.c`.
3. Replace `tommath_amalgam.c` with the newly generated `mpi.c`.
   - If using `make pre_gen`, replace `tommath_amalgam.c` with the newly generated `pre_gen/tommath_amalgam.c`.
4. Replace `tommath.h`, `tommath_private.h`, `tommath_class.h`, `tommath_cutoffs.h`,
   `tommath_superclass.h`, and `LICENSE` with their counterparts from the new release.
5. Verify the set is still self-contained:
   ```
   cc -c -DMP_NO_FILE -DMP_PREC=8 -DMP_MALLOC=zend_mp_malloc -DMP_REALLOC=zend_mp_realloc \
      -DMP_CALLOC=zend_mp_calloc -DMP_FREE=zend_mp_free -I Zend/bigint/libtommath \
      Zend/bigint/libtommath/tommath_amalgam.c -o /tmp/ltm_check.o
   ```
6. Update the version recorded in this file and in README.REDIST.BINS.

Do not edit any of the vendored files in place; all configuration is done via compile flags.
