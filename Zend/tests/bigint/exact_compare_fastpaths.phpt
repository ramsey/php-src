--TEST--
bigint: inline compare fast paths stay exact beyond the float significand
--FILE--
<?php
function check(string $expr, mixed $result): void {
    echo $expr . ': ';
    var_dump($result);
}

$pairs = [
    '2 ** 63 - 1' => [2 ** 63 - 1, (float) (2 ** 63 - 1)],
    '2 ** 53 + 1' => [2 ** 53 + 1, (float) (2 ** 53 + 1)],
];

foreach ($pairs as $label => [$l, $d]) {
    for ($i = 0; $i < 3; $i++) {
        check($label . ' == float (int on left)', $l == $d);
        check($label . ' != float (int on left)', $l != $d);
        check($label . ' < float (int on left)', $l < $d);
        check($label . ' <= float (int on left)', $l <= $d);
        check($label . ' >= float (int on left)', $l >= $d);
        check($label . ' <=> float (int on left)', $l <=> $d);
        check($label . ' == float (float on left)', $d == $l);
        check($label . ' != float (float on left)', $d != $l);
        check($label . ' < float (float on left)', $d < $l);
        check($label . ' <= float (float on left)', $d <= $l);
        check($label . ' <=> float (float on left)', $d <=> $l);
    }
}

$big = PHP_INT_MAX;
$nan = NAN;
check('PHP_INT_MAX == NAN (int on left)', $big == $nan);
check('PHP_INT_MAX < NAN (int on left)', $big < $nan);
check('PHP_INT_MAX <= NAN (int on left)', $big <= $nan);
check('PHP_INT_MAX != NAN (int on left)', $big != $nan);
check('NAN == PHP_INT_MAX (float on left)', $nan == $big);
check('NAN < PHP_INT_MAX (float on left)', $nan < $big);
check('NAN <= PHP_INT_MAX (float on left)', $nan <= $big);
check('NAN != PHP_INT_MAX (float on left)', $nan != $big);

$val = 2 ** 53 + 1;
switch ($val) {
    case (float) (2 ** 53 + 1):
        echo "switch: float-arm\n";
        break;
    default:
        echo "switch: default\n";
}

$dsubject = (float) (2 ** 53 + 1);
switch ($dsubject) {
    case 2 ** 53 + 1:
        echo "switch (float subject): int-arm\n";
        break;
    default:
        echo "switch (float subject): default\n";
}

check('in_array(2 ** 63 - 1, [(float) (2 ** 63 - 1)])', in_array(2 ** 63 - 1, [(float) (2 ** 63 - 1)]));
check('in_array(2 ** 53 + 1, [(float) (2 ** 53 + 1)])', in_array(2 ** 53 + 1, [(float) (2 ** 53 + 1)]));
check('in_array((float) (2 ** 63 - 1), [2 ** 63 - 1])', in_array((float) (2 ** 63 - 1), [2 ** 63 - 1]));

