<?php
/**
 * Define hooks (wp content, theme options, menus & locations, set up pages, revolution slider, widgets) for importer
 *
 * @since	1.2
 * @package	WordPress
 */

add_action( 'wp_rem_cs_import_wp_data', 'wp_rem_cs_import_wp_data_handle' );
if ( ! function_exists( 'wp_rem_cs_import_wp_data_handle' ) ) {
	/**
	 * Import WP content using WP importer
	 *
	 * @param CS_Data_Importer $obj An instance of CS_Data_Importer class which contains different configurations.
	 */
	function wp_rem_cs_import_wp_data_handle( $obj ) {
		if ( ! class_exists( 'WP_Importer' ) ) {
			$wp_importer = ABSPATH . 'wp-admin/includes/class-wp-importer.php';
			require_once $wp_importer;
		}

		if ( ! class_exists( 'WP_Import' ) ) {
			require_once $obj->wp_rem_cs_importer_class_path;
		}

		if ( class_exists( 'WP_Importer' ) && class_exists( 'WP_Import' ) ) {
			$importer = new WP_Import();
			$importer->fetch_attachments = true;
			ob_start();
			$importer->import( $obj->wp_data_path );
			ob_end_clean();
			$obj->action_return = true;
		} else {
			$obj->action_return = false;
		}
	}
}

add_action( 'wp_rem_cs_import_theme_options', 'wp_rem_cs_import_wp_options_handle' );
if ( ! function_exists( 'wp_rem_cs_import_wp_options_handle' ) ) {
	/**
	 * Import Theme Options
	 *
	 * @param CS_Data_Importer $obj An instance of CS_Data_Importer class which contains different configurations.
	 */
	function wp_rem_cs_import_wp_options_handle( $obj ) {
		global $wp_filesystem;
		
		require_once ABSPATH . '/wp-admin/includes/file.php';
		
		$theme_options = $wp_filesystem->get_contents( $obj->theme_options_data_path );
		
		// replace attachments upload url's
		if( $obj->demo_data_name == 'rtl' ){
			$obj->demo_data_name = $obj->demo_data_name. '-demo';
		}else{
			$demo_name = explode( 'v', $obj->demo_data_name );
			if( !empty($demo_name) && is_array($demo_name)){
				if( isset($demo_name[0]) && !empty($demo_name[0]) && isset($demo_name[1]) && !empty($demo_name[1])){
					if( $demo_name[1] > 5 ){
						$obj->demo_data_name = 'demo-v'.$demo_name[1];
					}else{
						$obj->demo_data_name = $demo_name[0]. '-v'. $demo_name[1];
					}
				}
			}
		}
		$obj->attachments_replace_url = str_replace( '{{{demo_data_name}}}', $obj->demo_data_name, DEMO_DATA_URL );
		$theme_options = str_replace( str_replace('"','',json_encode($obj->attachments_replace_url)), str_replace('"','',json_encode($obj->wp_upload_url_path)), $theme_options );
		//
		
		$wp_rem_cs_theme_skin = json_decode( $theme_options, true );
		update_option( 'wp_rem_cs_var_options', $wp_rem_cs_theme_skin );

		// Create css file when theme option call.
		if ( function_exists( 'wp_rem_cs_write_stylesheet_content' ) ) {
			wp_rem_cs_write_stylesheet_content();
		}

		$obj->action_return = true;
	}
}

