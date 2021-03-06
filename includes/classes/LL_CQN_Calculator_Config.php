<?php
/**
 * Created by PhpStorm.
 * User: Dave
 * Date: 12/11/2014
 * Time: 10:44
 */

class CQN_Calculator_Config {

    public $VATRate;

    public $maxSalePrice;
    public $maxPurchasePrice;
    public $maxRemortgagePrice;
    public $maxTransferPrice;

    private $saleFees;
    private $saleBands;

    private $purchaseFees;
    private $purchaseBands;

    private $remortgageFees;
    private $remortgageBands;

    private $transferFees;
    private $transferBands;

    private $salePurchaseLRFees;
    private $remortgageTransferLRFees;

    private $salePurchaseLRBands;
    private $remortgageTransferLRBands;

    private $stampDutyBands;
    private $stampDutyFees;
    private $stampDutyFeesFirstTimeBuyers;

    private $saleDisbursements;
    private $remortgageDisbursements;
    private $transferDisbursements;
    private $purchaseDisbursements;

    public $sale_leasehold_fee;
    public $purchase_leasehold_fee;
    public $remortgage_leasehold_fee;
    public $transfer_leasehold_fee;

    public $leadsSystemEmailAddress;
    public $instructEmailAddress;
    public $leadsSystemEmailSubject;
    public $clientEmailSubject;

    private $noWinNoFee;

    private $discountCodes;


