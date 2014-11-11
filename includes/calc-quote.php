<?php
function calcQuote( $posted  ){


    $calcRef = $_SESSION['cqn_calculator_ref'];

    $html = '';
//    $html .=  print_r( $posted, true );

//    $calcRef = $_SESSION['cqn_calculator_ref'];
    $html .= '<p>';
    $html .= '<p>Please find your quote below, click the buttons at the bottom to proceed</p>';
    $html .= '</p>';


    $html = '';
    $html .= '
                <table>
                    <thead>
                        <tr>
                            <th>Thing</th>
                            <th>Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr><td>  12333  </td><td>   abcder </td></tr>
                        <tr><td>  12333  </td><td>   abcder </td></tr>
                        <tr><td>  12333  </td><td>   abcder </td></tr>
                        <tr><td>  12333  </td><td>   abcder </td></tr>
                    </tbody>
                </table>';

    $html .= '
        <form action="" method="post">
            <input type="hidden" value="1" name="cqn_calc_form"/>
            <input type="hidden" value="1" name="cqn_quote_viewed"/>
            <input type="text" name="calculator_ref" value="' . $calcRef . '">


            <input type="submit" id="form-submit" name="cqn_instruct_now" value="Instruct">
            <input type="submit" id="form-submit" name="cqn_email_me_quote" value="Email me quote">

        </form>';


    return $html;
}
?>
