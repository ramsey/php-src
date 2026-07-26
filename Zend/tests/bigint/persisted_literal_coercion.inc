<?php

function coerce(): void {
    $length = 340282366920938463463374607431768211456;
    echo strlen($length) . "\n";

    $float = 340282366920938463463374607431768211456;
    settype($float, 'float');
    var_dump($float);

    $bool = 340282366920938463463374607431768211456;
    settype($bool, 'bool');
    var_dump($bool);

    $string = 340282366920938463463374607431768211456;
    settype($string, 'string');
    var_dump($string);

    $intact = 340282366920938463463374607431768211456;
    echo $intact . "\n";
}

for ($i = 0; $i < 6; $i++) {
    coerce();
}

echo 'survived' . "\n";
