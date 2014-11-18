<?php

require_once './classes/CQN_Calculator_Config.php';
require_once './classes/CQN_Calculator_Submission.php';
require_once '../../../../wp-blog-header.php';

echo "\n";

$config = new CQN_Calculator_Config;


$sub = new CQN_Calculator_Submission( $config );

$sub->loadFromPost( [   'quoteType' => 'remortgage',
                        'remortgage_involves_transfer' => 1,
                        'remortgage_no_of_people' => 2,
                        'transfer_no_of_people' => 2,
                        'transfer_leasehold' => 1,
                        'transfer_price'        => 33321,
                        'remortgage_price'        => 25330,
                        'sale_price'        => 1033033,
                        'purchase_price'        => 13300,
                        'contact_email'        => 'dave@dbennet.ts',

] );

$sub->calculate();
//
//echo $sub->getTextQuote();
//echo "\n";
//
//foreach( $sub->getDisbursements() as $db ){
//    echo "\n ". $db->code . " : ". $db->name . " - " . number_format( $db->price );
//}

$valid = $sub->validate();


print_r( $sub->errors );


