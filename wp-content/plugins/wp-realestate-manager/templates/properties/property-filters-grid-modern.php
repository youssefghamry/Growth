<?php
/**
 * Property search box
 *
 */
?>
<!--Element Section Start-->
<!--Wp-rem Element Start-->
<?php
global $wp_rem_post_property_types, $wp_rem_plugin_options;
$user_id = $user_company = '';
if (is_user_logged_in()) {
    $user_id = get_current_user_id();
    $user_company = get_user_meta($user_id, 'wp_rem_company', true);
}
$default_property_no_custom_fields = isset($wp_rem_plugin_options['wp_rem_property_no_custom_fields']) ? $wp_rem_plugin_options['wp_rem_property_no_custom_fields'] : '';
$compare_property_switch = isset($atts['compare_property_switch']) ? $atts['compare_property_switch'] : 'no';
if (false === ( $property_view = wp_rem_get_transient_obj('wp_rem_property_view' . $property_short_counter) )) {
    $property_view = isset($atts['property_view']) ? $atts['property_view'] : '';
}
$property_no_custom_fields = isset($atts['property_no_custom_fields']) ? $atts['property_no_custom_fields'] : $default_property_no_custom_fields;
if ($property_no_custom_fields == '' || !is_numeric($property_no_custom_fields)) {
    $property_no_custom_fields = 3;
}
$property_enquiry_switch = isset($atts['property_enquiry_switch']) ? $atts['property_enquiry_switch'] : 'no';
$property_notes_switch = isset($atts['property_notes_switch']) ? $atts['property_notes_switch'] : 'no';

wp_enqueue_script('wp-rem-prettyPhoto');
wp_enqueue_style('wp-rem-prettyPhoto');
$wp_rem_cs_inline_script = '
        jQuery(document).ready(function () {
             jQuery("a.property-video-btn[data-rel^=\'prettyPhoto\']").prettyPhoto({animation_speed:"fast",slideshow:10000, hideflash: true,autoplay:true,autoplay_slideshow:false});
        });';
wp_rem_cs_inline_enqueue_script($wp_rem_cs_inline_script, 'wp-rem-custom-inline');