add_action( 'wp_rem_import_fonts', 'wp_rem_import_fonts_handle' );
if ( ! function_exists( 'wp_rem_import_fonts_handle' ) ) {
	/**
	 * Import Fonts
	 *
	 * @param CS_Data_Importer $obj An instance of CS_Data_Importer class which contains different configurations.
	 */
	function wp_rem_import_fonts_handle( $obj ) {
		
		global $wp_filesystem;
		
		require_once ABSPATH . '/wp-admin/includes/file.php';
		
		$fonts = $wp_filesystem->get_contents( $obj->fonts_data_path );
		
		if ( $obj->demo_data_name == DEFAULT_DEMO_DATA_NAME ) {
			$obj->attachments_replace_url = DEFAULT_DEMO_DATA_URL;
		} else {
			if( $obj->demo_data_name == 'rtl' ){
				$obj->demo_data_name = $obj->demo_data_name. '-demo';
			}else{
				$demo_name = explode( 'v', $obj->demo_data_name );
				if( !empty($demo_name) && is_array($demo_name)){
					if( isset($demo_name[0]) && !empty($demo_name[0]) && isset($demo_name[1]) && !empty($demo_name[1])){
						if( $demo_name[1] > 5 ){
							$obj->demo_data_name = 'demo-v'.$demo_name[1];
						}else{
							$obj->demo_data_name = $demo_name[0]. '-v'. $demo_name[1];
						}
					}
				}
			}
			$obj->attachments_replace_url = str_replace( '{{{demo_data_name}}}', $obj->demo_data_name, DEMO_DATA_URL );
			$fonts = str_replace( str_replace('"','',json_encode($obj->attachments_replace_url)), str_replace('"','',json_encode($obj->wp_upload_url_path)), $fonts );
		}
		
		$wp_rem_fonts = json_decode( $fonts );
		
		if( isset($wp_rem_fonts) && !empty($wp_rem_fonts) ){
			foreach( $wp_rem_fonts as $key => $val ){
				if( $key != 'wp_rem_google_fonts' ){
					$val = json_encode($val, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE);
					$val = json_decode( $val, true );
				}
				update_option( $key, $val );
			}
			$obj->action_return = true;
		}else{
			$obj->action_return = false;
		}
	}
}

add_action( 'wp_rem_cs_import_menus_and_locations', 'wp_rem_cs_import_menus_and_locations_handle' );
if ( ! function_exists( 'wp_rem_cs_import_menus_and_locations_handle' ) ) {
	/**
	 * Set Menu's locations
	 *
	 * @param CS_Data_Importer $obj An instance of CS_Data_Importer class which contains different configurations.
	 */
	function wp_rem_cs_import_menus_and_locations_handle( $obj ) {
		global $wp_filesystem, $wpdb;
		
		require_once ABSPATH . '/wp-admin/includes/file.php';
		
		$locations = get_theme_mod( 'nav_menu_locations' );
		$menus_str = $wp_filesystem->get_contents( $obj->menus_data_path );
		$menus = json_decode( $menus_str, true );
		foreach ( $menus as $item ) {
			$term_exists = term_exists( $item['menu_slug'], 'nav_menu' );
			if ( ! $term_exists ) {
				$wpdb->insert(
					$wpdb->terms, array(
						'name' => $item['menu_title'],
						'slug' => $item['menu_slug'],
						'term_group' => 0,
					), array(
						'%s',
						'%s',
						'%d',
					)
				);
				$insert_id = $wpdb->insert_id;
				$locations[ $item['location'] ] = $insert_id;
				$wpdb->insert(
					$wpdb->term_taxonomy, array(
						'term_id' => $insert_id,
						'taxonomy' => 'nav_menu',
						'description' => '',
						'parent' => 0,
						'count' => 0,
					), array(
						'%d',
						'%s',
						'%s',
						'%d',
						'%d',
					)
				);
			} else {
				$locations[ $item['location'] ] = $term_exists['term_id'];
			}
		}
		set_theme_mod( 'nav_menu_locations', $locations );
		$obj->action_return = true;
	}
}

add_action( 'wp_rem_cs_import_setup_pages', 'wp_rem_cs_import_setup_pages_handle' );
if ( ! function_exists( 'wp_rem_cs_import_setup_pages_handle' ) ) {
	/**
	 * Set homepage for site
	 *
	 * @param CS_Data_Importer $obj An instance of CS_Data_Importer class which contains different configurations.
	 */
	function wp_rem_cs_import_setup_pages_handle( $obj ) {
		$homepage = get_page_by_path( $obj->homepage_slug );

		if ( ! empty( $homepage->ID ) ) {
			update_option( 'page_on_front', $homepage->ID );
			update_option( 'show_on_front', 'page' );
			$obj->action_return = true;
		} else {
			$obj->action_return = false;
		}
	}
}

