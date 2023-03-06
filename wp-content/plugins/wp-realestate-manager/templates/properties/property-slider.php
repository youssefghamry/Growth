<?php
/**
 * Properties Slider
 *
 */
wp_enqueue_style('swiper');
wp_enqueue_script('swiper');
global $wp_rem_post_property_types;

$wp_rem_all_compare_buttons = isset($wp_rem_plugin_options['wp_rem_all_compare_buttons']) ? $wp_rem_plugin_options['wp_rem_all_compare_buttons'] : '';
$property_location_options = isset($atts['property_location']) ? $atts['property_location'] : '';
$properties_title = isset($atts['slider_properties_title']) ? $atts['slider_properties_title'] : '';
$properties_subtitle = isset($atts['properties_subtitle']) ? $atts['properties_subtitle'] : '';

$wp_rem_property_slider_element_title_color = isset($atts['wp_rem_property_slider_element_title_color']) ? $atts['wp_rem_property_slider_element_title_color'] : '';
$wp_rem_property_slider_element_subtitle_color = isset($atts['wp_rem_property_slider_element_subtitle_color']) ? $atts['wp_rem_property_slider_element_subtitle_color'] : '';
$wp_rem_properties_slider_seperator_style = isset($atts['wp_rem_properties_slider_seperator_style']) ? $atts['wp_rem_properties_slider_seperator_style'] : '';
$wp_rem_properties_title_limit = isset($atts['properties_title_limit']) ? $atts['properties_title_limit'] : '20';
$property_no_custom_fields = isset($atts['property_no_custom_fields']) ? $atts['property_no_custom_fields'] : $default_property_no_custom_fields;
if ($property_no_custom_fields == '' || !is_numeric($property_no_custom_fields)) {
    $property_no_custom_fields = 3;
}
$property_enquiry_switch = isset($atts['property_enquiry_switch']) ? $atts['property_enquiry_switch'] : 'no';
$property_notes_switch = isset($atts['property_notes_switch']) ? $atts['property_notes_switch'] : 'no';
$compare_property_switch = isset($atts['compare_property_switch']) ? $atts['compare_property_switch'] : 'no';
$properties_slider_alignment = isset($atts['properties_slider_alignment']) ? $atts['properties_slider_alignment'] : '';
if ($property_location_options != '') {
    $property_location_options = explode(',', $property_location_options);
}
if ($properties_title == '') {
    $padding_class = 'swiper-padding-top';
}
$rand_num = rand(12345, 54321);
wp_enqueue_script('wp-rem-prettyPhoto');
wp_enqueue_style('wp-rem-prettyPhoto');
$wp_rem_cs_inline_script = '
        jQuery(document).ready(function () {
             jQuery("a.property-video-btn[data-rel^=\'prettyPhoto\']").prettyPhoto({animation_speed:"fast",slideshow:10000, hideflash: true,autoplay:true,autoplay_slideshow:false});
        });';
