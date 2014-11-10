
jQuery(document).ready(function( $ ) {

    var sale_section = $('#sale_details');
    var purchase_section = $('#purchase_details');
    var remortgage_section = $('#remortgage_details');
    var transfer_section = $('#transfer_details');
    var no_move = $('#no_move');

    $('input[name=remortgage_involves_transfer]:checkbox').change(function () {
        if($(this).is(':checked')){
            transfer_section.show();
        }else{
            transfer_section.hide();
        }
    });
    $('input[name=quote_type]:radio').change(function () {

        section = $(this).val();
        sale_section.hide();
        purchase_section.hide();
        remortgage_section.hide();
        transfer_section.hide();
        no_move.hide();

        sale_section.find(':input').addClass('ignore');
        purchase_section.find(':input').addClass('ignore');
        remortgage_section.find(':input').addClass('ignore');
        transfer_section.find(':input').addClass('ignore');

        switch(section)
        {
            case 'sale':
                sale_section.show();
                sale_section.find(':input').removeClass('ignore');
                no_move.show();
                break;
            case 'purchase':
                purchase_section.show();
                purchase_section.find(':input').removeClass('ignore');
                no_move.show();
                break;
            case 'sale_purchase':
                sale_section.show();
                purchase_section.show();
                sale_section.find(':input').removeClass('ignore');
                purchase_section.find(':input').removeClass('ignore');
                no_move.show();
                break;
            case 'remortgage':
                remortgage_section.show();
                remortgage_section.find(':input').removeClass('ignore');
                if($('input[name=remortgage_involves_transfer]:checkbox').is(':checked')){
                    transfer_section.show();
                }
                break;
            case 'transfer':
                transfer_section.show();
                transfer_section.find(':input').removeClass('ignore');
                break;
        }
    });

    $( '#instruct-form' ).hide();

    $( '#show-instruct' ).click(  function(){
        $( '#instructButtons' ).hide();

        $('#quote_result tr:not(:last-child)').css('display', 'none');




        $( '#instruct-form' ).show();
    });
});