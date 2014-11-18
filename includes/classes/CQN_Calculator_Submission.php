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

    public $instruct_clicked;
    public $emailed_to_client;

    public $involves_sale;
    public $involves_purchase;
    public $involves_remortgage;
    public $involves_transfer;

    public $purchase_legal_fees;
    public $sale_legal_fees;
    public $remortgage_legal_fees;
    public $transfer_legal_fees;

    public $purchase_leasehold_fees;
    public $sale_leasehold_fees;
    public $remortgage_leasehold_fees;
    public $transfer_leasehold_fees;

    public $no_move_no_fee;

    public $vat_on_fees;

    public $legal_fees_total;// $fees excluding VAT

    public $fees_plus_vat;// $fees + VAT

    public $quote_total;
    public $discounted_total;

    public $purchase_disbursements_total;
    public $sale_disbursements_total;
    public $remortgage_disbursements_total;
    public $transfer_disbursements_total;

    public $disbursements_total;


    // for storing json of disbursements at time of quoting , as they can change over time

    public $purchase_disbursements_list = [];
    public $sale_disbursements_list = [];
    public $remortgage_disbursements_list = [];
    public $transfer_disbursements_list = [];

    public $optional_disbursements_list = [];

    public $discount_code;
    public $discount_total;

    public $sale_price;
    public $purchase_price;
    public $remortgage_price;
    public $transfer_price;

    public $sale_leasehold;
    public $sale_mortgage;

    public $purchase_leasehold;
    public $purchase_mortgage;
    public $purchase_1st_time_buyer;
    public $purchase_no_of_buyers;

    public $remortgage_leasehold;
    public $remortgage_no_of_people;
    public $remortgage_involves_transfer;

    public $transfer_leasehold;
    public $transfer_no_of_people;

    public $contact_email;
    public $contact_telephone;
    public $contact_name;


    public $quoteType;


    public $contact_street_address;
    public $contact_locality;
    public $contact_town;
    public $contact_postcode;

    public $additional_1_fullname;
    public $additional_2_fullname;

    public $sale_street_address;
    public $sale_locality;
    public $sale_town;
    public $sale_postcode;

    public $purchase_street_address;
    public $purchase_locality;
    public $purchase_town;
    public $purchase_postcode;

    public $remortgage_street_address;
    public $remortgage_locality;
    public $remortgage_town;
    public $remortgage_postcode;

    public $transfer_street_address;
    public $transfer_locality;
    public $transfer_town;
    public $transfer_postcode;

    public $site_name;

    public $errors;


    public function calculate()
    {

        $this->sale_legal_fees = 0;
        $this->purchase_legal_fees = 0;
        $this->transfer_legal_fees = 0;
        $this->remortgage_legal_fees = 0;

        $this->purchase_leasehold_fees = 0;
        $this->sale_leasehold_fees = 0;
        $this->remortgage_leasehold_fees = 0;
        $this->transfer_leasehold_fees = 0;

        $this->no_move_no_fee = 0;

        $this->sale_disbursements_total = 0;
        $this->purchase_disbursements_total = 0;
        $this->transfer_disbursements_total = 0;
        $this->remortgage_disbursements_total = 0;

        $this->disbursements_total = 0;

        $this->legal_fees_total = 0;
        $this->vat_on_fees = 0;
        $this->quote_total = 0;

        if ($this->involves_sale) {

            $this->sale_legal_fees = $this->config->getSaleFees($this->sale_price);

            if ($this->sale_leasehold) {
                $this->sale_leasehold_fees = $this->config->sale_leasehold_fee;
            }

            $saleDisbursements = $this->config->getSaleDisbursements();


            foreach ($saleDisbursements as $disbursement) {

                if( !$disbursement->optional ) {// if mandatory disbursement += total
                    $this->sale_disbursements_total += $disbursement->price;
                }else{//add to optional disbursements array
                    $this->optional_disbursements_list[] = $disbursement;
                }
                $this->sale_disbursements_list[] = $disbursement;
            }
        }

        if ($this->involves_purchase) {

            $this->purchase_legal_fees = $this->config->getPurchaseFees($this->purchase_price);

            if ($this->purchase_leasehold) {
                $this->purchase_leasehold_fees = $this->config->purchase_leasehold_fee;
            }
            $purchaseDisbursements = $this->config->getPurchaseDisbursements();

            foreach ($purchaseDisbursements as $disbursement) {
                if( $disbursement->code == 'PURCHASE_BS' ) {
                    for( $i = 1; $i <= $this->purchase_no_of_buyers; $i++ ){
                        $this->purchase_disbursements_total += $disbursement->price;
                        $this->purchase_disbursements_list[] = $disbursement;
                    }
                }else {


                    if( !$disbursement->optional ) {// if mandatory disbursement += total
                        $this->purchase_disbursements_total += $disbursement->price;
                    }else{//add to optional disbursements array
                        $this->optional_disbursements_list[] = $disbursement;
                    }
                    $this->purchase_disbursements_list[] = $disbursement;
                }
            }

            $stampDuty = $this->config->getStampDuty( $this->purchase_price, $this->purchase_1st_time_buyer );
            $this->purchase_disbursements_total += $stampDuty;
            $this->purchase_disbursements_list[] = (object) [ 'code' => 'PURCHASE_SD'       , 'optional' => false      , 'price' => $stampDuty ,    'name' => 'Stamp Duty' ];


            $landRegistryFee = $this->config->getSPLandRegistryFees( $this->purchase_price );
            $this->purchase_disbursements_total += $landRegistryFee;
            $this->purchase_disbursements_list[] = (object) [ 'code' => 'PURCHASE_LRF'     , 'optional' => false        , 'price' => $landRegistryFee ,    'name' => 'Land Registry Fee' ];
        }

        if ($this->involves_remortgage) {

            $this->remortgage_legal_fees = $this->config->getRemortgageFees($this->remortgage_price);

            if ($this->remortgage_leasehold) {
                $this->remortgage_leasehold_fees = $this->config->remortgage_leasehold_fee;
            }
            $remortgageDisbursements = $this->config->getRemortgageDisbursements();

            foreach ($remortgageDisbursements as $disbursement) {

                if( $disbursement->code == 'REMORTGAGE_BS' ) {
                    for( $i = 1; $i <= $this->remortgage_no_of_people; $i++ ){
                        $this->remortgage_disbursements_total += $disbursement->price;
                        $this->remortgage_disbursements_list[] = $disbursement;
                    }
                }else {
                    if( !$disbursement->optional ) {// if mandatory disbursement += total
                        $this->remortgage_disbursements_total += $disbursement->price;
                    }else{//add to optional disbursements array
                        $this->optional_disbursements_list[] = $disbursement;
                    }
                    $this->remortgage_disbursements_list[] = $disbursement;
                }
            }

            $landRegistryFee = $this->config->getRTLandRegistryFees( $this->remortgage_price );
            $this->remortgage_disbursements_total += $landRegistryFee;
            $this->remortgage_disbursements_list[] = (object) [ 'code' => 'REMORTGAGE_LRF'     , 'optional' => false        , 'price' => $landRegistryFee ,    'name' => 'Land Registry Fee' ];
        }

        if ($this->involves_transfer) {

            $this->transfer_legal_fees = $this->config->getTransferFees($this->transfer_price);

            if ($this->transfer_leasehold) {
                $this->transfer_leasehold_fees = $this->config->transfer_leasehold_fee;
            }
            $transferDisbursements = $this->config->getTransferDisbursements();

            foreach ($transferDisbursements as $disbursement) {

                if( $disbursement->code == 'TRANSFER_BS' ) {
                    for( $i = 1; $i <= $this->transfer_no_of_people; $i++ ){
                        $this->transfer_disbursements_total += $disbursement->price;

                        $this->transfer_disbursements_list[] = $disbursement;
                    }
                }else {
                    if( !$disbursement->optional ) {// if mandatory disbursement += total
                        $this->transfer_disbursements_total += $disbursement->price;
                    }else{//add to optional disbursements array
                        $this->optional_disbursements_list[] = $disbursement;
                    }
                    $this->transfer_disbursements_list[] = $disbursement;
                }
            }

            $landRegistryFee = $this->config->getRTLandRegistryFees( $this->transfer_price );
            $this->transfer_disbursements_total += $landRegistryFee;
            $this->transfer_disbursements_list[] = (object) [ 'code' => 'TRANSFER_LRF'     , 'optional' => false        , 'price' => $landRegistryFee ,    'name' => 'Land Registry Fee' ];
        }

        $this->legal_fees_total = $this->sale_legal_fees + $this->purchase_legal_fees + $this->remortgage_legal_fees + $this->transfer_legal_fees +
            $this->sale_leasehold_fees + $this->purchase_leasehold_fees + $this->remortgage_leasehold_fees + $this->transfer_leasehold_fees +
            $this->no_move_no_fee;

        $this->disbursements_total = $this->sale_disbursements_total +
            $this->purchase_disbursements_total +
            $this->remortgage_disbursements_total +
            $this->transfer_disbursements_total;


        $this->vat_on_fees = $this->legal_fees_total * $this->config->VATRate;

        $this->fees_plus_vat = $this->vat_on_fees + $this->legal_fees_total;

        $this->quote_total = $this->legal_fees_total + $this->vat_on_fees + $this->disbursements_total;

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
                            $this->discount_total = ( $discount->amount * $this->quote_total );
                            break;
                default:

                            $this->discount_total = 0;
                            break;
            }

        }else{

            $this->discount_total = 0;
        }

        $this->discounted_total = $this->quote_total - $this->discount_total ;

    }

    public function getCalculatorRef()
    {

        return $this->calculator_ref;
    }

    public function __construct(CQN_Calculator_Config $config)
    {

        $this->config = $config;
        $this->calculator_ref = $this->generateUniqueID();

        $this->errors = false;

        $this->site_name = get_bloginfo();
    }

    public function validate()
    {
        $validator = new \Hampel\Validate\Validator();
        $this->errors = false;

        if( !$validator->isEmail( $this->contact_email ) )
            $this->errors[ 'contact_email' ] = 'please enter a valid email';

        if( !( is_numeric( $this->sale_price ) && $this->sale_price > 1000 )  )
            $this->errors[ 'sale_price' ] = 'please enter a valid sale price';

        if( !( is_numeric( $this->purchase_price ) && $this->purchase_price > 1000 )  )
            $this->errors[ 'purchase_price' ] = 'please enter a valid purchase price';

        if( !( is_numeric( $this->remortgage_price ) && $this->remortgage_price > 1000 )  )
            $this->errors[ 'remortgage_price' ] = 'please enter a valid remortgage price';

        if( !( is_numeric( $this->transfer_price ) && $this->transfer_price > 1000 )  )
            $this->errors[ 'transfer_price' ] = 'please enter a valid transfer price';




        return true;// true that validation has run ok, not validation is ok

    }

    public function loadFromPost($postArray)
    {

        $fillable = [
            'sale_price', 'sale_leasehold', 'sale_mortgage', 'purchase_price', 'purchase_leasehold', 'purchase_mortgage', 'purchase_1st_time_buyer', 'purchase_no_of_buyers', 'remortgage_price', 'remortgage_leasehold', 'remortgage_no_of_people', 'remortgage_involves_transfer', 'transfer_price', 'transfer_leasehold', 'transfer_no_of_people', 'discount_code', 'contact_email', 'contact_telephone', 'contact_name', 'contact_street_address', 'contact_locality', 'contact_town', 'contact_postcode', 'additional_1_fullname', 'additional_2_fullname' , 'sale_street_address', 'sale_locality', 'sale_town', 'sale_postcode', 'purchase_street_address', 'purchase_locality', 'purchase_town', 'purchase_postcode', 'remortgage_street_address', 'remortgage_locality', 'remortgage_town', 'remortgage_postcode', 'transfer_street_address', 'transfer_locality', 'transfer_town', 'transfer_postcode'
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

            $this->purchase_disbursements_list   = json_decode( $submission->purchase_disbursements_list );
            $this->sale_disbursements_list       = json_decode( $submission->sale_disbursements_list );
            $this->remortgage_disbursements_list = json_decode( $submission->remortgage_disbursements_list );
            $this->transfer_disbursements_list   = json_decode( $submission->transfer_disbursements_list );
            $this->optional_disbursements_list   = json_decode( $submission->optional_disbursements_list );


            $this->purchase_legal_fees  = $submission->purchase_legal_fees;
            $this->sale_legal_fees  = $submission->sale_legal_fees;
            $this->remortgage_legal_fees  = $submission->remortgage_legal_fees;
            $this->transfer_legal_fees  = $submission->transfer_legal_fees;

            $this->purchase_leasehold_fees  = $submission->purchase_leasehold_fees;
            $this->sale_leasehold_fees  = $submission->sale_leasehold_fees;
            $this->remortgage_leasehold_fees  = $submission->remortgage_leasehold_fees;
            $this->transfer_leasehold_fees  = $submission->transfer_leasehold_fees;

            $this->vat_on_fees  = $submission->vat_on_fees;

            $this->no_move_no_fee  = $submission->no_move_no_fee;

            $this->fees_plus_vat  = $submission->fees_plus_vat;




            $this->quote_total  = $submission->quote_total;
            $this->discount_total  = $submission->discount_total;
            $this->discounted_total  = $submission->discounted_total;


            $this->purchase_disbursements_total  = $submission->purchase_disbursements_total;
            $this->sale_disbursements_total  = $submission->sale_disbursements_total;
            $this->remortgage_disbursements_total  = $submission->remortgage_disbursements_total;
            $this->transfer_disbursements_total  = $submission->transfer_disbursements_total;
            $this->disbursements_total  = $submission->disbursements_total;


            $this->instruct_clicked  = $submission->instruct_clicked;
            $this->emailed_to_client  = $submission->emailed_to_client;

            $this->loadedFromDB = true;
            return true;
        } else {
            $this->loadedFromDB = false;
            return false;
        }

    }

    public function generateUniqueID()
    {
        $calcRef = str_replace([' ', '.'], ['', ''], microtime(false));
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
            'additional_1_fullname'        => mysql_real_escape_string($this->additional_1_fullname),
            'additional_2_fullname'        => mysql_real_escape_string($this->additional_2_fullname),
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

            'purchase_disbursements_total'   => $this->purchase_disbursements_total,
            'sale_disbursements_total'       => $this->sale_disbursements_total,
            'remortgage_disbursements_total' => $this->remortgage_disbursements_total,
            'transfer_disbursements_total'   => $this->transfer_disbursements_total,
            'disbursements_total'            => $this->disbursements_total,

            'purchase_disbursements_list'    => json_encode(  $this->purchase_disbursements_list ),
            'sale_disbursements_list'        => json_encode(  $this->sale_disbursements_list ),
            'remortgage_disbursements_list'  => json_encode(  $this->remortgage_disbursements_list ),
            'transfer_disbursements_list'    => json_encode(  $this->transfer_disbursements_list ),
            'optional_disbursements_list'    => json_encode(  $this->optional_disbursements_list ),


            'purchase_legal_fees'		=> $this->purchase_legal_fees,
            'sale_legal_fees'		=> $this->sale_legal_fees,
            'remortgage_legal_fees'		=> $this->remortgage_legal_fees,
            'transfer_legal_fees'		=> $this->transfer_legal_fees,

            'purchase_leasehold_fees'		=> $this->purchase_leasehold_fees,
            'sale_leasehold_fees'		=> $this->sale_leasehold_fees,
            'remortgage_leasehold_fees'		=> $this->remortgage_leasehold_fees,
            'transfer_leasehold_fees'		=> $this->transfer_leasehold_fees,



            'vat_on_fees' => $this->vat_on_fees,
            'no_move_no_fee' => $this->no_move_no_fee,

            'fees_plus_vat' => $this->fees_plus_vat,


            'quote_total' => $this->quote_total ,
            'discounted_total' => $this->discounted_total ,
            'discount_total' => $this->discount_total ,

            'involves_sale'		    => $this->involves_sale,
            'involves_purchase'		=> $this->involves_purchase,
            'involves_remortgage'	=> $this->involves_remortgage,
            'involves_transfer'		=> $this->involves_transfer,

            'instruct_clicked'	    => $this->instruct_clicked,
            'emailed_to_client'		=> $this->emailed_to_client




        );
