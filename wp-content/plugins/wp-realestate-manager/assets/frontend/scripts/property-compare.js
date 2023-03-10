var $ = jQuery;

$(document).on('click', '.wp_rem_property_compare_check', function () {
    var _this = $(this);
    var this_id = _this.data('id');
    var this_rand = _this.data('random');
    var this_ajaxurl = wp_rem_property_compare.ajax_url;
    var compare_class = _this.next().find('i').attr('class');
    var loader_class = 'icon-spinner8';
    var _action = 'check';
    
    if (_this.is(":checked")) {
        _action = 'check';
        _this.parent().parent().closest( ".property-medium" ).addClass('active');
        _this.parent().parent().closest( ".property-grid" ).addClass('active');
    } else {
        _action = 'uncheck';
        _this.parent().closest( ".property-medium" ).removeClass('active');
        _this.parent().closest( ".property-grid" ).removeClass('active');
    }
    
    var dataString = 'wp_rem_property_id=' + this_id + '&_action=' + _action + '&action=wp_rem_compare_add';
    _this.next().find('i').removeClass(compare_class).addClass(loader_class);
    _this.parent().addClass('comparing');
        
    $.ajax({
        type: "POST",
        url: this_ajaxurl,
        data: dataString,
        dataType: 'json',
        success: function (response) {
            if (response.mark !== 'undefined') {
             
                if( response.type === '1' ){
                    _this.parent().closest( ".property-medium" ).addClass('active');
                    _this.parent().closest( ".property-grid" ).addClass('active');
                    _this.parent().find('.option-content span').html(wp_rem_property_compare.compared_label);
                }else{
                    _this.parent().closest( ".property-medium" ).removeClass('active');
                    _this.parent().closest( ".property-grid" ).removeClass('active');
                    _this.parent().find('.option-content span').html(wp_rem_property_compare.compare_label);
                }
                _this.parent().removeClass('comparing');
                _this.next().find('i').removeClass(loader_class).addClass(compare_class);
                
                if( response.type !== '3' ){
                    _this.parent().toggleClass('compared');
                    if( response.html ){
                        if( response.status === 'removed' ){
                            jQuery('.chosen-compare-list .sidebar-properties-list ul li.compare-property-'+ this_id).slideUp(500);
                            jQuery('.chosen-compare-list .sidebar-properties-list ul li.compare-property-'+ this_id).remove();
                        }else if( response.status === 'added' ){
                            jQuery('.chosen-compare-list .sidebar-properties-list ul').append(response.html);
                            jQuery('.chosen-compare-list .sidebar-properties-list li.compare-property-'+ this_id).hide();
                            jQuery('.chosen-compare-list .sidebar-properties-list li.compare-property-'+ this_id).slideDown(500);
                        }else if( response.status === 'already_added' && jQuery('.compare-property-'+ this_id).length <= 0 ){
                            jQuery('.chosen-compare-list .sidebar-properties-list ul').append(response.html);
                            jQuery('.chosen-compare-list .sidebar-properties-list li.compare-property-'+ this_id).hide();
                            jQuery('.chosen-compare-list .sidebar-properties-list li.compare-property-'+ this_id).slideDown(500);
                        }
                    }
                }else{
                    _this.parent().find('input:checkbox').removeAttr('checked');
                }
                
                jQuery('.chosen-compare-list .compare-response').html('');
                jQuery('.chosen-compare-list .compare-response').html(response.mark);
                jQuery('.chosen-compare-list .compare-response').fadeIn('slow');
                
                if ( jQuery('.chosen-compare-list .sidebar-properties-list ul li').length > 0 ) {
                    jQuery('#compare-sidebar-panel').addClass('sidebar-panel-open');
                    jQuery('#compare-sidebar-panel .sidebar-panel-btn').fadeIn('slow');
                }else{
                    jQuery('#compare-sidebar-panel').removeClass('sidebar-panel-open');
                    jQuery('#compare-sidebar-panel .sidebar-panel-btn').fadeOut('slow');
                }
                
                setTimeout(function() {
                    jQuery('.chosen-compare-list .compare-response').fadeOut('slow');
                    jQuery('.chosen-compare-list .compare-response').html('');
                }, 5000);
            }
        }
    });
});

