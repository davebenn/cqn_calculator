<?php
function calcThanks( $posted  ){


    $sub = $_SESSION['CQN_calculator_submission'];
    $calcRef = $sub->getCalculatorRef();
    $html = '';

//    $html .=  print_r( $posted, true );

//    $calcRef = $_SESSION['cqn_calculator_ref'];
    $html .= '<p>';
    $html .= '<p>Thankyou for instructing we will be in touch shortly</p>';
    $html .= '</p>';
    $html .= '<p>'. $calcRef . '</p>';

$html .= print_r( $sub );

    return $html;
}
?>
