<?php
/*
*   Plugin Name: CQN Calculator
*   Description: Calculator For CQN
*   Version: 0.001
*
*/
global $wpdb;

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

                `additional_1_fullname` varchar(255),
                `additional_2_fullname` varchar(255),

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

                `fees_plus_vat` decimal ( 10, 2) ,

                `purchase_disbursements_total` decimal ( 10, 2) ,
                `sale_disbursements_total` decimal ( 10, 2) ,
                `remortgage_disbursements_total` decimal ( 10, 2) ,
                `transfer_disbursements_total` decimal ( 10, 2) ,
                `disbursements_total` decimal ( 10, 2) ,

                `quote_total` decimal ( 10, 2) ,
                `discount_total` decimal ( 10, 2) ,
                `discounted_total` decimal ( 10, 2) ,

                `purchase_disbursements_list` text,
                `sale_disbursements_list` text,
                `remortgage_disbursements_list` text,
                `transfer_disbursements_list` text,
                `optional_disbursements_list` text,

                `created_at` TIMESTAMP,
                PRIMARY KEY (`id`)
            )';
    dbDelta( $sql );
}

register_activation_hook( __FILE__ , 'cqn_activation' );

function cqn_init(){

    if( !is_admin() ){

        require 'vendor/autoload.php';

        require_once  CQN_PLUGIN_PATH . '/includes/classes/CQN_Calculator_Config.php' ;
        require_once  CQN_PLUGIN_PATH . '/includes/classes/CQN_Calculator_Submission.php' ;

        //require_once CQN_PLUGIN_PATH . '/vendor/twig/twig/lib/Twig/Autoloader.php';
        Twig_Autoloader::register();


        global $CQN_twigLoader;
        global $CQN_twig;

        $CQN_twigLoader = new Twig_Loader_Filesystem ( CQN_PLUGIN_PATH . '/includes/templates');

//       $CQN_twig = new Twig_Environment($CQN_twigLoader, array(
//           'cache' => CQN_PLUGIN_PATH . '/storage/cache',
//       ));

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
                error_log( $ref );
                $loaded =  $sub->load( $ref ) ;

                if( $_POST[ 'cqn_instructType' ] == 'instructNow' ) {

                    $sub->loadFromPost($_POST);

                    $html = '<style type="text/css">' . file_get_contents( CQN_PLUGIN_PATH . '/includes/css/cqn_emails.css' ) . '</style>';
                    $template = $CQN_twig->loadTemplate('calc-email-instruct-admin.twig');
                    $html .= $template->render( [ 'sub' => $sub ] );

                    wp_mail($config->instructEmailAddress, 'Calculator form instruction request', $html, '', CQN_PDF_STORAGE_DIR . $sub->getCalculatorRef() . '.pdf');



                    $sub->instruct_clicked = 1;
                    $sub->emailed_to_client = 0;



                    $html = '<style type="text/css">' . file_get_contents( CQN_PLUGIN_PATH . '/includes/css/cqn_emails.css' ) . '</style>';
                    $template = $CQN_twig->loadTemplate('calc-email-instruct-client.twig');
                    $html .= $template->render( [ 'sub' => $sub ] );

                    wp_mail($config->instructEmailAddress, 'Instruction Requested', $html, '', CQN_PDF_STORAGE_DIR . $sub->getCalculatorRef() . '.pdf');


                    $leadsSubmissionBody = $sub->getWebleadsSubmissionBody();
                    wp_mail( $config->leadsSystemEmailAddress, $config->leadsSystemEmailSubject, $leadsSubmissionBody , '' );



                }elseif ( $_POST[ 'cqn_instructType' ] == 'downloadQuote' ){// downloload clicked

                    $sub->savePDF( CQN_PDF_STORAGE_DIR );

                    $file = CQN_PDF_STORAGE_DIR . $sub->getCalculatorRef() . '.pdf';

                    if (file_exists($file)) {
                        header('Content-Description: File Transfer');
                        header('Content-Type: application/pdf');
                        header('Content-Disposition: attachment; filename=CSUK-conveyancing-quote.pdf');
                        header('Content-Transfer-Encoding: binary');
                        header('Expires: 0');
                        header('Cache-Control: must-revalidate');
                        header('Pragma: public');
                        header('Content-Length: ' . filesize($file));
                        ob_clean();
                        flush();
                        readfile($file);
                        exit;
                    }

                }else{// email me quote clicked

                    $html = '<style type="text/css">' . file_get_contents( CQN_PLUGIN_PATH . '/includes/css/cqn_emails.css' ) . '</style>';
                    $template = $CQN_twig->loadTemplate('calc-email-instruct-client.twig');
                    $html .= $template->render( [ 'sub' => $sub ] );

                    wp_mail( $sub->contact_email,  $config->clientEmailSubject, $html, '', CQN_PDF_STORAGE_DIR . $sub->getCalculatorRef() . '.pdf'  );

                    $sub->emailed_to_client = 1;
                    $sub->instruct_clicked = 0;

                    $leadsSubmissionBody = $sub->getWebleadsSubmissionBody();
                    wp_mail( $config->leadsSystemEmailAddress, $config->leadsSystemEmailSubject, $leadsSubmissionBody , '' );

                }

                $sub->save();

                $sub->clearConfig();// to help prevent details of config leaking

                $_SESSION['CQN_calculator_submission'] = $sub;

                add_shortcode('cqn_calculator', 'cqn_show_thanks');

            }else{// show quote

                $config = new CQN_Calculator_Config;
                $sub    = new CQN_Calculator_Submission( $config );

                $sub->loadFromPost( $_POST );

                $sub->validate();

                $errors = $sub->errors;

                if (  !$errors  ) {//  calculator form submitted
                    /*
                     * do calculation
                     * save the submission to database
                     * create the pdf
                     * send details to callback system
                     *
                     * set the shortcode to show the 'quote' and the 'extra fields' form
                     *
                    */

                    $sub->calculate();
                    $sub->save();

                    // still need to send calculator used email?
                    // email to callback system

                    $_SESSION['CQN_calculator_submission'] = $sub;
                    $sub->savePDF( CQN_PDF_STORAGE_DIR );


                    $leadsSubmissionBody = $sub->getWebleadsSubmissionBody();

                    error_log( 'sending .."'.$config->leadsSystemEmailSubject.'" .... to ' . $config->leadsSystemEmailAddress );
                    wp_mail( $config->leadsSystemEmailAddress, $config->leadsSystemEmailSubject, $leadsSubmissionBody , '', CQN_PDF_STORAGE_DIR . $sub->getCalculatorRef() . '.pdf'  );



                    wp_enqueue_script( 'cqn_calculator_script', CQN_PLUGIN_URL . '/includes/js/min/cqn_calc.min.js', [ 'jquery' ] );
                    wp_enqueue_style(  'cqn_calculator_styles', CQN_PLUGIN_URL . '/includes/css/cqn_styles.css');

                    $sub->clearConfig();// to help prevent details of config leaking
                    add_shortcode('cqn_calculator', 'cqn_show_quote');

                }else{

                    wp_enqueue_script( 'cqn_calculator_script', CQN_PLUGIN_URL . '/includes/js/min/cqn_calc.min.js', [ 'jquery' ] );
                    wp_enqueue_style(  'cqn_calculator_styles', CQN_PLUGIN_URL . '/includes/css/cqn_styles.css');

                    $_SESSION['CQN_calculator_submission'] = $sub;
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

function cqn_test_sale(   ){
    return cqn_show_test ( [
        'quote_type' => 'sale',
        'sale_no_of_people' => 2,
        'sale_leasehold' => 1,
        'sale_price'        => 250000,
    ]);
}

function cqn_test_purchase(   ){
    return cqn_show_test ( [
        'quote_type' => 'purchase',
        'purchase_leasehold' => 1,
        'purchase_no_of_buyers' => 2,
        'purchase_price'        => 250000,
    ]);
}

function cqn_test_sale_purchase(   ){
    return cqn_show_test ( [
        'quote_type' => 'sale_purchase',
        'purchase_leasehold' => 1,
        'purchase_no_of_buyers' => 2,
        'purchase_price'        => 250000,
        'sale_no_of_people' => 2,
        'sale_leasehold' => 1,
        'sale_price'        => 250000,
    ]);
}
function cqn_test_remortgage(   ){
    return cqn_show_test ( [
        'quote_type' => 'remortgage',
        'remortgage_involves_transfer' => 1,
        'remortgage_no_of_people' => 2,
        'transfer_no_of_people' => 2,
        'transfer_leasehold' => 1,
        'transfer_price'        => 80000,
        'remortgage_price'        => 250000,
    ]);
}
function cqn_test_transfer(   ){
    return cqn_show_test ( [
        'quote_type' => 'transfer',
        'transfer_no_of_people' => 2,
        'transfer_leasehold' => 1,
        'transfer_price'        => 80000,
    ]);
}
function cqn_test_remortgage_transfer(   ){
    return cqn_show_test ( [
        'quote_type' => 'remortgage',
        'remortgage_involves_transfer' => 1,
        'remortgage_no_of_people' => 2,
        'transfer_no_of_people' => 2,
        'transfer_leasehold' => 1,
        'transfer_price'        => 80000,
        'remortgage_price'        => 250000,
    ]);
}
function cqn_show_test( $post ){

    wp_enqueue_script( 'cqn_calculator_script', CQN_PLUGIN_URL . '/includes/js/min/cqn_calc.min.js', [ 'jquery' ] );
    wp_enqueue_style(  'cqn_calculator_styles', CQN_PLUGIN_URL . '/includes/css/cqn_styles.css');

    $post['contact_email'] = 'davebenn@gmail.com';
    $post['discount_code'] = 'CSUK25';

    global $CQN_twig;
    $template = $CQN_twig->loadTemplate('calc-quote.twig');

    $config = new CQN_Calculator_Config;
    $sub = new CQN_Calculator_Submission( $config );

    $sub->loadFromPost( $post );

    $sub->calculate();

    return $template->render( [ 'sub' => $sub ] );
}

function cqn_test_instruct_email(   ){

    global $CQN_twig;

    $post =  [

            'contact_email' =>  'davebenn@gmail.com',
            'contact_telephone' => '07875295076',
            'contact_name' => 'Dave Tester',
            'quote_type' => 'sale_purchase',
            'quote_type' => 'remortgage',

            'remortgage_involves_transfer' => 1,
            'remortgage_no_of_people' => 2,
            'transfer_no_of_people' => 2,
            'transfer_leasehold' => 1,
            'transfer_price'        => 80000,
            'remortgage_price'        => 250000,

            'contact_street_address' => '1 Chrurch St.',
            'contact_locality' => 'Longridge',
            'contact_town' => 'Preston',
            'contact_postcode' => 'PR3 3ZZ',
            'additional_1_fullname' => 'Dave Smith',
            'additional_2_fullname' => 'Freddy Dodge',

            'remortgage_street_address' => '1 Feee St.',
            'remortgage_locality' => 'Fedd',
            'remortgage_town' => 'Preston',
            'remortgage_postcode' => 'LO3 5JJ',

            'transfer_street_address' => '41 DsSd St.',
            'transfer_locality' => 'Ssdfsf',
            'transfer_town' => 'Preston',
            'transfer_postcode' => 'LO3 5JJ',
        ];
    $post =  [

            'contact_email' =>  'davebenn@gmail.com',
            'contact_telephone' => '07875295076',
            'contact_name' => 'Dave Tester',
            'quote_type' => 'sale_purchase',

            'sale_no_of_people' => 2,

            'purchase_no_of_buyers' => 2,
            'purchase_leasehold' => 1,

            'sale_leasehold' => 1,
            'sale_mortgage'  => 0,

            'purchase_price'        => 80000,
            'sale_price'        => 250000,

            'contact_street_address' => '1 Chrurch St.',
            'contact_locality' => 'Longridge',
            'contact_town' => 'Preston',
            'contact_postcode' => 'PR3 3ZZ',

            'additional_1_fullname' => 'Dave Smith',
            'additional_2_fullname' => 'Freddy Dodge',

            'sale_street_address' => '1 Feee St.',
            'sale_locality' => 'Fedd',
            'sale_town' => 'Preston',
            'sale_postcode' => 'LO3 5JJ',

            'purchase_street_address' => '41 DsSd St.',
            'purchase_locality' => 'Ssdfsf',
            'purchase_town' => 'Preston',
            'purchase_postcode' => 'LO3 5JJ',
        ];

    $post['discount_code'] = 'CSUK25';


    $config = new CQN_Calculator_Config;
    $sub = new CQN_Calculator_Submission( $config );

    $sub->loadFromPost( $post );

    $sub->calculate();

    $html = '<style type="text/css">' . file_get_contents( CQN_PLUGIN_PATH . '/includes/css/cqn_emails.css' ) . '</style>';
    $template = $CQN_twig->loadTemplate('calc-email-instruct-admin.twig');
    //$template = $CQN_twig->loadTemplate('calc-email-instruct-client.twig');
//    $template = $CQN_twig->loadTemplate('calc-email-client.twig');
    $html .= $template->render( [ 'sub' => $sub ] );

    return $html;
}

function cqn_add_test_shortcodes(){
    /*
     * adds shortcodes to display forms for easier tesing of the quote template / styles
     *
     * <ul>
            <li><a href="/calctest-s"  title="Calctest Sale">Calctest Sale</a></li>
            <li><a href="/calctest-p"  title="Calctest Sale">Calctest Purchase</a></li>
            <li><a href="/calctest-sp" title="Calctest Sale">Calctest Sale Purchase</a></li>
            <li><a href="/calctest-r"  title="Calctest Sale">Calctest Remortgage</a></li>
            <li><a href="/calctest-t"  title="Calctest Sale">Calctest Transfer</a></li>
            <li><a href="/calctest-rt" title="Calctest Sale">Calctest Remortgage & Transfer</a></li>
        </ul>
     *
     *  */

    add_shortcode('cqn_test_sale',                'cqn_test_sale');
    add_shortcode('cqn_test_purchase',            'cqn_test_purchase');
    add_shortcode('cqn_test_sale_purchase',       'cqn_test_sale_purchase');
    add_shortcode('cqn_test_transfer',            'cqn_test_transfer');
    add_shortcode('cqn_test_remortgage',          'cqn_test_remortgage');
    add_shortcode('cqn_test_remortgage_transfer', 'cqn_test_remortgage_transfer');
    add_shortcode('cqn_test_instruct_email',      'cqn_test_instruct_email');
}

add_action( 'init', 'cqn_init' );

/* session initialising and killing actions */
add_action( 'init',      'cqn_startSession', 1 );
add_action( 'wp_logout', 'cqn_destroySession' );
add_action( 'wp_login',  'cqn_destroySession' );

/* shortcode to display the test quotes */
add_action( 'init', 'cqn_add_test_shortcodes' );

//add_filter( 'wp_mail_content_type', 'cqn_set_html_content_type' );

function cqn_set_html_content_type() {

    return 'text/html';
}