$(document).on('click', '.wp_rem_compare_check_add, .wp-rem-btn-compare', function () {
    var _this = $(this);
    var this_id = _this.data('id');
    var this_rand = _this.data('random');
    var this_ajaxurl = wp_rem_property_compare.ajax_url;
    var _action = 'check';
    _action = _this.attr("data-check");
    _this.find('i').removeClass('icon-compare-filled2');
    _this.find('i').addClass('icon-spinner8');
    
    var dataString = 'wp_rem_property_id=' + this_id + '&_action=' + _action + '&action=wp_rem_compare_add';
    
    $.ajax({
        type: "POST",
        url: this_ajaxurl,
        data: dataString,
        dataType: 'json',
        success: function (response) {
            _this.find('> i').show();
            if (response.mark !== 'undefined') {
                _this.find('i').addClass('icon-compare-filled2');
                _this.find('i').removeClass('icon-spinner8');

                if( response.html && response.type !== '3' ){
                    if( response.status === 'removed' ){
                        jQuery('.chosen-compare-list .sidebar-properties-list ul li.compare-property-'+ this_id).slideUp(500);
                        jQuery('.chosen-compare-list .sidebar-properties-list ul li.compare-property-'+ this_id).remove();
                    }else if( response.status === 'added' ){
                        jQuery('.chosen-compare-list .sidebar-properties-list ul').append(response.html);
                        jQuery('.chosen-compare-list .sidebar-properties-list li.compare-property-'+ this_id).hide();
                        jQuery('.chosen-compare-list .sidebar-properties-list li.compare-property-'+ this_id).slideDown(500);
                    }else if( response.status === 'already_added' && jQuery('.compare-property-'+ this_id).length <= 0 ){
                        jQuery('.chosen-compare-list .sidebar-properties-list ul').append(response.html);
                        jQuery('.chosen-compare-list .sidebar-properties-list li.compare-property-'+ this_id).hide();
                        jQuery('.chosen-compare-list .sidebar-properties-list li.compare-property-'+ this_id).slideDown(500);

                    }
                }
                if ( jQuery('.chosen-compare-list .sidebar-properties-list ul li').length > 0 ) {
                    jQuery('#compare-sidebar-panel').addClass('sidebar-panel-open');
                    jQuery('#compare-sidebar-panel .sidebar-panel-btn').fadeIn('slow');
                }else{
                    jQuery('#compare-sidebar-panel').removeClass('sidebar-panel-open');
                    jQuery('#compare-sidebar-panel .sidebar-panel-btn').fadeOut('slow');
                }

                jQuery('.chosen-compare-list .compare-response').html('');
                jQuery('.chosen-compare-list .compare-response').html(response.mark);
                jQuery('.chosen-compare-list .compare-response').fadeIn('slow');

                setTimeout(function() {
                    jQuery('.chosen-compare-list .compare-response').fadeOut('slow');
                    jQuery('.chosen-compare-list .compare-response').html('');
                }, 5000);
                _this.find('span').html(response.compare);
                if( response.type !== '3' ){
                    var check_val = _this.attr("data-check");
                    if (check_val == 'uncheck') {
                        check_val = 'check';
                    } else {
                        check_val = 'uncheck';
                    }
                    _this.attr('data-check', check_val);
                }
            }
        }
    });
});

$(document).on('click', '.compare-message .compare-large .icon-cross', function () {
    jQuery('.compare-message').removeClass('active');
    jQuery('.compare-message .compare-large .compare-text').html('');
});
$(document).on('click', '.clear-list', function () {
    var this_id = $(this).data('id');
    var this_type_id = $(this).data('type-id');
    var this_ajaxurl = wp_rem_property_compare.ajax_url;
    var dataString = 'property_id=' + this_id + '&type_id=' + this_type_id + '&action=wp_rem_clear_compare';
    $.ajax({
        type: "POST",
        url: this_ajaxurl,
        data: dataString,
        dataType: 'json',
        success: function (response) {
            if (response.type === 'success') {
                var current_url = window.location.href; //window.location.href;
                window.location.href = current_url;
            }
        }
    });
});

