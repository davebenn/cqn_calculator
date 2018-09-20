<?php

$tests = [];

$tests[] = [ 'price' => 100000, 'ftb' => true,    'b2l' => false];
$tests[] = [ 'price' => 200000, 'ftb' => true,    'b2l' => false];
$tests[] = [ 'price' => 290000, 'ftb' => true,    'b2l' => false];
$tests[] = [ 'price' => 300000, 'ftb' => true,    'b2l' => false];
$tests[] = [ 'price' => 350000, 'ftb' => true,    'b2l' => false];
$tests[] = [ 'price' => 400000, 'ftb' => true,    'b2l' => false];
$tests[] = [ 'price' => 600000, 'ftb' => true,    'b2l' => false];
$tests[] = [ 'price' => 1010000, 'ftb' => true,   'b2l' => false];
$tests[] = [ 'price' => 1610000, 'ftb' => true,   'b2l' => false];

$tests[] = [ 'price' => 100000, 'ftb' => false,   'b2l' => true];
$tests[] = [ 'price' => 200000, 'ftb' => false,   'b2l' => true];
$tests[] = [ 'price' => 290000, 'ftb' => false,   'b2l' => true];
$tests[] = [ 'price' => 300000, 'ftb' => false,   'b2l' => true];
$tests[] = [ 'price' => 350000, 'ftb' => false,   'b2l' => true];
$tests[] = [ 'price' => 400000, 'ftb' => false,   'b2l' => true];
$tests[] = [ 'price' => 600000, 'ftb' => false,   'b2l' => true];
$tests[] = [ 'price' => 1010000, 'ftb' => false,  'b2l' => true];
$tests[] = [ 'price' => 1610000, 'ftb' => false,  'b2l' => true];

//$tests[] = [ 'price' => 100000, 'ftb' => false,  'b2l' => true];
//$tests[] = [ 'price' => 200000, 'ftb' => false,  'b2l' => true];
//$tests[] = [ 'price' => 290000, 'ftb' => false,  'b2l' => true];
//$tests[] = [ 'price' => 300000, 'ftb' => false,  'b2l' => true];
//$tests[] = [ 'price' => 350000, 'ftb' => false,  'b2l' => true];
//$tests[] = [ 'price' => 400000, 'ftb' => false,  'b2l' => true];
//$tests[] = [ 'price' => 600000, 'ftb' => false,  'b2l' => true];
//$tests[] = [ 'price' => 1010000, 'ftb' => false, 'b2l' => true];
//$tests[] = [ 'price' => 1610000, 'ftb' => false, 'b2l' => true];

echo "\n------------------\n";

foreach( $tests as $test ){
	echo  "\n£" . $test['price'] .
		'  first-time-buyer ' . ($test['ftb']? 'Y' : 'N' ) .
		'  buy-to-let ' . ($test['b2l']? 'Y' : 'N' ) .
		' = '  . getStampDuty( $test['price'], $test['ftb'], $test['b2l'] );
}

echo "\n------------------\n";




function getStampDuty($propertyPrice, $firstTimeBuyer = false, $buyToLet = false){

	$buyToLetRate = 0;

	if ($buyToLet == true) {
		$buyToLetRate = 0.03;
	}

	if ( !$firstTimeBuyer || $buyToLet == true ) {
		if ($propertyPrice > 1500000) {
			$total = ($propertyPrice - 1500000) * (0.12 + $buyToLetRate);
			$total += (1500000 - 925000) * (0.10 + $buyToLetRate);
			$total += (925000 - 250000) * (0.05 + $buyToLetRate);
			$total += (250000 - 125000) * (0.02 + $buyToLetRate);
			$total += (125000) * (0.0 + $buyToLetRate);
		} elseif ($propertyPrice > 925000) {
			$total = ($propertyPrice - 925000) * (0.10 + $buyToLetRate);
			$total += (925000 - 250000) * (0.05 + $buyToLetRate);
			$total += (250000 - 125000) * (0.02 + $buyToLetRate);
			$total += (125000) * (0.0 + $buyToLetRate);
		} elseif ($propertyPrice > 250000) {
			$total = ($propertyPrice - 250000) * (0.05 + $buyToLetRate);
			$total += (250000 - 125000) * (0.02 + $buyToLetRate);
			$total += (125000) * (0.0 + $buyToLetRate);
		} elseif ($propertyPrice > 125000) {
			$total = ($propertyPrice - 125000) * (0.02 + $buyToLetRate);
			$total += (125000) * (0.0 + $buyToLetRate);
		} else {
			$total = 0;
		}
	} else {
		if ($propertyPrice > 1500000) {
			$total = ($propertyPrice - 1500000) * 0.12 ;
			$total += (1500000 - 925000) * 0.10 ;
			$total += (925000 - 250000) * 0.05 ;
			$total += (250000 - 125000) * 0.02 ;
			$total += (125000) * 0.0 ;
		} elseif ($propertyPrice > 925000) {
			$total = ($propertyPrice - 925000) * 0.10 ;
			$total += (925000 - 250000) * 0.05 ;
			$total += (250000 - 125000) * 0.02 ;
			$total += 0;//(125000) * 0.0 ;
		} elseif ($propertyPrice > 500000) {
			$total = ($propertyPrice - 250000) * 0.05 ;
			$total += (250000 - 125000) * 0.02 ;
		} elseif ($propertyPrice > 300000) {
// amount over 300 * 0.5
			$total = ($propertyPrice - 300000) * 0.05 ;
		} else {
			$total = 0;
		}
	}

	return $total;
}



