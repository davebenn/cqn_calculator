<?php
/**
 * Created by PhpStorm.
 * User: Dave
 * Date: 12/11/2014
 * Time: 10:47
 */

class CQN_Calculator_Submission
{

    private $config;
    private $calculator_ref;
    private $loadedFromDB;
    private $id;

    private $instructClicked;
    private $emailedToClient;

    public $involves_sale;
    public $involves_purchase;
    public $involves_remortgage;
    public $involves_transfer;

    public $purchaseLegalFees;
    public $saleLegalFees;
    public $remortgageLegalFees;
    public $transferLegalFees;
    public $purchaseLeaseholdFees;
    public $saleLeaseholdFees;
    public $remortgageLeaseholdFees;
    public $transferLeaseholdFees;

    public $noMoveNoFee;

    public $VATOnFees;

    public $legalFeesTotal;// $fees excluding VAT

    public $feesPlusVAT;// $fees + VAT

    public $quoteTotal;
    public $discountedTotal;

    public $purchaseDisbursementsTotal;
    public $saleDisbursementsTotal;
    public $remortgageDisbursementsTotal;
    public $transferDisbursementsTotal;
    public $disbursementsTotal;


    // for storing json of disbursements at time of quoting , as they can change over time

    public $purchaseDisbursementsList = [];
    public $saleDisbursementsList = [];
    public $remortgageDisbursementsList = [];
    public $transferDisbursementsList = [];

    public $discount_code;
    public $discount_total;

    public $sale_price;
    public $sale_leasehold;
    public $sale_mortgage;
    public $purchase_price;
    public $purchase_leasehold;
    public $purchase_mortgage;
    public $purchase_1st_time_buyer;
    public $purchase_no_of_buyers;
    public $remortgage_price;
    public $remortgage_leasehold;
    public $remortgage_no_of_people;
    public $remortgage_involves_transfer;
    public $transfer_price;
    public $transfer_leasehold;
    public $transfer_no_of_people;

    public $contact_email;
    public $contact_telephone;
    public $contact_name;

    public $quoteType;


