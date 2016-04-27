<?php

require_once './classes/CQN_Calculator_Config.php';
require_once './classes/CQN_Calculator_Submission.php';
require_once '../../../../wp-blog-header.php';


echo "\n";

$config = new CQN_Calculator_Config;

$prices = [ 50000, 400001, 185000, 275000, 510000, 937500, 1600000, 2100000];


echo "\nStamp Duty ";
echo "\n=============";

foreach ($prices as $price){
//    echo "\n"  . number_format( $price ) . '  -  ' . number_format( $config->getRemortgageFees($price));
    echo "\n"  . number_format( $price ) . '  -  ' . number_format( $config->getStampDuty($price, true, true));
}




//foreach ($prices as $price){
//    echo "\n"  . number_format( $price ) . '  -  ' . number_format( $config->getStampDuty($price, false));
//}



//
//
//$sub = new CQN_Calculator_Submission( $config );
//
//$sub->loadFromPost( [   'quote_type' => 'sale_purchase',
//                        'purchase_no_of_buyers' => 2,
//                        'purchase_leasehold' => true,
//                        'purchase_price'     => 350000,
//                        'purchase_1st_time_buyer'     => true,
//                        'sale_price'         => 400000,
//                        'sale_leasehold'         => true,
//                        'sale_price'         => 400000,
//                        'discount_code'         => 'dbhalved12',
//
//] );
//
//$sub->calculate();
//
//echo $sub->getTextQuote();
//echo "\n";
//
//foreach( $sub->getDisbursements() as $db ){
//    echo "\n ". $db->code . " : ". $db->name . " - " . number_format( $db->price, 2 );
//}
//
//
//
//
//
//$sub = new CQN_Calculator_Submission( $config );
//
//$sub->loadFromPost( [   'quote_type' => 'remortgage',
//                        'remortgage_involves_transfer' => 1,
//                        'remortgage_no_of_people' => 2,
//                        'transfer_no_of_people' => 2,
//                        'transfer_leasehold' => 1,
//                        'transfer_price'        => 80000,
//                        'remortgage_price'        => 250000,
//
//] );
//
//$sub->calculate();
//
//echo $sub->getTextQuote();
//echo "\n";
//
//foreach( $sub->getDisbursements() as $db ){
//    echo "\n ". $db->code . " : ". $db->name . " - " . number_format( $db->price );
//}
//


//
//
//
//
//
//
//$sub = new CQN_Calculator_Submission( $config );
//
//$sub->loadFromPost( [   'quote_type' => 'remortgage',
//                        'remortgage_no_of_people' => 7,
//                        'remortgage_price'        => 298000,
//] );
//
//$sub->calculate();
//
//echo $sub->getTextQuote();
//echo "\n";
//
//foreach( $sub->getDisbursements() as $db ){
//    echo "\n ". $db->name . " - " . number_format( $db->price );
//}
//
//
//
//
//
//
//



//
//$sub = new CQN_Calculator_Submission( $config );
//
//$sub->loadFromPost( [   'quote_type' => 'sale_purchase',
//                        'purchase_no_of_buyers' => 2,
//                        'purchase_price'        => 198000,
//                        'sale_price'            => 400000,
//                        'purchase_1st_time_buyer' => true
//] );
//
//$sub->calculate();
//
//echo $sub->getTextQuote();
//echo "\n";
//
//foreach( $sub->getDisbursements() as $db ){
//    echo "\n ". $db->name . " - " . number_format( $db->price );
//}
//
//










//$sub = new CQN_Calculator_Submission( $config );
//
//$sub->loadFromPost( [   'quote_type' => 'sale',
//                        'sale_price' => 123000 ] );
//
//$sub->calculate();
//
//echo $sub->getTextQuote();
//



//print_r($sub);
//global $wpdb;
//print_r($wpdb);
//    print_r( $config->getSaleDisbursements() );

//
//$prices = [ 50000, 120000, 180000, 501000, 900000, 1200000, 2200000 ];
//
//
//echo "\nStamp Duty ";
//echo "\n=============";
//
//foreach ($prices as $price){
//    echo "\n"  . number_format( $price ) . '  -  ' . number_format( $config->getStampDuty($price, false));
//}
//
//
//echo "\n";
//echo "\nStamp Duty (1st time buyer)";
//echo "\n===========================";
//
//foreach ($prices as $price){
//
//    echo "\n"  . number_format( $price ) . '  -  ' . number_format( $config->getStampDuty($price, true));
//
//}
//
//echo "\n";
//
//$codes = [ 'CSUK25', 'TEST-CODE-912' , 'some', 'TEST-CODE-2123'    ];
//
//foreach( $codes as $code){
//    $discount =  $config->checkDiscountCode( $code );
//    if( $discount ){
//        echo "\n'$code' -- " . "'$discount->name' ( $discount->type of $discount->amount  )";
//
//    }else{
//        echo "\n'$code' -- " . ' Not Valid' ;
//    }
//}