$search_box = isset($atts['search_box']) ? $atts['search_box'] : '';
$main_class = 'property-medium';
$wp_rem_properties_title_limit = isset($atts['properties_title_limit']) ? $atts['properties_title_limit'] : '20';
// start ads script
$property_ads_switch = isset($atts['property_ads_switch']) ? $atts['property_ads_switch'] : 'no';
if ($property_ads_switch == 'yes') {
    $property_ads_after_list_series = isset($atts['property_ads_after_list_count']) ? $atts['property_ads_after_list_count'] : '5';
    if ($property_ads_after_list_series != '') {
	$property_ads_list_array = explode(",", $property_ads_after_list_series);
    }
    $property_ads_after_list_array_count = sizeof($property_ads_list_array);
    $property_ads_after_list_flag = 0;
    $i = 0;
    $array_i = 0;
    $property_ads_after_list_array_final = '';
    while ($property_ads_after_list_array_count > $array_i) {
	if (isset($property_ads_list_array[$array_i]) && $property_ads_list_array[$array_i] != '') {
	    $property_ads_after_list_array[$i] = $property_ads_list_array[$array_i];
	    $i ++;
	}
	$array_i ++;
    }
    // new count 
    $property_ads_after_list_array_count = sizeof($property_ads_after_list_array);
}
$properties_ads_array = array();
if ($property_ads_switch == 'yes' && $property_ads_after_list_array_count > 0) {
    $list_count = 0;
    for ($i = 0; $i <= $property_loop_obj->found_posts; $i ++) {
	if ($list_count == $property_ads_after_list_array[$property_ads_after_list_flag]) {
	    $list_count = 1;
	    $properties_ads_array[] = $i;
	    $property_ads_after_list_flag ++;
	    if ($property_ads_after_list_flag >= $property_ads_after_list_array_count) {
		$property_ads_after_list_flag = $property_ads_after_list_array_count - 1;
	    }
	} else {
	    $list_count ++;
	}
    }
}
$property_page = isset($_REQUEST['property_page']) && $_REQUEST['property_page'] != '' ? $_REQUEST['property_page'] : 1;
$posts_per_page = isset($atts['posts_per_page']) ? $atts['posts_per_page'] : '';
$counter = 1;
if ($property_page >= 2) {
    $counter = ( ($property_page - 1) * $posts_per_page ) + 1;
}
// end ads script
$columns_class = 'col-lg-4 col-md-4 col-sm-6 col-xs-12';
$main_class = 'property-grid modern v1';
$property_location_options = isset($atts['property_location']) ? $atts['property_location'] : '';
if ($property_location_options != '') {
    $property_location_options = explode(',', $property_location_options);
}
$http_request = wp_rem_server_protocol();
if ($property_loop_obj->have_posts()) {
    $flag = 1;
    ?>
    <div class="row">
	<?php if (sizeof($properties_ads_array) > 0 && in_array(0, $properties_ads_array) && ($property_page == 1 || $property_page == '')) { ?>
	    <div class="portfolio grid-fading animated col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<?php do_action('wp_rem_random_ads', 'property_banner'); ?>
	    </div>
	    <?php
	}
	while ($property_loop_obj->have_posts()) : $property_loop_obj->the_post();
	    global $post, $wp_rem_member_profile;
	    $property_id = $post;
	    $pro_is_compare = apply_filters('wp_rem_is_compare', $property_id, $compare_property_switch);
	    $property_random_id = rand(1111111, 9999999);
	    $Wp_rem_Locations = new Wp_rem_Locations();

	    $get_property_location = $Wp_rem_Locations->get_element_property_location($property_id, $property_location_options);
	    $wp_rem_property_username = get_post_meta($property_id, 'wp_rem_property_username', true);
	    $wp_rem_property_member = get_post_meta($property_id, 'wp_rem_property_member', true);
	    $wp_rem_property_is_featured = get_post_meta($property_id, 'wp_rem_property_is_featured', true);
	    $wp_rem_property_is_top_cat = get_post_meta($property_id, 'wp_rem_property_is_top_cat', true);

	    $wp_rem_profile_image = $wp_rem_member_profile->member_get_profile_image($wp_rem_property_username);
	    $wp_rem_property_price_options = get_post_meta($property_id, 'wp_rem_property_price_options', true);
	    $wp_rem_property_type = get_post_meta($property_id, 'wp_rem_property_type', true);
	    $wp_rem_property_posted = get_post_meta($property_id, 'wp_rem_property_posted', true);
	    $wp_rem_property_posted = wp_rem_time_elapsed_string($wp_rem_property_posted);
	    $number_of_gallery_items = get_post_meta($property_id, 'wp_rem_detail_page_gallery_ids', true);

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

	    $wp_rem_property_type_price_switch = get_post_meta($property_type_id, 'wp_rem_property_type_price', true);

	    // end checking review on in property type

	    /*
	     * Video and gallery from type 
	     */
	    $wp_rem_video_element = get_post_meta($property_type_id, 'wp_rem_video_element', true);
	    $wp_rem_image_gallery_element = get_post_meta($property_type_id, 'wp_rem_image_gallery_element', true);
	    $wp_rem_video_element = isset($wp_rem_video_element) ? $wp_rem_video_element : '';
	    $wp_rem_image_gallery_element = isset($wp_rem_image_gallery_element) ? $wp_rem_image_gallery_element : '';
	    /*
	     * End Video and gallery 
	     */


	    $wp_rem_property_price = '';
	    if ($wp_rem_property_price_options == 'price') {
		$wp_rem_property_price = get_post_meta($property_id, 'wp_rem_property_price', true);
	    } else if ($wp_rem_property_price_options == 'on-call') {
		$wp_rem_property_price = wp_rem_plugin_text_srt('wp_rem_properties_price_on_request');
	    }
	    $wp_rem_price_type = get_post_meta($property_id, 'wp_rem_price_type', true);
	    // get all categories
	    $wp_rem_cate = '';
	    $wp_rem_cate_str = '';
	    $wp_rem_property_category = get_post_meta($property_id, 'wp_rem_property_category', true);
	    $wp_rem_post_loc_address_property = get_post_meta($property_id, 'wp_rem_post_loc_address_property', true);

	    if (!empty($wp_rem_property_category) && is_array($wp_rem_property_category)) {
		$comma_flag = 0;
		foreach ($wp_rem_property_category as $cate_slug => $cat_val) {
		    $wp_rem_cate = get_term_by('slug', $cat_val, 'property-category');

		    if (!empty($wp_rem_cate)) {
			$cate_link = wp_rem_property_category_link($property_type_id, $cat_val);
			if ($comma_flag != 0) {
			    $wp_rem_cate_str .= ', ';
			}
			$wp_rem_cate_str = '<a href="' . $cate_link . '">' . $wp_rem_cate->name . '</a>';
			$comma_flag ++;
		    }
		}
	    }

	    $featured = $urgent = '';
	    if ($wp_rem_property_is_featured == 'on') {
		$featured = 'featured';
	    } if ($wp_rem_property_is_top_cat == 'on') {
		$urgent = 'urgent';
	    }

	    $user_id = '';
	    if (is_user_logged_in()) {
		$user_id = get_current_user_id();
	    }
	    ?>
	    <div class="portfolio grid-fading animated <?php echo esc_html($columns_class); ?>">
		<div class="<?php echo esc_html($main_class); ?><?php echo esc_html($pro_is_compare); ?>" itemscope itemtype="<?php echo force_balance_tags($http_request); ?>schema.org/Product">
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
					<?php if ($property_video_url != '' && $wp_rem_video_element == 'on') { ?>
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
					<?php if ($count_all > 0 && $wp_rem_image_gallery_element == 'on') { ?>
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
		    <div class="text-holder">
			<?php if (!empty($get_property_location)) { ?>
	    		<ul class="property-location">
	    		    <li><i class="icon-location-pin2"></i><span><?php echo esc_html(implode(' / ', $get_property_location)); ?></span></li>
	    		</ul>
			<?php } ?>
			<?php
			$favourite_label = '';
			$favourite_label = '';
			$figcaption_div = true;
			$book_mark_args = array(
			    'before_label' => $favourite_label,
			    'after_label' => $favourite_label,
			    'before_icon' => '<i class="icon-heart-o"></i>',
			    'after_icon' => '<i class="icon-heart5"></i>',
			);
			do_action('wp_rem_favourites_frontend_button', $property_id, $book_mark_args, $figcaption_div);
			?>
			<div class="post-title">
			    <h4 itemprop="name"><a href="<?php echo esc_url(get_permalink($property_id)); ?>"><?php echo esc_html(wp_trim_words(get_the_title($property_id), $wp_rem_properties_title_limit)) ?></a></h4>
			</div>
			<?php
			// All custom fields with value
			$cus_fields = array('content' => '');
			$cus_fields = apply_filters('wp_rem_custom_fields', $property_id, $cus_fields, $property_no_custom_fields);
			if (isset($cus_fields['content']) && $cus_fields['content'] != '') {
			    ?>
	    		<ul class="post-category-list" itemprop="category">
				<?php echo wp_rem_allow_special_char($cus_fields['content']); ?>
	    		</ul>
			<?php } ?>

		    </div>
		    <div class="post-property-footer">
			<?php
			$has_thumb_class = ' has-thumb';
			if (empty($wp_rem_profile_image)) {
			    $has_thumb_class = '';
			}
			?>
			<div class="price-holder">
			    <?php if ($wp_rem_property_type_price_switch == 'on' && $wp_rem_property_price != '') { ?>
	    		    <span class="property-price<?php echo wp_rem_allow_special_char($has_thumb_class); ?>" itemprop="offers" itemscope itemtype="<?php echo force_balance_tags($http_request); ?>schema.org/Offer">
				    <?php
				    if ($wp_rem_property_price_options == 'on-call') {
                        $phone_number = get_post_meta($property_id, 'wp_rem_phone_number_property', true);
					echo '<em>' . force_balance_tags($wp_rem_property_price) . " " . $phone_number . '</em>';
				    } else {
					echo '<small>' . wp_rem_plugin_text_srt('wp_rem_property_price_start_from') . '</small>';
					$property_info_price = wp_rem_property_price($property_id, $wp_rem_property_price, '<span class="guid-price">', '</span>', '<span class="price-type">', '</span>', 'right', '<span content="' . $wp_rem_property_price . '" itemprop="price">', '</span>');
					$wp_rem_get_currency_sign = wp_rem_get_currency_sign('code');
					echo '<span itemprop="priceCurrency" style="display:none;" content="' . $wp_rem_get_currency_sign . '"></span>';
					echo force_balance_tags($property_info_price);
				    }
				    ?>
	    		    </span>
			    <?php } ?>
			    <?php if ($wp_rem_profile_image != '') { ?>
	    		    <div class="thumb-img">
	    			<figure>
	    			    <a href="<?php echo esc_url(get_permalink($wp_rem_property_member)); ?>"><img src="<?php echo esc_url($wp_rem_profile_image); ?>" alt=""></a>
	    			</figure>
	    		    </div>
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
	    <?php if (sizeof($properties_ads_array) > 0 && in_array($counter, $properties_ads_array)) { ?>
	        <div class="portfolio grid-fading animated col-lg-12 col-md-12 col-sm-12 col-xs-12">
		    <?php do_action('wp_rem_random_ads', 'property_banner'); ?>
	        </div>
		<?php
	    }
	    $counter ++;
	endwhile;
	?>
    </div>
    <?php
} else {
    echo '<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 no-property-match-error"><h6><i class="icon-warning"></i><strong> ' . wp_rem_plugin_text_srt('wp_rem_property_slider_sorry') . '</strong>&nbsp; ' . wp_rem_plugin_text_srt('wp_rem_property_slider_doesn_match') . ' </h6></div></div>';
}
?>