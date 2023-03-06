var propertyFilterAjax;
function wp_rem_property_map_setter(counter, properties) {
    "use strict";
    var name = "wp_rem_property_map_" + counter;
    var func = new Function(
            "return " + name + "(" + properties + ");"
            )();
}

function top_map_change_cords(counter) {
    "use strict";
    var top_map = jQuery('.wp-rem-ontop-gmap');
    if (top_map.length !== 0) {
        var ajax_url = wp_rem_globals.ajax_url;
        var data_vals = 'ajax_filter=true&map=top_map&action=wp_rem_top_map_search&' + jQuery(jQuery("#frm_property_arg" + counter)[0].elements).not(":input[name='alerts-email'], :input[name='alert-frequency']").serialize();
        if (jQuery('form[name="wp-rem-top-map-form"]').length > 0) {
            data_vals += "&" + jQuery('form[name="wp-rem-top-map-form"]').serialize() + '&atts=' + jQuery('#atts').html();
        }
        data_vals = stripUrlParams(data_vals);
        var loading_top_map = $.ajax({
            url: ajax_url,
            method: "POST",
            data: data_vals,
            dataType: "json"
        }).done(function (response) {
            if (typeof response.html !== 'undefined') {
                jQuery('.top-map-action-scr').html(response.html);
            }
        }).fail(function () {
        });
    }
}

jQuery(document).on('change', '.dev-property-list-enquiry-check', function () {
    var _this = $(this);
    var _this_id = _this.attr('data-id');
    var pop_buton = $('#prop-enquiry-pop-list-box');
    var _appending_inp = $('#prop-enquiry-list-all');
    if (_this.is(":checked")) {
        if (_appending_inp.val() == '') {
            _appending_inp.val(_this_id);
        } else {
            var new_val = _appending_inp.val() + ',' + _this_id;
            _appending_inp.val(new_val);
        }
    } else {
        if (_appending_inp.val() != '') {
            var strVal = _appending_inp.val();
            var dataArray = strVal.split(",");
            var valIndex = dataArray.indexOf(_this_id);
            if (valIndex > -1) {
                dataArray.splice(valIndex, 1);
                strVal = dataArray.join(',');
            }
            _appending_inp.val(strVal);
        }
    }
    if (_appending_inp.val() != '') {
        pop_buton.show();
    } else {
        pop_buton.hide();
    }
    pop_buton.find('#wp_rem_property_id').val(_appending_inp.val());
});

jQuery(document).on('click', '.dev-prop-notes-login', function () {
    $('#sign-in').modal('show');
    $('#sign-in').find('div[id^="user-login-tab-"]').addClass('active in');
    $('#sign-in').find('div[id^="user-register-"]').removeClass('active in');
});

jQuery(document).on('click', '.submit-prop-notes', function () {

    var returnType = wp_rem_validation_process(jQuery(".prop-not"));
    if (returnType == false) {
        return false;
    }

    var ajax_url = wp_rem_globals.ajax_url;
    var that = $(this);
    var prop_id = $(this).attr('data-id');
    var prop_rand_id = $(this).attr('data-rand');
    
    var prop_notes = $('#prop-notes-text-' + prop_rand_id).val();

    var prop_note_btn = $('#property-note-' + prop_rand_id);

    if (prop_notes != '') {
        that.prop('disabled', true);
        var thisObj = $('#prop-notes-model-popup').find(".submit-prop-notes-btn");
        wp_rem_show_loader("#prop-notes-model-popup .submit-prop-notes-btn", "", "button_loader", thisObj);
        var data_vals = 'adding_notes=true&prop_id=' + prop_id + '&prop_notes=' + prop_notes + '&action=wp_rem_adding_property_notes';
        $.ajax({
            url: ajax_url,
            method: "POST",
            data: data_vals,
            dataType: "json"
        }).done(function (response) {
            wp_rem_show_response(response, '', thisObj);
            that.prop('disabled', false);
            if (response.type == 'success') {
                $('#prop-notes-model-popup').find('textarea').val('');
                $('#prop-notes-model-popup').modal('toggle');
                prop_note_btn.removeAttr('data-toggle');
                prop_note_btn.removeAttr('data-target');
                prop_note_btn.removeAttr('href');
                //var new_html = prop_note_btn.attr('data-afterlabel') + ' ' + '<i class="' + prop_note_btn.attr('data-aftericon') + '"></i>';
                var new_html = '<i class="' + prop_note_btn.attr('data-aftericon') + '"></i>'+'<div class="option-content"><span>'+prop_note_btn.attr('data-afterlabel')+'</span></div>';
                prop_note_btn.html(new_html);
            }
        }).fail(function () {
            that.prop('disabled', false);
        });
    } else {
        var thisObj = $('#prop-notes-model-popup').find(".submit-prop-notes-btn");
        wp_rem_show_loader("#prop-notes-model-popup .submit-prop-notes-btn", "", "button_loader", thisObj);
        var msg_obj = {msg: wp_rem_globals.some_txt_error, type: 'error'};
        wp_rem_show_response(msg_obj, '', thisObj);

    }
});