    public function calculate()
    {

        $this->saleLegalFees = 0;
        $this->purchaseLegalFees = 0;
        $this->transferLegalFees = 0;
        $this->remortgageLegalFees = 0;

        $this->purchaseLeaseholdFees = 0;
        $this->saleLeaseholdFees = 0;
        $this->remortgageLeaseholdFees = 0;
        $this->transferLeaseholdFees = 0;

        $this->noMoveNoFee = 0;

        $this->saleDisbursementsTotal = 0;
        $this->purchaseDisbursementsTotal = 0;
        $this->transferDisbursementsTotal = 0;
        $this->remortgageDisbursementsTotal = 0;

        $this->disbursementsTotal = 0;

        $this->legalFeesTotal = 0;
        $this->VATOnFees = 0;
        $this->quoteTotal = 0;

        if ($this->involves_sale) {

            $this->saleLegalFees = $this->config->getSaleFees($this->sale_price);

            if ($this->sale_leasehold) {
                $this->saleLeaseholdFees = $this->config->saleLeaseholdFee;
            }

            $saleDisbursements = $this->config->getSaleDisbursements();

            foreach ($saleDisbursements as $disbursement) {
                $this->saleDisbursementsTotal += $disbursement->price;
                $this->saleDisbursementsList[] = $disbursement;
            }
        }

        if ($this->involves_purchase) {

            $this->purchaseLegalFees = $this->config->getPurchaseFees($this->purchase_price);

            if ($this->purchase_leasehold) {
                $this->purchaseLeaseholdFees = $this->config->purchaseLeaseholdFee;
            }
            $purchaseDisbursements = $this->config->getPurchaseDisbursements();

            foreach ($purchaseDisbursements as $disbursement) {
                if( $disbursement->code == 'PURCHASE_BS' ) {
                    for( $i = 1; $i <= $this->purchase_no_of_buyers; $i++ ){
                        $this->purchaseDisbursementsTotal += $disbursement->price;
                        $this->purchaseDisbursementsList[] = $disbursement;
                    }
                }else {
                    $this->purchaseDisbursementsTotal += $disbursement->price;
                    $this->purchaseDisbursementsList[] = $disbursement;
                }
            }

            $stampDuty = $this->config->getStampDuty( $this->purchase_price, $this->purchase_1st_time_buyer );
            $this->purchaseDisbursementsTotal += $stampDuty;
            $this->purchaseDisbursementsList[] = (object) [ 'code' => 'PURCHASE_SD'       , 'optional' => false      , 'price' => $stampDuty ,    'name' => 'Stamp Duty' ];

            $landRegistryFee = $this->config->getSPLandRegistryFees( $this->purchase_price );
            $this->purchaseDisbursementsTotal += $landRegistryFee;
            $this->purchaseDisbursementsList[] = (object) [ 'code' => 'PURCHASE_LRF'     , 'optional' => false        , 'price' => $landRegistryFee ,    'name' => 'Land Registry Fee' ];
        }

        if ($this->involves_remortgage) {

            $this->remortgageLegalFees = $this->config->getRemortgageFees($this->remortgage_price);

            if ($this->remortgage_leasehold) {
                $this->remortgageLeaseholdFees = $this->config->remortgageLeaseholdFee;
            }
            $remortgageDisbursements = $this->config->getRemortgageDisbursements();

            foreach ($remortgageDisbursements as $disbursement) {

                if( $disbursement->code == 'REMORTGAGE_BS' ) {
                    for( $i = 1; $i <= $this->remortgage_no_of_people; $i++ ){
                        $this->remortgageDisbursementsTotal += $disbursement->price;
                        $this->remortgageDisbursementsList[] = $disbursement;
                    }
                }else {
                    $this->remortgageDisbursementsTotal += $disbursement->price;
                    $this->remortgageDisbursementsList[] = $disbursement;
                }
            }

            $landRegistryFee = $this->config->getRTLandRegistryFees( $this->remortgage_price );
            $this->remortgageDisbursementsTotal += $landRegistryFee;
            $this->remortgageDisbursementsList[] = (object) [ 'code' => 'REMORTGAGE_LRF'     , 'optional' => false        , 'price' => $landRegistryFee ,    'name' => 'Land Registry Fee' ];
        }

        if ($this->involves_transfer) {

            $this->transferLegalFees = $this->config->getTransferFees($this->transfer_price);

            if ($this->transfer_leasehold) {
                $this->transferLeaseholdFees = $this->config->transferLeaseholdFee;
            }
            $transferDisbursements = $this->config->getTransferDisbursements();

            foreach ($transferDisbursements as $disbursement) {

                if( $disbursement->code == 'TRANSFER_BS' ) {
                    for( $i = 1; $i <= $this->transfer_no_of_people; $i++ ){
                        $this->transferDisbursementsTotal += $disbursement->price;

                        $this->transferDisbursementsList[] = $disbursement;
                    }
                }else {
                    $this->transferDisbursementsTotal += $disbursement->price;
                    $this->transferDisbursementsList[] = $disbursement;
                }
            }

            $landRegistryFee = $this->config->getRTLandRegistryFees( $this->transfer_price );
            $this->transferDisbursementsTotal += $landRegistryFee;
            $this->transferDisbursementsList[] = (object) [ 'code' => 'TRANSFER_LRF'     , 'optional' => false        , 'price' => $landRegistryFee ,    'name' => 'Land Registry Fee' ];
        }

        $this->legalFeesTotal = $this->saleLegalFees + $this->purchaseLegalFees + $this->remortgageLegalFees + $this->transferLegalFees +
            $this->saleLeaseholdFees + $this->purchaseLeaseholdFees + $this->remortgageLeaseholdFees + $this->transferLeaseholdFees +
            $this->noMoveNoFee;

        $this->disbursementsTotal = $this->saleDisbursementsTotal +
            $this->purchaseDisbursementsTotal +
            $this->remortgageDisbursementsTotal +
            $this->transferDisbursementsTotal;


        $this->VATOnFees = $this->legalFeesTotal * $this->config->VATRate;

        $this->feesPlusVAT = $this->VATOnFees + $this->legalFeesTotal;

        $this->quoteTotal = $this->legalFeesTotal + $this->VATOnFees + $this->disbursementsTotal;

        $this->applyDiscount();

        return 1;
    }

