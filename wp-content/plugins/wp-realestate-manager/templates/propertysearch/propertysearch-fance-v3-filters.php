<?php
/**
 * Property search box
 * default variable which is getting from ajax request or shotcode
 * $property_short_counter, $property_arg
 */
global $wp_rem_plugin_options, $wp_rem_form_fields_frontend, $wp_rem_post_property_types, $wp_rem_shortcode_properties_frontend, $wp_rem_search_fields;
$propertysearch_title_switch = isset($atts['propertysearch_title_field_switch']) ? $atts['propertysearch_title_field_switch'] : '';
$propertysearch_property_type_switch = isset($atts['propertysearch_property_type_field_switch']) ? $atts['propertysearch_property_type_field_switch'] : '';
$propertysearch_location_switch = isset($atts['propertysearch_location_field_switch']) ? $atts['propertysearch_location_field_switch'] : '';
$propertysearch_categories_switch = isset($atts['propertysearch_categories_field_switch']) ? $atts['propertysearch_categories_field_switch'] : '';
$propertysearch_price_switch = isset($atts['propertysearch_price_field_switch']) ? $atts['propertysearch_price_field_switch'] : '';
$propertysearch_advance_filter_switch = isset($atts['propertysearch_advance_filter_switch']) ? $atts['propertysearch_advance_filter_switch'] : '';
$property_types_array = array();
wp_enqueue_script('bootstrap-datepicker');
wp_enqueue_style('datetimepicker');
wp_enqueue_style('datepicker');
wp_enqueue_script('datetimepicker');
$search_title = isset($_REQUEST['search_title']) ? $_REQUEST['search_title'] : '';
$property_type_slug = '';
$wp_rem_form_fields_frontend->wp_rem_form_hidden_render(
	array(
	    'simple' => true,
	    'cust_id' => '',
	    'cust_name' => '',
	    'classes' => "property-counter",
	    'std' => absint($property_short_counter),
	)
);
?> 
<div style="display:none" id='property_arg<?php echo absint($property_short_counter); ?>'><?php
    echo json_encode($property_arg);
    ?>
</div>
<form method="GET" id="top-search-form-<?php echo wp_rem_allow_special_char($property_short_counter); ?>" action="<?php echo esc_html($wp_rem_search_result_page); ?>" onsubmit="wp_rem_top_search('<?php echo wp_rem_allow_special_char($property_short_counter); ?>');">
    <?php echo wp_rem_wpml_lang_code_field(); ?>
    <div role="tabpanel" class="tab-pane" id="home">
        <div class="search-default-fields">
	    <?php if ($propertysearch_title_switch == 'yes') { ?>
    	    <div class="field-holder search-input">
    		<label>
			<?php
			$wp_rem_form_fields_frontend->wp_rem_form_text_render(
				array(
				    'cust_name' => 'search_title',
				    'classes' => 'input-field',
				    'std' => $search_title,
				    'extra_atr' => 'placeholder="' . wp_rem_plugin_text_srt('wp_rem_property_search_flter_wt_looking_for') . '"',
				)
			);
			?>  
    		</label>
    	    </div>
		<?php
	    }
	    if ($propertysearch_property_type_switch == 'yes') {
		$number_option_flag = 1;
		?>
    	    <div id="property_type_select_fields_<?php echo wp_rem_allow_special_char($property_short_counter); ?>" class="property-type-cate-fields field-holder select-dropdown">
    		<label>
			<?php
			$wp_rem_post_property_types = new Wp_rem_Post_Property_Types();
			$property_types_array = $wp_rem_post_property_types->wp_rem_types_array_callback('NULL');
			if (is_array($property_types_array) && !empty($property_types_array)) {
			    foreach ($property_types_array as $key => $value) {
				$property_type_slug = $key;
				break;
			    }
			}
			foreach ($property_types_array as $key => $value) {
			    $types_array[$key] = $value;
			}
			?>
    		    <select onchange="wp_rem_property_type_search_fields(this,<?php echo $property_short_counter; ?>, '<?php echo $propertysearch_price_switch; ?>'); wp_rem_property_type_cate_fields(this,<?php echo $property_short_counter; ?> ,'<?php echo  $propertysearch_categories_switch; ?>','fancy-v3'); " class="chosen-select-no-single" id="search_form_property_type<?php echo $number_option_flag; ?>" name="property_type">
			    <?php
			    $value = $property_type_slug;
			    if (!is_array($value)) {
				$value = array();
			    }
			    foreach ($types_array as $key => $types_array) {
				$selected = '';
				if (in_array($key, $value)) {
				    $selected = 'selected="selected"';
				}
				$property_post_name = get_page_by_path($key, OBJECT, 'property-type');
				$property_id = $property_post_name->ID;
				$wp_rem_search_result_page_type = get_post_meta($property_id, 'wp_rem_search_result_page', true);
				$wp_rem_search_result_page_type = wp_rem_wpml_lang_page_permalink($wp_rem_search_result_page_type, 'page');
				if ($wp_rem_search_result_page_type == '') {
				    $wp_rem_search_result_page_type = $wp_rem_search_result_page;
				}
				echo '<option ' . $selected . 'value="' . $key . '" data-search-page= "' . $wp_rem_search_result_page_type . '">' . $types_array . '</option>';
			    }
			    ?>
    		    </select>
    		</label>
    	    </div>
	    <?php } ?>
	    <?php
	    $property_cats_array = $wp_rem_search_fields->wp_rem_property_type_categories_options($property_type_slug);
	    if ($propertysearch_categories_switch == 'yes' && !empty($property_cats_array)) {
		?>
    	    <div id="property_type_cate_fields_<?php echo wp_rem_allow_special_char($property_short_counter); ?>" class="property-category-fields field-holder select-dropdown">
    		<label>
			<?php
			$wp_rem_opt_array = array(
			    'std' => '',
			    'id' => 'property_category',
			    'classes' => 'chosen-select',
			    'cust_name' => 'property_category',
			    'options' => $property_cats_array,
			);
			if (count($property_cats_array) <= 6) {
			    $wp_rem_opt_array['classes'] = 'chosen-select-no-single';
			}
			$wp_rem_form_fields_frontend->wp_rem_form_select_render($wp_rem_opt_array);
			?>
    		</label>
    	    </div>
	    <?php } ?>
	    <?php if ($propertysearch_location_switch == 'yes') { ?>
		<?php do_action('homevillas_search_location_filters', '', '', false, $property_short_counter, 'fancy-v3');
		?>
	    <?php } ?>
            <div class="field-holder search-btn">
                <div class="search-btn-loader-<?php echo wp_rem_allow_special_char($property_short_counter); ?> input-button-loader">
		    <?php
		    $wp_rem_form_fields_frontend->wp_rem_form_text_render(
			    array(
				'cust_name' => '',
				'classes' => 'bgcolor',
				'std' => wp_rem_plugin_text_srt('wp_rem_property_search_flter_saerch'),
				'cust_type' => "submit",
			    )
		    );
		    ?> 
                </div>
            </div>
        </div>
    </div>
</form>