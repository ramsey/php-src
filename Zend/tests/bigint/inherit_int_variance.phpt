--TEST--
bigint: widened int type stays compatible across inheritance
--FILE--
<?php
class MyCountable implements Countable {
    public function count(): int {
        return 3;
    }
}
echo count(new MyCountable()) . "\n";

class Base {
    public int $val = 0;
    public function get(int $x): int {
        return $x;
    }
}
class Derived extends Base {
    public int $val = 0;
    public function get(int $x): int {
        return $x + 1;
    }
}
echo (new Derived())->get(10) . "\n";
echo "no deprecations\n";
?>
--EXPECT--
3
11
no deprecations
