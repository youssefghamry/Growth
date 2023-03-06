/**  jquery document.ready functions */
var $ = jQuery;
var ajaxRequest;

function equalHeight(target) {
    target.matchHeight();
}
equalHeight($(".property-grid"));
function equalHeight(element) {
    this.thisElement = element;
    this.thisTarget = $(this.thisElement);
}
equalHeight.prototype.equalHeightActive = function () {
    this.thisTarget.matchHeight();
};
equalHeight.prototype.equalHeightActiveSubChild = function (subChild) {
    this.subTarget = this.thisTarget.find(subChild);
    this.subTarget.matchHeight();
};
equalHeight.prototype.equalHeightDisable = function () {
    $(this.thisTarget).matchHeight({remove: true});
};
equalHeight.prototype.equalHeightChildDisable = function (subChild2) {
    $(this.thisTarget).find(subChild2).matchHeight({remove: true});
};
// match height variables
var propertGridEqual,
        propertMediumModernEqual,
        propertMediumAdvanceEqual,
        propertAdvanceEqual,
        propertModernEqual,
        propertDefaultEqual,
        blogGridEqual,
        memberGridEqual,
        memberInfoEqual,
        topLocationsEqual,
        topLocationsEqual,
        propertGridModernEqual,
        propertModernv1Equal,
        propertGridModernEqualV3,
        propertGridMasnory,
        dsidxListings;
// match height variables
jQuery(document).ready(function ($) {

    jQuery(document).on("click", "#play-video", function (e) {
        "use strict";
        var id = jQuery(this).data('id');
        var videoObj = jQuery(this).closest('.video-fit-holder');
        videoObj.find("i").removeClass('icon-play_arrow');
        videoObj.find("i").addClass('icon-spinner8');
        jQuery.ajax({
            type: "POST",
            dataType: "json",
            url: wp_rem_globals.ajax_url,
            data: "action=wp_rem_detail_video_render&property_id=" + id,
            success: function (response) {
                videoObj.html(response);
                videoObj.fitVids();
            }
        });
    });


});

jQuery(document).on('change', '.property-sold-check', function () {
    "use strict";
    var _this = $(this);
    var _parent = _this.parents('.sold-property-box');
    var id = _this.attr('data-id');

    if (_this.is(':checked')) {
        var conf = confirm(wp_rem_globals.property_sold_confirm);
        if (conf == true) {
            _parent.html('<span class="property-loader"><i class="icon-spinner8"></i></span>');
            var ajax_url = wp_rem_globals.ajax_url;
            var data_vals = 'prop_id=' + id + '&action=wp_rem_property_sold_check';
            $.ajax({
                url: ajax_url,
                method: "POST",
                data: data_vals,
                dataType: "json"
            }).done(function (response) {
                if (typeof response.html !== 'undefined') {
                    _parent.html(response.html);
                }
                wp_rem_show_response(response);
            }).fail(function () {
                var resp = {
                    type: "error",
                    msg: wp_rem_globals.property_sold_action_failed
                };
                wp_rem_show_response(resp);
            });
        } else {
            _this.prop('checked', false);
        }
    }
});