$(document).on('click', '.wp-rem-remove-compare-item', function () {
    var this_id = $(this).data('id');
    var this_type_id = $(this).data('type-id');
    var this_ajaxurl = wp_rem_property_compare.ajax_url;
    var wp_rem_prop_ids = $('.wp-rem-compare').data('ids');
    var wp_rem_page_id = $('.wp-rem-compare').data('id');
    var dataString = 'property_id=' + this_id + '&type_id=' + this_type_id + '&prop_ids=' + wp_rem_prop_ids + '&page_id=' + wp_rem_page_id + '&action=wp_rem_removing_compare';
    $(this).html('<i class="icon-spinner8 icon-spin"></i>');
    $.ajax({
        type: "POST",
        url: this_ajaxurl,
        data: dataString,
        dataType: 'json',
        success: function (response) {
            if (response.url !== 'undefined' && response.url != '') {
                $('.dev-rem-' + this_id).remove();
                window.location.href = response.url;
            }
        }
    });
});

jQuery(document).on('click', '.chosen-compare-list .sidebar-properties-list ul li .icon-trash3', function () {
    var thisObj = jQuery(this);
    var this_id = thisObj.closest('li').data('id');
    var this_type_id = thisObj.closest('li').data('type-id');
    var this_ajaxurl = wp_rem_property_compare.ajax_url;
    thisObj.removeClass('icon-trash3');
    thisObj.addClass('icon-spinner8');
    if ( jQuery('.compare-properties-'+ this_id).length > 0 ) {
        jQuery('.compare-properties-'+ this_id).each(function(){
            if( jQuery(this).hasClass('compared') ){
                var compare_class = jQuery(this).find('i').attr('class');
                var loader_class = 'icon-spinner8';
                jQuery(this).removeClass('compared');
                jQuery(this).find('i').removeClass(compare_class).addClass(loader_class);
                jQuery(this).addClass('comparing');
            }
        });
    }else if( jQuery('.wp-rem-btn-compare').length > 0 ){
        var this_check = jQuery('.wp-rem-btn-compare').attr('data-check');
        if( this_check == 'uncheck' ){
            var compare_class = jQuery('.wp-rem-btn-compare').find('i').attr('class');
            var loader_class = 'icon-spinner8';
            jQuery('.wp-rem-btn-compare').find('i').removeClass(compare_class).addClass(loader_class);
        }
    }
    var dataString = 'property_id=' + this_id + '&type_id=' + this_type_id + '&action=wp_rem_removed_compare';
    jQuery.ajax({
        type: "POST",
        url: this_ajaxurl,
        data: dataString,
        dataType: 'json',
        success: function (response) {
            if (response.type === 'success') {
                if ( jQuery('.compare-properties-'+ this_id).length > 0 ) {
                    jQuery('.compare-properties-'+ this_id).each(function(){
                        if( jQuery(this).hasClass('comparing') ){
                            var compare_class = 'icon-compare_arrows';
                            var loader_class = 'icon-spinner8';
                            jQuery(this).removeClass('comparing');
                            jQuery(this).find('i').removeClass(loader_class).addClass(compare_class);
                            jQuery(this).find('input:checkbox').removeAttr('checked');
                            jQuery(this).closest( ".property-medium" ).removeClass('active');
                            jQuery(this).closest( ".property-grid" ).removeClass('active');
                            jQuery(this).find('.option-content span').html(wp_rem_property_compare.compare_label);
                        }
                    });
                }else if( jQuery('.wp-rem-btn-compare').length > 0 ){
                    var this_check = jQuery('.wp-rem-btn-compare').attr('data-check');
                    if( this_check == 'uncheck' ){
                        var compare_class = 'icon-compare-filled2';
                        var loader_class = 'icon-spinner8';
                        jQuery('.wp-rem-btn-compare').find('i').removeClass(loader_class).addClass(compare_class);
                        jQuery('.wp-rem-btn-compare').attr('data-check', 'check');
                        jQuery('.wp-rem-btn-compare').find('span').text(wp_rem_property_compare.add_to_compare);
                    }
                }
                
                thisObj.closest('li').slideUp(500, function () {
                    thisObj.closest('li').remove();
                });
                jQuery('.chosen-compare-list .compare-response').html('');
                jQuery('.chosen-compare-list .compare-response').html(response.mark);
                jQuery('.chosen-compare-list .compare-response').fadeIn('slow');
                if ( jQuery('.chosen-compare-list .sidebar-properties-list ul li').length > 1 ) {
                    jQuery('#compare-sidebar-panel').addClass('sidebar-panel-open');
                    jQuery('#compare-sidebar-panel .sidebar-panel-btn').fadeIn('slow');
                }else{
                    jQuery('#compare-sidebar-panel').removeClass('sidebar-panel-open');
                    jQuery('#compare-sidebar-panel .sidebar-panel-btn').fadeOut('slow');
                }

                setTimeout(function() {
                    jQuery('.chosen-compare-list .compare-response').fadeOut('slow');
                    jQuery('.chosen-compare-list .compare-response').html('');
                }, 5000);
                
            }
        }
    });
});

