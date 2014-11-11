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
                `created_at` TIMESTAMP,
                PRIMARY KEY (`id`)
            )';

    //error_log( "\n--------------" );
    //error_log($sql);
    //error_log( "\n--------------" );


//    $sql = '  CREATE TABLE `' . $tableName . '` (
//              `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
//              `email` varchar(255) NOT NULL,
//              `full_name` varchar(255) NOT NULL,
//              PRIMARY KEY (`id`)
//            )';

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

        if( $_POST['cqn_calc_form'] ){



            if( $_POST[ 'cqn_quote_viewed' ] ) {


                add_shortcode('cqn_calculator', 'cqn_show_thanks');
            }else{

                $errors = validateInput();

                if (!$errors) {

                    /*
                     calculate fees
                     calculate disbursements

                    */
                    require  CQN_PLUGIN_PATH . '/includes/classes/CalculatorConfig.php' ;

                    $calcConfig = new CalculatorConfig;
                    $calcConfig->initDefaultVars();

                    error_log( print_r( $fees , true ) );

                    $submission = array(
                                            'calculator_ref'               => mysql_real_escape_string($_POST['calculator_ref']),
                                            'sale_price'                   => mysql_real_escape_string($_POST['sale_price']),
                                            'sale_leasehold'               => mysql_real_escape_string($_POST['sale_leasehold']),
                                            'sale_mortgage'                => mysql_real_escape_string($_POST['sale_mortgage']),
                                            'purchase_price'               => mysql_real_escape_string($_POST['purchase_price']),
                                            'purchase_leasehold'           => mysql_real_escape_string($_POST['purchase_leasehold']),
                                            'purchase_mortgage'            => mysql_real_escape_string($_POST['purchase_mortgage']),
                                            'purchase_1st_time_buyer'      => mysql_real_escape_string($_POST['purchase_1st_time_buyer']),
                                            'purchase_no_of_buyers'        => mysql_real_escape_string($_POST['purchase_no_of_buyers']),
                                            'remortgage_price'             => mysql_real_escape_string($_POST['remortgage_price']),
                                            'remortgage_leasehold'         => mysql_real_escape_string($_POST['remortgage_leasehold']),
                                            'remortgage_no_of_people'      => mysql_real_escape_string($_POST['remortgage_no_of_people']),
                                            'remortgage_involves_transfer' => mysql_real_escape_string($_POST['remortgage_involves_transfer']),
                                            'transfer_price'               => mysql_real_escape_string($_POST['transfer_price']),
                                            'transfer_leasehold'           => mysql_real_escape_string($_POST['transfer_leasehold']),
                                            'transfer_no_of_people'        => mysql_real_escape_string($_POST['transfer_no_of_people']),
                                            'discount_code'                => mysql_real_escape_string($_POST['discount_code']),
                                            'contact_email'                => mysql_real_escape_string($_POST['contact_email']),
                                            'contact_telephone'            => mysql_real_escape_string($_POST['contact_telephone']),
                                            'contact_name'                 => mysql_real_escape_string($_POST['contact_name']),
                                    );


                    global $wpdb;
                    $wpdb->insert(CQN_TABLE_NAME, $submission);




                    add_shortcode('cqn_calculator', 'cqn_show_quote');
                } else {
                    $_POST = array_merge($_POST, $errors);
                    add_shortcode('cqn_calculator', 'cqn_show_calculator');
                }
            }
        }else{

            if( !isset ( $_SESSION[ 'cqn_calculator_ref' ] ) ){
                $_SESSION['cqn_calculator_ref'] = cqn_generate_unique_id();
            }

            wp_enqueue_script( 'cqn_calculator_script', CQN_PLUGIN_URL . '/includes/js/cqn_calc.js', [ 'jquery' ] );
            wp_enqueue_style(  'cqn_calculator_styles', CQN_PLUGIN_URL . '/includes/css/cqn_styles.css');
            add_shortcode(     'cqn_calculator', 'cqn_show_calculator' );
        }
    }
}

function cqn_generate_unique_id( ){

    $calcRef = str_replace( [ ' ', '.' ], [ '', ''] , microtime( false ) );
    return $calcRef;

}

function cqn_show_thanks(   ){
    /*
        show form
        or show the results +  email me and instruct links
    */
    //$html = file_get_contents( CQN_PLUGIN_PATH . '/includes/calc-form.html'  );
    require( CQN_PLUGIN_PATH . '/includes/calc-thanks.php'  );
    $html = calcThanks( $_POST );
    return $html;
}
function cqn_show_quote(   ){
    /*
        show form
        or show the results +  email me and instruct links
    */
    //$html = file_get_contents( CQN_PLUGIN_PATH . '/includes/calc-form.html'  );
    require( CQN_PLUGIN_PATH . '/includes/calc-quote.php'  );
    $html = calcQuote( $_POST );
    return $html;
}
function cqn_show_calculator(  $errors ){
    /*
        show form
        or show the results +  email me and instruct links
    */
    //$html = file_get_contents( CQN_PLUGIN_PATH . '/includes/calc-form.html'  );
    require( CQN_PLUGIN_PATH . '/includes/calc-form.php'  );
    $html = calcForm( $_POST );
    return $html;
}

function cqn_show_thankyou(){

    $html = file_get_contents( CQN_PLUGIN_PATH . '/includes/thankyou.html'  );
    return $html;
}

add_action(     'init', 'cqn_init' );

add_action( 'init',      'cqn_startSession', 1 );
add_action( 'wp_logout', 'cqn_destroySession' );
add_action( 'wp_login',  'cqn_destroySession' );

function cqn_startSession() {
    //session_start();
    //session_destroy ();

    if(!session_id()) {
        error_log( 'starting session');
        session_start();
    }
    //    error_log( print_r( $_SESSION, true ) );
}

function cqn_destroySession() {
    session_destroy ();
}


