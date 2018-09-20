<?php

// This script allows you to manually process entries in the database which were not sent to the webleads system for some reason //
//
// path for storage directory is manually configured

require_once('../wp-blog-header.php');


require_once '../wp-content/plugins/cqn_calculator2/includes/classes/CQN_Calculator_Config.php';
require_once '../wp-content/plugins/cqn_calculator2/includes/classes/CQN_Calculator_Submission.php';
//require_once '../../../../wp-blog-header.php';



$config = new CQN_Calculator_Config;


//
//$refs =[
//    '0552768001457620506',
//    '0690059001457618592',
//    '0399285001457612957',
//    '0468890001457612569',
//    '0614589001457606928',
//    '0632354001457566813',
//    '0785629001457566690',
//    '0703047001457562747',
//    '0492602001457548990'
//];
//$refs =[
//    '0161534001457546315',
//    '0359308001457542250',
//    '0746638001457539154',
//    '0988212001457539123',
//    '0954868001457539100',
//    '0491623001457538406',
//    '0284890001457536940',
//    '0402693001457534601',
//    '0893700001457533351'
//];
//$refs =[
//    '0405475001457530579',
//    '0454455001457527746',
//    '0265849001457525989',
//    '0698847001457524936',
//    '0849211001457524855',
//    '0125192001457490356',
//    '0864472001457490304',
//    '0572576001457479836',
//    '0338502001457461987'
//];
//$refs =[
//    '0276242001457449297',
//    '0459430001457441222',
//    '0801901001457440549',
//    '0618971001457433993',
//    '0354677001457433824',
//    '0472754001457427366',
//    '0888620001457426979'
//];
//$refs =[
//    '0333890001457420803',
//    '0772252001457392720',
//    '0826877001457388725',
//    '0283505001457368872'
//];
//$refs =[
//    '0819156001457361746',
//    '0586407001457361405',
//    '0659646001457357103'
//];

define( 'CQN_PDF_STORAGE_DIR', '/home/znegnwjh/public_html/wp-content/plugins/cqn_calculator2/storage/quotes/');

foreach( $refs as $ref ){

    $sub = new CQN_Calculator_Submission( $config );
    $sub->load( $ref );
    $leadsSubmissionBody = $sub->getWebleadsSubmissionBody();
    print_r(  $leadsSubmissionBody );

    echo "\n<br /> - sending - " . wp_mail( $config->leadsSystemEmailAddress, $config->leadsSystemEmailSubject, $leadsSubmissionBody , '', CQN_PDF_STORAGE_DIR . $sub->getCalculatorRef() . '.pdf'  );
    echo "\n<br>\n";
}




//wp_mail( 'dave@dbennett.info' , $config->leadsSystemEmailSubject, $leadsSubmissionBody , '', CQN_PDF_STORAGE_DIR . $sub->getCalculatorRef() . '.pdf'  );
exit();



echo wp_mail( 'dave@dbennett.info', 'testing from csuk', 'this is the body' );


echo "fini";
