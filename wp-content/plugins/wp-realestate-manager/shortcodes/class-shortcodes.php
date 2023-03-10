<?php
/**
 * File Type: Shortcodes Function
 */
if (!class_exists('wp_rem_shortcode_functions')) {

    class wp_rem_shortcode_functions {

        /**
         * Start construct Functions
         */
        public function __construct() {

            /*
             * Add shortcode button on top of editor.
             */
            add_action('media_buttons', array($this, 'wp_rem_shortcode_button'), 11);
            wp_rem_include_shortcode_files();
            wp_rem_include_frontend_shortcode_files();
        }

        /*
         * Function to add shortcode.
         * To add shortcode use the filter.
         */

        public function wp_rem_shortcode_names() {

            $shortcode_array = array();

            $shortcode_array = apply_filters('wp_rem_shortcodes_list', $shortcode_array);
            ksort($shortcode_array);
            return $shortcode_array;
        }

        /*
         * Function to add Shortcodes Categories.
         * To add shortcode category use the filter.
         */

        public function wp_rem_elements_categories() {
            $categories_array = array(
                'typography' => wp_rem_plugin_text_srt('wp_rem_shortcodes_typography'),
                'commonelements' => wp_rem_plugin_text_srt('wp_rem_shortcodes_common_elements'),
                'mediaelement' => wp_rem_plugin_text_srt('wp_rem_shortcodes_media_element'),
                'contentblocks' => wp_rem_plugin_text_srt('wp_rem_shortcodes_content_blocks'),
                'loops' => wp_rem_plugin_text_srt('wp_rem_shortcodes_loops'));

            $categories_array = apply_filters('wp_rem_shortcodes_categories_list', $categories_array);

            return $categories_array;
        }

        /*
         * Function to add shortcode select options in button.
         * Do not edit this file unless required
         */

        public function wp_rem_shortcode_button($die = 0, $shortcode = 'shortcode') {

            global $wp_rem_form_fields;
            $i = 1;
            $rand = rand(1, 999);
            $wp_rem_page_elements_name = array();
            $wp_rem_page_elements_name = $this->wp_rem_shortcode_names();
            $wp_rem_page_categories_name = $this->wp_rem_elements_categories();

            $wp_rem_insert_btn = true;
            $screen = get_current_screen();
            if (is_admin() && isset($screen->parent_file) && $screen->parent_file == 'users.php') {
                $wp_rem_insert_btn = false;
            }
            ?> 
            <div class="cs-page-composer  <?php echo sanitize_html_class($shortcode); ?> composer-<?php echo intval($rand) ?>" id="composer-<?php echo intval($rand) ?>" style="display:none;">
                <div class="page-elements">
                    <div class="cs-heading-area">
                        <h5>
                            <i class="icon-plus-circle"></i> <?php echo wp_rem_plugin_text_srt('wp_rem_shortcodes_add_element'); ?>
                        </h5>
                        <span class='cs-btnclose' onclick='javascript:removeoverlay("composer-<?php echo esc_js($rand) ?>", "append")'>
                            <i class="icon-times"></i>
                        </span>
                    </div>
                    <script>
                        jQuery(document).ready(function ($) {
                            wp_rem_page_composer_filterable('<?php echo esc_js($rand) ?>');
                        });
                    </script>
                    <div class="cs-filter-content shortcode">
                        <p>
                            <?php
                            $wp_rem_opt_array = array(
                                'std' => '',
                                'cust_id' => 'quicksearch' . $rand,
                                'extra_atr' => ' placeholder="' . wp_rem_plugin_text_srt('wp_rem_property_search') . '"',
                                'cust_name' => '',
                                'required' => false,
                            );
                            $wp_rem_form_fields->wp_rem_form_text_render($wp_rem_opt_array);
                            ?>
                        </p>
                        <div class="cs-filtermenu-wrap">
                            <h6><?php echo wp_rem_plugin_text_srt('wp_rem_shortcodes_filter_by'); ?></h6>
                            <ul class="cs-filter-menu" id="filters<?php echo intval($rand) ?>">
                                <li data-filter="all" class="active"><?php echo wp_rem_plugin_text_srt('wp_rem_shortcodes_show_all'); ?></li>
                                <?php
                                foreach ($wp_rem_page_categories_name as $key => $value) {
                                    echo '<li data-filter="' . $key . '">' . $value . '</li>';
                                }
                                ?>
                            </ul>
                        </div>
                        <div class="cs-filter-inner" id="page_element_container<?php echo intval($rand) ?>">
                            <?php
                            foreach ($wp_rem_page_elements_name as $key => $element_value) {
                                echo '<div class="element-item ' . $element_value['categories'] . ' pb_' . esc_js($key) . '">';
                                $icon = isset($element_value['icon']) ? $element_value['icon'] : 'accordion-icon';
                                ?>
                                <a href='javascript:wp_rem_shortocde_selection("<?php echo esc_js($key); ?>","<?php echo admin_url('admin-ajax.php'); ?>","composer-<?php echo intval($rand) ?>")'><?php $this->wp_rem_page_composer_elements($element_value['title'], $icon) ?></a>
                            </div>
            <?php } ?>
                    </div>
                </div>
            </div>
            <div class="cs-page-composer-shortcode"></div>
            </div>
            <?php
        }

        public function wp_rem_page_composer_elements($element, $icon) {
            echo '<i class="fa ' . $icon . '"></i><span data-title="' . $element . '"> ' . $element . '</span>';
        }

    }

    global $wp_rem_shortcode_functions;
    $wp_rem_shortcode_functions = new wp_rem_shortcode_functions();
}