    public function __construct(  ){

        $this->VATRate = 0.2;
//      $this->quote_types = [ 'sale', 'purchase', 'sale_purchase', 'remortgage', 'transfer' ];

//          $this->leadsSystemEmailAddress = 'callback-inbox@wantfound.com';

//        $this->leadsSystemEmailAddress = 'davebenn@gmail.com';
        if(
            (gethostname() == 'dave-office')
            ||
            (gethostname() == 'dave-home-desktop')

        ){

            $this->leadsSystemEmailAddress = 'davebenn+leadsImport@gmail.com';
            $this->instructEmailAddress    = 'davebenn+conveyancingcalcDEV@gmail.com';
            $this->leadsSystemEmailSubject = 'DEV callback - calculator submission ';
            $this->clientEmailSubject      = 'DEV Your conveyancing quote';

        }else{

            $this->leadsSystemEmailAddress = 'callback-inbox@webleads.latimerlee.com';
            $this->instructEmailAddress    = 'conveyancing@latimerlee.com, davebenn+conveyancingcalc@gmail.com';
            $this->leadsSystemEmailSubject = 'callback - calculator submission ';
            $this->clientEmailSubject      = 'Your conveyancing quote';
        }

        $this->maxSalePrice       = 450000;
        $this->maxPurchasePrice   = 450000;
        $this->maxRemortgagePrice = 450000;
        $this->maxTransferPrice   = 450000;

        // $this->saleBands       = array( 125000, 250000, 500000, 1000000, 9999999   );
        // $this->saleFees        = array(    300,    300,    350,     624,     624   );
        // $this->purchaseBands   = array( 125000, 250000, 500000, 1000000, 9999999  );
        // $this->purchaseFees    = array(    300,    324,    340,     624,     624  );

        $this->saleBands       = array( 100000, 120000, 160000, 250000, 300000, 350000, 400000, 500000,  9999999   );
        $this->saleFees        = array(    299,    325,    350,    375,    400,    425,    450,    475,      500   );
        $this->purchaseBands   = array( 100000, 120000, 160000, 250000, 300000, 350000, 400000, 500000,  9999999   );
        $this->purchaseFees    = array(    325,    350,    375,    400,    425,    450,    475,    500,      525  );


        $this->remortgageBands = array( 400000, 99999999  );
        $this->remortgageFees  = array( 250, 350  );
        $this->transferBands   = array( 99999999  );
        $this->transferFees    = array( 250  );

        $this->noWinNoFee   = 100;

        $this->sale_leasehold_fee       = 95;
        $this->purchase_leasehold_fee   = 95;
        $this->remortgage_leasehold_fee =  0;
        $this->transfer_leasehold_fee   =  0;

        $this->purchase_shared_ownership_fee   =  100;

        $this->saleDisbursements = array(
            (object)  array( 'code' => 'SALE_LREP'                 , 'optional' => false , 'price' => 8 ,    'name' => 'Land Registry Entry and Plan' ),
            (object)  array( 'code' => 'SALE_BANK_TRANSFER'        , 'optional' => false , 'price' => 36.80, 'name' => 'Bank Transfer' ),
        );

        $this->remortgageDisbursements =  array(
            (object)  array( 'code' => 'REMORTGAGE_LREP'           , 'optional' => false , 'price' => 8 ,    'name' => 'Land Registry Entry and Plan' ),
            (object)  array( 'code' => 'REMORTGAGE_LRS'            , 'optional' => false , 'price' => 4 ,    'name' => 'Land Registry Search' ),
            (object)  array( 'code' => 'REMORTGAGE_BS'             , 'optional' => false , 'price' => 2 ,    'name' => 'Bankruptcy Search' )
        );

        $this->transferDisbursements =  array(
            (object)  array( 'code' => 'TRANSFER_LREP'             , 'optional' => false , 'price' => 6 ,    'name' => 'Land Registry Entry and Plan' ),
            (object)  array( 'code' => 'TRANSFER_LRS'              , 'optional' => false , 'price' => 4 ,    'name' => 'Land Registry Search' ),
            (object)  array( 'code' => 'TRANSFER_BS'               , 'optional' => false , 'price' => 2 ,    'name' => 'Bankruptcy Search' )
        );

        $this->purchaseDisbursements =  array(
             (object)  array( 'code' => 'PURCHASE_LS'              , 'optional' => true , 'price' => 100 ,  'name' => 'Local Search' ),
             (object)  array( 'code' => 'PURCHASE_ES'              , 'optional' => true , 'price' => 58  ,   'name' => 'Environmental Search' ),
             (object)  array( 'code' => 'PURCHASE_WS'              , 'optional' => true , 'price' => 45 ,   'name' => 'Water Search' ),
             (object)  array( 'code' => 'PURCHASE_CS'              , 'optional' => true , 'price' => 42 ,   'name' => 'Coal Search' ),
             (object)  array( 'code' => 'PURCHASE_CHS'             , 'optional' => true , 'price' => 20 ,   'name' => 'Chancel Search' ),
             (object)  array( 'code' => 'PURCHASE_HS2S'            , 'optional' => true , 'price' => 25 ,   'name' => 'High Speed 2 Search' ),
             (object)  array( 'code' => 'PURCHASE_HMLRS'           , 'optional' => false , 'price' => 8 ,    'name' => 'HMLR Search' ),
             (object)  array( 'code' => 'PURCHASE_BS'              , 'optional' => false , 'price' => 2 ,    'name' => 'Bankruptcy Search' ),
//               (object)  array( 'code' => 'PURCHASE_DOCUMENT_RETURN' , 'optional' => true , 'price' => 10 ,   'name' => 'Document Return' ),
             (object)  array( 'code' => 'PURCHASE_BANK_TRANSFER'   , 'optional' => false , 'price' => 36.80, 'name' => 'Bank Transfer')
        );

        $this->salePurchaseLRBands =        array(  50000, 80000, 100000, 200000, 500000, 1000000);
        $this->salePurchaseLRFees  =        array(     20,    20,     40,     95,    135,     270 );
        $this->remortgageTransferLRBands =  array(  100000, 200000, 500000, 1000000 );
        $this->remortgageTransferLRFees  =  array(      20,     30,     40,      60 );

        $this->discountCodes = array(
//            'TEST-CODE-912'          => (object) [ 'type' => 'F', 'amount' => 50,     'name' => '£50 off' ],
//            'dbhalved12'             => (object) [ 'type' => 'P', 'amount' => 0.50 ,  'name' => '50% off' ],
            'CSUK25'                 => (object) array( 'type' => 'F', 'amount' => 25 ,    'name' => '£25 off' )
        );

        $this->stampDutyBands =                array( 125000, 250000, 500000, 1000000, 2000000 );
        
        // $this->stampDutyFees  =                array( 0.00 , 0.01 , 0.03 , 0.04 , 0.05 , 0.07 );
        // $this->stampDutyFeesFirstTimeBuyers  = array( 0.00 , 0.01 , 0.03 , 0.04 , 0.05 , 0.07 );
        
        $this->stampDutyFees  =                array( 0.00 , 0.00, 0.00 , 0.00 , 0.00 , 0.00 );
        $this->stampDutyFeesFirstTimeBuyers  = array( 0.00 , 0.00, 0.00 , 0.00 , 0.00 , 0.00 );
    }

//    public function OLD__getStampDuty__OLD( $propertyPrice, $firstTimeBuyer = false ){
//
//        $bandIndex = $this->getBandIndex( $this->stampDutyBands, $propertyPrice );
//
//        if( $firstTimeBuyer ){
//            $theFees = $this->stampDutyFeesFirstTimeBuyers;
//        }else{
//            $theFees = $this->stampDutyFees;
//        }
//
//        if( isset( $theFees[ $bandIndex ] ) ){
//            return  $theFees[ $bandIndex ] * $propertyPrice;
//        }else{
//            return false;
//        }
//        return 1;
//    }

