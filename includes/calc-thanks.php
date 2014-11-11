<?php
function calcThanks( $posted  ){


    $calcRef = $_SESSION['cqn_calculator_ref'];

    $html = '';
//    $html .=  print_r( $posted, true );

//    $calcRef = $_SESSION['cqn_calculator_ref'];
    $html .= '<p>';
    $html .= '<p>Thankyou for instructing we will be in touch shortly</p>';
    $html .= '</p>';
    $html .= '<p>'. $calcRef . '</p>';



    return $html;
}
?>