    private function applyDiscount(){

        $discount = $this->config->checkDiscountCode( $this->discount_code );
        if( $discount  ){

            switch( $discount->type ){

                case 'F':

                            $this->discount_total = $discount->amount;
                            break;
                case 'P':
                            $this->discount_total = ( $discount->amount * $this->quoteTotal );
                            break;
                default:

                            $this->discount_total = 0;
                            break;
            }

        }else{

            $this->discount_total = 0;
        }

        $this->discountedTotal = $this->quoteTotal - $this->discount_total ;

    }

    public function getCalculatorRef()
    {

        return $this->calculator_ref;
    }

    public function __construct(CQN_Calculator_Config $config)
    {

        $this->config = $config;
        $this->calculator_ref = $this->generateUniqueID();

    }

    public function loadFromPost($postArray)
    {

        $fillable = [
            'sale_price', 'sale_leasehold', 'sale_mortgage', 'purchase_price', 'purchase_leasehold', 'purchase_mortgage', 'purchase_1st_time_buyer', 'purchase_no_of_buyers', 'remortgage_price', 'remortgage_leasehold', 'remortgage_no_of_people', 'remortgage_involves_transfer', 'transfer_price', 'transfer_leasehold', 'transfer_no_of_people', 'discount_code', 'contact_email', 'contact_telephone', 'contact_name', 'contact_street_address', 'contact_locality', 'contact_town', 'contact_postcode', 'additional_1_title', 'additional_1_forename', 'additional_1_surname', 'additional_2_title', 'additional_2_forename', 'additional_2_surname', 'sale_street_address', 'sale_locality', 'sale_town', 'sale_postcode', 'purchase_street_address', 'purchase_locality', 'purchase_town', 'purchase_postcode', 'remortgage_street_address', 'remortgage_locality', 'remortgage_town', 'remortgage_postcode', 'transfer_street_address', 'transfer_locality', 'transfer_town', 'transfer_postcode'
        ];

        foreach ($fillable as $field) {


            if (isset($postArray[$field])) {
                $this->$field = mysql_real_escape_string($postArray[$field]);
            }

        }

        if (isset($postArray['quote_type'])) {

            $this->involves_sale = false;
            $this->involves_purchase = false;
            $this->involves_remortgage = false;
            $this->involves_transfer = false;

            switch ($postArray['quote_type']) {

                case 'sale':
                    $this->involves_sale = true;
                    $this->quoteType = 'Sale';
                    break;
                case 'purchase':
                    $this->involves_purchase = true;
                    $this->quoteType = 'Purchase';
                    break;
                case 'sale_purchase':
                    $this->involves_sale = true;
                    $this->involves_purchase = true;
                    $this->quoteType = 'Sale & Purchase';
                    break;
                case 'remortgage':
                    $this->involves_remortgage = true;
                    $this->quoteType = 'Remortgage';

                    if (isset($postArray['remortgage_involves_transfer'])) {
                        $this->quoteType = 'Remortgage & Transfer';
                        $this->involves_transfer = true;
                    }
                    break;
                case 'transfer':
                    $this->quoteType = 'Transfer';
                    $this->involves_transfer = true;
                    break;
                default:
                    //this should never happen
                    break;

            }
        }
        return true;


    }

