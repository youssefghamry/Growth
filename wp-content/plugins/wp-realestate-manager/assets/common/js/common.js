var $   = jQuery;

jQuery(document).ready(function($) {
    if (jQuery(".wp_rem_editor").length != "") {
        jQuery(".wp_rem_editor").jqte();
    }
    jQuery(".cs-drag-slider").each(function(index) {
    "use strict";
    if (jQuery(this).attr("data-slider-step") != "") {
        var data_min_max = jQuery(this).attr("data-min-max");
        var val_parameter = [parseInt(jQuery(this).attr("data-slider-min")), parseInt(jQuery(this).attr("data-slider-max"))];
        if (data_min_max != "yes") {
            var val_parameter = parseInt(jQuery(this).attr("data-slider-min"));
        }
        jQuery(this).children("input").slider({
            min: parseInt(jQuery(this).attr("data-slider-min")),
            max: parseInt(jQuery(this).attr("data-slider-max")),
            value: val_parameter,
            focus: true
        });
    }
    });
	
});

jQuery(document).on("click", "a.wp-rem-dev-property-delete", function() {
    "use strict";
    jQuery("#id_confrmdiv").show();
    var deleting_property, _this_ = jQuery(this),
    _this_id = jQuery(this).data("id"),
    ajax_url = jQuery("#wp-rem-dev-user-property").data("ajax-url"),
    this_parent = jQuery("#user-property-" + _this_id);
    _this_.html('<i class="icon-spinner8"></i>');
    jQuery("#id_truebtn").click(function() {
        jQuery("#id_confrmdiv").hide();
        deleting_property = jQuery.ajax({
            url: ajax_url,
            method: "POST",
            data: {
                property_id: _this_id,
                action: "wp_rem_member_property_delete"
            },
            dataType: "json"
        }).done(function(response) {
            if (typeof response.delete !== "undefined" && response.delete == "true") {
                this_parent.hide("slow");
            }
            _this_.html('<i class="icon-close2"></i>');
        }).fail(function() {
            _this_.html('<i class="icon-close2"></i>');
        });
    });

    jQuery("#id_falsebtn").click(function() {
        _this_.html('<i class="icon-close2"></i>');
        jQuery("#id_confrmdiv").hide();
        return false;
    });

});


jQuery(".book-list #close-btn4").click(function() {
    "use strict";
    jQuery(".book-list .open-close-time").addClass("opening-time");
});

jQuery(".book-list #close-btn3").click(function() {
    "use strict";
    jQuery(".book-list .open-close-time").removeClass("opening-time");
});


jQuery(".service-list ul li a.edit").on("click", function(e) {
    "use strict";
    e.preventDefault();
    jQuery(this).parent().toggleClass("open").find(".service-list ul li .info-holder");
    jQuery(this).parent().siblings().find(".service-list ul li .info-holder");
    jQuery(this).parent().siblings().removeClass("open");
});


/*
 * Packages
 */
jQuery(document).on("click", ".wp-rem-subscribe-pkg", function() {
    "use strict";
    var id = jQuery(this).data("id");
    jQuery("#response-" + id).slideDown();
});

$(document).on("click", ".wp-rem-dev-dash-detail-pkg", function() {
    "use strict";
    var _this_id = $(this).data("id"),
        package_detail_sec = $("#package-detail-" + _this_id);
    if (!package_detail_sec.is(":visible")) {
        $(".all-pckgs-sec").find(".package-info-sec").hide();
        package_detail_sec.slideDown();
    } else {
        package_detail_sec.slideUp();
    }
});

jQuery(document).on("click", ".wp-rem-subscribe-pkg-btn .buy-btn", function() {
    "use strict";
    var pkg_id = jQuery(this).parent().attr("data-id");
    var thisObj = jQuery(".buy-btn-" + pkg_id);
    wp_rem_show_loader(".buy-btn-" + pkg_id, "", "button_loader", thisObj);
});


/*
 * Open Time Block
 */

/* Time Open Close Function Start */

jQuery(".time-list #close-btn2").click(function() {
    jQuery(".time-list .open-close-time").addClass("opening-time");
});

jQuery(".time-list #close-btn1").click(function() {
    jQuery(".time-list .open-close-time").removeClass("opening-time");
});
    

jQuery(document).on("click", 'a[id^="wp-rem-dev-open-time"]', function() {
    "use strict";
    var _this_id = jQuery(this).data("id"),
        _this_day = jQuery(this).data("day"),
        _this_con = jQuery("#open-close-con-" + _this_day + "-" + _this_id),
        _this_status = jQuery("#wp-rem-dev-open-day-" + _this_day + "-" + _this_id);
    if (typeof _this_id !== "undefined" && typeof _this_day !== "undefined") {
        _this_status.val("on");
        _this_con.addClass("opening-time");
    }
});

jQuery(document).on("click", 'a[id^="wp-rem-dev-close-time"]', function() {
    "use strict";
    var _this_id = jQuery(this).data("id"),
        _this_day = jQuery(this).data("day"),
        _this_con = jQuery("#open-close-con-" + _this_day + "-" + _this_id),
        _this_status = jQuery("#wp-rem-dev-open-day-" + _this_day + "-" + _this_id);
    if (typeof _this_id !== "undefined" && typeof _this_day !== "undefined") {
        _this_status.val("");
        _this_con.removeClass("opening-time");
    }
});

/*
 * Common Block
 */

$(document).on("click", ".book-btn", function() {
    "use strict";
    $(this).next(".calendar-holder").slideToggle("fast");

});

$(document).on("click", 'a[id^="wp-rem-dev-day-off-rem-"]', function() {
    "use strict";
    var _this_id = $(this).data("id");
    $("#day-remove-" + _this_id).remove();
});

