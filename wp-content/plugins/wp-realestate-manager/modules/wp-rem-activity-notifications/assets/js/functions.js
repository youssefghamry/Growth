jQuery(document).ready(function ($) {
    /*
     * Hide Notification from Member Dashboard
     */
    jQuery(document).on("click", ".hide_notification", function () {
        thisObj = jQuery(this);
        var id = thisObj.parent('li').data('id');
        wp_rem_show_loader('.loader-holder');
        jQuery.ajax({
            type: 'POST',
            dataType: 'json',
            url: wp_rem_globals.ajax_url,
            data: 'id=' + id + '&action=wp_rem_hide_notification',
            success: function (response) {
                wp_rem_show_response(response);
                thisObj.parent('li').remove();
            }
        });
    });

    /*
     * Clearing All Notifications from member dashboard
     */
    jQuery(document).on("click", ".wp-rem-clear-notifications a", function () {
        thisObj = jQuery(this);
        wp_rem_show_loader('.loader-holder');
        jQuery.ajax({
            type: 'POST',
            dataType: 'json',
            url: wp_rem_globals.ajax_url,
            data: 'action=wp_rem_clear_all_notification',
            success: function (response) {
                wp_rem_show_response(response);
                thisObj.closest('.user-notification').remove();
            }
        });
    });
    
    
    
    /*
     * Load More for notifications on dashboard
     */
    jQuery(document).on("click", ".load-more-notifications", function () {
       var thisObj = jQuery(this);
       wp_rem_show_loader(".load-more-notifications", "", "button_loader", thisObj);
       var current_page    = jQuery("#current_page").val();
       var max_num_pages   = jQuery("#max_num_pages").val();
       current_page    = parseInt(current_page) + 1;
       jQuery.ajax({
            type: 'POST',
            url: wp_rem_globals.ajax_url,
            data: 'current_page='+current_page+'&action=wp_rem_notification_loadmore',
            success: function (response) {
                jQuery(".user-notification ul").append(response);
                jQuery("#current_page").val(current_page);
                if( max_num_pages == current_page ){
                    jQuery(".load-more-notifications").hide();
                }
                wp_rem_hide_button_loader(thisObj);
            }
        });
    });
    
});