    public function load($calcRef = '')
    {


        global $wpdb;
        $q = 'SELECT * FROM `' . CQN_TABLE_NAME . '` WHERE `calculator_ref` =  "' . $calcRef . '";';
        error_log( $q );
        $submission = $wpdb->get_row($q, OBJECT);

        if ($submission) {

            $this->id = $submission->id;

            $this->involves_sale = $submission->involves_sale;
            $this->involves_purchase = $submission->involves_purchase;
            $this->involves_remortgage = $submission->involves_remortgage;
            $this->involves_transfer = $submission->involves_transfer;

            $this->calculator_ref = $calcRef;
            $this->sale_price = $submission->sale_price;
            $this->sale_leasehold = $submission->sale_leasehold;
            $this->sale_mortgage = $submission->sale_mortgage;

            $this->purchase_price = $submission->purchase_price;
            $this->purchase_leasehold = $submission->purchase_leasehold;
            $this->purchase_mortgage = $submission->purchase_mortgage;
            $this->purchase_1st_time_buyer = $submission->purchase_1st_time_buyer;
            $this->purchase_no_of_buyers = $submission->purchase_no_of_buyers;

            $this->remortgage_price = $submission->remortgage_price;
            $this->remortgage_leasehold = $submission->remortgage_leasehold;
            $this->remortgage_no_of_people = $submission->remortgage_no_of_people;
            $this->remortgage_involves_transfer = $submission->remortgage_involves_transfer;

            $this->transfer_price = $submission->transfer_price;
            $this->transfer_leasehold = $submission->transfer_leasehold;
            $this->transfer_no_of_people = $submission->transfer_no_of_people;

            $this->discount_code = $submission->discount_code;

            $this->contact_email = $submission->contact_email;
            $this->contact_telephone = $submission->contact_telephone;
            $this->contact_name = $submission->contact_name;

            $this->loadedFromDB = true;
            error_log( 'db loaded for ref: '.$calcRef );
            return true;
        } else {
            $this->loadedFromDB = false;
            error_log( 'db not loaded' );
            return false;
        }

    }

    public function generateUniqueID()
    {
        $calcRef = str_replace([' ', '.'], ['', ''], microtime(false));
        error_log('generated ref: '.$calcRef);
        return $calcRef;

    }