// echo "\n";
//echo "\n SP LR Fees";
//echo "\n ======================= ";
//echo "\n 30000 = "   . $config->getSPLandRegistryFees(   30000 );
//echo "\n 70000 = "   . $config->getSPLandRegistryFees(   70000 );
//echo "\n 90000 = "  .  $config->getSPLandRegistryFees(   90000 );
//echo "\n 150000 = "  . $config->getSPLandRegistryFees(  150000 );
//echo "\n 300000 = "  . $config->getSPLandRegistryFees(  300000 );
//echo "\n 600000 = "  . $config->getSPLandRegistryFees(  600000 );
//echo "\n 800000 = "  . $config->getSPLandRegistryFees(  800000 );
//echo "\n 1200000 = " . $config->getSPLandRegistryFees( 1200000 );
//echo "\n 2400000 = " . $config->getSPLandRegistryFees( 2400000 );
//echo "\n";
//
//echo "\n";
//echo "\n RT LR Fees";
//echo "\n ======================= ";
//echo "\n 50000 = "   . $config->getRTLandRegistryFees(   50000 );
//echo "\n 70000 = "   . $config->getRTLandRegistryFees(   70000 );
//echo "\n 110000 = "  . $config->getRTLandRegistryFees(  110000 );
//echo "\n 220000 = "  . $config->getRTLandRegistryFees(  220000 );
//echo "\n 400000 = "  . $config->getRTLandRegistryFees(  400000 );
//echo "\n 600000 = "  . $config->getRTLandRegistryFees(  600000 );
//echo "\n 800000 = "  . $config->getRTLandRegistryFees(  800000 );
//echo "\n 1200000 = " . $config->getRTLandRegistryFees( 1200000 );
//echo "\n 2400000 = " . $config->getRTLandRegistryFees( 2400000 );
//echo "\n";
//
////
//foreach( $config->getSaleDisbursements() as $d ){
//
//    echo "\n " . $d->name .  ' -- ' . $d->price;
//
//}

//
//echo "\n";
//echo "\n Purchase ";
//echo "\n ======================= ";
//echo "\n 50000 = "   . $config->getPurchaseFees(   50000 );
//echo "\n 100000 = "  . $config->getPurchaseFees(  100000 );
//echo "\n 200000 = "  . $config->getPurchaseFees(  200000 );
//echo "\n 400000 = "  . $config->getPurchaseFees(  400000 );
//echo "\n 600000 = "  . $config->getPurchaseFees(  600000 );
//echo "\n 800000 = "  . $config->getPurchaseFees(  800000 );
//echo "\n 1200000 = " . $config->getPurchaseFees( 1200000 );
//echo "\n 2400000 = " . $config->getPurchaseFees( 2400000 );
//echo "\n";
//
//echo "\n";
//echo "\n Sale ";
//echo "\n ======================= ";
//echo "\n 50000 = "   . $config->getSaleFees(   50000 );
//echo "\n 100000 = "  . $config->getSaleFees(  100000 );
//echo "\n 200000 = "  . $config->getSaleFees(  200000 );
//echo "\n 400000 = "  . $config->getSaleFees(  400000 );
//echo "\n 600000 = "  . $config->getSaleFees(  600000 );
//echo "\n 800000 = "  . $config->getSaleFees(  800000 );
//echo "\n 1200000 = " . $config->getSaleFees( 1200000 );
//echo "\n 2400000 = " . $config->getSaleFees( 2400000 );
//echo "\n";
//
//echo "\n";
//echo "\n Remortgage ";
//echo "\n ======================= ";
//echo "\n 50000 = "   . $config->getRemortgageFees(   50000 );
//echo "\n 100000 = "  . $config->getRemortgageFees(  100000 );
//echo "\n 200000 = "  . $config->getRemortgageFees(  200000 );
//echo "\n 400000 = "  . $config->getRemortgageFees(  400000 );
//echo "\n 600000 = "  . $config->getRemortgageFees(  600000 );
//echo "\n 800000 = "  . $config->getRemortgageFees(  800000 );
//echo "\n 1200000 = " . $config->getRemortgageFees( 1200000 );
//echo "\n 2400000 = " . $config->getRemortgageFees( 2400000 );
//echo "\n";
//
//echo "\n";
//echo "\n Transfer ";
//echo "\n ======================= ";
//echo "\n 50000 = "   . $config->getTransferFees(   50000 );
//echo "\n 100000 = "  . $config->getTransferFees(  100000 );
//echo "\n 200000 = "  . $config->getTransferFees(  200000 );
//echo "\n 400000 = "  . $config->getTransferFees(  400000 );
//echo "\n 600000 = "  . $config->getTransferFees(  600000 );
//echo "\n 800000 = "  . $config->getTransferFees(  800000 );
//echo "\n 1200000 = " . $config->getTransferFees( 1200000 );
//echo "\n 2400000 = " . $config->getTransferFees( 2400000 );
//echo "\n";
echo "\n";