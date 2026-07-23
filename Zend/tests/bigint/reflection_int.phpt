--TEST--
bigint: reflection reports an int declaration as a named int type
--FILE--
<?php
function check(string $expr, mixed $result): void {
    echo $expr . ': ';
    var_dump($result);
}

function target(int $x, ?int $y): int {
    return $x;
}

$px = (new ReflectionParameter('target', 0))->getType();
check('int param is ReflectionNamedType', $px instanceof ReflectionNamedType);
check('int param name', $px->getName());
check('int param string', (string) $px);
check('int param isBuiltin', $px->isBuiltin());
check('int param allowsNull', $px->allowsNull());

$py = (new ReflectionParameter('target', 1))->getType();
check('?int param is ReflectionNamedType', $py instanceof ReflectionNamedType);
check('?int param string', (string) $py);
check('?int param allowsNull', $py->allowsNull());

$rt = (new ReflectionFunction('target'))->getReturnType();
check('int return is ReflectionNamedType', $rt instanceof ReflectionNamedType);
check('int return string', (string) $rt);
?>
--EXPECT--
int param is ReflectionNamedType: bool(true)
int param name: string(3) "int"
int param string: string(3) "int"
int param isBuiltin: bool(true)
int param allowsNull: bool(false)
?int param is ReflectionNamedType: bool(true)
?int param string: string(4) "?int"
?int param allowsNull: bool(true)
int return is ReflectionNamedType: bool(true)
int return string: string(3) "int"