/**
 * Sizes for Shortcodes elements
 *
 */
if ( ! function_exists('wp_rem_cs_shortcode_element_size') ) {

	function wp_rem_cs_shortcode_element_size($column_size = '') {
		global $wp_rem_html_fields;
		$wp_rem_cs_opt_array = array(
			'name' => wp_rem_plugin_text_srt('wp_rem_cs_var_size'),
			'desc' => '',
			'hint_text' => wp_rem_plugin_text_srt('wp_rem_cs_var_column_hint'),
			'echo' => true,
			'field_params' => array(
				'std' => $column_size,
				'cust_id' => 'column_size',
				'cust_type' => 'button',
				'classes' => 'column_size  dropdown chosen-select-no-single select-medium',
				'cust_name' => 'wp_rem_cs_var_column_size[]',
				'extra_atr' => '',
				'options' => array(
					'1/1' => wp_rem_plugin_text_srt('wp_rem_cs_var_full_width'),
					'1/2' => wp_rem_plugin_text_srt('wp_rem_cs_var_one_half'),
					'1/3' => wp_rem_plugin_text_srt('wp_rem_cs_var_one_third'),
					'2/3' => wp_rem_plugin_text_srt('wp_rem_cs_var_two_third'),
					'1/4' => wp_rem_plugin_text_srt('wp_rem_cs_var_one_fourth'),
					'3/4' => wp_rem_plugin_text_srt('wp_rem_cs_var_three_fourth'),
				),
				'return' => true,
			),
		);
		$wp_rem_html_fields->wp_rem_select_field($wp_rem_cs_opt_array);
	}

}
/*
 * Page builder Element (shortcode(s))
 */
if ( ! function_exists('wp_rem_cs_page_composer_elements') ) {

	function wp_rem_cs_page_composer_elements($element = '', $icon = '', $description = '') {
		echo '<i class="' . $icon . '"></i><span data-title="' . esc_html($element) . '"> ' . esc_html($element) . '</span>';
	}

}

/**
 * Section element Size(s)
 *
 * @returm size
 */
