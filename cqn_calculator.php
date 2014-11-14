<?php
/*
*   Plugin Name: CQN Calculator
*   Description: Calculator For CQN
*   Version: 0.001
*
*/

define( 'CQN_PLUGIN_PATH' , dirname( __FILE__ ) );
define( 'CQN_PLUGIN_URL'  , plugins_url( '', __FILE__ ) );
define( 'CQN_PLUGIN_FILE' , plugin_basename( __FILE__ ) );
define( 'CQN_TABLE_NAME'  , $wpdb->prefix . "cqn_calc_submissions" );
define( 'DOMPDF_ENABLE_AUTOLOAD', false);

define( 'CQN_PDF_STORAGE_DIR'  , CQN_PLUGIN_PATH .  '/storage/quotes/' );



function cqn_activation(){
    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

    $tableName = $wpdb->prefix . "cqn_calc_submissions" ;
    $sql = '  CREATE TABLE `' . $tableName . '` (
                `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
                `calculator_ref` varchar( 255 ),
                `sale_price` int( 11 ) unsigned ,
                `sale_leasehold` int( 1 ) unsigned ,
                `sale_mortgage` int( 1 ) unsigned ,
                `purchase_price` int( 11 ) unsigned ,
                `purchase_leasehold` int( 1 ) unsigned ,
                `purchase_mortgage` int( 1 ) unsigned ,
                `purchase_1st_time_buyer` int( 1 ) unsigned ,
                `purchase_no_of_buyers` int( 2 ) unsigned ,
                `remortgage_price` int( 11 ) unsigned ,
                `remortgage_leasehold` int( 1 ) unsigned ,
                `remortgage_no_of_people` int( 2 ) unsigned ,
                `remortgage_involves_transfer` int( 1 ) unsigned ,
                `transfer_price` int( 11 ) unsigned ,
                `transfer_leasehold` int( 1 ) unsigned ,
                `transfer_no_of_people` int( 2 ) unsigned ,
                `discount_code` varchar( 255  )  ,
                `contact_email` varchar( 255 )  ,
                `contact_telephone` varchar( 25  ),
                `contact_name` varchar( 255 ),


                `involves_sale` int ( 1 ),
                `involves_purchase` int ( 1 ),
                `involves_remortgage` int ( 1 ),
                `involves_transfer` int ( 1 ),

                `instruct_clicked` int ( 1 ),
                `emailed_to_client` int ( 1 ),

                `contact_street_address` varchar(255),
                `contact_locality` varchar(255),
                `contact_town` varchar(255),
                `contact_postcode` varchar(255),
                `additional_1_title` varchar(255),
                `additional_1_forename` varchar(255),
                `additional_1_surname` varchar(255),
                `additional_2_title` varchar(255),
                `additional_2_forename` varchar(255),
                `additional_2_surname` varchar(255),
                `sale_street_address` varchar(255),
                `sale_locality` varchar(255),
                `sale_town` varchar(255),
                `sale_postcode` varchar(255),
                `purchase_street_address` varchar(255),
                `purchase_locality` varchar(255),
                `purchase_town` varchar(255),
                `purchase_postcode` varchar(255),
                `remortgage_street_address` varchar(255),
                `remortgage_locality` varchar(255),
                `remortgage_town` varchar(255),
                `remortgage_postcode` varchar(255),
                `transfer_street_address` varchar(255),
                `transfer_locality` varchar(255),
                `transfer_town` varchar(255),
                `transfer_postcode` varchar(255),

                `purchase_legal_fees` decimal ( 10, 2) ,
                `sale_legal_fees` decimal ( 10, 2) ,
                `remortgage_legal_fees` decimal ( 10, 2) ,
                `transfer_legal_fees` decimal ( 10, 2) ,

                `purchase_leasehold_fees` decimal ( 10, 2) ,
                `sale_leasehold_fees` decimal ( 10, 2) ,
                `remortgage_leasehold_fees` decimal ( 10, 2) ,
                `transfer_leasehold_fees` decimal ( 10, 2) ,





                `no_move_no_fee` decimal ( 10, 2) ,
                `vat_on_fees` decimal ( 10, 2) ,

                `purchase_disbursements_total` decimal ( 10, 2) ,
                `sale_disbursements_total` decimal ( 10, 2) ,
                `remortgage_disbursements_total` decimal ( 10, 2) ,
                `transfer_disbursements_total` decimal ( 10, 2) ,
                `disbursements_total` decimal ( 10, 2) ,

                `quote_total` decimal ( 10, 2) ,
                `discounted_total` decimal ( 10, 2) ,
                `discount_total` decimal ( 10, 2) ,

                `purchase_disbursements_list` text,
                `sale_disbursements_list` text,
                `remortgage_disbursements_list` text,
                `transfer_disbursements_list` text,

                `created_at` TIMESTAMP,
                PRIMARY KEY (`id`)
            )';

    dbDelta( $sql );
}

register_activation_hook( __FILE__ , 'cqn_activation' );

function validateInput(){
    return null;
    return ['CQN_ERROR_contact_email' => 'Invalid Email Address'];
    // idea for validation, just add the error to the $_POST array with keys prewfixed
    // wit some determinable and namespacey string

}

function cqn_init(){

    if( !is_admin() ){

        require_once  CQN_PLUGIN_PATH . '/includes/classes/CQN_Calculator_Config.php' ;
        require_once  CQN_PLUGIN_PATH . '/includes/classes/CQN_Calculator_Submission.php' ;

        require_once CQN_PLUGIN_PATH . '/vendor/twig/twig/lib/Twig/Autoloader.php';
        Twig_Autoloader::register();


        global $CQN_twigLoader;
        global $CQN_twig;

        $CQN_twigLoader = new Twig_Loader_Filesystem ( CQN_PLUGIN_PATH . '/includes/templates');

//        $CQN_twig = new Twig_Environment($CQN_twigLoader, array(
//            'cache' => CQN_PLUGIN_PATH . '/storage/cache',
//        ));
        $CQN_twig = new Twig_Environment($CQN_twigLoader, array(
            'cache' => false,
        ));

        $CQN_twig->getExtension('core')->setNumberFormat(2, '.', ',');

        if( isset( $_POST['cqn_calc_form'] ) ){


            require 'vendor/autoload.php';


            if( isset(  $_POST[ 'cqn_instructType' ] ) ) {

                /*
                 * instruct now or email me clicked
                 *
                 * update the database
                 * send update to the callback system
                 *
                */

                $config = new CQN_Calculator_Config;
                $sub    = new CQN_Calculator_Submission( $config );


                $ref = $_POST['CQN_calculator_ref'];
                $loaded =  $sub->load( $ref ) ;


                if($_POST[ 'cqn_instructType' ] == 'instructNow' ){

                    $sub->loadFromPost( $_POST );



                    error_log(CQN_PDF_STORAGE_DIR . $sub->getCalculatorRef() . '.pdf' );
                    wp_mail( $config->instructEmailAddress,  'Calculator form instruction request', $sub->getTextQuote() , '', CQN_PDF_STORAGE_DIR . $sub->getCalculatorRef() . '.pdf'  );
                    $sub->instruct_clicked = 1;
                    $sub->emailed_to_lient = 0;

                }else{// email me quote clicked

                    error_log('sending to -- '. $sub->contact_email );
                    wp_mail( $sub->contact_email,  $config->clientEmailSubject, $sub->getTextQuote() , '', CQN_PDF_STORAGE_DIR . $sub->getCalculatorRef() . '.pdf'  );
                    $sub->emailed_to_client = 1;
                    $sub->instruct_clicked = 0;
                }


               // $sub->calculate();







                $sub->save();


                $sub->clearConfig();// to help prevent details of config leaking

                $_SESSION['CQN_calculator_submission'] = $sub;

                add_shortcode('cqn_calculator', 'cqn_show_thanks');

            }else{// show quote

                $errors = validateInput();

                if (!$errors) {

                    /*
                     * do calculation
                     * save the submission to database
                     * create the pdf
                     * send details to callback system
                     *
                     * set the shortcode to show the 'quote' and the 'extra fields' form
                     *
                    */



                    $config = new CQN_Calculator_Config;
                    $sub    = new CQN_Calculator_Submission( $config );

//
//                    if( isset(  $_POST['CQN_calculator_ref']  ) ){
//
//                        $ref = $_POST['CQN_calculator_ref'];
//                        $loaded =  $sub->load( $ref ) ;
//                        error_log( 'loaded = ' . $loaded );
//                        error_log('found posted ref: '. $ref );
//
//                    }

                    $sub->loadFromPost( $_POST );

//                  error_log( "Quote type = " . $sub->quoteType );

                    $sub->calculate();

                    $sub->save();


                    // email to callback system


                    $_SESSION['CQN_calculator_submission'] = $sub;


                    $sub->savePDF( CQN_PDF_STORAGE_DIR );


                    error_log(CQN_PDF_STORAGE_DIR . $sub->getCalculatorRef() . '.pdf' );

                    wp_mail( $config->leadsSystemEmailAddress, $config->leadsSystemEmailSubject, '__calc-ref=davelala;__saleprice=12333', '', CQN_PDF_STORAGE_DIR . $sub->getCalculatorRef() . '.pdf'  );


                    wp_enqueue_script( 'cqn_calculator_script', CQN_PLUGIN_URL . '/includes/js/min/cqn_calc.min.js', [ 'jquery' ] );
                    wp_enqueue_style(  'cqn_calculator_styles', CQN_PLUGIN_URL . '/includes/css/cqn_styles.css');

                    $sub->clearConfig();// to help prevent details of config leaking
                    add_shortcode('cqn_calculator', 'cqn_show_quote');
                } else {

                    // there are errors ??
                    // set shortcode to show the form again

                    add_shortcode('cqn_calculator', 'cqn_show_calculator');
                }
            }
        }else{



            $config = new CQN_Calculator_Config;
            $sub    = new CQN_Calculator_Submission( $config );

            $_SESSION['CQN_calculator_submission'] = $sub;

            wp_enqueue_script( 'cqn_calculator_script', CQN_PLUGIN_URL . '/includes/js/min/cqn_calc.min.js', [ 'jquery' ] );
            wp_enqueue_style(  'cqn_calculator_styles', CQN_PLUGIN_URL . '/includes/css/cqn_styles.css');

            add_shortcode(     'cqn_calculator', 'cqn_show_calculator' );
        }
    }
}


function cqn_show_thanks(   ){
    global $CQN_twig;
    $template = $CQN_twig->loadTemplate('calc-thanks.twig');
    return $template->render( [ 'sub' => $_SESSION['CQN_calculator_submission'] ] );
}

function cqn_show_quote(   ){
    global $CQN_twig;
    $template = $CQN_twig->loadTemplate('calc-quote.twig');
    return $template->render( [ 'sub' => $_SESSION['CQN_calculator_submission'] ] );
}

function cqn_show_calculator(  $errors )
{
    global $CQN_twig;
    $template = $CQN_twig->loadTemplate('calc-form.twig');
    return $template->render( [ 'sub' => $_SESSION['CQN_calculator_submission'] ] );

}

add_action(     'init', 'cqn_init' );

add_action( 'init',      'cqn_startSession', 1 );
add_action( 'wp_logout', 'cqn_destroySession' );
add_action( 'wp_login',  'cqn_destroySession' );

function cqn_startSession() {
    //session_start();
    //session_destroy ();
    if( !is_admin() ) {

        if (!session_id()) {
            session_start();
        }
    }
    //    error_log( print_r( $_SESSION, true ) );
}

function cqn_destroySession() {
    session_destroy ();
}