    public function getStampDuty( $propertyPrice, $firstTimeBuyer = false, $buyToLet = false )
	{

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

    public function checkDiscountCode( $code ){

        $code = strtoupper( $code );
        if( array_key_exists(  $code,  $this->discountCodes ) ) {

            return $this->discountCodes[$code];
        }else{
            return false;
        }
    }
    public function getSPLandRegistryFees( $propertyPrice ){// type = SP or RT

        $bandIndex = $this->getBandIndex( $this->salePurchaseLRBands, $propertyPrice );

        if( isset( $this->salePurchaseLRFees[ $bandIndex ] ) ){
            return  $this->salePurchaseLRFees[ $bandIndex ];
        }else{
            return false;
        }
    }
    public function getRTLandRegistryFees( $propertyPrice ){// type = SP or RT

        $bandIndex = $this->getBandIndex( $this->remortgageTransferLRBands, $propertyPrice );

        if( isset( $this->remortgageTransferLRFees[ $bandIndex ] ) ){
            return  $this->remortgageTransferLRFees[ $bandIndex ];
        }else{
            return false;
        }
    }
    public function getPurchaseFees( $propertyPrice ){

        $bandIndex = $this->getBandIndex( $this->purchaseBands, $propertyPrice );

        if( isset( $this->purchaseFees[ $bandIndex ] ) ){
            return  $this->purchaseFees[ $bandIndex ];
        }else{
            return false;
        }

    }
    public function getSaleFees( $propertyPrice ){


        $bandIndex = $this->getBandIndex( $this->saleBands, $propertyPrice );

        if( isset(  $this->saleFees[ $bandIndex ] ) ){
            return  $this->saleFees[ $bandIndex ];
        }else{
            return false;
        }

    }
    public function getRemortgageFees( $propertyPrice ){

        $bandIndex = $this->getBandIndex( $this->remortgageBands, $propertyPrice );

        if( isset( $this->remortgageFees[ $bandIndex ] ) ){
            return  $this->remortgageFees[ $bandIndex ];
        }else{
            return false;
        }

    }
    public function getTransferFees( $propertyPrice ){

        $bandIndex = $this->getBandIndex( $this->transferBands, $propertyPrice );

        if( isset( $this->transferFees[ $bandIndex ] ) ){
            return  $this->transferFees[ $bandIndex ];
        }else{
            return false;
        }
    }
    public function getBandIndex( $bands, $price ){
        $i = 0;
        while( ( $bands[$i] < $price ) && ( $i < ( count( $bands ) - 1 ) ) ){
            $i++;
        }
        if( $i > count( $bands ) - 1 ){
            return '9999';
        }
        return $i;
    }

    public function getSaleDisbursements(  ){
        return $this->saleDisbursements;
    }
    public function getPurchaseDisbursements(  ){
        return $this->purchaseDisbursements;
    }
    public function getRemortgageDisbursements(  ){
        return $this->remortgageDisbursements;
    }
    public function getTransferDisbursements(  ){
        return $this->transferDisbursements;
    }
}