jQuery(document).ready(function ($) {
    if ($(".property-medium.modern").length > 0) {
        var imageUrlFind = $(".property-medium.modern .img-holder").css("background-image").match(/url\(["']?([^()]*)["']?\)/).pop();
        if (imageUrlFind) {
            $(".property-medium.modern .img-holder").addClass("image-loaded");
        }
    }
    // property grid
    propertGridEqual = new equalHeight(".property-grid");
    propertGridEqual.equalHeightActive();
    // property grid

    // property grid Masnory
    propertGridMasnory = new equalHeight(".masnory .property-grid");
    propertGridMasnory.equalHeightDisable();
    // property grid Masnory

    // property medium modern
    propertMediumModernEqual = new equalHeight(".property-medium.modern .text-holder");
    propertMediumModernEqual.equalHeightActive();
    // property medium modern

    // property-medium Advance
    propertMediumAdvanceEqual = new equalHeight(".property-medium.advance-grid .text-holder");
    propertMediumAdvanceEqual.equalHeightActive();
    // property-medium Advance

    // property-grid Advance
    propertAdvanceEqual = new equalHeight(".property-grid.advance-grid");
    propertAdvanceEqual.equalHeightDisable();
    propertAdvanceEqual.equalHeightActiveSubChild(".text-holder");
    // property-grid Advance

    // property-grid Modern
    propertModernEqual = new equalHeight(".property-grid.modern");
    propertModernEqual.equalHeightDisable();
    propertModernEqual.equalHeightActiveSubChild(".text-holder");
    // property-grid Modern

    // property-grid Modern
    propertModernv1Equal = new equalHeight(".property-grid.modern.v1");
    propertModernv1Equal.equalHeightActiveSubChild(".post-property-footer");
    // property-grid Modern

    // property-grid default
    propertDefaultEqual = new equalHeight(".property-grid.default");
    propertDefaultEqual.equalHeightDisable();
    propertDefaultEqual.equalHeightActiveSubChild(".text-holder");
    // property-grid default

    // blog post grid
    blogGridEqual = new equalHeight(".blog.blog-grid .blog-post");
    blogGridEqual.equalHeightActive();
    // blog post grid

    // member-grid 
    memberGridEqual = new equalHeight(".member-grid .post-inner-member");
    memberGridEqual.equalHeightActive();
    // member-grid 

    // member-grid member-info
    memberInfoEqual = new equalHeight(".member-grid .member-info");
    memberInfoEqual.equalHeightActive();
    // member-grid member-info

    // top-locations
    topLocationsEqual = new equalHeight(".top-locations ul li .image-holder");
    topLocationsEqual.equalHeightActive();
    // top-locations 

    // property-grid default
    topLocationsEqual = new equalHeight(".property-grid.default .text-holder");
    topLocationsEqual.equalHeightActive();
    // property-grid default 

    // Dsidx Listings
    dsidxListings = new equalHeight("#dsidx-listings .dsidx-listing .dsidx-data");
    dsidxListings.equalHeightActive();
    // Dsidx Listings  

    // add class when image loaded
    $(".property-medium .img-holder img, .property-grid .img-holder img").one("load", function () {
        $(this).parents(".img-holder").addClass("image-loaded");
    }).each(function () {
        if (this.complete)
            $(this).load();
    });


    function wp_rem_getParameterByName(name, url) {
        if (!url)
            url = window.location.href;
        name = name.replace(/[\[\]]/g, "\\$&");
        var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
                results = regex.exec(url);
        if (!results)
            return null;
        if (!results[2])
            return '';
        return decodeURIComponent(results[2].replace(/\+/g, " "));
    }

    /*                          
     * Load Dashboard Tabs  
     */
    jQuery(document).on("click", ".user_dashboard_ajax", function () {
        "use strict";
        var actionString = jQuery(this).attr("id");
        if (typeof actionString === "undefined") {
            actionString = jQuery(this).attr("data-id");
        }
        var pageNum = jQuery(this).attr("data-pagenum");
        var data_param = jQuery(this).attr("data-param");

        if (typeof data_param !== "undefined" && data_param !== "") {
            actionString = actionString.replace('_' + data_param, '');
        }

        var filter_parameters = "";
        if (typeof pageNum !== "undefined" || typeof data_param !== "undefined") {
            filter_parameters = wp_rem_get_filter_parameters(this);
        } else {
            filter_parameters = "";
        }

        var lang_code_param = '';
        if (typeof lang_code !== "undefined" && lang_code !== '') {
            lang_code_param = "lang=" + lang_code;
        }

        var lang = wp_rem_getParameterByName('lang');
        if (typeof lang !== "undefined" && lang !== '' && lang !== null) {
            lang_code_param = "lang=" + lang;
        }

        var page_qry_append = "";
        if (typeof pageNum === "undefined") {
            if (typeof page_id_all !== "undefined" && page_id_all > 1) {
                pageNum = page_id_all;
                page_qry_append = "&page_id_all=" + page_id_all;
                page_id_all = 0;
            }
        }
        if (typeof pageNum === "undefined" || pageNum == "") {
            pageNum = "1";
        }

        if (typeof data_param !== "undefined") {
            page_qry_append += "&data_param=" + data_param;
        }
        var actionClass = jQuery(this).attr("class");
        var query_var = jQuery(this).data("queryvar");
        if (history.pushState) {
            if (query_var != undefined) {
                if (query_var != "") {
                    if (typeof lang_code_param !== "undefined" && lang_code_param !== '') {
                        var newurl = window.location.protocol + "//" + window.location.host + window.location.pathname + "?" + lang_code_param + '&' + query_var + page_qry_append;
                    } else {
                        var newurl = window.location.protocol + "//" + window.location.host + window.location.pathname + "?" + query_var + page_qry_append;
                    }
                } else {
                    if (typeof lang_code_param !== "undefined" && lang_code_param !== '') {
                        var newurl = window.location.protocol + "//" + window.location.host + window.location.pathname + '?' + lang_code_param;
                    } else {
                        var newurl = window.location.protocol + "//" + window.location.host + window.location.pathname;
                    }
                }
                window.history.pushState({
                    path: newurl
                }, "", newurl);
            }
        }

        jQuery(".user_dashboard_ajax").removeClass("active");
        jQuery(".orders-inquiries").removeClass("active");
        wp_rem_show_loader(".loader-holder");
        jQuery("#" + actionString + "." + actionClass).addClass("active");
        if (actionString == "wp_rem_member_received_orders" || actionString == "wp_rem_member_received_inquiries") {
            jQuery(".dashboard-nav .orders-inquiries").addClass("active");
            jQuery(".dashboard-nav .orders-inquiries #" + actionString + "." + actionClass).addClass("active");
        } else if (actionString == "wp_rem_member_orders" || actionString == "wp_rem_member_inquiries") {
            jQuery(".dashboard-nav .orders-inquiries").addClass("active");
        }

        if (typeof ajaxRequest != "undefined") {
            ajaxRequest.abort();
        }

        ajaxRequest = jQuery.ajax({
            type: "POST",
            url: wp_rem_globals.ajax_url,
            data: "page_id_all=" + pageNum + "&action=" + actionString + filter_parameters,
            success: function (response) {
                wp_rem_hide_loader();
                var timesRun = 0;
                setInterval(function () {
                    timesRun++;
                    if (timesRun === 1) {
                        if (jQuery(document).find("#cropContainerModal").attr("data-img-type") == "default") {
                            jQuery("#cropContainerModal .cropControls").hide();
                        }
                    }
                }, 50);
                jQuery(".user-holder").html(response);
            }
        });
    });

    /*
     * Saving Member Data
     */
    jQuery(document).on("click", "#company_profile_form", function () {
        "use strict";
        wp_rem_show_loader();
        var serializedValues = jQuery("#member_company_profile").serialize();
        jQuery.ajax({
            type: "POST",
            dataType: "json",
            url: wp_rem_globals.ajax_url,
            data: serializedValues + "&action=wp_rem_save_company_data",
            success: function (response) {
                wp_rem_show_response(response);
            }
        });
    });
});

/*
 * register pop up
 */

jQuery(document).on("click", ".no-logged-in", function () {
    $("#join-us").modal();
});

/* range slider */

jQuery(document).ready(function () {

    /*Featured Slider Start*/

    if ("" != jQuery(".featured-slider .swiper-container").length) {
        new Swiper(".featured-slider .swiper-container", {
            nextButton: ".swiper-button-next",
            prevButton: ".swiper-button-prev",
            paginationClickable: true,
            slidesPerView: 1,
            slidesPerColumn: 1,
            grabCursor: !0,
            loop: !0,
            spaceBetween: 30,
            arrow: false,
            pagination: ".swiper-pagination",
            breakpoints: {
                1024: {
                    slidesPerView: 1
                },
            }
        })
    }

});

/**
 * show alert message
 */

function show_alert_msg(msg) {
    "use strict";
    jQuery("#member-dashboard .main-cs-loader").html("");
    jQuery(".cs_alerts").html('<div class="cs-remove-msg"><i class="icon-check-circle"></i>' + msg + "</div>");
    var classes = jQuery(".cs_alerts").attr("class");
    classes = classes + " active";
    jQuery(".cs_alerts").addClass(classes);
    setTimeout(function () {
        jQuery(".cs_alerts").removeClass("active");
    }, 4e3);
}

/*HTML Functions Start*/

jQuery(document).ready(function () {

    function SidbarPanelHeight() {
        var WindowHeightForSidbarPanel = $(window).height();
        $(".user-account-nav.user-account-sidebar").css({
            "max-height": WindowHeightForSidbarPanel - 200,
            "overflow-y": "auto"
        });
    }
    if ($(".page-section.account-header").width() < 991) {
        SidbarPanelHeight();
        $(window).resize(function () {
            SidbarPanelHeight();
        });

        if (jQuery('.dashboard-sidebar-panel').length > 0) {
            $('.dashboard-sidebar-panel .dashboard-nav-btn').click(function (e) {
                e.preventDefault();
                if ($('.dashboard-sidebar-panel').hasClass('sidebar-nav-open')) {
                    $('.dashboard-sidebar-panel').removeClass('sidebar-nav-open');
                } else {
                    $('.dashboard-sidebar-panel').addClass('sidebar-nav-open');
                }
            });
        }
    }

    $('.spinner-btn > .form-control').attr("readonly", "readonly");
    // search placeholder remover.
    $(".main-search.advance .search-input input").blur(function () {
        if ($(this).val()) {
            $(this).next().hide();
        } else {
            $(this).next().show();
        }
    });
    // search placeholder remover.
    /*
     * detail page nav property feature toggler
     */

    $(".detail-nav-toggler").click(function () {
        $(this).next(".detail-nav").slideToggle().toggleClass("open");
    });

    /*Detail Nav Sticky*/

    function stickyDetailNavBar() {
        "use strict";
        var $window = $(window);
        if ($window.width() > 980) {
            if ($(".detail-nav").length) {
                var el = $(".detail-nav");
                var stickyTop = $(".detail-nav").offset().top;
                var stickyHeight = $(".detail-nav").height();
                var AdminBarHeight_ = $("#wpadminbar").height();
                if ($("#wpadminbar").length > 0) {
                    stickyTop = stickyTop - AdminBarHeight_;
                }
                $(window).scroll(function () {
                    var windowTop = $(window).scrollTop();
                    if (stickyTop < windowTop) {
                        el.css({
                            position: "fixed",
                            width: "100%",
                            "z-index": "1000",
                            top: "0"
                        });
                        $(".detail-nav").css("margin-top", AdminBarHeight_);
                        $(".property-detail").css("padding-top", stickyHeight);
                        $(".detail-nav-wrap.detail-v5 .detail-nav").addClass("detail-nav-sticky");
                    } else {
                        el.css({
                            position: "relative",
                            width: "100%",
                            "z-index": "initial",
                            top: "auto"
                        });
                        $(".detail-nav").css("margin-top", "0");
                        $(".property-detail").css("padding-top", "0");
                        $(".detail-nav-wrap.detail-v5 .detail-nav").removeClass("detail-nav-sticky");
                    }
                });
            }
        }
    }
    stickyDetailNavBar();
    $(window).resize(function () {
        stickyDetailNavBar();
    });

    /*Scroll Nav and Active li Start*/

    if (jQuery(".detail-nav-map").length != "" && jQuery(".property-act-btns-list").length === 0) {
        var wpadminbarHeight = 0;
        if ($("#wpadminbar").length) {
            wpadminbarHeight = $("#wpadminbar").height();
        }
        var lastId, topMenu = $(".detail-nav-map"),
                topMenuHeight = topMenu.outerHeight() + 15 + wpadminbarHeight,
                menuItems = topMenu.find("ul li a"),
                scrollItems = menuItems.map(function () {
                    var item = $($(this).attr("href"));
                    if (item.length) {
                        return item;
                    }
                });

        menuItems.click(function (e) {
            var href = $(this).attr("href");
            if (href.length) {
                offsetTop = href === "#" ? 0 : $(href).offset().top - topMenuHeight + 1;
                $("html, body").stop().animate({
                    scrollTop: offsetTop
                }, 650);
                e.preventDefault();
            }
        });

        $(window).scroll(function () {
            var fromTop = $(this).scrollTop() + topMenuHeight;
            var cur = scrollItems.map(function () {
                if ($(this).offset().top < fromTop)
                    return this;
            });
            cur = cur[cur.length - 1];
            var id = cur && cur.length ? cur[0].id : "";
            if (lastId !== id) {
                lastId = id;
                menuItems.parent().removeClass("active").end().filter("[href='#" + id + "']").parent().addClass("active");
            }
        });
    }

    /*Detail Nav Sticky*/

    /*Modal Backdrop Start*/

    jQuery(".main-search .search-popup-btn").click(function () {
        setTimeout(function () {
            jQuery(".modal-backdrop").appendTo(".main-search.fancy");
        }, 4);
    });
    jQuery(".detail-nav-map .enquire-holder a").click(function () {
        setTimeout(function () {
            jQuery(".modal-backdrop").appendTo(".detail-nav");
        }, 4);
    });
    jQuery(".detail-v5 .detail-nav ul li a").click(function () {
        setTimeout(function () {
            jQuery(".modal-backdrop").appendTo(".detail-nav");
        }, 4);
    });
    jQuery(".profile-info.boxed .submit-btn").click(function () {
        setTimeout(function () {
            jQuery(".modal-backdrop").appendTo(".detail-nav");
        }, 4);
    });

    /*               
     * property banner slider start
     */




    if (jQuery(".banner .property-banner-slider .swiper-container").length != "") {
        var mySwiper = new Swiper(".banner .property-banner-slider .swiper-container", {
            pagination: ".swiper-pagination",
            paginationClickable: true,
            loop: false,
            grabCursor: true,
            nextButton: ".banner .property-banner-slider .swiper-button-next",
            prevButton: ".banner .property-banner-slider .swiper-button-prev",
            spaceBetween: 30,
            autoplay: 3e3,
            effect: "fade",
            onInit: function (swiper) {
                stickyDetailNavBar();
            }
        });
    }

    /*===========Range Slider Start============
     ==========================================*/
    function addCommas(nStr)
    {
        nStr += '';
        x = nStr.split('.');
        x1 = x[0];
        x2 = x.length > 1 ? '.' + x[1] : '';
        var rgx = /(\d+)(\d{3})/;
        while (rgx.test(x1)) {
            x1 = x1.replace(rgx, '$1' + ',' + '$2');
        }
        return x1 + x2;
    }



    if ($(".range-slider").length > 0) {
        // Instantiate property price slider
        var ppValue = $(".range-slider #ex2").bootstrapSlider();
        ppValue.bootstrapSlider().on('change', function (event) {
            var a = event.value.newValue;
            $(this).parents(".range-slider").find(".slider-value").text(addCommas(a));
        });
        // Instantiate property price slider

        // Instantiate Deposit price slider
        var depValue = $(".range-slider #ex3").bootstrapSlider();
        depValue.bootstrapSlider().on('change', function (event) {
            var a = event.value.newValue;
            $(this).parents(".range-slider").find(".slider-value").text(addCommas(a));
        });
        // Instantiate Deposit price slider

        // Instantiate Annual Interest price slider

        if ($(".range-slider #ex4").length > 0) {
            var anlValue = $(".range-slider #ex4").bootstrapSlider();
            anlValue.bootstrapSlider().on('change', function (event) {
                var a = event.value.newValue;
                $(this).parents(".range-slider").find(".slider-value").text(a);
            });
        }


        // Instantiate Annual Interest price slider

        // Instantiate Year value slider
        var yearValue = $(".range-slider #ex5").bootstrapSlider();
        yearValue.bootstrapSlider().on('change', function (event) {
            var a = event.value.newValue;
            $(this).parents(".range-slider").find(".slider-value").text(a);
        });
        // Instantiate Year value slider
    }
    /*===========Range Slider Start============
     ==========================================*/


    /*Main Categories List Show Hide*/

    if (jQuery(".categories-holder .text-holder ul").length != "" && jQuery(".categories-holder .text-holder ul").data("showmore") == "yes") {
        jQuery(".categories-holder .text-holder ul").each(function () {
            var $ul = $(this),
                    $lis = $ul.find("li:gt(3)"),
                    isExpanded = $ul.hasClass("expanded");
            $lis[isExpanded ? "show" : "hide"]();
            if ($lis.length > 0) {
                $ul.append($('<li class="expand">' + (isExpanded ? "Less" : "view More") + "</li>").click(function (event) {
                    var isExpanded = $ul.hasClass("expanded");
                    event.preventDefault();
                    $(this).text(isExpanded ? "view More" : "Less");
                    $ul.toggleClass("expanded");
                    $lis.toggle(350);
                }));
            }
        });
    }

    /*Modal Tab Link Start*/

    if (jQuery(".login-popup-btn").length != "") {
        jQuery(".login-popup-btn").click(function (e) {
            jQuery(".cs-login-switch").click();
            var tab = e.target.hash;
            var data_id = jQuery(this).data("id");
            jQuery(".tab-content .popupdiv" + data_id).removeClass("in active");
            jQuery('a[href="' + tab + '"]').tab("show");
            jQuery(tab).addClass("in active");
        });
    }

    /*Modal Tab Link End*/

    $(document).on("click", ".reviews-sortby li.reviews-sortby-active", function () {
        setTimeout(function () {
            jQuery("#reviews-overlay").remove();
        }, 4);
    });

    jQuery(".reviews-sortby > li").on("click", function () {
        jQuery("#reviews-overlay").remove();
        setTimeout(function () {
            jQuery(".reviews-sortby > li").toggleClass("reviews-sortby-active");
        }, 3);
        jQuery(".reviews-sortby > li").siblings();
        jQuery(".reviews-sortby > li").siblings().removeClass("reviews-sortby-active");
        jQuery(".reviews-sortby").append("<div id='reviews-overlay' class='reviews-overlay'></div>");
    });

    jQuery(".input-reviews > .radio-field label").on("click", function () {
        jQuery(this).parent().toggleClass("active");
        jQuery(this).parent().siblings();
        jQuery(this).parent().siblings().removeClass("active");
        /*replace inner Html*/
        var radio_field_active = jQuery(this).html();
        jQuery(".active-sort").html(radio_field_active);
        jQuery(".reviews-sortby > li").removeClass("reviews-sortby-active");
        setTimeout(function () {
            jQuery("#reviews-overlay").remove();
        }, 400);
    });

    $(document).on("click", "#reviews-overlay", function () {
        "use strict";
        jQuery(this).closest(".reviews-overlay").remove();
        jQuery(".reviews-sortby > li").removeClass("reviews-sortby-active");
    });

    /* Spinner Btn Start*/

    $(".spinner .btn:last-of-type").on("click", function () {
        $(".spinner input").val(parseInt($(".spinner input").val(), 10) + 1);
    });

    $(".spinner .btn:first-of-type").on("click", function () {
        var val = parseInt($(".spinner input").val(), 10);
        if (val < 1) {
            return;
        }
        $(".spinner input").val(val - 1);
    });

    $(".spinner2 .btn:last-of-type").on("click", function () {
        $(".spinner2 input").val(parseInt($(".spinner2 input").val(), 10) + 1);
    });

    $(".spinner2 .btn:first-of-type").on("click", function () {
        $(".spinner2 input").val(parseInt($(".spinner2 input").val(), 10) - 1);
    });

    $(".spinner3 .btn:last-of-type").on("click", function () {
        $(".spinner3 input").val(parseInt($(".spinner3 input").val(), 10) + 1);
    });

    $(".spinner3 .btn:first-of-type").on("click", function () {
        $(".spinner3 input").val(parseInt($(".spinner3 input").val(), 10) - 1);
    });

    /* Spinner Btn End*/


    jQuery(".user-dashboard-menu > ul > li.user-dashboard-menu-children > a").on("click", function (e) {
        e.preventDefault();
        jQuery(this).parent().toggleClass("menu-open");
        jQuery(this).parent().siblings().removeClass("menu-open");
        setTimeout(function () {
            jQuery(".user-dashboard-menu > ul > li.user-dashboard-menu-children > a").addClass("open-overlay");
        }, 2);
        jQuery(".main-header .login-option,.main-header .login-area").append("<div class='location-overlay'></div>");
        jQuery(".user-dashboard-menu > ul > li > ul").append("<i class='icon-cross close-menu-location'></i>");
    });

    jQuery(document).on("click", ".user-dashboard-menu > ul > li.user-dashboard-menu-children > a.open-overlay", function () {
        jQuery(".location-overlay").remove();
        jQuery(".close-menu-location").remove();
        setTimeout(function () {
            jQuery(".user-dashboard-menu > ul > li.user-dashboard-menu-children > a").removeClass("open-overlay");
        }, 2);
    });

    $(".main-header .user-dashboard-menu li.user-dashboard-menu-children ul").bind("clickoutside", function (event) {
        $(this).hide();
    });

    jQuery(document).on("click", ".location-overlay", function () {
        "use strict";
        jQuery(this).closest(".location-overlay").remove();
        jQuery(".close-menu-location").remove();
        jQuery(".user-dashboard-menu > ul > li.user-dashboard-menu-children").removeClass("menu-open");
        jQuery(".user-dashboard-menu > ul > li.user-dashboard-menu-children > a").removeClass("open-overlay");
    });

    jQuery(document).on("click", ".close-menu-location", function () {
        jQuery(this).closest(".close-menu-location").remove();
        jQuery(".location-overlay").remove();
        jQuery(".user-dashboard-menu > ul > li.user-dashboard-menu-children").removeClass("menu-open");
        jQuery(".user-dashboard-menu > ul > li.user-dashboard-menu-children > a").removeClass("open-overlay");
    });

    /*cs-calendar-combo input Start*/
    jQuery(document).ready(function () {
        if (jQuery(".cs-calendar-from input").length != "") {
            jQuery(".cs-calendar-from input").datetimepicker({
                timepicker: false,
                format: "Y/m/d",
            });
        }

        if (jQuery(".cs-calendar-to input").length != "") {
            jQuery(".cs-calendar-to input").datetimepicker({
                timepicker: false,
                format: "Y/m/d",
            });
        }
    });
    /*Flickr Gallery Slider Functions Start*/



    if (jQuery(".flickr-gallery-slider .swiper-container").length != '') {



        var swiper = new Swiper('.flickr-gallery-slider .swiper-container', {
            nextButton: '.flickr-gallery-slider .swiper-button-next',
            prevButton: '.flickr-gallery-slider .swiper-button-prev',
            paginationClickable: true,
            spaceBetween: 0,
            centeredSlides: true,
            autoplay: 2500,
            autoplayDisableOnInteraction: false,
            loop: false,
        });

    }

    /*Flickr Gallery Slider Functions End*/
    /*prettyPhoto Start*/

    if (jQuery(".photo-gallery.gallery").length != "") {
        jQuery("area[data-rel^='prettyPhoto']").prettyPhoto();
        jQuery(".gallery:first a[data-rel^='prettyPhoto']").prettyPhoto({
            animation_speed: "normal",
            theme: "light_square",
            slideshow: 5e3,
            deeplinking: true,
            autoplay_slideshow: true
        });

        jQuery(".gallery:gt(0) a[data-rel^='prettyPhoto']").prettyPhoto({
            animation_speed: "fast",
            slideshow: 5e4,
            deeplinking: false,
            hideflash: true
        });

        jQuery("#custom_content a[data-rel^='prettyPhoto']:first").prettyPhoto({
            custom_markup: '<div id="map_canvas"></div>',
            changepicturecallback: function () {
                initialize();
            }
        });

        jQuery("#custom_content a[data-rel^='prettyPhoto']:last").prettyPhoto({
            custom_markup: '<div id="bsap_1259344" class="bsarocks bsap_d49a0984d0f377271ccbf01a33f2b6d6"></div><div id="bsap_1237859" class="bsarocks bsap_d49a0984d0f377271ccbf01a33f2b6d6"></div><div id="bsap_1251710" class="bsarocks bsap_d49a0984d0f377271ccbf01a33f2b6d6"></div>',
            changepicturecallback: function () {
                _bsap.exec();
            }
        });
    }

    /*prettyPhoto End*/

    /* Gallery Counter Start*/

    if (jQuery(".photo-gallery .gallery-counter li").length != "") {
        count = jQuery(".photo-gallery .gallery-counter li").size();
        if (count > 7) {
            jQuery(".photo-gallery .gallery-counter  li:gt(6) .img-holder figure").append("<figcaption><span></span></figcaption>");
            jQuery(".photo-gallery .gallery-counter  li figure figcaption span").append('<em class="counter"></em>');
            jQuery(".photo-gallery .gallery-counter  li figure figcaption span .counter").html("<i class='icon-plus'></i>" + count);
        } else {
            jQuery('<em class="counter"></em>').remove();
        }
        jQuery(".photo-gallery .gallery-counter  li:gt(7)").hide();
    }

});

/*
 * Framework JS
 */
jQuery(document).on("click", ".icon-circle-with-cross", function () {
    "use strict";
    jQuery(this).parents("li").remove();
    var attachment_id = jQuery(this).attr("data-attachment_id");
    var all_attachments = jQuery("#wp_rem_member_gallery_attathcments").val();
    var new_attachemnts = all_attachments.replace(attachment_id, "");
    jQuery("#wp_rem_member_gallery_attathcments").val(new_attachemnts);
});

var size_li = jQuery("#collapseseven .cs-checkbox-list li").size();
x = 5;
jQuery("#collapseseven .cs-checkbox-list li:lt(" + x + ")").show(200);

jQuery(document).on("click", ".reset-results", function () {
    "use strict";
    jQuery(".search-results").fadeOut(200);
});

jQuery(document).on("click", "#pop-close1", function () {
    "use strict";
    jQuery("#popup1").addClass("popup-open");
});

jQuery(document).on("click", "#close1", function () {
    "use strict";
    jQuery("#popup1").removeClass("popup-open");
});

jQuery(document).on("click", "#pop-close", function () {
    "use strict";
    jQuery("#popup").addClass("popup-open");
});

jQuery(document).on("click", "#close", function () {
    "use strict";
    jQuery("#popup").removeClass("popup-open");
});

if (jQuery(".selectpicker").length != "") {
    jQuery(".selectpicker").selectpicker({
        size: 5
    });
}

jQuery(".closeall").click(function () {
    jQuery(".openall").addClass("show");
    jQuery(".filters-options .panel-collapse.in").collapse("hide");
});

jQuery(".openall").click(function () {
    jQuery(".openall").removeClass("show");
    jQuery('.filters-options .panel-collapse:not(".in")').collapse("show");
});

jQuery(".orders-list li a.orders-detail").on("click", function (e) {
    "use strict";
    e.preventDefault();
    jQuery(this).parent().addClass("open").find(".orders-list .info-holder");
    jQuery(this).parent().siblings().find(".orders-list .info-holder");
});

jQuery(".orders-list li a.close").on("click", function (e) {
    e.preventDefault();
    jQuery(".orders-list > li.open").removeClass("open");
});

/* On Scroll Fixed Map Start*/

if (jQuery(".property-map-holder.map-right .detail-map").length != "") {
    "use strict";
    var Header_height = jQuery("header#header").height();
    if (jQuery(".property-map-holder.map-right .detail-map").length != "") {
        jQuery("header#header").addClass("fixed-header");
        jQuery(".property-map-holder.map-right .detail-map").addClass("fixed-item").css("padding-top", Header_height);
    } else {
        jQuery(".property-map-holder.map-right .detail-map").removeClass("fixed-item").css("padding-top", "auto");
        jQuery("header#header").removeClass("fixed-header");
    }
}

/* Close Effects Start */

jQuery(".clickable").on("click", function () {
    "use strict";
    var effect = jQuery(this).data("effect");
    jQuery(this).closest(".page-sidebar")[effect]();
});

jQuery(".filter-show").on("click", function () {
    jQuery(".page-sidebar").fadeIn();
});

/*
 * Croppic Block
 */

jQuery(document).on("click", ".cropControls .cropControlRemoveCroppedImage", function () {
    "use strict";
    jQuery("#cropContainerModal .cropControls").hide();
    var img_src = jQuery("#cropContainerModal").attr("data-def-img");
    var timesRun = 0;
    setInterval(function () {
        timesRun++;
        if (timesRun === 1) {
            jQuery("#cropContainerModal").find("figure a img").attr("src", img_src);
        }
    }, 50);
});

jQuery(document).on("click", ".upload-file", function () {
    jQuery(".cropControlUpload").click();
});

jQuery(document).on("click", ".cropControlRemoveCroppedImage", function () {
    "use strict";
    jQuery("#cropContainerModal img").attr("src", "");
    jQuery("#wp_rem_member_profile_image").val("");
});

/*
 * Location Block
 */

jQuery(document).on("click", ".loc-icon-holder", function () {
    "use strict";
    var thisObj = jQuery(this);
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function (position) {
            var pos = {
                lat: position.coords.latitude,
                lng: position.coords.longitude
            };
            var dataString = "lat=" + pos.lat + "&lng=" + pos.lng + "&action=wp_rem_get_geolocation";
            jQuery.ajax({
                type: "POST",
                url: wp_rem_globals.ajax_url,
                data: dataString,
                dataType: "json",
                success: function (response) {
                    console.log(response);
                    thisObj.next("input").val(response.address);
                }
            });
        });
    }
});

/*
 * Opening Hours Block
 */

/*Delivery Timing Dropdown Functions Start*/

jQuery(document).ready(function ($) {
    $(".field-select-holder .active").on("click", function () {
        "use strict";
        $(this).next("ul").slideToggle();
        $(this).parents("ul").toggleClass("open");
        $(".dropdown-select > li > a").on("click", function (e) {
            e.preventDefault();
            var anchorText = $(this).text();
            $(".field-select-holder .active small").text(anchorText);
            $(".field-select-holder .active").next("ul").slideUp();
            $(this).parents("ul").removeClass("open");
        });
    });

    $(document).mouseup(function (e) {
        var container = $(".field-select-holder > ul");
        if (!container.is(e.target) && container.has(e.target).length === 0) {
            $(".field-select-holder .active").next("ul").slideUp();
            $(".field-select-holder > ul").removeClass("open");
        }
    });

    $(".field-select-holder ul li ul.delivery-dropdown li").click(function () {
        $(".field-select-holder .active").next("ul").slideUp();
        $(".field-select-holder > ul").removeClass("open");
    });

    jQuery(document).on("click", "#member-opening-hours-btn", function () {
        "use strict";
        var thisObj = jQuery(this);
        wp_rem_show_loader("#member-opening-hours-btn", "", "button_loader", thisObj);
        var serializedValues = jQuery("#member-opening-hours-form").serialize();
        jQuery.ajax({
            type: "POST",
            dataType: "json",
            url: wp_rem_globals.ajax_url,
            data: serializedValues + "&action=wp_rem_member_opening_hours_submission",
            success: function (response) {
                wp_rem_show_response(response, "", thisObj);
            }
        });
    });
});


function wp_rem_top_search(counter) {
    "use strict";
    var thisObj = jQuery(".search-btn-loader-" + counter);
    wp_rem_show_loader(".search-btn-loader-" + counter, "", "button_loader", thisObj);
    jQuery("#top-search-form-" + counter).find("input, textarea, select").each(function (_, inp) {
        if (jQuery(inp).val() === "" || jQuery(inp).val() === null)
            inp.disabled = true;
    });
}

/*
 * chosen selection box
 */

function chosen_selectionbox() {
    "use strict";
    if (jQuery(".chosen-select, .chosen-select-deselect, .chosen-select-no-single, .chosen-select-no-results, .chosen-select-width").length != "") {
        var config = {
            ".chosen-select": {
                width: "100%"
            },
            ".chosen-select-deselect": {
                allow_single_deselect: true
            },
            ".chosen-select-no-single": {
                disable_search_threshold: 10,
                width: "100%"
            },
            ".chosen-select-no-results": {
                no_results_text: "Oops, nothing found!"

            },
            ".chosen-select-width": {
                width: "95%"
            }
        };
        for (var selector in config) {
            jQuery(selector).chosen(config[selector]);
        }
    }
}
// Chosen touch support.
if (jQuery('.chosen-container').length > 0) {
    jQuery('.chosen-container').on('touchstart', function (e) {
        // e.stopPropagation();
        // e.preventDefault();
        // Trigger the mousedown event.
        jQuery(this).trigger('mousedown');
    });
}


jQuery(document).on('click', '.chosen-container', function (event) {
    event.preventDefault();
    event.stopPropagation();
    jQuery(".search-btn").toggleClass('disable-search');
    jQuery(".advanced-btn").toggleClass('disable-search');
});
jQuery(document).click(function (e) {
    if (jQuery(".disable-search").length > 0) {
        jQuery(".disable-search").removeClass("disable-search");
    }
});



function wp_rem_multicap_all_functions() {
    "use strict";
    var all_elements = jQuery(".g-recaptcha");
    for (var i = 0; i < all_elements.length; i++) {
        var id = all_elements[i].getAttribute("id");
        var site_key = all_elements[i].getAttribute("data-sitekey");
        if (null != id) {
            grecaptcha.render(id, {
                sitekey: site_key,
                callback: function (resp) {
                    jQuery.data(document.body, "recaptcha", resp);
                }
            });
        }
    }
}

/*
 * captcha reload
 */

function captcha_reload(admin_url, captcha_id) {
    "use strict";
    jQuery("#" + captcha_id + "_div").html('');
    var dataString = "&action=wp_rem_reload_captcha_form&captcha_id=" + captcha_id;
    jQuery.ajax({
        type: "POST",
        url: admin_url,
        data: dataString,
        dataType: "html",
        success: function (data) {
            jQuery("body").append(data);
        }
    });
}

/*More Less Text Start*/

var showChar = 490;

// How many characters are shown by default

var ellipsestext = "...";
var moretext = "Read more >>";
var lesstext = "Read Less >>";

/* counter more Start */

jQuery(".more").each(function () {
    var content = jQuery(this).text();
    var showcharnew = $(this).attr("data-count");
    if (showcharnew != undefined && showcharnew != "") {
        showChar = showcharnew;
    }

    if (content.length > showChar) {
        var c = content.substr(0, showChar);
        var h = content.substr(showChar, content.length - showChar);
        var html = c + '<span class="moreellipses">' + ellipsestext + ' </span><span class="morecontent"><span>' + h + '</span>  <a href="" class="readmore-text">' + moretext + "</a></span>";
        jQuery(this).html(html);
    }
});
/*Read More Text Start*/

jQuery(".readmore-text").click(function () {
    "use strict";
    if (jQuery(this).hasClass("less")) {
        jQuery(this).removeClass("less");
        jQuery(this).html(moretext);
    } else {
        jQuery(this).addClass("less");
        jQuery(this).html(lesstext);
    }
    jQuery(this).parent().prev().toggle();
    jQuery(this).prev().toggle();
    return false;
});

/*Upload Gallery Start*/

if (jQuery(".upload-gallery").length != "") {
    function dragStart(ev) {
        ev.dataTransfer.effectAllowed = "move";
        ev.dataTransfer.setData("Text", ev.target.getAttribute("id"));
        ev.dataTransfer.setDragImage(ev.target, 100, 100);
        return true;
    }
}

if (jQuery(".upload-gallery").length != "") {
    function dragEnter(ev) {
        event.preventDefault();
        ev.css({
            margin: "0 0 0 15px"
        });
        return true;
    }
}

if (jQuery(".upload-gallery").length != "") {
    function dragOver(ev) {
        event.preventDefault();
        ev.css({
            margin: "0 0 0 15px"
        });
    }
}

if (jQuery(".upload-gallery").length != "") {
    function dragDrop(ev) {
        var data = ev.dataTransfer.getData("Text");
        ev.target.appendChild(document.getElementById(data));
        ev.stopPropagation();
        return false;
    }
}

if (jQuery(".files").length != "") {
    $(".files").sortable({
        revert: true
    });
}

jQuery(document).ready(function ($) {
    "use strict";
    if ($("body").hasClass("rtl") == true) {
        jQuery('[data-toggle="popover"]').popover({
            placement: 'right'
        });
    } else {
        jQuery('[data-toggle="popover"]').popover();
    }
});


var default_loader = jQuery(".wp_rem_loader").html();
var default_button_loader = jQuery(".wp-rem-button-loader").html();

/*
 * Loader Show Function
 */
function wp_rem_show_loader(loading_element, loader_data, loader_style, thisObj) {
    var loader_div = ".wp_rem_loader";
    if (loader_style == "button_loader") {
        loader_div = ".wp-rem-button-loader";
        if (thisObj != "undefined" && thisObj != "") {
            thisObj.addClass("wp-rem-processing");
        }
    }
    if (typeof loader_data !== "undefined" && loader_data != "" && typeof jQuery(loader_div) !== "undefined") {
        jQuery(loader_div).html(loader_data);
    }
    if (typeof loading_element !== "undefined" && loading_element != "" && typeof jQuery(loader_div) !== "undefined") {
        jQuery(loader_div).appendTo(loading_element);
    }
    jQuery(loader_div).css({
        display: "flex",
        display: "-webkit-box",
                display: "-moz-box",
                display: "-ms-flexbox",
                display: "-webkit-flex"
    });
}

/*
 * Loader Show Response Function
 */
function wp_rem_show_response(loader_data, loading_element, thisObj, clickTriger) {

    if (thisObj != "undefined" && thisObj != "" && thisObj != undefined) {
        thisObj.removeClass("wp-rem-processing");
    }
    jQuery(".wp-rem-button-loader").appendTo("#footer");
    jQuery(".wp_rem_loader").hide();
    jQuery(".wp-rem-button-loader").hide();
    if (clickTriger != "undefined" && clickTriger != "" && clickTriger != undefined) {
        jQuery(clickTriger).click();
    }
    jQuery("#growls").removeClass("wp_rem_element_growl");
    jQuery("#growls").find(".growl").remove();
    if (loader_data != "undefined" && loader_data != "") {
        if (loader_data.type != "undefined" && loader_data.type == "error") {
            var error_message = jQuery.growl.error({
                message: loader_data.msg
            });
            if (loading_element != "undefined" && loading_element != undefined && loading_element != "") {
                jQuery("#growls").prependTo(loading_element);
                jQuery("#growls").addClass("wp_rem_element_growl");
                setTimeout(function () {
                    jQuery(".growl-close").trigger("click");
                }, 5e3);
            }
        } else if (loader_data.type != "undefined" && loader_data.type == "success") {
            var success_message = jQuery.growl.success({
                message: loader_data.msg
            });
            if (loading_element != "undefined" && loading_element != undefined && loading_element != "") {
                jQuery("#growls").prependTo(loading_element);
                jQuery("#growls").addClass("wp_rem_element_growl");
                setTimeout(function () {
                    jQuery(".growl-close").trigger("click");
                }, 5e3);
            }
        }
    }
}

/*
 * Loader Hide Function  
 */
function wp_rem_hide_loader() {
    jQuery(".wp_rem_loader").hide();
    jQuery(".wp_rem_loader").html(default_loader);
}

/*
 * Hide Button loader
 */

function wp_rem_hide_button_loader(processing_div) {
    "use strict";
    if (processing_div != "undefined" && processing_div != "" && processing_div != undefined) {
        jQuery(processing_div).removeClass("wp-rem-processing");
    }
    jQuery(".wp-rem-button-loader").hide();
    jQuery(".wp-rem-button-loader").html(default_button_loader);
}


jQuery(document).ajaxComplete(function () {
    if (jQuery("body").hasClass("rtl") == true) {
        jQuery('[data-toggle="popover"]').popover({
            placement: 'right'
        });
    } else {
        jQuery('[data-toggle="popover"]').popover();
    }
    // property grid
    propertGridEqual = new equalHeight(".property-grid");
    propertGridEqual.equalHeightActive();
    // property grid

    // property grid Masnory
    propertGridMasnory = new equalHeight(".masnory .property-grid");
    propertGridMasnory.equalHeightDisable();
    // property grid Masnory

    // property medium modern
    propertMediumModernEqual = new equalHeight(".property-medium.modern .text-holder");
    propertMediumModernEqual.equalHeightActive();
    // property medium modern

    // property-medium Advance
    propertMediumAdvanceEqual = new equalHeight(".property-medium.advance-grid .text-holder");
    propertMediumAdvanceEqual.equalHeightActive();
    // property-medium Advance

    // property-grid Advance
    propertAdvanceEqual = new equalHeight(".property-grid.advance-grid");
    propertAdvanceEqual.equalHeightDisable();
    propertAdvanceEqual.equalHeightActiveSubChild(".text-holder");
    // property-grid Advance

    // property-grid Modern
    propertModernEqual = new equalHeight(".property-grid.modern");
    propertModernEqual.equalHeightDisable();
    propertModernEqual.equalHeightActiveSubChild(".text-holder");
    // property-grid Modern

    // property-grid Modern
    propertModernv1Equal = new equalHeight(".property-grid.modern.v1");
    propertModernv1Equal.equalHeightActiveSubChild(".post-property-footer");
    // property-grid Modern

    // property-grid default
    propertDefaultEqual = new equalHeight(".property-grid.default");
    propertDefaultEqual.equalHeightDisable();
    propertDefaultEqual.equalHeightActiveSubChild(".text-holder");
    // property-grid default

    // blog post grid
    blogGridEqual = new equalHeight(".blog.blog-grid .blog-post");
    blogGridEqual.equalHeightActive();
    // blog post grid

    // member-grid 
    memberGridEqual = new equalHeight(".member-grid .post-inner-member");
    memberGridEqual.equalHeightActive();
    // member-grid 

    // member-grid member-info
    memberInfoEqual = new equalHeight(".member-grid .member-info");
    memberInfoEqual.equalHeightActive();
    // member-grid member-info

    // top-locations
    topLocationsEqual = new equalHeight(".top-locations ul li .image-holder");
    topLocationsEqual.equalHeightActive();
    // top-locations 

    // property-grid default
    topLocationsEqual = new equalHeight(".property-grid.default .text-holder");
    topLocationsEqual.equalHeightActive();
    // property-grid default

    // Dsidx Listings
    dsidxListings = new equalHeight("#dsidx-listings .dsidx-listing .dsidx-data");
    dsidxListings.equalHeightActive();
    // Dsidx Listings  



    jQuery(document).on("click", ".wp-rem-open-register-tab", function (e) {
        e.stopImmediatePropagation();
        jQuery(".wp-rem-open-register-button").click();
    });

    jQuery(document).on("click", ".wp-rem-open-signin-tab", function (e) {
        e.stopImmediatePropagation();
        jQuery(".wp-rem-open-signin-button").click();
    });
});



jQuery(document).on("click", ".rem-pretty-photos", function (e) {
    "use strict";

//   jQuery(this).off("click");
//    
//    jQuery(this).addClass('disable-click');

    var id = jQuery(this).data('id');
    var rand_id = jQuery(this).data('rand');
    //echo rand_id;
    var galleryObj = jQuery(this).closest('#galley-img' + rand_id + '');
    jQuery(this).closest('#galley-img' + rand_id + '').find("i").removeClass('icon-camera6');
    jQuery(this).closest('#galley-img' + rand_id + '').find("i").addClass('icon-spinner8');

    var is_class_exists = jQuery(this).parent().hasClass('disable-click');

    if (!is_class_exists) {
        jQuery.ajax({
            type: "POST",
            dataType: "json",
            url: wp_rem_globals.ajax_url,
            data: "action=wp_rem_gallery_photo_render&property_id=" + id + '&property_rand="' + rand_id,
            success: function (response) {
                galleryObj.html(response);
                jQuery("#galley-img" + rand_id + " a[data-rel^='prettyPhoto']").prettyPhoto();
                jQuery(".btnnn" + rand_id + "").trigger("click");

            }
        });
    }

    jQuery(this).parent().addClass('disable-click');

});

$(document).on('click', '.first-big-image a, .all-remian-images a', function () {

    $('.first-big-image a, .all-remian-images a').removeClass('active');
    $(this).addClass('active');
    $('#gallery-expander').trigger('click');
});

$(document).on('click', '#gallery-expander', function () {
    "use strict";

    var _this = $(this);
    var property_id = _this.data('id');
    var this_apender = $('#gallery-appender-' + property_id);
    var this_loader = _this.find('.loader-img');

    var targetImg = '';
    if ($('.first-big-image').find('a.active').length > 0) {
        targetImg = $('.first-big-image').find('a.active').attr('data-id');
    } else if ($('.all-remian-images').find('a.active').length > 0) {
        targetImg = $('.all-remian-images').find('a.active').attr('data-id');
    }
    $('.first-big-image a, .all-remian-images a').removeClass('active');

    if (this_apender.find("a").length > 0) {
        if (targetImg != '') {
            this_apender.find("a#" + targetImg).trigger('click');
        } else {
            this_apender.find("a:first").trigger('click');
        }
    } else {

        this_loader.html('<i class="icon-spinner8 icon-spin"></i>');
        var is_class_exists = jQuery(this).parent().hasClass('disable-click');
        if (!is_class_exists) {

            $.ajax({
                url: wp_rem_globals.ajax_url,
                method: "POST",
                data: {
                    property_id: property_id,
                    action: 'property_detail_gallery_imgs_load'
                },
                dataType: "json"
            }).done(function (response) {
                this_apender.html(response.html);
                this_apender.find("a[data-rel^='prettyPhoto']").prettyPhoto();
                if (targetImg != '') {
                    this_apender.find("a#" + targetImg).trigger('click');
                } else {
                    this_apender.find("a:first").trigger('click');
                }
                this_loader.html('');
            }).fail(function () {
                this_loader.html('');
            });

        }
        jQuery(this).parent().addClass('disable-click');

    }
});


jQuery(document).on("click", ".wp-rem-open-register-tab", function (e) {
    e.stopImmediatePropagation();
    jQuery(".wp-rem-open-register-button").click();
});

jQuery(document).on("click", ".wp-rem-open-signin-tab", function (e) {
    e.stopImmediatePropagation();
    jQuery(".wp-rem-open-signin-button").click();
});

jQuery(document).on("click", ".delete-hidden-property", function () {
    var thisObj = jQuery(this);
    var property_id = thisObj.data('id');
    var action_type = thisObj.data('type');
    var delete_icon_class = thisObj.find("i").attr('class');
    var loader_class = 'icon-spinner8 icon-spin';
    var dataString = 'property_id=' + property_id + '&action=wp_rem_removed_hidden_properties';
    jQuery('#id_confrmdiv').addClass(action_type);
    jQuery('#id_confrmdiv').show();
    jQuery('.' + action_type + ' #id_truebtn').click(function () {
        thisObj.find('i').removeClass(delete_icon_class);
        thisObj.find('i').addClass(loader_class);
        jQuery.ajax({
            type: "POST",
            url: wp_rem_globals.ajax_url,
            data: dataString,
            dataType: "json",
            success: function (response) {
                thisObj.find('i').removeClass(loader_class).addClass(delete_icon_class);
                if (response.status == true) {

                    thisObj.closest('li').hide('slow', function () {
                        thisObj.closest('li').remove();
                    });

                    var msg_obj = {msg: response.message, type: 'success'};
                    wp_rem_show_response(msg_obj);
                }
            }
        });
        jQuery('#id_confrmdiv').hide();
        jQuery('#id_confrmdiv').removeClass(action_type);
        return false;
    });
    jQuery('#id_falsebtn').click(function () {
        jQuery('#id_confrmdiv').hide();
        jQuery('#id_confrmdiv').removeClass(action_type);
        return false;
    });
    return false;
});

jQuery(document).on("click", ".delete-prop-notes", function () {
    var thisObj = jQuery(this);
    var property_id = thisObj.data('id');
    var action_type = thisObj.data('type');
    var delete_icon_class = thisObj.find("i").attr('class');
    var loader_class = 'icon-spinner8 icon-spin';
    var dataString = 'property_id=' + property_id + '&action=wp_rem_removed_property_notes';
    jQuery('#id_confrmdiv').addClass(action_type);
    jQuery('#id_confrmdiv').show();
    jQuery('.' + action_type + ' #id_truebtn').click(function () {
        thisObj.find('i').removeClass(delete_icon_class);
        thisObj.find('i').addClass(loader_class);
        jQuery.ajax({
            type: "POST",
            url: wp_rem_globals.ajax_url,
            data: dataString,
            dataType: "json",
            success: function (response) {
                thisObj.find('i').removeClass(loader_class).addClass(delete_icon_class);
                if (response.status == true) {

                    thisObj.closest('li').hide('slow', function () {
                        thisObj.closest('li').remove();
                    });

                    var msg_obj = {msg: response.message, type: 'success'};
                    wp_rem_show_response(msg_obj);
                }
            }
        });
        jQuery('#id_confrmdiv').hide();
        jQuery('#id_confrmdiv').removeClass(action_type);
        return false;
    });
    jQuery('#id_falsebtn').click(function () {
        jQuery('#id_confrmdiv').hide();
        jQuery('#id_confrmdiv').removeClass(action_type);
        return false;
    });
    return false;
});


jQuery(document).on('click', '.property-visibility .property-visibility-update', function () {
    "use strict";
    var thisObj = jQuery(this);
    var property_id = jQuery(this).attr('data-id');
    var visibility_status = jQuery(this).attr('title');
    jQuery.ajax({
        type: "POST",
        url: wp_rem_globals.ajax_url,
        data: 'action=wp_rem_update_property_visibility&property_id=' + property_id + '&visibility_status=' + visibility_status,
        dataType: 'json',
        success: function (response) {
            wp_rem_show_response(response);
            if (jQuery('[data-toggle="tooltip"]').length != '') {
                jQuery('.property-visibility .property-visibility-update').tooltip('hide');
            }
            if (typeof response.icon !== 'undefined' && response.icon != '') {
                var icon_class = thisObj.parent().find('i').attr('class');
                thisObj.parent().find('i').removeClass(icon_class).addClass(response.icon);
            }
            if (typeof response.label !== 'undefined' && response.label != '') {
                thisObj.attr('data-original-title', response.label);
            }
            if (typeof response.value !== 'undefined' && response.value === 'public') {
                thisObj.parent().find('i').css("color", "green");
            } else {
                thisObj.parent().find('i').css("color", "red");
            }
        }
    });
});

// Chosen Container Backdrop Function Style Start
var chosenContainerBackdrop = function () {
    return {
        //main function to initiate the module of Chosen Container
        init: function () {
            jQuery(document).on("click", ".chosen-container", function () {
                var count = 0;
                var chosen_container = jQuery(".chosen-container");
                var get_chosen_drop_width = jQuery(this).parent().width();
                if (jQuery(".chosen-search").length > 0) {
                    var get_chosen_search_height = jQuery(this).find(".chosen-search").innerHeight();
                }
                jQuery("body").find(".chosen-has-backdrop").removeClass("chosen-has-backdrop").css({
                    "position": "",
                    "z-index": ""
                });
                if (chosen_container.hasClass("chosen-with-drop")) {
                    jQuery(".chosen-backdrop").remove();
                    jQuery(this).parent().append("<div class='chosen-backdrop'></div>");
                    jQuery(this).parent().addClass("chosen-has-backdrop");
                    jQuery(".chosen-has-backdrop").css({
                        "position": "relative",
                        "z-index": "101"
                    });
                    var get_chosen_drop_height = [];
                    jQuery(this).find("li").each(function (index) {
                        get_chosen_drop_height[index] = jQuery(this).innerHeight();
                        count = count + get_chosen_drop_height[index];
                    });
                    jQuery(".chosen-backdrop").css({
                        "height": count,
                        "max-height": 240 + get_chosen_search_height,
                        "position": "absolute",
                        "left": 0,
                        "top": "100%",
                        "width": get_chosen_drop_width,
                        "z-index": "2",
                    });
                } else {
                    jQuery("body").find(".chosen-has-backdrop").removeClass("chosen-has-backdrop").css({
                        "position": "",
                        "z-index": ""
                    });
                    jQuery(".chosen-backdrop").remove();
                }
            });
            jQuery(document).click(function (event) {
                if (!(jQuery(event.target).closest(".chosen-container").length)) {
                    jQuery("body").find(".chosen-has-backdrop").removeClass("chosen-has-backdrop").css({
                        "position": "",
                        "z-index": ""
                    });
                    jQuery(".chosen-backdrop").remove();
                }
            });
        }

    };
}();

jQuery(document).ready(function () {
    chosenContainerBackdrop.init();
});
// Chosen Container Backdrop Function Style End

/*
 * Google Login Signin
 */


function onLoadGoogleCallback() {
    gapi.load('auth2', function () {
        auth2 = gapi.auth2.init({
            client_id: '884989793352-o03to6urj4cqkalhstg3shhi9ot6rnd9.apps.googleusercontent.com',
            cookiepolicy: 'single_host_origin',
            scope: 'profile email'
        });

        auth2.attachClickHandler(element, {},
                function (googleUser) {
                    var profile = googleUser.getBasicProfile();
                    var dataString = 'id=' + profile.getId() + '&full_name=' + profile.getName() + '&given_name=' + profile.getGivenName() + '&family_name=' + profile.getFamilyName() + '&image_url=' + profile.getImageUrl() + '&email_address=' + profile.getEmail() + '&action=google_api_login';
                    jQuery.ajax({
                        type: "POST",
                        url: jobhunt_globals.ajax_url,
                        data: dataString,
                        success: function (response) {
                            if (response == 'Loggedin') {
                                location.reload();
                            }
                        }
                    });
                }, function (error) {
            console.log('Sign-in error', error);
        }
        );
    });

    element = document.getElementById('googlesignin');
}

/*
 * Google Login Signout
 */
jQuery(document).on('click', '.logout-btn', function () {
    var auth2 = gapi.auth2.getAuthInstance();
    auth2.signOut().then(function () {
    });
});