jQuery(document).on('click', '.chosen-compare-list .sidebar-btn-holder .clear-compare-list', function () {
    var thisObj = jQuery(this);
    var this_ajaxurl = wp_rem_property_compare.ajax_url;
    wp_rem_show_loader(".chosen-compare-list .clear-compare-list", "", "button_loader", thisObj);
    
    if ( jQuery('.compare-property').length > 0 ) {
        jQuery('.compare-property').each(function(){
            if( jQuery(this).hasClass('compared') ){
                var compare_class = jQuery(this).find('i').attr('class');
                var loader_class = 'icon-spinner8';
                jQuery(this).removeClass('compared');
                jQuery(this).find('i').removeClass(compare_class).addClass(loader_class);
                jQuery(this).addClass('comparing');
            }
        });
    }else if( jQuery('.wp-rem-btn-compare').length > 0 ){
        var this_check = jQuery('.wp-rem-btn-compare').attr('data-check');
        if( this_check == 'uncheck' ){
            var compare_class = jQuery('.wp-rem-btn-compare').find('i').attr('class');
            var loader_class = 'icon-spinner8';
            jQuery('.wp-rem-btn-compare').find('i').removeClass(compare_class).addClass(loader_class);
        }
    }
    
    var dataString = 'action=wp_rem_clear_compare_list';
    $.ajax({
        type: "POST",
        url: this_ajaxurl,
        data: dataString,
        dataType: 'json',
        success: function (response) {
            if (response.type === 'success') {
                if ( jQuery('.compare-property').length > 0 ) {
                    jQuery('.compare-property').each(function(){
                        if( jQuery(this).hasClass('comparing') ){
                            var compare_class = 'icon-compare_arrows';
                            var loader_class = 'icon-spinner8';
                            jQuery(this).removeClass('comparing');
                            jQuery(this).find('i').removeClass(loader_class).addClass(compare_class);
                            jQuery(this).find('input:checkbox').removeAttr('checked');
                            jQuery(this).closest( ".property-medium" ).removeClass('active');
                            jQuery(this).closest( ".property-grid" ).removeClass('active');
                            jQuery(this).find('.option-content span').html(wp_rem_property_compare.compare_label);
                        }
                    });
                }else if( jQuery('.wp-rem-btn-compare').length > 0 ){
                    var this_check = jQuery('.wp-rem-btn-compare').attr('data-check');
                    if( this_check == 'uncheck' ){
                        var compare_class = 'icon-compare-filled2';
                        var loader_class = 'icon-spinner8';
                        jQuery('.wp-rem-btn-compare').find('i').removeClass(loader_class).addClass(compare_class);
                        jQuery('.wp-rem-btn-compare').attr('data-check', 'check');
                        jQuery('.wp-rem-btn-compare').find('span').text(wp_rem_property_compare.add_to_compare);
                    }
                }
                wp_rem_hide_button_loader('.chosen-compare-list .clear-compare-list');
                if ( jQuery('.chosen-compare-list .sidebar-properties-list ul').length > 0 ) {
                    jQuery(".chosen-compare-list .sidebar-properties-list ul").empty();
                }
                jQuery('#compare-sidebar-panel').removeClass('sidebar-panel-open');
                jQuery('#compare-sidebar-panel .sidebar-panel-btn').fadeOut('slow');
            }
        }
    });
});

jQuery( document ).ready(function() {
    if (jQuery('#compare-sidebar-panel').length > 0) {
        jQuery('.fixed-sidebar-panel.right .sidebar-panel-btn').click(function (e) {
            e.preventDefault();
            if (jQuery('#compare-sidebar-panel').hasClass('sidebar-panel-open')) {
                jQuery('#compare-sidebar-panel').removeClass('sidebar-panel-open');
            } else {
                jQuery('#compare-sidebar-panel').addClass('sidebar-panel-open');
            }
        });
        jQuery('#compare-sidebar-panel .sidebar-panel-title .sidebar-panel-btn-close').click(function (e) {
            jQuery('#compare-sidebar-panel').removeClass('sidebar-panel-open');
        });
    }
});