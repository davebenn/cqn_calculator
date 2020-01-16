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

    protected $disbursements = [];
	protected $introducerFees;

    public function __construct(  ){

        $this->VATRate = 0.2;

		// $this->leadsSystemEmailAddress = 'callback-inbox@webleads.latimerlee.com';
		// $this->instructEmailAddress    = 'conveyancing@latimerlee.com, davebenn+conveyancingcalc@gmail.com';
		// $this->leadsSystemEmailSubject = 'callback - calculator submission ';
		// $this->clientEmailSubject      = 'Your conveyancing quote';

        $this->leadsSystemEmailAddress = get_option('cqn_calculator_leads_system_email_address');
        $this->instructEmailAddress    = get_option('cqn_calculator_instruct_email_address');
        $this->leadsSystemEmailSubject = get_option('cqn_calculator_leads_system_email_subject');
        $this->clientEmailSubject      = get_option('cqn_calculator_client_email_subject');
		$this->POCAgentId              = get_option('cqn_calculator_poc_agent_id');
		$this->POCAPIKey               = get_option('cqn_calculator_poc_api_key');

		$this->maxSalePrice       = get_option('cqn_calculator_max_sale_price');
		$this->maxPurchasePrice   = get_option('cqn_calculator_max_purchase_price');
		$this->maxRemortgagePrice = get_option('cqn_calculator_max_remortgage_price');
		$this->maxTransferPrice   = get_option('cqn_calculator_max_transfer_price');


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
			(object)  array( 'code' => 'SALE_ID_CHECK'             , 'optional' => false , 'price' => 12, 'name' => 'ID Check' ),
		);

		$this->remortgageDisbursements =  array(
			(object)  array( 'code' => 'REMORTGAGE_LREP'           , 'optional' => false , 'price' => 8 ,    'name' => 'Land Registry Entry and Plan' ),
			(object)  array( 'code' => 'REMORTGAGE_LRS'            , 'optional' => false , 'price' => 4 ,    'name' => 'Land Registry Search' ),
			(object)  array( 'code' => 'REMORTGAGE_BS'             , 'optional' => false , 'price' => 2 ,    'name' => 'Bankruptcy Search' ),
			(object)  array( 'code' => 'REMORTGAGE_ID_CHECK'       , 'optional' => false , 'price' => 12, 'name' => 'ID Check' ),
		);

		$this->transferDisbursements =  array(
			(object)  array( 'code' => 'TRANSFER_LREP'             , 'optional' => false , 'price' => 6 ,    'name' => 'Land Registry Entry and Plan' ),
			(object)  array( 'code' => 'TRANSFER_LRS'              , 'optional' => false , 'price' => 4 ,    'name' => 'Land Registry Search' ),
			(object)  array( 'code' => 'TRANSFER_BS'               , 'optional' => false , 'price' => 2 ,    'name' => 'Bankruptcy Search' ),
			(object)  array( 'code' => 'TRANSFER_ID_CHECK'         , 'optional' => false , 'price' => 12, 'name' => 'ID Check' ),
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
			(object)  array( 'code' => 'PURCHASE_BANK_TRANSFER'   , 'optional' => false , 'price' => 36.80, 'name' => 'Bank Transfer'),
			(object)  array( 'code' => 'PURCHASE_ID_CHECK'             , 'optional' => false , 'price' => 12, 'name' => 'ID Check' ),
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
		$this->stampDutyFees  =                array( 0.00 , 0.01 , 0.03 , 0.04 , 0.05 , 0.07 );
		$this->stampDutyFeesFirstTimeBuyers  = array( 0.00 , 0.01 , 0.03 , 0.04 , 0.05 , 0.07 );
    }

	protected function getDisbursementPrice($code){

		if( isset($this->disbursements->{$code}) ){
			return  $this->disbursements->{$code};
		}

		error_log('cqn_calculator::' . 'MISSING_DISBURSEMENT -- "' . $code . '"' );
		return 0;

	}

	/***  NOT CURRENTLY USED -- code to use is in previous commit (ref: 48f8c5a) ***/
	/***  NOT CURRENTLY USED -- code to use is in previous commit (ref: 48f8c5a) ***/
	/***  NOT CURRENTLY USED -- code to use is in previous commit (ref: 48f8c5a) ***/
	protected function loadIntroducerFees(){

		$feesUpdated    = get_option( 'cqn_calculator_stored_fees_time' );
		$feesData       = json_decode( get_option('cqn_calculator_stored_fees_data') );
		$feesCacheHours = json_decode( get_option('cqn_calculator_stored_fees_cache') );

		$cacheInvalidationTime = time() - $feesCacheHours * 60 * 60;

		if( !empty ($feesData ) && $feesUpdated > ($cacheInvalidationTime)  ){
			return $feesData;
		}else{

			$pocURL    = get_option('cqn_calculator_poc_protocol')  . '://' . get_option(  'cqn_calculator_poc_fees_server_url' );
			$pocAPIKey = get_option('cqn_calculator_poc_api_key');
			$pocAgent  = get_option('cqn_calculator_poc_agent_id');

			try{
				$feeDataURL = $pocURL . '?introducer=' . $pocAgent . '&apikey=' . $pocAPIKey;

				$opts = array(
					'http'=>array(
						'method'=>"GET",
						'header'=>"Accept-language: en\r\n" .
							"Cookie: XDEBUG_SESSION=PHPSTORM\r\n"
					)
				);

				$context = stream_context_create($opts);

				$rawJson = file_get_contents($feeDataURL, false, $context);

			}catch ( Exception $e){
				error_log( 'cqn_calculator::' . 'error loading fees data from "'.$pocURL.'"' );
				return false;
			}

			$fees = json_decode($rawJson);

			if( ! $this->validateFees($fees) ){
				// rawJson wasnt json
				error_log( 'cqn_calculator::' . 'Fee data invalid' );
				return false;
			}

			update_option( 'cqn_calculator_stored_fees_time', time() );
			update_option( 'cqn_calculator_stored_fees_data', json_encode($fees) );

			return $fees;

		}
	}

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

	private function validateFees( $fees)
	{
		// do some checking of fees data structure

		if( ! $fees instanceof stdClass ) return false;
		if( ! $fees->disbursements instanceof stdClass ) return false;

		if( !is_array( $fees->purchaseFees ) || count( $fees->purchaseFees )   < 2 ) return false;
		if( !is_array( $fees->purchaseBands ) || count( $fees->purchaseBands ) < 2 ) return false;
		if( count( $fees->purchaseBands ) !== count( $fees->purchaseFees)  ) return false;

		if( !is_array( $fees->saleFees ) || count( $fees->saleFees )   < 2 ) return false;
		if( !is_array( $fees->saleBands ) || count( $fees->saleBands ) < 2 ) return false;
		if( count( $fees->saleBands ) !== count( $fees->saleFees)  ) return false;

		if( !is_array( $fees->remortgageFees ) || count( $fees->remortgageFees )   < 1 ) return false;
		if( !is_array( $fees->remortgageBands ) || count( $fees->remortgageBands ) < 1 ) return false;
		if( count( $fees->remortgageBands ) !== count( $fees->remortgageFees)  ) return false;

		if( !is_array( $fees->transferFees ) || count( $fees->transferFees )   < 1 ) return false;
		if( !is_array( $fees->transferBands ) || count( $fees->transferBands ) < 1 ) return false;
		if( count( $fees->transferBands ) !== count( $fees->transferFees)  ) return false;

		// check all the disbursements are valid numbers
		foreach ($fees->disbursements as $key => $value){
			if( ! is_numeric($value) ) return false;
		}

		return true;
	}
}