
jQuery(document).on("click", "#wp_rem_claim_property_submit", function () {
    "use strict";
    var returnType = wp_rem_validation_process(jQuery("#wp_rem_claim_property"));
    if (returnType == false) {
        return false;
    }
    var thisObj = jQuery(this);
    var this_loader_Obj = jQuery(".claim-request-holder");
    wp_rem_show_loader(".claim-request-holder", "", "button_loader", this_loader_Obj);

    thisObj.prop('disabled', true);
    var serilaized_data = jQuery('#wp_rem_claim_property').serialize();
    var dataString = serilaized_data + '&action=claim_property_from_save';
    var ajax_url = jQuery('#wp_rem_claim_ajax_url').val();

    jQuery.ajax({
        type: "POST",
        url: ajax_url,
        data: dataString,
        dataType: 'json',
        success: function (response) {
            thisObj.prop('disabled', false);
            wp_rem_show_response(response, "", this_loader_Obj);
            if (response.type == 'success') {
                if (jQuery('#wp_rem_claim_user_login').val() != '1') {
                    jQuery('#wp_rem_claim_property_user_name').val('');
                    jQuery('#wp_rem_claim_property_user_email').val('');
                }
                jQuery('#wp_rem_claim_property_reason').val('');
                jQuery('.claim_term_policy input[name=term_policy]').attr('checked', false);
            }
        }
    });
    return false;
});

jQuery(document).on("click", "#wp_rem_flag_property_submit", function () {
    "use strict";
    var returnType = wp_rem_validation_process(jQuery("#wp_rem_flag_property"));
    if (returnType == false) {
        return false;
    }
    var thisObj = jQuery(this);
    var this_loader_Obj = jQuery(".flag-request-holder");
    wp_rem_show_loader(".flag-request-holder", "", "button_loader", this_loader_Obj);

    thisObj.prop('disabled', true);
    var serilaized_data = jQuery('#wp_rem_flag_property').serialize();
    var dataString = serilaized_data + '&action=flag_property_from_save';
    var ajax_url = jQuery('#wp_rem_flag_ajax_url').val();

    jQuery.ajax({
        type: "POST",
        url: ajax_url,
        data: dataString,
        dataType: 'json',
        success: function (response) {
            thisObj.prop('disabled', false);
            wp_rem_show_response(response, "", this_loader_Obj);
            if (response.type == 'success') {
                if (jQuery('#wp_rem_flag_user_login').val() != '1') {
                    jQuery('#wp_rem_flag_property_user_name').val('');
                    jQuery('#wp_rem_flag_property_user_email').val('');
                }
                jQuery('#wp_rem_flag_property_reason').val('');
                jQuery('.flag_term_policy input[name=term_policy]').attr('checked', false);
            }
        }
    });
    return false;
});