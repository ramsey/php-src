<?php

#[Attribute]
class Tag {
    public function __construct(public int $value) {}
}

class Box {
    const BIG = 340282366920938463463374607431768211456;
    const FOLD = 9223372036854775807 + 1;
    public int $prop = 340282366920938463463374607431768211456;
}

#[Tag(340282366920938463463374607431768211456)]
class Tagged {}

function report(): void {
    echo Box::BIG . "\n";
    echo Box::FOLD . "\n";
    echo (new Box())->prop . "\n";
    $attr = (new ReflectionClass(Tagged::class))->getAttributes(Tag::class)[0];
    echo $attr->newInstance()->value . "\n";
    var_dump(Box::BIG === 340282366920938463463374607431768211456);
    var_dump(Box::FOLD === 9223372036854775808);
}

report();