$(document).on("click", ".wp-rem-dev-insert-off-days .wp-rem-dev-calendar-days .day a", function() {
    "use strict";
    var adding_off_day, _this_ = $(this),
        _this_id = $(this).parents(".wp-rem-dev-insert-off-days").data("id"),
        _day = $(this).data("day"),
        _month = $(this).data("month"),
        _year = $(this).data("year"),
        _adding_date = _year + "-" + _month + "-" + _day,
        _add_date = true,
        _this_append = $("#wp-rem-dev-add-off-day-app-" + _this_id),
        no_off_day_msg = _this_append.find("#no-book-day-" + _this_id),
        this_loader = $("#dev-off-day-loader-" + _this_id),
        this_act_msg = $("#wp-rem-dev-act-msg-" + _this_id);
    _this_append.find("li").each(function() {
        var date_field = $(this).find('input[name^="wp_rem_property_off_days"]');
        if (_adding_date == date_field.val()) {
            var response = {
                type: "success",
                msg: wp_rem_property_strings.off_day_already_added
            };
            wp_rem_show_response(response);
            _add_date = false;
        }
    });

    if (typeof _day !== "undefined" && typeof _month !== "undefined" && typeof _year !== "undefined" && _add_date === true) {
        var thisObj = jQuery(".book-btn");
        wp_rem_show_loader(".book-btn", "", "button_loader", thisObj);
        adding_off_day = $.ajax({
            url: wp_rem_globals.ajax_url,
            method: "POST",
            data: {
                off_day_day: _day,
                off_day_month: _month,
                off_day_year: _year,
                property_add_counter: _this_id,
                action: "wp_rem_property_off_day_to_list"
            },
            dataType: "json"
        }).done(function(response) {
            if (typeof response.html !== "undefined") {
                no_off_day_msg.remove();
                _this_append.append(response.html);
                this_act_msg.html(wp_rem_property_strings.off_day_added);
            }
            var response = {
                type: "success",
                msg: wp_rem_property_strings.off_day_added
            };
            wp_rem_show_response(response, "", thisObj);
            $("#wp-rem-dev-cal-holder-" + _this_id).slideUp("fast");
        }).fail(function() {
            wp_rem_show_response("", "", thisObj);
        });
    }
});

/*
 * sorting gallery images
 */

function wp_rem_gallery_sorting_list(id, random_id) {
    var gallery = [];
    // more efficient than new Array()
    jQuery("#gallery_sortable_" + random_id + " li").each(function() {
        var data_value = jQuery.trim(jQuery(this).data("attachment_id"));
        gallery.push(jQuery(this).data("attachment_id"));
    });
    jQuery("#" + id).val(gallery.toString());
}


function wp_rem_load_location_ajax(postfix, allowed_location_types, location_levels, security) {
    "use strict";
    var $ = jQuery;
    $('#loc_country_' + postfix).change(function () {
        popuplate_data(this, 'country');
    });

    $('#loc_state_' + postfix).change(function () {
        popuplate_data(this, 'state');
    });

    $('#loc_city_' + postfix).change(function () {
        popuplate_data(this, 'city');
    });

    $('#loc_town_' + postfix).change(function () {
        popuplate_data(this, 'town');
    });

    function popuplate_data(elem, type) {
        "use strict";
        var plugin_url = $(elem).parents("#locations_wrap").data('plugin_url');
        var ajaxurl = $(elem).parents("#locations_wrap").data('ajaxurl');

        var index = allowed_location_types.indexOf(type);
        if (index + 1 >= allowed_location_types.length) {
            return;
        }
        var location_type = allowed_location_types[ index + 1 ];
        $(".loader-" + location_type + "-" + postfix).html("<img src='" + plugin_url + "/assets/backend/images/ajax-loader.gif' />").show();
        $.ajax({
            type: "POST",
            url: ajaxurl,
            data: {
                action: "get_locations_list",
                security: security,
                location_type: location_type,
                location_level: location_levels[ location_type ],
                selector: elem.value,
            },
            dataType: "json",
            success: function (response) {
                if (response.error == true) {
                    return;
                }
                var control_selector = "#loc_" + location_type + "_" + postfix;
                var data = response.data;
                $(control_selector + ' option').remove();
                $(control_selector).append($("<option></option>").attr("value", '').text('Choose...'));
                $.each(data, function (key, term) {
                    $(control_selector).append($("<option></option>").attr("value", term.slug).text(term.name));
                });

                $(".loader-" + location_type + "-" + postfix).html('').hide();
                // Only for style implementation.
                $(".chosen-select").data("placeholder", "Select").trigger('chosen:updated');
            }
        });
    }

    jQuery(document).ready(function (e) {

        //changeMap();
        jQuery('input#wp-rem-search-location').keypress(function (e) {
            if (e.which == '13') {
                e.preventDefault();
                cs_search_map(this.value);
                return false;
            }
        });
        jQuery('#loc_country_property').change(function (e) {
            setAutocompleteCountry('property');
        });
        jQuery('#loc_country_member').change(function (e) {
            setAutocompleteCountry('member');
        });
        jQuery('#loc_country_default').change(function (e) {
            setAutocompleteCountry('default');
        });
    });
    function setAutocompleteCountry(type) {
        "use strict";
        var country = jQuery('select#loc_country_' + type + ' option:selected').attr('data-name'); /*document.getElementById('country').value;*/
        if (country != '') {
            autocomplete.setComponentRestrictions({'country': country});
        } else {
            autocomplete.setComponentRestrictions([]);
        }
    }

}