    public function save()
    {

        //save to the database using the $wpdb global


        $submission = array(
            'calculator_ref'               => mysql_real_escape_string($this->calculator_ref),


            'sale_price'                   => mysql_real_escape_string($this->sale_price),
            'sale_leasehold'               => mysql_real_escape_string($this->sale_leasehold),
            'sale_mortgage'                => mysql_real_escape_string($this->sale_mortgage),

            'purchase_price'               => mysql_real_escape_string($this->purchase_price),
            'purchase_leasehold'           => mysql_real_escape_string($this->purchase_leasehold),
            'purchase_mortgage'            => mysql_real_escape_string($this->purchase_mortgage),
            'purchase_1st_time_buyer'      => mysql_real_escape_string($this->purchase_1st_time_buyer),
            'purchase_no_of_buyers'        => mysql_real_escape_string($this->purchase_no_of_buyers),

            'remortgage_price'             => mysql_real_escape_string($this->remortgage_price),
            'remortgage_leasehold'         => mysql_real_escape_string($this->remortgage_leasehold),
            'remortgage_no_of_people'      => mysql_real_escape_string($this->remortgage_no_of_people),
            'remortgage_involves_transfer' => mysql_real_escape_string($this->remortgage_involves_transfer),

            'transfer_price'               => mysql_real_escape_string($this->transfer_price),
            'transfer_leasehold'           => mysql_real_escape_string($this->transfer_leasehold),
            'transfer_no_of_people'        => mysql_real_escape_string($this->transfer_no_of_people),

            'discount_code'                => mysql_real_escape_string($this->discount_code),

            'contact_email'                => mysql_real_escape_string($this->contact_email),
            'contact_telephone'            => mysql_real_escape_string($this->contact_telephone),
            'contact_name'                 => mysql_real_escape_string($this->contact_name),

            'contact_street_address'       => mysql_real_escape_string($this->contact_street_address),
            'contact_locality'             => mysql_real_escape_string($this->contact_locality),
            'contact_town'                 => mysql_real_escape_string($this->contact_town),
            'contact_postcode'             => mysql_real_escape_string($this->contact_postcode),

            'additional_1_title'           => mysql_real_escape_string($this->additional_1_title),
            'additional_1_forename'        => mysql_real_escape_string($this->additional_1_forename),
            'additional_1_surname'         => mysql_real_escape_string($this->additional_1_surname),
            'additional_2_title'           => mysql_real_escape_string($this->additional_2_title),
            'additional_2_forename'        => mysql_real_escape_string($this->additional_2_forename),
            'additional_2_surname'         => mysql_real_escape_string($this->additional_2_surname),

            'sale_street_address'          => mysql_real_escape_string($this->sale_street_address),
            'sale_locality'                => mysql_real_escape_string($this->sale_locality),
            'sale_town'                    => mysql_real_escape_string($this->sale_town),
            'sale_postcode'                => mysql_real_escape_string($this->sale_postcode),

            'purchase_street_address'      => mysql_real_escape_string($this->purchase_street_address),
            'purchase_locality'            => mysql_real_escape_string($this->purchase_locality),
            'purchase_town'                => mysql_real_escape_string($this->purchase_town),
            'purchase_postcode'            => mysql_real_escape_string($this->purchase_postcode),

            'remortgage_street_address'    => mysql_real_escape_string($this->remortgage_street_address),
            'remortgage_locality'          => mysql_real_escape_string($this->remortgage_locality),
            'remortgage_town'              => mysql_real_escape_string($this->remortgage_town),
            'remortgage_postcode'          => mysql_real_escape_string($this->remortgage_postcode),

            'transfer_street_address'      => mysql_real_escape_string($this->transfer_street_address),
            'transfer_locality'            => mysql_real_escape_string($this->transfer_locality),
            'transfer_town'                => mysql_real_escape_string($this->transfer_town),
            'transfer_postcode'            => mysql_real_escape_string($this->transfer_postcode),

            'purchase_disbursements_total'   => $this->purchaseDisbursementsTotal,
            'sale_disbursements_total'       => $this->saleDisbursementsTotal,
            'remortgage_disbursements_total' => $this->remortgageDisbursementsTotal,
            'transfer_disbursements_total'   => $this->transferDisbursementsTotal,
            'disbursements_total'            => $this->disbursementsTotal,

            'purchase_disbursements_list'    => json_encode(  $this->purchaseDisbursementsList ),
            'sale_disbursements_list'        => json_encode(  $this->saleDisbursementsList ),
            'remortgage_disbursements_list'  => json_encode(  $this->remortgageDisbursementsList ),
            'transfer_disbursements_list'    => json_encode(  $this->transferDisbursementsList ),


            'purchase_legal_fees'		=> $this->purchaseLegalFees,
            'sale_legal_fees'		=> $this->saleLegalFees,
            'remortgage_legal_fees'		=> $this->remortgageLegalFees,
            'transfer_legal_fees'		=> $this->transferLegalFees,

            'vat_on_fees' => $this->VATOnFees,
            'no_move_no_fee' => $this->noMoveNoFee,
            'quote_total' => $this->quoteTotal ,
            'discounted_total' => $this->discountedTotal ,
            'discount_total' => $this->discount_total ,

            'involves_sale'		    => $this->involves_sale,
            'involves_purchase'		=> $this->involves_purchase,
            'involves_remortgage'	=> $this->involves_remortgage,
            'involves_transfer'		=> $this->involves_transfer



        );
/*
 *
 *   $this->VATOnFees = $this->legalFeesTotal * $this->config->VATRate;

        $this->feesPlusVAT = $this->VATOnFees + $this->legalFeesTotal;

        $this->quoteTotal = $this->legalFeesTotal + $this->VATOnFees + $this->disbursementsTotal;

 * */

        global $wpdb;

        if ($this->loadedFromDB) {
            $wpdb->update(CQN_TABLE_NAME, $submission, ['id' => $this->id]);
        } else {
            $wpdb->insert(CQN_TABLE_NAME, $submission);
        }


    }