/*
 *
 *   $this->vat_on_fees = $this->legal_fees_total * $this->config->VATRate;

        $this->fees_plus_vat = $this->vat_on_fees + $this->legal_fees_total;

        $this->quote_total = $this->legal_fees_total + $this->vat_on_fees + $this->disbursements_total;

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

        $ret  .= "\nSale LH Fee           =  " . number_format( (int) $this->sale_leasehold_fees , 2);
        $ret  .= "\nPurchase LH Fee       =  " . number_format( (int) $this->purchase_leasehold_fees , 2);
        $ret  .= "\nRemortgage LH Fee     =  " . number_format( (int) $this->remortgage_leasehold_fees , 2);
        $ret  .= "\nTransfer LH Fee       =  " . number_format( (int) $this->transfer_leasehold_fees , 2);

        $ret  .= "\n-------------------------------";

        $ret  .= "\nSale Fees Total       =  " . number_format( (int) $this->sale_legal_fees , 2);
        $ret  .= "\nPurchase Fees Total   =  " . number_format( (int) $this->purchase_legal_fees , 2);
        $ret  .= "\nRemortgage Fees Total =  " . number_format( (int) $this->remortgage_legal_fees , 2);
        $ret  .= "\nTransfer Fees Total   =  " . number_format( (int) $this->transfer_legal_fees , 2);

        $ret  .= "\nVat                   =  " . number_format( (int) $this->vat_on_fees , 2);

        $ret  .= "\n         -------------------------------";
        $ret  .= "\n         Fees incl VAT    =           " . number_format( (int) $this->fees_plus_vat , 2);
        $ret  .= "\n         -------------------------------";


        $ret  .= "\nSale Disb             =  " . number_format( (int) $this->sale_disbursements_total , 2);
        $ret  .= "\nPurchase Disb         =  " . number_format( (int) $this->purchase_disbursements_total , 2);
        $ret  .= "\nRemortgage Disb       =  " . number_format( (int) $this->remortgage_disbursements_total , 2);
        $ret  .= "\nTransfer Disb         =  " . number_format( (int) $this->transfer_disbursements_total , 2);

        $ret  .= "\n         Disb. Total      =           " . number_format( (int) $this->disbursements_total , 2);
        $ret  .= "\n         -------------------------------";
        $ret  .= "\n         Quote Total      =           " . number_format( (int) $this->quote_total , 2);

        $ret  .= "\n         -------------------------------";
        $ret  .= "\n         Discount Amount  =           " . number_format( (int) $this->discount_total , 2);

        $ret  .= "\n         -------------------------------";
        $ret  .= "\n         Discounted Total =           " . number_format( (int) $this->discounted_total , 2);




        return $ret;
    }

    public function getDisbursements()
    {
        return array_merge( $this->purchase_disbursements_list, $this->sale_disbursements_list, $this->remortgage_disbursements_list, $this->transfer_disbursement_slist );
    }

    public function clearConfig(){
        $this->config = 'XoXoXoXoXoXoXoX';
    }

    public function savePDF( $saveDir )
    {

        require_once CQN_PLUGIN_PATH . '/vendor/dompdf/dompdf/dompdf_config.inc.php';

        $html = $this->getPDFQuoteHTML();

        $dompdf = new DOMPDF();
        $dompdf->load_html($html);
        $dompdf->render();
        $saved = file_put_contents( $saveDir . $this->calculator_ref . '.pdf' , $dompdf->output() );
        return $saved;
//        $dompdf->stream( $this->calculator_ref .  '' . '.pdf' );

    }


    public function getPDFQuoteHTML()
    {
        global $CQN_twig;
        $template = $CQN_twig->loadTemplate('calc-quote-pdf.twig');

        $styles = '<style type="text/css">' . file_get_contents( CQN_PLUGIN_PATH . '/includes/css/cqn_pdf-quote.css' ) . '</style>';

        $html = '<html><head>'.$styles.'</head><body>';

            //$html .= $this->getTextQuote();
//        $html .=  $template->render( [ 'sub' => $_SESSION['CQN_calculator_submission'] ] );
        $html .=  $template->render( [ 'sub' => $this ] );

        $html .= '</body></html>';




        return $html;
    }


    function emailToLeadsSystem(){
        return true;
    }


}