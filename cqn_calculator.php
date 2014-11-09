<?php

/*
*   Plugin Name: CQN Calculator
*   Description: Calculator For CQN
*   Version: 0.001
 *
*/



function cqn_init(){


    if( !is_admin() ){
        if(a){



        }
    }
}

function cqn_show_calculator(){
/*
 show form
or show the results +  email me and instruct links
or thank you message [if not redirected]

*/

    $pluginPath = plugin_dir_path( __FILE__ );
    $html = file_get_contents( $pluginPath . '/includes/calc-form.html'  );

    return $html;

}

add_shortcode(  'cqn_calculator', 'cqn_show_calculator' );
add_action(     'init', 'cqn_init' );
//add_action( 'wp', 'cqn_init' );


