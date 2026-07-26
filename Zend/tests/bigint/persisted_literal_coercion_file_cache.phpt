--TEST--
bigint: coercing a file-cached big literal leaves the literal intact
--EXTENSIONS--
opcache
--SKIPIF--
<?php
if (PHP_OS_FAMILY === 'Windows') die('skip file cache subprocess pattern is POSIX-only here');
?>
--FILE--
<?php
$dir = __DIR__ . '/coercion_fc_' . getmypid();
@mkdir($dir);
$inc = __DIR__ . '/persisted_literal_coercion.inc';

function run(string $dir, string $inc): string {
    $spec = [1 => ['pipe', 'w'], 2 => ['pipe', 'w']];
    $proc = proc_open([
        PHP_BINARY,
        '-n',
        '-d', 'opcache.enable_cli=1',
        '-d', 'opcache.file_cache=' . $dir,
        '-d', 'opcache.file_cache_only=1',
        '-d', 'opcache.file_update_protection=0',
        '-d', 'opcache.jit=disable',
        '-d', 'opcache.jit_buffer_size=0',
        $inc,
    ], $spec, $pipes);
    $out = stream_get_contents($pipes[1]);
    fclose($pipes[1]);
    $err = stream_get_contents($pipes[2]);
    fclose($pipes[2]);
    proc_close($proc);
    return $out . $err;
}

echo run($dir, $inc);
echo run($dir, $inc);

$it = new RecursiveIteratorIterator(
    new RecursiveDirectoryIterator($dir, FilesystemIterator::SKIP_DOTS),
    RecursiveIteratorIterator::CHILD_FIRST,
);
foreach ($it as $entry) {
    $entry->isDir() ? rmdir($entry->getPathname()) : unlink($entry->getPathname());
}
rmdir($dir);
?>
--EXPECT--
39
float(3.402823669209385E+38)
bool(true)
string(39) "340282366920938463463374607431768211456"
340282366920938463463374607431768211456
39
float(3.402823669209385E+38)
bool(true)
string(39) "340282366920938463463374607431768211456"
340282366920938463463374607431768211456
39
float(3.402823669209385E+38)
bool(true)
string(39) "340282366920938463463374607431768211456"
340282366920938463463374607431768211456
39
float(3.402823669209385E+38)
bool(true)
string(39) "340282366920938463463374607431768211456"
340282366920938463463374607431768211456
39
float(3.402823669209385E+38)
bool(true)
string(39) "340282366920938463463374607431768211456"
340282366920938463463374607431768211456
39
float(3.402823669209385E+38)
bool(true)
string(39) "340282366920938463463374607431768211456"
340282366920938463463374607431768211456
survived
39
float(3.402823669209385E+38)
bool(true)
string(39) "340282366920938463463374607431768211456"
340282366920938463463374607431768211456
39
float(3.402823669209385E+38)
bool(true)
string(39) "340282366920938463463374607431768211456"
340282366920938463463374607431768211456
39
float(3.402823669209385E+38)
bool(true)
string(39) "340282366920938463463374607431768211456"
340282366920938463463374607431768211456
39
float(3.402823669209385E+38)
bool(true)
string(39) "340282366920938463463374607431768211456"
340282366920938463463374607431768211456
39
float(3.402823669209385E+38)
bool(true)
string(39) "340282366920938463463374607431768211456"
340282366920938463463374607431768211456
39
float(3.402823669209385E+38)
bool(true)
string(39) "340282366920938463463374607431768211456"
340282366920938463463374607431768211456
survived