add_action( 'wp_rem_cs_import_rev_sliders', 'wp_rem_cs_import_rev_slider_handle' );
if ( ! function_exists( 'wp_rem_cs_import_rev_slider_handle' ) ) {
	/**
	 * Import Revolution Slider content using Revolution slider's importer
	 *
	 * @param CS_Data_Importer $obj An instance of CS_Data_Importer class which contains different configurations.
	 */
	function wp_rem_cs_import_rev_slider_handle( $obj ) {
		if ( class_exists( 'RevSlider' ) ) {
			global $wp_filesystem;
			
			require_once ABSPATH . '/wp-admin/includes/file.php';
			
			$first_str_filename = $obj->demo_data_name . '-sliders';
			$filename = $obj->wp_upload_dir_path . 'temp-' . $obj->demo_data_name . '-sliders.zip';
			if ( copy( $obj->sliders_data_path, $filename ) ) {
				WP_Filesystem();
				$unzipfile = unzip_file( $filename, $obj->wp_upload_dir_path . '/' . $first_str_filename . '/' );
				// Delete zip after completion.
				unlink( $filename );
			}

			$path = $obj->wp_upload_dir_path . $obj->demo_data_name . '-sliders/';
			if(file_exists( $path.'slider_export.txt')){
				$slider_data = $wp_filesystem->get_contents( $obj->sliders_data_path );
				$wp_rem_cs_upload_dir = wp_upload_dir();
				$slider_file = $wp_rem_cs_upload_dir['path'] . '/slider_data_' . time() . '.zip';
				$wp_filesystem->put_contents( $slider_file, $slider_data );
				if ( file_exists( $slider_file ) ) {
					$slider = new RevSlider();
					$update_anim = isset( $obj->sliders_options[0] ) ? $obj->sliders_options[0] : false;
					$update_static = isset( $obj->sliders_options[1] ) ? $obj->sliders_options[1] : false;
					$update_navigation = isset( $obj->sliders_options[2] ) ? $obj->sliders_options[2] : false;
					$slider->importSliderFromPost( $update_anim, $update_static, $slider_file, false, false, $update_navigation );
				}
				// Delete slider conetnt file after import.
				unlink( $slider_file );
			}else{
				$sub_dir = $path. $obj->demo_data_name .'_sliders';
				if ( $dir = opendir( $sub_dir ) ) {
					// iterate over all demo directories
					while ( false !== ( $file = readdir( $dir ) ) ) {
						if ( $file != "." && $file != ".." ) {
							$info = pathinfo( $file );
							if ( $file != "." && $file != ".." ) {
								$info = pathinfo( $file );
								if ( $info["extension"] === 'zip' ) {
									$slider_data = $wp_filesystem->get_contents( $sub_dir . '/' . $file );
									$wp_rem_cs_upload_dir = wp_upload_dir();
									$slider_file = $wp_rem_cs_upload_dir['path'] . '/slider_data_' . time() . '.zip';
									$wp_filesystem->put_contents( $slider_file, $slider_data );
									if ( file_exists( $slider_file ) ) {
										$slider = new RevSlider();
										$update_anim = isset( $obj->sliders_options[0] ) ? $obj->sliders_options[0] : false;
										$update_static = isset( $obj->sliders_options[1] ) ? $obj->sliders_options[1] : false;
										$update_navigation = isset( $obj->sliders_options[2] ) ? $obj->sliders_options[2] : false;
										$slider->importSliderFromPost( $update_anim, $update_static, $slider_file, false, false, $update_navigation );
									}
									// Delete slider conetnt file after import.
									unlink( $slider_file );
								}
							}
						}
					}
				}
				closedir( $dir );
			}
			// Delete sliders folder after import.
			$wp_filesystem->rmdir( $path, true );
			$obj->action_return = true;
		} else {
			$obj->action_return = false;
		}
	}
}

add_action( 'wp_rem_cs_import_widgets', 'wp_rem_cs_import_widgets_handle' );
if ( ! function_exists( 'wp_rem_cs_import_widgets_handle' ) ) {
	/**
	 * Import Widgets
	 *
	 * @param CS_Data_Importer $obj An instance of CS_Data_Importer class which contains different configurations.
	 */
	function wp_rem_cs_import_widgets_handle( $obj ) {
		if ( class_exists( 'wp_rem_cs_var_widget_data' ) ) {
			wp_rem_cs_var_widget_data::wp_rem_cs_import_widget_data( $obj->widget_data_path );
			$obj->action_return = true;
		} else {
			$obj->action_return = false;
		}
	}
}