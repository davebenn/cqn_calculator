<?php
    function calcForm( $posted  ){

        $html = '';
//        $html .=  print_r( $posted, true );

        $sub = $_SESSION['CQN_calculator_submission'];
        $calcRef = $sub->getCalculatorRef();

        $html .= '<p>Please fill in our little form</p>

        <form action="" method="post">
            <input type="hidden" value="yes" name="cqn_calc_form"/>
            <input type="hidden" name="CQN_calculator_ref" value="' . $calcRef . '">

            <p style="padding-left:12px;padding-top:10px;">Conveyancing type:</p>

            <div class="radio_buttons">
                <input checked="checked" value="sale" id="option_sale" name="quote_type" autocomplete="off"
                       type="radio"><label for="option_sale">Sale</label>
                <input value="purchase" id="option_purchase" name="quote_type" autocomplete="off" type="radio"><label
                    for="option_purchase">Purchase</label>
                <input value="sale_purchase" id="option_sale_and_purchase" name="quote_type" autocomplete="off"
                       type="radio"><label for="option_sale_and_purchase">Sale &amp; Purchase</label>
                <input value="remortgage" id="option_remortgage" name="quote_type" autocomplete="off"
                       type="radio"><label for="option_remortgage">Remortgage</label>
                <input value="transfer" id="option_transfer" name="quote_type" autocomplete="off" type="radio"><label
                    for="option_transfer">Transfer of Equity</label>
            </div>
		  <span class="section_holder" id="sale_details">
		      <h2 class="form_title">Sale Details</h2>
			  <label for="sale_price">Property price</label>
			  <input name="sale_price" size="40" type="number" class="required number" value=""><br>
			  <label for="sale_leasehold">Is the property Leasehold?</label>
			  <input name="sale_leasehold" id="sale_leasehold" value="sale_leasehold" type="checkbox"><br>
			  <label for="sale_mortgage">Is there a mortgage?</label>
			  <input name="sale_mortgage" id="sale_mortgage" value="sale_mortgage" type="checkbox"><br>
		  </span>
		  <span class="section_holder" id="purchase_details" style="display:none;">
		      <h2 class="form_title">Purchase Details</h2>
			  <label for="purchase_price">Property price</label>
			  <input value="" name="purchase_price" size="40" type="number" class="required number ignore"><br>
			  <label for="purchase_leasehold">Is the property Leasehold?</label>
			  <input name="purchase_leasehold" id="purchase_leasehold" value="purchase_leasehold" type="checkbox"><br>
			  <label for="purchase_mortgage">Is there a mortgage?</label>
			  <input name="purchase_mortgage" id="purchase_mortgage" value="purchase_mortgage" type="checkbox"><br>
			  <label for="purchase_1st_time_buyer">1st Time Buyer?</label>
			  <input name="purchase_1st_time_buyer" id="purchase_1st_time_buyer" value="purchase_1st_time_buyer"
                     type="checkbox"><br>
			  <label for="purchase_no_of_buyers">No. Of Buyers?</label>
			  <input name="purchase_no_of_buyers" id="purchase_no_of_buyers" value="1" type="number" size="2" step="1"
                     max="10" min="1" class="required number ignore"><br>
		  </span>
		  <span class="section_holder" id="remortgage_details" style="display:none;">
		      <h2 class="form_title">Remortgage Details</h2>
			  <label for="remortgage_price">Property Remortgage Value</label>
			  <input value="" name="remortgage_price" size="40" type="number" class="required number ignore"><br>
			  <label for="remortgage_leasehold">Is the property Leasehold?</label>
			  <input name="remortgage_leasehold" id="remortgage_leasehold" value="remortgage_leasehold" type="checkbox"><br>

			  <label for="remortgage_no_of_people">No. Of People?</label>
			  <input name="remortgage_no_of_people" id="remortgage_no_of_people" value="1" type="text" size="2"
                     class="required digits ignore"><br>

			  <label for="remortgage_involves_transfer">Involves equity transfer?</label>
			  <input name="remortgage_involves_transfer" id="remortgage_involves_transfer"
                     value="remortgage_involves_transfer" type="checkbox"><br>
		  </span>

		  <span class="section_holder" id="transfer_details" style="display:none;">
		      <h2 class="form_title">Transfer Of Equity Details</h2>
			  <label for="transfer_price">Equity Value</label>
			  <input value="" name="transfer_price" size="40" type="number" class="required number ignore"><br>
			  <label for="transfer_leasehold">Is the property Leasehold?</label>
			  <input name="transfer_leasehold" id="transfer_leasehold" value="transfer_leasehold" type="checkbox"><br>
			  <label for="transfer_no_of_people">No. Of People?</label>
			  <input name="transfer_no_of_people" id="transfer_no_of_people" value="1" type="number" step="1" max="10"
                     min="1" class="required digits ignore"><br>
		  </span>


			<span class="section_holder" id="save_quote_holder">

					<label for="discount_code">Discount Code</label>
					<input value="" name="discount_code" id="discount_code" size="40" type="text" class=""><br>
					<div class="spacer">&nbsp;</div>

			</span>

			<span class="section_holder" id="save_quote_holder">
					<label for="contact_email">Email address*</label>
					<input name="contact_email" id="contact_email" size="40" type="email" class="required email"
                           value=""><br>
					<label for="contact_telephone">Telephone Number*</label>
					<input value="" name="contact_telephone" id="contact_telephone" size="40" type="tel"><br>
					<label for="contact_name">Full Name</label>
					<input value="" name="contact_name" id="contact_name" size="40" type="text"><br>
					<div class="privacy"><span>*</span>We fully respect your privacy. We will not share your email or
                        other information with any third party.
                    </div>
					<input type="submit" id="form-submit" value="Calculate">

			</span>

        </form>';

        return $html;
    }
?>
