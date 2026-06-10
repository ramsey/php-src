--TEST--
Bigint: a bigint return does not satisfy a static return type
--FILE--
<?php

// A bigint must not satisfy a static return type (IS_BIGINT=15 collides with
// MAY_BE_STATIC=1<<15 in ZEND_TYPE masks, so the fast path must remap the
// type code to IS_LONG before calling ZEND_TYPE_CONTAINS_CODE).
class Base {
    public function make(): static {
        return new static();
    }
}

// Case 1: bigint return must throw TypeError
class Child extends Base {
    public function bigintReturn(): static {
        return 2 ** 100;
    }
}

try {
    new Child()->bigintReturn();
    echo "No error - BUG\n";
} catch (TypeError $e) {
    echo $e->getMessage() . "\n";
}

// Case 2: returning $this satisfies static (baseline sanity)
class GoodChild extends Base {
    public function selfReturn(): static {
        return $this;
    }
}

$obj = new GoodChild();
$ret = $obj->selfReturn();
var_dump($ret === $obj);

// Case 3: self return type; bigint must also throw TypeError
class WithSelf {
    public function f(): self {
        return 2 ** 100;
    }
}

try {
    new WithSelf()->f();
    echo "No error - BUG\n";
} catch (TypeError $e) {
    echo $e->getMessage() . "\n";
}

// Case 4: int return type; bigint must still be accepted (regression guard)
function intReturn(): int {
    return 2 ** 100;
}
var_dump(intReturn());

?>
--EXPECT--
Child::bigintReturn(): Return value must be of type Child, int returned
bool(true)
WithSelf::f(): Return value must be of type WithSelf, int returned
int(1267650600228229401496703205376)