    public function showQuote()
    {

        $disbursements = $this->config->getPurchaseDisbursements();
        $html = '<table>
                    <thead>
                        <tr>
                            <th>Thing</th>
                            <th>Amount</th>
                        </tr>
                    </thead>';
        foreach ($disbursements as $disbursement) {
            $html .= '<tr><td>' . $disbursement->name . '</td><td>' . $disbursement->price . '</td></tr>';
        }
        $html .= '</table>';

        return $html;
    }

    public function getTextQuote()
    {

        $ret = '';

        $ret  .= "\nSale Price            =  " . number_format( (int)$this->sale_price , 2);
        $ret  .= "\nPurchase Price        =  " . number_format( (int)$this->purchase_price , 2);
        $ret  .= "\nRemortgage Price      =  " . number_format( (int)$this->remortgage_price , 2);
        $ret  .= "\nTransfer Price        =  " . number_format( (int)$this->transfer_price , 2);
        $ret  .= "\n-------------------------------";

        $ret  .= "\nSale LH Fee           =  " . number_format($this->saleLeaseholdFees , 2);
        $ret  .= "\nPurchase LH Fee       =  " . number_format($this->purchaseLeaseholdFees , 2);
        $ret  .= "\nRemortgage LH Fee     =  " . number_format($this->remortgageLeaseholdFees , 2);
        $ret  .= "\nTransfer LH Fee       =  " . number_format($this->transferLeaseholdFees , 2);

        $ret  .= "\n-------------------------------";

        $ret  .= "\nSale Fees Total       =  " . number_format($this->saleLegalFees , 2);
        $ret  .= "\nPurchase Fees Total   =  " . number_format($this->purchaseLegalFees , 2);
        $ret  .= "\nRemortgage Fees Total =  " . number_format($this->remortgageLegalFees , 2);
        $ret  .= "\nTransfer Fees Total   =  " . number_format($this->transferLegalFees , 2);

        $ret  .= "\nVat                   =  " . number_format($this->VATOnFees , 2);

        $ret  .= "\n         -------------------------------";
        $ret  .= "\n         Fees incl VAT    =           " . number_format($this->feesPlusVAT , 2);
        $ret  .= "\n         -------------------------------";


        $ret  .= "\nSale Disb             =  " . number_format($this->saleDisbursementsTotal , 2);
        $ret  .= "\nPurchase Disb         =  " . number_format($this->purchaseDisbursementsTotal , 2);
        $ret  .= "\nRemortgage Disb       =  " . number_format($this->remortgageDisbursementsTotal , 2);
        $ret  .= "\nTransfer Disb         =  " . number_format($this->transferDisbursementsTotal , 2);

        $ret  .= "\n         Disb. Total      =           " . number_format($this->disbursementsTotal , 2);
        $ret  .= "\n         -------------------------------";
        $ret  .= "\n         Quote Total      =           " . number_format($this->quoteTotal , 2);

        $ret  .= "\n         -------------------------------";
        $ret  .= "\n         Discount Amount  =           " . number_format($this->discount_total , 2);

        $ret  .= "\n         -------------------------------";
        $ret  .= "\n         Discounted Total =           " . number_format($this->discountedTotal , 2);




        return $ret;
    }

    public function getDisbursements()
    {
        return array_merge( $this->purchaseDisbursementsList, $this->saleDisbursementsList, $this->remortgageDisbursementsList, $this->transferDisbursementsList );
    }

    public function clearConfig(){
        $this->config = 'XoXoXoXoXoXoXoX';
    }

}