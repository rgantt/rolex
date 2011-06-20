<?php namespace rolex;
require_once "../lib/rolex.php";
    
$obj = new \stdClass;
$obj->foo = "bar";
$dev_null = null;
$foo = "test";
    
echo r::run( "instance variable access", function() use ($obj, $dev_null) {
    $dev_null = $obj->foo; // to test how long it takes to make the assignment
}, 1);

echo r::run( "straight-up variable access", function() use ($dev_null, $foo) {
    $dev_null = $foo;
}, 1);