if ( ! function_exists('wp_rem_cs_element_size_data_array_index') ) {

	function wp_rem_cs_element_size_data_array_index($size) {
		if ( $size == "" or $size == 100 ) {
			return 0;
		} else if ( $size == 75 ) {
			return 1;
		} else if ( $size == 67 ) {
			return 2;
		} else if ( $size == 50 ) {
			return 3;
		} else if ( $size == 33 ) {
			return 4;
		} else if ( $size == 25 ) {
			return 5;
		}
	}

}
/**
 * Shortcode Names for Elements
 *
 */
if ( ! function_exists('wp_rem_cs_shortcode_names') ) {

	function wp_rem_cs_shortcode_names() {
		global $post, $wp_rem_cs_var_frame_static_text;
		$shortcode_array = array();
		$shortcode_array = apply_filters('wp_rem_cs_shortcode_names_list_populate', $shortcode_array);
		ksort($shortcode_array);
		return $shortcode_array;
	}

}
/**
 * List of the elements in Page Builder
 *
 */
if ( ! function_exists('wp_rem_cs_element_list') ) {

	function wp_rem_cs_element_list() {
		global $wp_rem_cs_var_frame_static_text;
		$element_list = array(
			'element_list' => array(),
		);
		$element_list['element_list'] = apply_filters('wp_rem_cs_element_list_populate', $element_list['element_list']);
		return $element_list;
	}

}
/**
 * Page Builder Elements Settings
 *
 */
if ( ! function_exists('wp_rem_cs_element_setting') ) {

	function wp_rem_cs_element_setting($name, $wp_rem_cs_counter, $element_size, $element_description = '', $page_element_icon = 'icon-star', $type = '') {
		global $wp_rem_form_fields_frontend;
		$element_title = str_replace("wp_rem_cs_var_page_builder_", "", $name);
		$elm_name = str_replace("wp_rem_cs_var_page_builder_", "", $name);
                
		$element_list = wp_rem_cs_element_list();
		$all_shortcode_list = wp_rem_cs_shortcode_names();
		$current_shortcode_name = str_replace("wp_rem_cs_var_page_builder_", "", $name);
		$current_shortcode_detail = $all_shortcode_list[$current_shortcode_name];
		$shortcode_icon = isset($current_shortcode_detail['icon']) ? $current_shortcode_detail['icon'] : '';
		?>
		<div class="column-in">
			<?php
                        
			$wp_rem_cs_opt_array = array(
				'std' => esc_attr($element_size),
				'id' => '',
				'before' => '',
				'after' => '',
				'classes' => 'item',
				'extra_atr' => '',
				'cust_id' => '',
				'cust_name' => esc_attr($element_title) . '_element_size[]',
				'required' => false
			);
			$wp_rem_form_fields_frontend->wp_rem_form_hidden_render($wp_rem_cs_opt_array);
			?>
			<a href="javascript:;" onclick="javascript:wp_rem_cs_createpopshort(jQuery(this))" class="options"><i class="icon-cog3"></i></a>
			<a href="#" class="delete-it btndeleteit"><i class="icon-trash-o"></i></a> &nbsp;
			<?php
			$no_size_elemnts = array();
			$no_size_elemnts = apply_filters('wp_rem_cs_shortcode_remove_sizes', $no_size_elemnts);
			if ( ! in_array($current_shortcode_name, $no_size_elemnts) ) {
				?>
				<a class="decrement" onclick="javascript:wp_rem_cs_decrement(this)"><i class="icon-minus3"></i></a> &nbsp; 
				<a class="increment" onclick="javascript:wp_rem_cs_increment(this)"><i class="icon-plus3"></i></a> 
			<?php } ?>
			<span> 
				<i class="<?php echo $shortcode_icon . ' ' . str_replace("wp_rem_cs_var_page_builder_", "", $name); ?>-icon"></i> 
				<strong>
					<?php
					echo esc_html($element_list['element_list'][$elm_name]);
					?>
				</strong><br/>
				<?php echo esc_attr($element_description); ?> 
			</span>
		</div>
		<?php
                
	}

}