function persisted_cmp(float $d): array {
    return [9223372036854775807 == $d, 9223372036854775807 < $d];
}
var_dump(persisted_cmp((float) (2 ** 63 - 1)));
var_dump(persisted_cmp((float) (2 ** 63 - 1)));
?>
--EXPECT--
2 ** 63 - 1 == float (int on left): bool(false)
2 ** 63 - 1 != float (int on left): bool(true)
2 ** 63 - 1 < float (int on left): bool(true)
2 ** 63 - 1 <= float (int on left): bool(true)
2 ** 63 - 1 >= float (int on left): bool(false)
2 ** 63 - 1 <=> float (int on left): int(-1)
2 ** 63 - 1 == float (float on left): bool(false)
2 ** 63 - 1 != float (float on left): bool(true)
2 ** 63 - 1 < float (float on left): bool(false)
2 ** 63 - 1 <= float (float on left): bool(false)
2 ** 63 - 1 <=> float (float on left): int(1)
2 ** 63 - 1 == float (int on left): bool(false)
2 ** 63 - 1 != float (int on left): bool(true)
2 ** 63 - 1 < float (int on left): bool(true)
2 ** 63 - 1 <= float (int on left): bool(true)
2 ** 63 - 1 >= float (int on left): bool(false)
2 ** 63 - 1 <=> float (int on left): int(-1)
2 ** 63 - 1 == float (float on left): bool(false)
2 ** 63 - 1 != float (float on left): bool(true)
2 ** 63 - 1 < float (float on left): bool(false)
2 ** 63 - 1 <= float (float on left): bool(false)
2 ** 63 - 1 <=> float (float on left): int(1)
2 ** 63 - 1 == float (int on left): bool(false)
2 ** 63 - 1 != float (int on left): bool(true)
2 ** 63 - 1 < float (int on left): bool(true)
2 ** 63 - 1 <= float (int on left): bool(true)
2 ** 63 - 1 >= float (int on left): bool(false)
2 ** 63 - 1 <=> float (int on left): int(-1)
2 ** 63 - 1 == float (float on left): bool(false)
2 ** 63 - 1 != float (float on left): bool(true)
2 ** 63 - 1 < float (float on left): bool(false)
2 ** 63 - 1 <= float (float on left): bool(false)
2 ** 63 - 1 <=> float (float on left): int(1)
2 ** 53 + 1 == float (int on left): bool(false)
2 ** 53 + 1 != float (int on left): bool(true)
2 ** 53 + 1 < float (int on left): bool(false)
2 ** 53 + 1 <= float (int on left): bool(false)
2 ** 53 + 1 >= float (int on left): bool(true)
2 ** 53 + 1 <=> float (int on left): int(1)
2 ** 53 + 1 == float (float on left): bool(false)
2 ** 53 + 1 != float (float on left): bool(true)
2 ** 53 + 1 < float (float on left): bool(true)
2 ** 53 + 1 <= float (float on left): bool(true)
2 ** 53 + 1 <=> float (float on left): int(-1)
2 ** 53 + 1 == float (int on left): bool(false)
2 ** 53 + 1 != float (int on left): bool(true)
2 ** 53 + 1 < float (int on left): bool(false)
2 ** 53 + 1 <= float (int on left): bool(false)
2 ** 53 + 1 >= float (int on left): bool(true)
2 ** 53 + 1 <=> float (int on left): int(1)
2 ** 53 + 1 == float (float on left): bool(false)
2 ** 53 + 1 != float (float on left): bool(true)
2 ** 53 + 1 < float (float on left): bool(true)
2 ** 53 + 1 <= float (float on left): bool(true)
2 ** 53 + 1 <=> float (float on left): int(-1)
2 ** 53 + 1 == float (int on left): bool(false)
2 ** 53 + 1 != float (int on left): bool(true)
2 ** 53 + 1 < float (int on left): bool(false)
2 ** 53 + 1 <= float (int on left): bool(false)
2 ** 53 + 1 >= float (int on left): bool(true)
2 ** 53 + 1 <=> float (int on left): int(1)
2 ** 53 + 1 == float (float on left): bool(false)
2 ** 53 + 1 != float (float on left): bool(true)
2 ** 53 + 1 < float (float on left): bool(true)
2 ** 53 + 1 <= float (float on left): bool(true)
2 ** 53 + 1 <=> float (float on left): int(-1)
PHP_INT_MAX == NAN (int on left): bool(false)
PHP_INT_MAX < NAN (int on left): bool(false)
PHP_INT_MAX <= NAN (int on left): bool(false)
PHP_INT_MAX != NAN (int on left): bool(true)
NAN == PHP_INT_MAX (float on left): bool(false)
NAN < PHP_INT_MAX (float on left): bool(false)
NAN <= PHP_INT_MAX (float on left): bool(false)
NAN != PHP_INT_MAX (float on left): bool(true)
switch: default
switch (float subject): default
in_array(2 ** 63 - 1, [(float) (2 ** 63 - 1)]): bool(false)
in_array(2 ** 53 + 1, [(float) (2 ** 53 + 1)]): bool(false)
in_array((float) (2 ** 63 - 1), [2 ** 63 - 1]): bool(false)
array(2) {
  [0]=>
  bool(false)
  [1]=>
  bool(true)
}
array(2) {
  [0]=>
  bool(false)
  [1]=>
  bool(true)
}
