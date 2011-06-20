<?php
require_once "../rolex.php";
    
$obj = new stdClass;
$obj->foo = "bar";
$dev_null = null;
    
echo rolex\r::run( "instance variable access", function() use ($obj, $dev_null) {
    $dev_null = $obj->foo; // to test how long it takes to make the assignment
}, 10);

$foo = "test";
$dev_null = null;

echo rolex\r::run( "straight-up variable access", function() use ($dev_null, $foo) {
    $dev_null = $foo;
}, 10);