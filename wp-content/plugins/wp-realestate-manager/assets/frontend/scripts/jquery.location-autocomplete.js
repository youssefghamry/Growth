jQuery(document).ready(function (jQuery) {
    jQuery.fn.extend({
        cityAutocomplete: function (options) {

            return this.each(function () {

                var input = jQuery(this), opts = jQuery.extend({}, jQuery.cityAutocomplete);
                var input_id = input.data('id');

                var autocompleteService = new google.maps.places.AutocompleteService();

                var predictionsDropDown = jQuery('<div class="wp_rem_location_autocomplete" class="city-autocomplete" style="min-height: 35px;"></div>').appendTo(jQuery(this).parent());

                var predictionsLoader = jQuery('<div class="location-loader-wrapper" style="display: none;"><i class="icon-spinner8 icon-spin"></i></div>');
                predictionsDropDown.append(predictionsLoader);

                var predictionsGoogleWrapper = jQuery('<div class="location-google-wrapper" style="display: none;"></div>');
                predictionsDropDown.append(predictionsGoogleWrapper);

                var predictionsDBWrapper = jQuery('<div class="location-db-wrapper" style="display: none;"></div>');
                predictionsDropDown.append(predictionsDBWrapper);

                var plugin_url = input.parent(".wp_rem_searchbox_div").data('locationadminurl');

                var last_query = '';
                var new_query = '';
                var xhr = '';

                input.keyup(function () {
                    new_query = input.val();
                    // Min Number of characters
                    var num_of_chars = 0;
                    if (new_query.length > num_of_chars) {
                        predictionsDropDown.show();
                        predictionsGoogleWrapper.hide();
                        predictionsDBWrapper.hide();
                        predictionsLoader.show();

                        if (input.hasClass('wp-rem-locations-field-geo' + input_id)) {
                            var params = {
                                input: new_query,
                                bouns: 'upperbound',
                                //types: ['address'],
                                componentRestrictions: '', //{country: window.country_code}
                            };
                            //params.componentRestrictions = ''; //{country: window.country_code}
                            autocompleteService.getPlacePredictions(params, updateGooglePredictions);
                        }
                        updateDBPredictions();
                    } else {
                        predictionsDropDown.hide();
                    }
                    $("input.search_type").val('custom');
                });

                function updateGooglePredictions(predictions, status) {
                    var google_results = '';

                    if (google.maps.places.PlacesServiceStatus.OK == status) {
                        var address = cs_vars.address;
                        // AJAX GET ADDRESS FROM GOOGLE
                        google_results += '<div class="address_headers"><strong> ' + address + '  </strong></div><img src="'+cs_vars.plugin_dir_url+'assets/frontend/images/powered-by-google.png">'
                        jQuery.each(predictions, function (i, prediction) {
                            google_results += '<div class="wp_rem_google_suggestions"><i class="icon-location-arrow"></i>' + jQuery.fn.cityAutocomplete.transliterate(prediction.description) + '<span style="display:none">' + jQuery.fn.cityAutocomplete.transliterate(prediction.description) + '</span></div>';
                        });
                        predictionsLoader.hide();
                        predictionsGoogleWrapper.empty().append(google_results).show();
                    } else {
                        predictionsLoader.hide();
                    }
                }

                function updateDBPredictions() {
                    if (last_query == new_query) {
                        return;
                    }
                    last_query = new_query;
                    // AJAX GET STATE / PROVINCE.
                    var dataString = 'action=get_locations_for_search' + '&keyword=' + new_query;
                    if (xhr != '') {
                        xhr.abort();
                    }
                    xhr = jQuery.ajax({
                        type: "POST",
                        url: plugin_url,
                        data: dataString,
                        success: function (data) {
                            var results = jQuery.parseJSON(data);
                            if (results != '') {
                                // Set label for suggestions.
                                var labels_str = "";
                                if (typeof results.title != "undefined") {
                                    labels_str = results.title.join(" / ");
                                    
                                }
                                var locations_str = "";
                                // Populate suggestions.
                                if (typeof results.locations_for_display != "undefined") {
                                    var data = results.locations_for_display;
                                    $.each(data, function (key1, val1) {
                                        if (results.location_levels_to_show[0] == true && typeof val1.item != "undefined") {
                                            locations_str += '<div class="wp_rem_google_suggestions wp_rem_location_parent"><i class="icon-location-arrow"></i>' + val1.item.name + '<span style="display:none">' + val1.item.slug + '</span></div>';
                                        }
                                        if (val1.children.length > 0) {
                                            $.each(val1.children, function (key2, val2) {
                                                if (results.location_levels_to_show[1] == true && typeof val2.item != "undefined") {
                                                    locations_str += '<div class="wp_rem_google_suggestions wp_rem_location_child"><i class="icon-location-arrow"></i>' + val2.item.name + '<span style="display:none">' + val2.item.slug + '</span></div>';
                                                }
                                                if (val2.children.length > 0) {
                                                    $.each(val2.children, function (key3, val3) {
                                                        if (results.location_levels_to_show[2] == true && typeof val3.item != "undefined") {
                                                            locations_str += '<div class="wp_rem_google_suggestions wp_rem_location_child"><i class="icon-location-arrow"></i>' + val3.item.name + '<span style="display:none">' + val3.item.slug + '</span></div>';
                                                        }
                                                        if (val3.children.length > 0) {
                                                            $.each(val3.children, function (key4, val4) {
                                                                if (results.location_levels_to_show[3] == true && typeof val4.item != "undefined") {
                                                                    locations_str += '<div class="wp_rem_google_suggestions wp_rem_location_child"><i class="icon-location-arrow"></i>' + val4.item.name + '<span style="display:none">' + val4.item.slug + '</span></div>';
                                                                }
                                                            });
                                                        }
                                                    });
                                                }
                                            });
                                        }
                                    });
                                    predictionsDBWrapper.empty();
                                    if (locations_str != "") {
                                        predictionsLoader.hide();
                                        predictionsDBWrapper.append('<div class="address_headers"><strong>' + labels_str + '</strong></div>' + locations_str).show();
                                    } else {
                                        predictionsLoader.hide();
                                    }
                                }
                            }
                        }
                    });
                }

                predictionsDropDown.delegate('div.wp_rem_google_suggestions', 'click', function () {
                    if (jQuery(this).text() != "ADDRESS" && jQuery(this).text() != "STATE / PROVINCE" && jQuery(this).text() != "COUNTRY") {
                        // address with slug			
                        var wp_rem_address_html = jQuery(this).text();
                        // slug only
                        var wp_rem_address_slug = jQuery(this).find('span').html();
                        // remove slug
                        jQuery(this).find('span').remove();
                        input.val(jQuery(this).text());
                        input.next('.search_keyword').val(wp_rem_address_slug);
                        predictionsDropDown.hide();
                        input.next('.search_keyword').closest("form.side-loc-srch-form").submit();
                        $("input.search_type").val('autocomplete');
                    }
                });

                jQuery(document).mouseup(function (e) {
                    predictionsDropDown.hide();
                });

                jQuery(window).resize(function () {
                    updatePredictionsDropDownDisplay(predictionsDropDown, input);
                });

                updatePredictionsDropDownDisplay(predictionsDropDown, input);

                return input;
            });
        }
    });
    jQuery.fn.cityAutocomplete.transliterate = function (s) {
        s = String(s);
        var char_map = {
            // Latin
            'À': 'A', '�?': 'A', 'Â': 'A', 'Ã': 'A', 'Ä': 'A', 'Å': 'A', 'Æ': 'AE', 'Ç': 'C',
            'È': 'E', 'É': 'E', 'Ê': 'E', 'Ë': 'E', 'Ì': 'I', '�?': 'I', 'Î': 'I', '�?': 'I',
            '�?': 'D', 'Ñ': 'N', 'Ò': 'O', 'Ó': 'O', 'Ô': 'O', 'Õ': 'O', 'Ö': 'O', '�?': 'O',
            'Ø': 'O', 'Ù': 'U', 'Ú': 'U', 'Û': 'U', 'Ü': 'U', 'Ű': 'U', '�?': 'Y', 'Þ': 'TH',
            'ß': 'ss',
            'à': 'a', 'á': 'a', 'â': 'a', 'ã': 'a', 'ä': 'a', 'å': 'a', 'æ': 'ae', 'ç': 'c',
            'è': 'e', 'é': 'e', 'ê': 'e', 'ë': 'e', 'ì': 'i', 'í': 'i', 'î': 'i', 'ï': 'i',
            'ð': 'd', 'ñ': 'n', 'ò': 'o', 'ó': 'o', 'ô': 'o', 'õ': 'o', 'ö': 'o', 'ő': 'o',
            'ø': 'o', 'ù': 'u', 'ú': 'u', 'û': 'u', 'ü': 'u', 'ű': 'u', 'ý': 'y', 'þ': 'th',
            'ÿ': 'y',
            // Latin symbols
            '©': '(c)',
            // Greek
            'Α': 'A', 'Β': 'B', 'Γ': 'G', 'Δ': 'D', 'Ε': 'E', 'Ζ': 'Z', 'Η': 'H', 'Θ': '8',
            'Ι': 'I', 'Κ': 'K', 'Λ': 'L', 'Μ': 'M', '�?': 'N', 'Ξ': '3', 'Ο': 'O', 'Π': 'P',
            'Ρ': 'R', 'Σ': 'S', 'Τ': 'T', 'Υ': 'Y', 'Φ': 'F', 'Χ': 'X', 'Ψ': 'PS', 'Ω': 'W',
            'Ά': 'A', 'Έ': 'E', 'Ί': 'I', 'Ό': 'O', 'Ύ': 'Y', 'Ή': 'H', '�?': 'W', 'Ϊ': 'I',
            'Ϋ': 'Y',
            'α': 'a', 'β': 'b', 'γ': 'g', 'δ': 'd', 'ε': 'e', 'ζ': 'z', 'η': 'h', 'θ': '8',
            'ι': 'i', 'κ': 'k', 'λ': 'l', 'μ': 'm', 'ν': 'n', 'ξ': '3', 'ο': 'o', 'π': 'p',
            '�?': 'r', 'σ': 's', 'τ': 't', 'υ': 'y', 'φ': 'f', 'χ': 'x', 'ψ': 'ps', 'ω': 'w',
            'ά': 'a', 'έ': 'e', 'ί': 'i', 'ό': 'o', '�?': 'y', 'ή': 'h', 'ώ': 'w', 'ς': 's',
            'ϊ': 'i', 'ΰ': 'y', 'ϋ': 'y', '�?': 'i',
            // Turkish
            'Ş': 'S', 'İ': 'I', 'Ç': 'C', 'Ü': 'U', 'Ö': 'O', 'Ğ': 'G',
            'ş': 's', 'ı': 'i', 'ç': 'c', 'ü': 'u', 'ö': 'o', 'ğ': 'g',
            // Russian
            '�?': 'A', 'Б': 'B', 'В': 'V', 'Г': 'G', 'Д': 'D', 'Е': 'E', '�?': 'Yo', 'Ж': 'Zh',
            'З': 'Z', 'И': 'I', 'Й': 'J', 'К': 'K', 'Л': 'L', 'М': 'M', '�?': 'N', 'О': 'O',
            'П': 'P', 'Р': 'R', 'С': 'S', 'Т': 'T', 'У': 'U', 'Ф': 'F', 'Х': 'H', 'Ц': 'C',
            'Ч': 'Ch', 'Ш': 'Sh', 'Щ': 'Sh', 'Ъ': '', 'Ы': 'Y', 'Ь': '', 'Э': 'E', 'Ю': 'Yu',
            'Я': 'Ya',
            'а': 'a', 'б': 'b', 'в': 'v', 'г': 'g', 'д': 'd', 'е': 'e', 'ё': 'yo', 'ж': 'zh',
            'з': 'z', 'и': 'i', 'й': 'j', 'к': 'k', 'л': 'l', 'м': 'm', 'н': 'n', 'о': 'o',
            'п': 'p', 'р': 'r', '�?': 's', 'т': 't', 'у': 'u', 'ф': 'f', 'х': 'h', 'ц': 'c',
            'ч': 'ch', 'ш': 'sh', 'щ': 'sh', 'ъ': '', 'ы': 'y', 'ь': '', '�?': 'e', 'ю': 'yu',
            '�?': 'ya',
            // Ukrainian
            'Є'
                    : 'Ye', 'І': 'I', 'Ї': 'Yi', '�?': 'G',
            'є'
                    : 'ye', 'і': 'i', 'ї': 'yi', 'ґ': 'g',
            // Czech
            'Č'
                    : 'C', 'Ď': 'D', 'Ě': 'E', 'Ň': 'N', 'Ř': 'R', 'Š': 'S', 'Ť': 'T', 'Ů': 'U',
            'Ž'
                    : 'Z',
            '�?'
                    : 'c', '�?': 'd', 'ě': 'e', 'ň': 'n', 'ř': 'r', 'š': 's', 'ť': 't', 'ů': 'u',
            'ž'
                    : 'z',
            // Polish
            'Ą'
                    : 'A', 'Ć': 'C', 'Ę': 'e', '�?': 'L', 'Ń': 'N', 'Ó': 'o', 'Ś': 'S', 'Ź': 'Z',
            'Ż'
                    : 'Z',
            'ą'
                    : 'a', 'ć': 'c', 'ę': 'e', 'ł': 'l', 'ń': 'n', 'ó': 'o', 'ś': 's', 'ź': 'z',
            'ż'
                    : 'z',
            // Latvian
            'Ā'
                    : 'A', 'Č': 'C', 'Ē': 'E', 'Ģ': 'G', 'Ī': 'i', 'Ķ': 'k', 'Ļ': 'L', 'Ņ': 'N',
            'Š'
                    : 'S', 'Ū': 'u', 'Ž': 'Z',
            '�?'
                    : 'a', '�?': 'c', 'ē': 'e', 'ģ': 'g', 'ī': 'i', 'ķ': 'k', 'ļ': 'l', 'ņ': 'n',
            'š'
                    : 's', 'ū': 'u', 'ž': 'z'
        };
        jQuery.each(char_map, function (chars, normalized) {
            var regex = new RegExp('[' + chars + ']', 'gi');
            s = s.replace(regex, normalized);
        });
        return s;
    };
    function updatePredictionsDropDownDisplay(dropDown, input) {
        if (typeof (input.offset()) !== 'undefined') {
            dropDown.css({
                'width': input.outerWidth(),
                'left': input.offset().left,
                'top': input.offset().top + input.outerHeight()
            });
        }
    }

    jQuery('input.wp_rem_search_location_field').cityAutocomplete();

    jQuery(document).on('click', '.wp_rem_searchbox_div', function () {
        jQuery('.wp_rem_search_location_field').prop('disabled', false);
    });

    jQuery(document).on('click', 'form', function () {
        var src_loc_val = jQuery(this).find('.wp_rem_search_location_field');
        src_loc_val.next('.search_keyword').val(src_loc_val.val());
    });
}(jQuery));