wp_rem_cs_inline_enqueue_script($wp_rem_cs_inline_script, 'wp-rem-custom-inline');
$flag = 1;
$http_request = wp_rem_server_protocol();
?>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
	<?php
	$wp_rem_element_structure = '';
	$wp_rem_element_structure .= wp_rem_plugin_title_sub_align($properties_title, $properties_subtitle, $properties_slider_alignment, $wp_rem_property_slider_element_title_color, $wp_rem_properties_slider_seperator_style, $wp_rem_property_slider_element_subtitle_color);
	echo force_balance_tags($wp_rem_element_structure);
	?>
    </div>
    <?php if ($property_loop_obj->have_posts()) { ?>
        <div id="property-grid-slider-<?php echo intval($rand_num); ?>" class="property-grid-slider v2">
    	<div class="swiper-container">
    	    <div class="swiper-wrapper">
		    <?php
		    while ($property_loop_obj->have_posts()) : $property_loop_obj->the_post();
			global $post, $wp_rem_member_profile;
			$property_id = $post;
			$pro_is_compare = apply_filters('wp_rem_is_compare', $property_id, $compare_property_switch);
			$property_random_id = rand(1111111, 9999999);
			$Wp_rem_Locations = new Wp_rem_Locations();
			$get_property_location = $Wp_rem_Locations->get_element_property_location($property_id, $property_location_options);

			$wp_rem_property_username = get_post_meta($property_id, 'wp_rem_property_username', true);
			$wp_rem_property_is_featured = get_post_meta($property_id, 'wp_rem_property_is_featured', true);
			$wp_rem_property_price_options = get_post_meta($property_id, 'wp_rem_property_price_options', true);
			$wp_rem_property_type = get_post_meta($property_id, 'wp_rem_property_type', true);
			$number_of_gallery_items = get_post_meta($property_id, 'wp_rem_detail_page_gallery_ids', true);
			$wp_rem_property_price = '';

			$gallery_pics_allowed = get_post_meta($property_id, 'wp_rem_transaction_property_pic_num', true);
			$count_all = ( isset($number_of_gallery_items) && is_array($number_of_gallery_items) && sizeof($number_of_gallery_items) > 0 ) ? count($number_of_gallery_items) : 0;
			if ($count_all > $gallery_pics_allowed) {
			    $count_all = $gallery_pics_allowed;
			}


			// checking review in on in property type
			$wp_rem_property_type = isset($wp_rem_property_type) ? $wp_rem_property_type : '';
			if ($property_type_post = get_page_by_path($wp_rem_property_type, OBJECT, 'property-type'))
			    $property_type_id = $property_type_post->ID;
			$property_type_id = isset($property_type_id) ? $property_type_id : '';
			$property_type_id = wp_rem_wpml_lang_page_id($property_type_id, 'property-type');
			$wp_rem_user_reviews = get_post_meta($property_type_id, 'wp_rem_user_reviews', true);
			// end checking review on in property type

			/*
			 * Video and gallery from type 
			 */
			$wp_rem_video_element_switch = get_post_meta($property_type_id, 'wp_rem_video_element', true);
			$wp_rem_image_gallery_switch = get_post_meta($property_type_id, 'wp_rem_image_gallery_element', true);
			$wp_rem_video_element_switch = isset($wp_rem_video_element_switch) ? $wp_rem_video_element_switch : '';
			$wp_rem_image_gallery_switch = isset($wp_rem_image_gallery_switch) ? $wp_rem_image_gallery_switch : '';
			/*
			 * End Video and gallery 
			 */

			$wp_rem_property_type_price_switch = get_post_meta($property_type_id, 'wp_rem_property_type_price', true);
			if ($wp_rem_property_price_options == 'price') {
			    $wp_rem_property_price = get_post_meta($property_id, 'wp_rem_property_price', true);
			} else if ($wp_rem_property_price_options == 'on-call') {
			    $wp_rem_property_price = wp_rem_plugin_text_srt('wp_rem_properties_price_on_request');
			}
			$wp_rem_profile_image = $wp_rem_member_profile->member_get_profile_image($wp_rem_property_username);
			$featured = '';
			if ($wp_rem_property_is_featured == 'on') {
			    $featured = 'featured';
			}
			?>
			<div class="swiper-slide col-lg-4 col-md-4 col-sm-6 col-xs-12">
			    <div class="property-grid modern v3 <?php echo esc_html($pro_is_compare); ?>" itemscope itemtype="<?php echo force_balance_tags($http_request); ?>schema.org/Product">
				<div class="img-holder">
				    <figure>
					<a href="<?php the_permalink(); ?>">
					    <?php
					    if (function_exists('property_gallery_first_image')) {
						if ($property_view == 'grid-medern') {
						    $size = 'wp_rem_cs_media_5';
						} else if ($property_view == 'grid-classic') {
						    $size = 'wp_rem_cs_media_5';
						} else {
						    $size = 'wp_rem_cs_media_6';
						}
						$gallery_image_args = array(
						    'property_id' => $property_id,
						    'size' => $size,
						    'class' => 'img-grid',
						    'default_image_src' => esc_url(wp_rem::plugin_url() . 'assets/frontend/images/no-image9x6.jpg'),
						    'img_extra_atr' => 'itemprop="image"',
						);
						echo $property_gallery_first_image = property_gallery_first_image($gallery_image_args);
					    }
					    ?>
					</a>
					<figcaption>
					    <?php
					    wp_rem_property_sold_html($property_id);
					    if ($wp_rem_property_is_featured == 'on') {
						?>
	    				    <span class="featured"><?php echo wp_rem_plugin_text_srt('wp_rem_property_featrd'); ?></span>
					    <?php } ?>
					    <?php echo fetch_property_open_house_grid_view_callback($property_id, 'yes'); ?>
					    <div class="caption-inner">
						<?php
						$property_video_url = get_post_meta($property_id, 'wp_rem_property_video', true);
						$property_video_url = isset($property_video_url) ? $property_video_url : '';
						?>
						<ul class="rem-property-options">
						    <?php
						    if (isset($property_notes_switch) && $property_notes_switch == 'yes') {
							// Property Notes Button
							$prop_notes_args = array(
							    'property_notes_switch' => $property_notes_switch,
							    'before_html' => '<li class="property-note-opt"><div class="option-holder">',
							    'after_html' => '</div></li>',
							    'before_label' => wp_rem_plugin_text_srt('wp_rem_property_notes'),
							    'after_label' => wp_rem_plugin_text_srt('wp_rem_property_notes_added'),
							    'before_icon' => 'icon-book',
							    'after_icon' => 'icon-book2',
							    'notes_rand_id' => $property_random_id,
							);
							do_action('wp_rem_notes_frontend_button', $property_id, $prop_notes_args);
						    }
						    //
						    ?>
						    <?php do_action('wp_rem_property_compare', $property_id, $compare_property_switch, 'no', '<li class="property-compare-opt"><div class="option-holder">', '</div></li>'); ?>
						    <?php
						    $figcaption_div = true;
						    $book_mark_args = array(
							'before_html' => '<li class="property-like-opt"><div class="option-holder">',
							'after_html' => '</div></li>',
							'before_label' => wp_rem_plugin_text_srt('wp_rem_property_save_to_favourite'),
							'after_label' => wp_rem_plugin_text_srt('wp_rem_property_remove_to_favourite'),
							'before_icon' => 'icon-heart-o',
							'after_icon' => 'icon-heart5',
							'show_tooltip' => 'no',
						    );
						    do_action('wp_rem_property_favourite_button_frontend', $property_id, $book_mark_args, $figcaption_div);
						    ?>
						    <?php if ($property_video_url != '' && $wp_rem_video_element_switch == 'on') { ?>
							<?php $property_video_url = str_replace("player.vimeo.com/video", "vimeo.com", $property_video_url); ?>
	    					    <li class="property-video-opt">
	    						<div class="option-holder">
	    						    <a class="property-video-btn" data-rel="prettyPhoto" href="<?php echo esc_url($property_video_url); ?>">
	    							<i class="icon-film3"></i>
	    							<div class="option-content"><span><?php echo wp_rem_plugin_text_srt('wp_rem_subnav_item_3'); ?></span></div>
	    						    </a>
	    						</div>
	    					    </li>
						    <?php } ?>
						    <?php if ($count_all > 0 && $wp_rem_image_gallery_switch == 'on') { ?>
	    					    <li class="property-photo-opt">
	    						<div id="galley-img<?php echo absint($property_random_id) ?>" class="option-holder">
	    						    <a href="javascript:void(0)" class="rem-pretty-photos" data-id="<?php echo absint($property_id) ?>" data-rand="<?php echo absint($property_random_id) ?>">
	    							<i class="icon-camera6"></i><span class="capture-count"><?php echo absint($count_all); ?></span>
	    							<div class="option-content">
	    							    <span><?php echo wp_rem_plugin_text_srt('wp_rem_element_tooltip_icon_camera'); ?></span>
	    							</div>
	    						    </a>
	    						</div>
	    					    </li>
						    <?php } ?>
						</ul>
					    </div>
					</figcaption>
				    </figure>
				</div>
				<?php ?>
				<div class="text-holder">
				    <?php if (!empty($get_property_location)) { ?>
	    			    <ul class="property-location">
	    				<li><i class="icon-location-pin2"></i><span><?php echo esc_html(implode(' / ', $get_property_location)); ?></span></li>
	    			    </ul>
				    <?php } ?>
				    <div class="post-title">
					<h4 itemprop="name"><a href="<?php echo esc_url(get_permalink($property_id)); ?>"><?php echo esc_html(wp_trim_words(get_the_title($property_id), $wp_rem_properties_title_limit)) ?></a></h4>
				    </div>
				    <?php
				    // All custom fields with value
				    $cus_fields = array('content' => '');
				    $cus_fields = apply_filters('wp_rem_custom_fields', $property_id, $cus_fields, $property_no_custom_fields, true, false);
				    if (isset($cus_fields['content']) && $cus_fields['content'] != '') {
					?>
	    			    <ul class="post-category-list" itemprop="category">
					    <?php echo wp_rem_allow_special_char($cus_fields['content']); ?>
	    			    </ul>
				    <?php } ?>

				</div>
				<div class="post-property-footer">
				    <div class="price-holder">

					<?php if ($wp_rem_property_type_price_switch == 'on' && $wp_rem_property_price != '') { ?>
	    				<span class="property-price" itemprop="offers" itemscope itemtype="<?php echo force_balance_tags($http_request); ?>schema.org/Offer">
						<?php
						if ($wp_rem_property_price_options == 'on-call') {
                            $phone_number = get_post_meta($property_id, 'wp_rem_phone_number_property', true);
                            echo force_balance_tags($wp_rem_property_price).' '.$phone_number;
						} else {
						    $property_info_price = wp_rem_property_price($property_id, $wp_rem_property_price, '<span class="guid-price">', '</span>', '<span class="price-type">', '</span>', 'right', '<span content="' . $wp_rem_property_price . '" itemprop="price">', '</span>');
						    $wp_rem_get_currency_sign = wp_rem_get_currency_sign('code');
						    echo '<span itemprop="priceCurrency" style="display:none;" content="' . $wp_rem_get_currency_sign . '"></span>';
						    echo force_balance_tags($property_info_price);
						}
						?>
	    				</span>
					    <?php
					}
					if ($property_enquiry_switch == 'yes') {
					    $prop_enquir_args = array(
						'enquiry_label' => wp_rem_plugin_text_srt('wp_rem_enquiry_detail_enquiry'),
					    );
					    do_action('wp_rem_enquiry_check_frontend_button', $property_id, $prop_enquir_args);
					}
					?>
				    </div>
				</div>
			    </div>
			</div>
			<?php
		    endwhile;
		    ?>
    	    </div>
    	</div>
    	<!-- Add Arrows -->
    	<div class="swiper-button-next"><i class="icon-angle-right"></i> </div>
    	<div class="swiper-button-prev"><i class="icon-angle-left"></i></div>
        </div>
        <script type="text/javascript">
            jQuery(document).ready(function ($) {
                if (jQuery("#property-grid-slider-<?php echo intval($rand_num); ?> .swiper-container").length != "") {
                    "use strict";
                    var mySlider = new Swiper("#property-grid-slider-<?php echo intval($rand_num); ?> .swiper-container", {
                        nextButton: "#property-grid-slider-<?php echo intval($rand_num); ?> .swiper-button-next",
                        prevButton: "#property-grid-slider-<?php echo intval($rand_num); ?> .swiper-button-prev",
                        paginationClickable: !0,
                        slidesPerView: 3,
                        slidesPer: 1,
                        loop: !0,
                        autoplay: 2500,
                        loop: true,
                        autoplayDisableOnInteraction: false,
                        onInit: function (swiper) {
                            $(".property-grid.modern.v3 .text-holder").matchHeight();

                        },
                        breakpoints: {
                            991: {
                                slidesPerView: 2
                            },
                            600: {
                                slidesPerView: 1
                            }
                        }
                    });
                    var elementWidth = $(".wp-rem-property-content").width();
                    if (elementWidth < 992 && elementWidth > 600)
                        mySlider.params.slidesPerView = 2;
                    if (elementWidth < 600)
                        mySlider.params.slidesPerView = 1;
                    mySlider.update();
                    $(window).trigger('resize');
                }
            });
        </script>
	<?php
    } else {
	echo '<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 no-property-match-error"><h6><i class="icon-warning"></i><strong> ' . wp_rem_plugin_text_srt('wp_rem_property_slider_sorry') . '</strong>&nbsp; ' . wp_rem_plugin_text_srt('wp_rem_property_slider_doesn_match') . ' </h6></div></div>';
    }
    ?>
</div>
