<?php
function calcQuote( $posted  ){

    $sub = $_SESSION['CQN_calculator_submission'];
    $calcRef = $sub->getCalculatorRef();
    $html = '';
    $html .= '<pre>';


    $html .= $sub->getTextQuote();

    $html .= '</pre>';

    $additionalFields = ['contact_street_address',
        'contact_locality',
        'contact_town',
        'contact_postcode',
        'additional_1_title',
        'additional_1_forename',
        'additional_1_surname',
        'additional_2_title',
        'additional_2_forename',
        'additional_2_surname',
        'sale_street_address',
        'sale_locality',
        'sale_town',
        'sale_postcode',
        'purchase_street_address',
        'purchase_locality',
        'purchase_town',
        'purchase_postcode',
        'remortgage_street_address',
        'remortgage_locality',
        'remortgage_town',
        'remortgage_postcode',
        'transfer_street_address',
        'transfer_locality',
        'transfer_town',
        'transfer_postcode'];

    $additionalHTML = '';
    foreach( $additionalFields as $field ){

        $additionalHTML .= '<div class="form-group"><label for="' . $field . '">' . $field . '</label><input type="text" name="' . $field . '" id=="' . $field . '" /></div>';
    }



    $html .= '<form action="" method="post">
                    <input type="hidden" name="cqn_instructType" value="emailQuote">
                    <input type="hidden" name="cqn_calc_form" value="1">

						<input type="hidden" name="cqn_quote_viewed" value="1">
						<input type="hidden" name="CQN_calculator_ref" value="' .$calcRef .  '">
						<input class="instruct-input" type="submit" value="Email Me Details">
				</form>
				<input name="instruct_now" class="show-instruct instruct-input" id="show-instruct" type="submit" value="Instruct Solicitor Now">';

    $html .= '<div class="instruct-form" id="instruct-form" style="display: none;">';

    $html .= '<form action="" method="post">

                    <input type="hidden" name="cqn_instructType" value="instructNow">
                    <input type="hidden" name="cqn_calc_form" value="1">
                    <input type="hidden" name="CQN_calculator_ref" value="' .$calcRef .  '">

                    <input name="instruct_now" class="instruct instruct-input" type="submit" value="Finish">
                        '.$additionalHTML.'
                    <input name="instruct_now" class="instruct instruct-input" type="submit" value="Finish">
                </form>';

    $html .= '</div>';

    return $html;
}
?>