jQuery(document).on('click', '.property-notes', function () {
    var this_id = $(this).attr('data-id');
    var this_href = $(this).attr('data-href');
    var this_title = $(this).attr('data-title');
    var rand_id = $(this).attr('data-rand');
    
    var popup_box = $('#prop-notes-model-popup');

    popup_box.find('.modal-header a.property-title-notes').html(this_title);
    popup_box.find('.modal-header a.property-title-notes').attr('href', this_href);
    popup_box.find('.modal-body .property-notes-error').attr('id', 'property-notes-error-' + this_id);
    popup_box.find('.modal-body textarea').attr('id', 'prop-notes-text-' + rand_id);
    popup_box.find('.modal-body input.submit-prop-notes').attr('data-id', this_id);
    popup_box.find('.modal-body input.submit-prop-notes').attr('data-rand', rand_id);
    
});

jQuery(document).ready(function () {
    jQuery(function () {
        var $checkboxes = jQuery("input[type=checkbox]");
        $checkboxes.on('change', function () {
            var ids = $checkboxes.filter(':checked').map(function () {
                return this.id;
            }).get().join(',');
            jQuery('#hidden_input').val(ids);
        });
    });
});

function wp_rem_property_content(counter, view_type, animate_to) {
    //"use strict";

    counter = counter || '';
    animate_to = animate_to || '';
    var view_type = view_type || '';
    // move to top when search filter apply

    if (animate_to != 'false') {
        jQuery('html, body').animate({
            scrollTop: jQuery("#wp-rem-property-content-" + counter).offset().top - 120
        }, 700);
    }
    var property_arg = jQuery("#property_arg" + counter).html();
    var this_frm = jQuery("#frm_property_arg" + counter);


    var split_map = jQuery(".wp-rem-split-map-wrap").size();
    if (split_map > 0) {
        view_type = 'split_map';
    }

    var ads_list_count = jQuery("#ads_list_count_" + counter).val();
    var ads_list_flag = jQuery("#ads_list_flag_" + counter).val();
    var list_flag_count = jQuery("#ads_list_flag_count_" + counter).val();

    if (jQuery("#frm_property_arg" + counter).length > 0) {
        var data_vals = jQuery(jQuery("#frm_property_arg" + counter)[0].elements).not(":input[name='alerts-email'], :input[name='alert-frequency']").serialize();
        if (jQuery('form[name="wp-rem-top-map-form"]').length > 0) {
            data_vals += "&" + jQuery('form[name="wp-rem-top-map-form"]').serialize();
        }
        data_vals = data_vals.replace(/[^&]+=\.?(?:&|$)/g, ''); // remove extra and empty variables
        data_vals = data_vals.replace('undefined', ''); // remove extra and empty variables
        data_vals = data_vals + '&ajax_filter=true';
        data_vals = stripUrlParams(data_vals);
        
        if (!jQuery('body').hasClass('wp-rem-changing-view')) {
            // top map
            top_map_change_cords(counter);
        }

        jQuery('#Property-content-' + counter + ' .property').addClass('slide-loader');
        jQuery('#wp-rem-data-property-content-' + counter + ' .slide-loader-holder').addClass('slide-loader');
        //if (typeof (propertyFilterAjax) != 'undefined') {
        //    propertyFilterAjax.abort();
        //}
        var propertyFilterAjax = jQuery.ajax({
            type: 'POST',
            dataType: 'HTML',
            url: wp_rem_globals.ajax_url,
            data: data_vals + '&action=wp_rem_properties_content&view_type=' + view_type + '&property_arg=' + property_arg,
            success: function (response) {
                jQuery('body').removeClass('wp-rem-changing-view');
                jQuery('#Property-content-' + counter).html(response);
                // Replace double & from string.
                data_vals = data_vals.replace("&&", "&");
                var current_url = location.protocol + "//" + location.host + location.pathname + "?" + data_vals; //window.location.href;
                current_url = current_url.replace('&=undefined', ''); // remove extra and empty variables
                window.history.pushState(null, null, decodeURIComponent(current_url));
                jQuery(".chosen-select").chosen();
                jQuery('#wp-rem-data-property-content-' + counter + ' .slide-loader-holder').removeClass('slide-loader');
                wp_rem_hide_loader();
                // add class when image loaded
                jQuery(".property-medium .img-holder img, .property-grid .img-holder img").one("load", function () {
                    jQuery(this).parents(".img-holder").addClass("image-loaded");
                }).each(function () {
                    if (this.complete)
                        jQuery(this).load();
                });
                if (jQuery(".property-medium.modern").length > 0) {
                    var imageUrlFind = jQuery(".property-medium.modern .img-holder").css("background-image").match(/url\(["']?([^()]*)["']?\)/).pop();
                    if (imageUrlFind) {
                        jQuery(".property-medium.modern .img-holder").addClass("image-loaded");
                    }
                }
                // add class when image loaded
            }
        });
    }
}

function wp_rem_property_content_without_filters(counter, page_var, page_num, ajax_filter, view_type) {
    "use strict";
    counter = counter || '';
    var property_arg = jQuery("#property_arg" + counter).html();
    var data_vals = page_var + '=' + page_num;
    jQuery('#wp-rem-data-property-content-' + counter + ' .slide-loader-holder').addClass('slide-loader');
    if (typeof (propertyFilterAjax) != 'undefined') {
        propertyFilterAjax.abort();
    }
    propertyFilterAjax = jQuery.ajax({
        type: 'POST',
        dataType: 'HTML',
        url: wp_rem_globals.ajax_url,
        data: data_vals + '&action=wp_rem_properties_content&view_type=' + view_type + '&property_arg=' + property_arg,
        success: function (response) {
            jQuery('#Property-content-' + counter).html(response);
            // Replace double & from string.
            data_vals = data_vals.replace("&&", "&");
            var current_url = location.protocol + "//" + location.host + location.pathname + "?" + data_vals; //window.location.href;
            window.history.pushState(null, null, decodeURIComponent(current_url));
            jQuery(".chosen-select").chosen();
            jQuery('#wp-rem-data-property-content-' + counter + ' .slide-loader-holder').removeClass('slide-loader');
            wp_rem_hide_loader();
            // add class when image loaded
            jQuery(".property-medium .img-holder img, .property-grid .img-holder img").one("load", function () {
                jQuery(this).parents(".img-holder").addClass("image-loaded");
            }).each(function () {
                if (this.complete)
                    $(this).load();
            });
            if (jQuery(".property-medium.modern").length > 0) {
                var imageUrlFind = jQuery(".property-medium.modern .img-holder").css("background-image").match(/url\(["']?([^()]*)["']?\)/).pop();
                if (imageUrlFind) {
                    jQuery(".property-medium.modern .img-holder").addClass("image-loaded");
                }
            }
            // add class when image loaded
        }
    });
}

function stripUrlParams(args) {
    "use strict";
    var parts = args.split("&");
    var comps = {};
    for (var i = parts.length - 1; i >= 0; i--) {
        var spl = parts[i].split("=");
        // Overwrite only if existing is empty.
        if (typeof comps[ spl[0] ] == "undefined" || (typeof comps[ spl[0] ] != "undefined" && comps[ spl[0] ] == '')) {
            comps[ spl[0] ] = spl[1];
        }
    }
    parts = [];
    for (var a in comps) {
        parts.push(a + "=" + comps[a]);
    }

    return parts.join('&');
}

function wp_rem_property_filters_content(counter, page_var, page_num, tab) {
    "use strict";
    counter = counter || '';
    var property_arg = jQuery("#property_arg" + counter).html();
    var this_frm = jQuery("#frm_property_arg" + counter);

    var ads_list_count = jQuery("#ads_list_count_" + counter).val();
    var ads_list_flag = jQuery("#ads_list_flag_" + counter).val();
    var list_flag_count = jQuery("#ads_list_flag_count_" + counter).val();
    var data_vals = 'tab=' + tab + '&' + page_var + '=' + page_num + '&ajax_filter=true';
    jQuery('#wp-rem-data-property-content-' + counter + ' .all-results').addClass('slide-loader');
    if (typeof (propertyFilterAjax) != 'undefined') {
        propertyFilterAjax.abort();
    }
    propertyFilterAjax = jQuery.ajax({
        type: 'POST',
        dataType: 'HTML',
        url: wp_rem_globals.ajax_url,
        data: data_vals + '&action=wp_rem_properties_filters_content&property_arg=' + property_arg,
        success: function (response) {
            jQuery('#property-tab-content-' + counter).html(response);
            jQuery("#property-tab-content-" + counter + ' .row').mixItUp({
                selectors: {
                    target: ".portfolio",
                }
            });
            //replace double & from string
            data_vals = data_vals.replace("&&", "&");
            var current_url = location.protocol + "//" + location.host + location.pathname + "?" + data_vals; //window.location.href;
            window.history.pushState(null, null, decodeURIComponent(current_url));
            jQuery(".chosen-select").chosen();
            jQuery('#wp-rem-data-property-content-' + counter + ' .all-results').removeClass('slide-loader');
            // add class when image loaded
            jQuery(".property-medium .img-holder img, .property-grid .img-holder img").one("load", function () {
                jQuery(this).parents(".img-holder").addClass("image-loaded");
            }).each(function () {
                if (this.complete)
                    $(this).load();
            });
            // add class when image loaded
        }
    });

}



function wp_rem_property_by_categories_filters_content(counter, page_var, page_num, tab) {
    "use strict";
    counter = counter || '';
    var property_arg = jQuery("#property_arg" + counter).html();
    var this_frm = jQuery("#frm_property_arg" + counter);

    var ads_list_count = jQuery("#ads_list_count_" + counter).val();
    var ads_list_flag = jQuery("#ads_list_flag_" + counter).val();
    var list_flag_count = jQuery("#ads_list_flag_count_" + counter).val();
    var data_vals = 'tab=' + tab + '&' + page_var + '=' + page_num + '&ajax_filter=true';
    jQuery('#wp-rem-data-property-content-' + counter + ' .all-results').addClass('slide-loader');
    if (typeof (propertyFilterAjax) != 'undefined') {
        propertyFilterAjax.abort();
    }
    propertyFilterAjax = jQuery.ajax({
        type: 'POST',
        dataType: 'HTML',
        url: wp_rem_globals.ajax_url,
        data: data_vals + '&action=wp_rem_property_by_categories_filters_content&property_arg=' + property_arg,
        success: function (response) {
            jQuery('#property-tab-content-' + counter).html(response);
            jQuery("#property-tab-content-" + counter + ' .row').mixItUp({
                selectors: {
                    target: ".portfolio",
                }
            });
            //replace double & from string
            data_vals = data_vals.replace("&&", "&");
            var current_url = location.protocol + "//" + location.host + location.pathname + "?" + data_vals; //window.location.href;
            window.history.pushState(null, null, decodeURIComponent(current_url));
            jQuery(".chosen-select").chosen();
            jQuery('#wp-rem-data-property-content-' + counter + ' .all-results').removeClass('slide-loader');
            // add class when image loaded
            jQuery(".property-medium .img-holder img, .property-grid .img-holder img").one("load", function () {
                jQuery(this).parents(".img-holder").addClass("image-loaded");
            }).each(function () {
                if (this.complete)
                    $(this).load();
            });
            // add class when image loaded
        }
    });

}

function convertHTML(html) {
    "use strict";
    var newHtml = $.trim(html),
            $html = $(newHtml),
            $empty = $();

    $html.each(function (index, value) {
        if (value.nodeType === 1) {
            $empty = $empty.add(this);
        }
    });

    return $empty;
}
;

function wp_rem_property_type_search_fields(thisObj, counter, price_switch, page_url) {
    "use strict";
    if(typeof page_url === 'undefined' || page_url == ''){
        page_url = jQuery(thisObj).find(':selected').attr('data-search-page');
    }
    jQuery("#top-search-form-"+counter+"").attr("action", page_url );
    jQuery.ajax({
        type: 'POST',
        dataType: 'json',
        url: wp_rem_globals.ajax_url,
        data: '&action=wp_rem_property_type_search_fields&property_short_counter=' + counter + '&property_type_slug=' + thisObj.value + '&price_switch=' + price_switch,
        success: function (response) {
            jQuery('#property_type_fields_' + counter).html('');
            jQuery('#property_type_fields_' + counter).html(response.html);
        }
    });
}

function wp_rem_property_type_price_type_field(thisObj, counter, price_type_switch, view, color) {
    "use strict";
    if (typeof view === 'undefined') {
        view = 'default';
    }
    if (typeof color === 'undefined') {
        color = 'none';
    }
    var view_label;
    if (view == 'modern-v4') {
        view_label = '';
    } else {
        view_label = '<strong class="search_title">' + wp_rem_property_functions_string.price_type + '</strong>';
    }
    var cate_loader = view_label + '<b class="spinner-label">' + wp_rem_property_functions_string.all + '</b><span class="cate-spinning"><i class="icon-spinner8"></i></span>';
    jQuery('#property_type_price_type_field_' + counter).html(cate_loader);
    jQuery.ajax({
        type: 'POST',
        dataType: 'json',
        url: wp_rem_globals.ajax_url,
        data: '&action=wp_rem_property_type_price_type_field&property_short_counter=' + counter + '&property_type_slug=' + thisObj.value + '&view=' + view + '&price_type_switch=' + price_type_switch,
        success: function (response) {
            jQuery('#property_type_price_type_field_' + counter).html('');
            jQuery('#property_type_price_type_field_' + counter).html(response.html);
        }
    });
}

function wp_rem_property_type_cate_fields(thisObj, counter, cats_switch, view, color) {
    "use strict";
    if (typeof view === 'undefined') {
        view = 'default';
    }
    if (typeof color === 'undefined') {
        color = 'none';
    }
    var cate_loader = '<b class="spinner-label">' + wp_rem_property_functions_string.property_cats + '</b><span class="cate-spinning"><i class="icon-spinner8"></i></span>';
    jQuery('#property_type_cate_fields_' + counter).html(cate_loader);
    jQuery.ajax({
        type: 'POST',
        dataType: 'json',
        url: wp_rem_globals.ajax_url,
        data: '&action=wp_rem_property_type_cate_fields&property_short_counter=' + counter + '&property_type_slug=' + thisObj.value + '&view=' + view + '&color=' + color + '&cats_switch=' + cats_switch,
        success: function (response) {
            jQuery('#property_type_cate_fields_' + counter).html('');
            jQuery('#property_type_cate_fields_' + counter).html(response.html);
        }
    });
}

function wp_rem_empty_loc_polygon(counter) {
    if (jQuery("#frm_property_arg" + counter + " input[name=loc_polygon_path]").length) {
        jQuery("#frm_property_arg" + counter + " input[name=loc_polygon_path]").val('');
    }
}
function wp_rem_property_view_switch(view, counter, property_short_counter, view_type) {
    "use strict";
    var view_type = view_type || '';
    jQuery.ajax({
        type: 'POST',
        dataType: 'HTML',
        url: wp_rem_globals.ajax_url,
        data: '&action=wp_rem_property_view_switch&view=' + view + '&property_short_counter=' + property_short_counter,
        success: function () {
            jQuery('[data-toggle="popover"]').popover();
            jQuery('body').addClass('wp-rem-changing-view');
            wp_rem_property_content(counter, view_type);
        }
    });
}
function wp_rem_property_pagenation_ajax(page_var, page_num, counter, ajax_filter, view_type) {
    "use strict";
    var view_type = view_type || '';
    jQuery('html, body').animate({
        scrollTop: jQuery("#wp-rem-property-content-" + counter).offset().top - 120
    }, 1000);
    jQuery('#' + page_var + '-' + counter).val(page_num);
    if (ajax_filter == 'false') {
        wp_rem_property_content_without_filters(counter, page_var, page_num, ajax_filter, view_type);
    } else {
        wp_rem_property_content(counter, view_type);
    }
}

function wp_rem_property_filters_pagenation_ajax(page_var, page_num, counter, tab) {
    "use strict";
    jQuery('#' + page_var + '-' + counter).val(page_num);
    wp_rem_property_filters_content(counter, page_var, page_num, tab);
}

function wp_rem_property_by_category_filters_pagenation_ajax(page_var, page_num, counter, tab) {
    "use strict";
    jQuery('#' + page_var + '-' + counter).val(page_num);
    wp_rem_property_by_categories_filters_content(counter, page_var, page_num, tab);
}

function wp_rem_advanced_search_field(counter, tab, element) {
    "use strict";
    if (tab == 'simple') {
        jQuery('#property_type_fields_' + counter).slideUp();
        jQuery('#nav-tabs-' + counter + ' li').removeClass('active');
        jQuery(element).parent().addClass('active');
    } else if (tab == 'advance') {
        jQuery('#property_type_fields_' + counter).slideDown();
        jQuery('#nav-tabs-' + counter + ' li').removeClass('active');
        jQuery(element).parent().addClass('active');
    } else {
        jQuery('#property_type_fields_' + counter).slideToggle();
    }
}

function wp_rem_search_features(element, counter) {
    "use strict";
    jQuery('#property_type_fields_' + counter + ' .features-field-expand').slideToggle();
    var expand_class = jQuery('#property_type_fields_' + counter + ' .features-list .advance-trigger').find('i').attr('class');
    if (expand_class == 'icon-plus') {
        console.log(expand_class);
        jQuery('#property_type_fields_' + counter + ' .features-list .advance-trigger').find('i').removeClass(expand_class).addClass('icon-minus')
    } else {
        jQuery('#property_type_fields_' + counter + ' .features-list .advance-trigger').find('i').removeClass(expand_class).addClass('icon-plus')
    }
}


jQuery(document).on('click', '.dev-property-list-enquiry-own-property', function (e) {
    e.stopImmediatePropagation();
    var msg_obj = {msg: wp_rem_globals.own_property_error, type: 'error'};
    wp_rem_show_response(msg_obj);
});


/*
 * Enquiries Block
 */

jQuery(document).ready(function () {
    if (jQuery('#enquires-sidebar-panel').length > 0) {
        enquiry_sidebar_arrow();
    }
});

function enquiry_sidebar_arrow() {
    jQuery('.fixed-sidebar-panel.left .sidebar-panel-btn').click(function (e) {
        e.preventDefault();
        if (jQuery('#enquires-sidebar-panel').hasClass('sidebar-panel-open')) {
            jQuery('#enquires-sidebar-panel').removeClass('sidebar-panel-open');
        } else {
            jQuery('#enquires-sidebar-panel').addClass('sidebar-panel-open');
        }
    });
    jQuery('#enquires-sidebar-panel .sidebar-panel-title .sidebar-panel-btn-close').click(function (e) {
        jQuery('#enquires-sidebar-panel').removeClass('sidebar-panel-open');
    });
}
/*
 * Enquiry Multiple select
 */

jQuery(document).on('click', '.property-list-enquiry-check', function () {
    var data_id = jQuery(this).data('id');
    var thisObj = jQuery(this);
    if (thisObj.hasClass('active')) {
        jQuery('.chosen-enquires-list .sidebar-properties-list ul li[data-id="' + data_id + '"] .property-item-remove').click();
        return;
    }
    thisObj.append('<span class="enquiry-loader"><i class="icon-spinner8"></i></span>');
    jQuery.ajax({
        type: 'POST',
        dataType: 'HTML',
        url: wp_rem_globals.ajax_url,
        data: 'action=wp_rem_enquiry_list_frontend&property_id=' + data_id + '&add_enquiry=yes',
        success: function (response) {
            thisObj.find('.enquiry-loader').remove();
            jQuery('.chosen-enquires-list .sidebar-properties-list ul').append(response);
            if (response != '') {
                thisObj.addClass('active');
                if (!jQuery('.chosen-enquires-list').hasClass('sidebar-panel-open')) {
                    jQuery('#enquires-sidebar-panel').addClass('sidebar-panel-open');
                    jQuery('#enquires-sidebar-panel .sidebar-panel-btn').fadeIn('slow');
                }
                var _appending_inp = jQuery("#wp_rem_property_id");
                if (_appending_inp.val() == '') {
                    _appending_inp.val(data_id);
                } else {
                    console.log(data_id);
                    var new_val = _appending_inp.val() + ',' + data_id;
                    _appending_inp.val(new_val);
                }
            }
        }
    });
});

/*
 * Enquiry Remove from Multiple select
 */

jQuery(document).on('click', '.chosen-enquires-list .sidebar-properties-list ul li .property-item-remove', function () {
    var thisObj = jQuery(this);
    var data_id = thisObj.closest('li').data('id');
    thisObj.html('<i class="icon-spinner8"></i>');
    jQuery('.property-list-enquiry-check[data-id="' + data_id + '"]').append('<span class="enquiry-loader"><i class="icon-spinner8"></i></span>');
    jQuery.ajax({
        type: 'POST',
        dataType: 'HTML',
        url: wp_rem_globals.ajax_url,
        data: 'action=wp_rem_enquiry_list_remove_frontend&property_id=' + data_id,
        success: function (response) {
            jQuery('.property-list-enquiry-check[data-id="' + data_id + '"]').find('.enquiry-loader').remove();
            jQuery('.property-list-enquiry-check[data-id="' + data_id + '"]').removeClass('active');
            var strVal = jQuery("#wp_rem_property_id").val();
            var dataArray = strVal.split(",");
            dataArray.splice(dataArray.indexOf(data_id), 1);
            strVal = dataArray.join(',');
            jQuery("#wp_rem_property_id").val(strVal);
            if (strVal == '') {
                jQuery('#enquires-sidebar-panel').removeClass('sidebar-panel-open');
                jQuery('#enquires-sidebar-panel .sidebar-panel-btn').fadeOut('slow');
            }
            thisObj.closest('li').slideUp(400, function () {
                thisObj.closest('li').remove();
            });
        }
    });
});


function SidbarPanelHeight() {
    var WindowHeightForSidbarPanel = $(window).height();
    $(".sidebar-properties-list ul").css({"max-height": WindowHeightForSidbarPanel - 200, "overflow-y": "auto"});
}
SidbarPanelHeight();
$(window).resize(function () {
    SidbarPanelHeight();
});

/*
 * Enquiry Reset all Multiple select
 */

jQuery(document).on('click', '.chosen-enquires-list .enquiry-reset-btn', function () {
    var thisObj = jQuery(this);
    wp_rem_show_loader(".chosen-enquires-list .enquiry-reset-btn", "", "button_loader", thisObj);
    jQuery.ajax({
        type: 'POST',
        dataType: 'HTML',
        url: wp_rem_globals.ajax_url,
        data: 'action=wp_rem_enquiry_list_clear_frontend',
        success: function (response) {
            jQuery('.chosen-enquires-list .sidebar-properties-list ul li').remove();
            wp_rem_hide_button_loader('.chosen-enquires-list .enquiry-reset-btn');
            jQuery('.property-list-enquiry-check').removeClass('active');
            jQuery("#wp_rem_property_id").val('');
            jQuery('#enquires-sidebar-panel').removeClass('sidebar-panel-open');
            jQuery('#enquires-sidebar-panel .sidebar-panel-btn').fadeOut('slow');
        }
    });
});
