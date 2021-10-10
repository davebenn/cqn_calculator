<?php

$tests = [];

//$tests[] = [ 'price' => 100000, 'ftb' => true,    'b2l' => false];
//$tests[] = [ 'price' => 200000, 'ftb' => true,    'b2l' => false];
//$tests[] = [ 'price' => 290000, 'ftb' => true,    'b2l' => false];
//$tests[] = [ 'price' => 300000, 'ftb' => true,    'b2l' => false];
//$tests[] = [ 'price' => 350000, 'ftb' => true,    'b2l' => false];
//$tests[] = [ 'price' => 400000, 'ftb' => true,    'b2l' => false];
//$tests[] = [ 'price' => 600000, 'ftb' => true,    'b2l' => false];
//$tests[] = [ 'price' => 1010000, 'ftb' => true,   'b2l' => false];
//$tests[] = [ 'price' => 1610000, 'ftb' => true,   'b2l' => false];
//
//$tests[] = [ 'price' => 100000, 'ftb' => true,    'b2l' => true];
//$tests[] = [ 'price' => 200000, 'ftb' => true,    'b2l' => true];
//$tests[] = [ 'price' => 290000, 'ftb' => true,    'b2l' => true];
//$tests[] = [ 'price' => 300000, 'ftb' => true,    'b2l' => true];
//$tests[] = [ 'price' => 350000, 'ftb' => true,    'b2l' => true];
//$tests[] = [ 'price' => 400000, 'ftb' => true,    'b2l' => true];
//$tests[] = [ 'price' => 600000, 'ftb' => true,    'b2l' => true];
//$tests[] = [ 'price' => 1010000, 'ftb' => true,   'b2l' => true];
//$tests[] = [ 'price' => 1610000, 'ftb' => true,   'b2l' => true];

$tests[] = [ 'price' => 100000, 'ftb' => false,   'b2l' => false];
$tests[] = [ 'price' => 200000, 'ftb' => false,   'b2l' => false];
$tests[] = [ 'price' => 290000, 'ftb' => false,   'b2l' => false];
$tests[] = [ 'price' => 300000, 'ftb' => false,   'b2l' => false];
$tests[] = [ 'price' => 350000, 'ftb' => false,   'b2l' => false];
$tests[] = [ 'price' => 400000, 'ftb' => false,   'b2l' => false];
$tests[] = [ 'price' => 600000, 'ftb' => false,   'b2l' => false];
$tests[] = [ 'price' => 1010000, 'ftb' => false,  'b2l' => false];
$tests[] = [ 'price' => 1610000, 'ftb' => false,  'b2l' => false];

$tests[] = [ 'price' => 100000, 'ftb' => false,  'b2l' => true];
$tests[] = [ 'price' => 200000, 'ftb' => false,  'b2l' => true];
$tests[] = [ 'price' => 290000, 'ftb' => false,  'b2l' => true];
$tests[] = [ 'price' => 300000, 'ftb' => false,  'b2l' => true];
$tests[] = [ 'price' => 350000, 'ftb' => false,  'b2l' => true];
$tests[] = [ 'price' => 400000, 'ftb' => false,  'b2l' => true];
$tests[] = [ 'price' => 600000, 'ftb' => false,  'b2l' => true];
$tests[] = [ 'price' => 1010000, 'ftb' => false, 'b2l' => true];
$tests[] = [ 'price' => 1610000, 'ftb' => false, 'b2l' => true];

echo "\n------------------\n";
include 'CQN_Calculator_Config.php';

//$config = new CQN_Calculator_Config();

foreach( $tests as $test ){
	echo  "\n£" . $test['price'] .
		'  first-time-buyer ' . ($test['ftb']? 'Y' : 'N' ) .
		'  buy-to-let ' . ($test['b2l']? 'Y' : 'N' ) .
		' = '  . CQN_Calculator_Config::getStampDuty( $test['price'], $test['ftb'], $test['b2l'] );
}

echo "\n------------------\n";






