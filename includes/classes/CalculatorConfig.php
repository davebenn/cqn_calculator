<?php

class CalculatorConfig{

    private $VATRate;

    private $maxSalePrice;
    private $maxPurchasePrice;
    private $maxRemortgagePrice;
    private $maxTransferPrice;

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

    private $noWinNoFee;

    private $discountCodes;

    public function __construct(  ){

        $this->VATRate = 0.2;

        $this->maxSalePrice = 500000;
        $this->maxPurchasePrice = 500000;
        $this->maxRemortgagePrice = 500000;
        $this->maxTransferPrice = 500000;

        $this->saleBands       = [ 125000, 250000, 500000, 1000000 ];
        $this->saleFees        = [ 300, 300, 350, 624, 624 ];

        $this->purchaseBands   = [ 125000, 250000, 500000, 1000000, 9999999 ];
        $this->purchaseFees    = [ 300, 324, 340, 624, 624 ];

        $this->remortgageBands = [ 99999999 ];
        $this->remortgageFees  = [ 250 ];

        $this->transferBands   = [ 99999999 ];
        $this->transferFees    = [ 250 ];

        $this->noWinNoFee   = 100;

        $this->saleDisbursements = [
                'SALE_LREP'                     => (object) ['price' => 8 ,    'name' => 'disbursement' ],
                'SALE_BANK_TRANSFER'            => (object) ['price' => 36.80, 'name' => 'disbursement' ],
                'SALE_LEASEHOLD_FEE'            => (object) ['price' => 95 ,   'name' => 'disbursement' ]
            ];

        $this->remortgageDisbursements = [
                'REMORTGAGE_LREP'           => (object) ['price' => 8 ,    'name' => 'disbursement' ],
                'REMORTGAGE_LRS'            => (object) ['price' => 4 ,    'name' => 'disbursement' ],
                'REMORTGAGE_BS'             => (object) ['price' => 2 ,    'name' => 'disbursement' ]
            ];

        $this->transferDisbursements = [
                'TRANSFER_LREP'             => (object) ['price' => 6 ,    'name' => 'disbursement' ],
                'TRANSFER_LRS'              => (object) ['price' => 4 ,    'name' => 'disbursement' ],
                'TRANSFER_BS'               => (object) ['price' => 2 ,    'name' => 'disbursement' ]
            ];

        $this->purchaseDisbursements = [
                'PURCHASE_LS'               => (object) ['price' => 100 ,  'name' => 'disbursement' ],
                'PURCHASE_ES'               => (object) ['price' => 50 ,   'name' => 'disbursement' ],
                'PURCHASE_WS'               => (object) ['price' => 45 ,   'name' => 'disbursement' ],
                'PURCHASE_CS'               => (object) ['price' => 42 ,   'name' => 'disbursement' ],
                'PURCHASE_CHS'              => (object) ['price' => 20 ,   'name' => 'disbursement' ],
                'PURCHASE_HS2S'             => (object) ['price' => 25 ,   'name' => 'disbursement' ],
                'PURCHASE_HMLRS'            => (object) ['price' => 8 ,    'name' => 'disbursement' ],
                'PURCHASE_BS'               => (object) ['price' => 2 ,    'name' => 'disbursement' ],
                'PURCHASE_DOCUMENT_RETURN'  => (object) ['price' => 10 ,   'name' => 'disbursement' ],
                'PURCHASE_LEASEHOLD_FEE'    => (object) ['price' => 95 ,   'name' => 'disbursement' ],
                'PURCHASE_BANK_TRANSFER'    => (object) ['price' => 36.80, 'name' => 'disbursement' ]
            ];

        $this->salePurchaseLRBands =        [ 50000, 80000, 100000,200000, 500000, 1000000];
        $this->remortgageTransferLRBands =  [ 100000, 200000, 500000, 1000000 ];

        $this->salePurchaseLRFees =       [ 20 , 20, 40, 95, 135, 270 ];
        $this->remortgageTransferLRFees = [ 20 , 30 , 40 , 60];

        $this->discountCodes = [
//            'TEST-CODE-912'          => (object) [ 'type' => 'F', 'amount' => 50,     'name' => '£50 off' ],
//            'TEST-CODE-2123'         => (object) [ 'type' => 'P', 'amount' => 0.25 ,  'name' => '25% off' ],
            'CSUK25'                 => (object) [ 'type' => 'F', 'amount' => 25 ,    'name' => '£25 off' ]
        ];


        $this->stampDutyBands =                [ 125000, 250000, 500000, 1000000, 2000000 ];

        $this->stampDutyFees  =                [ 0.00 , 0.01 , 0.03 , 0.04 , 0.05 , 0.07 ];
        $this->stampDutyFeesFirstTimeBuyers  = [ 0.00 , 0.01 , 0.03 , 0.04 , 0.05 , 0.07 ];


    }


    public function getStampDuty( $propertyPrice, $firstTimeBuyer = false ){

        $bandIndex = $this->getBandIndex( $this->stampDutyBands, $propertyPrice );

        if( $firstTimeBuyer ){
            $theFees = $this->stampDutyFeesFirstTimeBuyers;
        }else{
            $theFees = $this->stampDutyFees;
        }

        if( isset( $theFees[ $bandIndex ] ) ){
            return  $theFees[ $bandIndex ] * $propertyPrice;
        }else{
            return false;
        }

        return 1;
    }


    public function checkDiscountCode( $code ){

        if( array_key_exists( $code, $this->discountCodes ) ){
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