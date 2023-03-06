var propertyFilterAjax;
function wp_rem_property_hide(thisObj, property_id, member_id, property_short_counter) {

    "use strict";
    var hide_icon_class = jQuery(thisObj).find("i").attr('class');
    var loader_class = 'icon-spinner8 icon-spin';
    jQuery(thisObj).find("i").removeClass(hide_icon_class).addClass(loader_class);
    var dataString = 'property_id=' + property_id + '&member_id=' + member_id + '&action=wp_rem_property_hide_submit&property_short_counter=' + property_short_counter;
    jQuery.ajax({
        type: "POST",
        url: wp_rem_globals.ajax_url,
        data: dataString,
        dataType: "json",
        success: function (response) {
            if (response.status == true) {
                jQuery(thisObj).closest('.property-row').slideUp(700);
                if (jQuery('#hidden-property-' + property_short_counter).length) {        // use this if you are using id to check
                    jQuery('#hidden-property-' + property_short_counter).append(response.new_element);
                } else {
                    var hidden_string = '<div class="real-estate-hidden-property"><div class="row"><div id="hidden-property-' + property_short_counter + '" class="col-lg-12 col-md-12 col-sm-12 col-xs-12"></div></div></div>';
                    jQuery('#real-estate-property-' + property_short_counter).append(hidden_string);
                    jQuery('#hidden-property-' + property_short_counter).append(response.new_element);
                }

            }
        }
    });
}
function wp_rem_property_only_hide(thisObj, property_id, member_id) {

    "use strict";
    var hide_icon_class = jQuery(thisObj).find("i").attr('class');
    var loader_class = 'icon-spinner8 icon-spin';
    jQuery(thisObj).find("i").removeClass(hide_icon_class).addClass(loader_class);
    var dataString = 'property_id=' + property_id + '&member_id=' + member_id + '&action=wp_rem_property_hide_submit&response_type=short';
    jQuery.ajax({
        type: "POST",
        url: wp_rem_globals.ajax_url,
        data: dataString,
        dataType: "json",
        success: function (response) {
            if (response.status == true) {
                jQuery(thisObj).html();
                jQuery(thisObj).replaceWith(response.new_element);

            }
        }
    });
}