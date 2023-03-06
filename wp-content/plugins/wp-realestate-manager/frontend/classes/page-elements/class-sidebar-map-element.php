<?php
/**
 * File Type: Property Sidebar Map Page Element
 */
if ( ! class_exists('wp_rem_sidebar_map_element') ) {

	class wp_rem_sidebar_map_element {

		/**
		 * Start construct Functions
		 */
		public function __construct() {
			add_action('wp_rem_sidebar_map_html', array( $this, 'wp_rem_sidebar_map_html_callback' ), 11, 2);
			add_action('wp_rem_property_sidebar_map_html', array( $this, 'wp_rem_property_sidebar_map_html_callback' ), 10, 2);
			add_action('wp_ajax_wp_rem_property_sidebar_map', array( $this, 'wp_rem_property_sidebar_map_results_callback' ));
			add_action('wp_ajax_nopriv_wp_rem_property_sidebar_map', array( $this, 'wp_rem_property_sidebar_map_results_callback' ));
		}

		/**
		 * Yelp places result html
		 * */
		public function wp_rem_property_sidebar_map_html_callback($property_id = '', $view = 'view-1') {
			global $wp_rem_plugin_options;
			
			$property_type_slug = get_post_meta($property_id, 'wp_rem_property_type', true);
			$property_type_post = get_page_by_path($property_type_slug, OBJECT, 'property-type');
			$type_id = isset($property_type_post->ID) ? $property_type_post->ID : 0;

			$map_in_content = get_post_meta($type_id, 'wp_rem_location_element', true);
			if ( $map_in_content != 'on') {
				return false;
			}
				
			wp_enqueue_script('wp-rem-google-map-api');
			$wp_rem_map_markers_data = isset($wp_rem_plugin_options['wp_rem_map_markers_data']) ? $wp_rem_plugin_options['wp_rem_map_markers_data'] : array();
			if ( isset($wp_rem_map_markers_data['image']) && is_array($wp_rem_map_markers_data['image']) && sizeof($wp_rem_map_markers_data['image']) > 0 ) {

				foreach ( $wp_rem_map_markers_data['image'] as $key => $row ) {
					$icon_group = isset($wp_rem_map_markers_data['icon_group'][$key]) ? $wp_rem_map_markers_data['icon_group'][$key] : '';
					wp_enqueue_style('cs_icons_data_css_' . $icon_group);
				}
			}
			?>
			<div class="widget widget-map-sec property-detail-section-loader" id="property_detail_sidebar_map_<?php echo absint($property_id); ?>" style="min-height:320px;">
				<script>
			        jQuery(document).ready(function () {
						wp_rem_load_sidebar_map_html(<?php echo absint($property_id); ?>, '<?php echo esc_html($view); ?>');
			        });
				</script>
			</div>
			<?php
		}

		public function wp_rem_property_sidebar_map_results_callback() {
			global $wp_rem_plugin_options, $post;

			$property_id = wp_rem_get_input('property_id');
			$det_view = wp_rem_get_input('view');
			$result = '';
			$response['status'] = false;
			$response['result'] = '';

			$sidebar_map = isset($wp_rem_plugin_options['wp_rem_property_detail_page_sidebar_map']) ? $wp_rem_plugin_options['wp_rem_property_detail_page_sidebar_map'] : '';
			if ( $sidebar_map != 'on' ) {
				//return;
			}

			if ( $property_id == '' ) {
				$property_id = $post->ID;
			}
			if ( $property_id != '' ) {
				
				$property_type_slug = get_post_meta($property_id, 'wp_rem_property_type', true);
				$property_type_post = get_page_by_path($property_type_slug, OBJECT, 'property-type');
				$type_id = isset($property_type_post->ID) ? $property_type_post->ID : 0;
				
				$map_in_content = get_post_meta($type_id, 'wp_rem_location_element', true);
				if ( $map_in_content != 'on') {
					return false;
				}
				
				$default_zoom_level = ( isset($wp_rem_plugin_options['wp_rem_map_zoom_level']) && $wp_rem_plugin_options['wp_rem_map_zoom_level'] != '' ) ? $wp_rem_plugin_options['wp_rem_map_zoom_level'] : 10;
				$wp_rem_post_loc_latitude = get_post_meta($property_id, 'wp_rem_post_loc_latitude_property', true);
				$wp_rem_post_loc_longitude = get_post_meta($property_id, 'wp_rem_post_loc_longitude_property', true);
				$wp_rem_post_loc_address_property = get_post_meta($property_id, 'wp_rem_post_loc_address_property', true);
				$wp_rem_property_zoom = get_post_meta($property_id, 'wp_rem_post_loc_zoom_property', true);
				if ( $wp_rem_property_zoom == '' || $wp_rem_property_zoom == 0 ) {
					$wp_rem_property_zoom = $default_zoom_level;
				}

				$property_type_id = '';
				$property_type = get_post_meta($property_id, 'wp_rem_property_type', true);
				if ( $property_type != '' ) {
					$property_type_post = get_posts(array( 'posts_per_page' => '1', 'post_type' => 'property-type', 'name' => "$property_type", 'post_status' => 'publish' ));
					$property_type_id = isset($property_type_post[0]->ID) ? $property_type_post[0]->ID : 0;
				}
				$map_marker_icon = get_post_meta($property_type_id, 'wp_rem_property_type_marker_image', true);
				$map_marker_icon = wp_get_attachment_url($map_marker_icon);
				ob_start();
				$map_height = '380';
				if ( $det_view == 'view-5' || $det_view == 'view-1' ) {
					$map_height = '305';
				}
				$map_atts = array(
					'map_height' => $map_height,
					'map_lat' => $wp_rem_post_loc_latitude,
					'map_lon' => $wp_rem_post_loc_longitude,
					'map_zoom' => $wp_rem_property_zoom,
					'map_type' => '',
					'map_info' => $wp_rem_post_loc_address_property, //$wp_rem_post_comp_address,
					'map_info_width' => '200',
					'map_info_height' => '350',
					'map_marker_icon' => $map_marker_icon,
					'map_show_marker' => 'true',
					'map_controls' => 'true',
					'map_draggable' => 'true',
					'map_scrollwheel' => 'false',
					'map_border' => '',
					'map_border_color' => '',
					'wp_rem_map_style' => '',
					'wp_rem_map_class' => '',
					'wp_rem_map_directions' => 'off',
					'wp_rem_map_circle' => '',
					'wp_rem_nearby_places' => true,
					'property_id' => $property_id,
					'map_det_view' => $det_view
				);
				if ( function_exists('wp_rem_map_content') ) {
					wp_rem_map_content($map_atts, false);
				}
				$result .= ob_get_clean();
				$response['status'] = true;
				$response['result'] = $result;
			}

			echo json_encode($response);
			wp_die();
		}

		public function wp_rem_sidebar_map_html_callback($property_id = '', $det_view = '') {
			global $post, $wp_rem_plugin_options;
			
			$property_type_slug = get_post_meta($property_id, 'wp_rem_property_type', true);
			$property_type_post = get_page_by_path($property_type_slug, OBJECT, 'property-type');
			$property_type_id = isset($property_type_post->ID) ? $property_type_post->ID : 0;
			
			$sidebar_map = wp_rem_element_hide_show($property_id, 'sidebar_map');
			if ( $sidebar_map != 'on' ) {
				return;
			}

			if ( $property_id == '' ) {
				$property_id = $post->ID;
			}
			if ( $property_id != '' ) {
				$default_zoom_level = ( isset($wp_rem_plugin_options['wp_rem_map_zoom_level']) && $wp_rem_plugin_options['wp_rem_map_zoom_level'] != '' ) ? $wp_rem_plugin_options['wp_rem_map_zoom_level'] : 10;
				$wp_rem_post_loc_latitude = get_post_meta($property_id, 'wp_rem_post_loc_latitude_property', true);
				$wp_rem_post_loc_longitude = get_post_meta($property_id, 'wp_rem_post_loc_longitude_property', true);
				$wp_rem_post_loc_address_property = get_post_meta($property_id, 'wp_rem_post_loc_address_property', true);
				$wp_rem_property_zoom = get_post_meta($property_id, 'wp_rem_post_loc_zoom_property', true);
				if ( $wp_rem_property_zoom == '' || $wp_rem_property_zoom == 0 ) {
					$wp_rem_property_zoom = $default_zoom_level;
				}

				$wp_rem_near_by_options = get_post_meta($property_type_id, 'wp_rem_near_by_options_element', true);
				$map_marker_icon = get_post_meta($property_type_id, 'wp_rem_property_type_marker_image', true);
				
				$map_marker_icon = wp_get_attachment_url($map_marker_icon);
				
				?>
				<div class="widget widget-map-sec">
					<?php 
					$map_dynmaic_no = rand(1000000, 99999999);
					if( $wp_rem_near_by_options == 'on' ){
						echo $this->wp_rem_sidaebar_map_markers_nearby($map_dynmaic_no); 
					}
					$map_atts = array(
                        'map_rand_num' => $map_dynmaic_no,
                        'map_height' => '380',
                        'map_lat' => $wp_rem_post_loc_latitude,
                        'map_lon' => $wp_rem_post_loc_longitude,
                        'map_zoom' => $wp_rem_property_zoom,
                        'map_type' => '',
                        'map_info' => $wp_rem_post_loc_address_property, //$wp_rem_post_comp_address,
                        'map_info_width' => '200',
                        'map_info_height' => '350',
                        'map_marker_icon' => $map_marker_icon,
                        'map_show_marker' => 'false',
                        'map_controls' => 'true',
                        'map_draggable' => 'true',
                        'map_scrollwheel' => 'false',
                        'map_border' => '',
                        'map_border_color' => '',
                        'wp_rem_map_style' => '',
                        'wp_rem_map_class' => '',
                        'wp_rem_map_directions' => 'off',
                        'wp_rem_map_circle' => '',
                        'map_det_view' => $det_view,
                        'wp_rem_nearby_places' => $wp_rem_near_by_options,
                    );
                    if (function_exists('wp_rem_map_content')) {
                        $this->wp_rem_sidebar_map_content($map_atts);
                    }
					?>
				</div>

				<?php
			}
		}
		
		public function wp_rem_sidaebar_map_markers_nearby($map_dynmaic_no = '') {
			global $wp_rem_plugin_options, $wp_rem_form_fields_frontend;
			$wp_rem_map_markers_data = isset($wp_rem_plugin_options['wp_rem_map_markers_data']) ? $wp_rem_plugin_options['wp_rem_map_markers_data'] : array();
			$distance_symbol = isset($wp_rem_plugin_options['wp_rem_distance_measure_by']) ? $wp_rem_plugin_options['wp_rem_distance_measure_by'] : 'km';
			if ( isset($wp_rem_map_markers_data['image']) ) :
				
				?>
				<div class="map-checkboxes-v2">
					<div class="swiper-container">
						<ul class="swiper-wrapper">
							<?php
							foreach ( $wp_rem_map_markers_data['image'] as $key => $row ) :
								$image = isset($wp_rem_map_markers_data['image'][$key]) ? $wp_rem_map_markers_data['image'][$key] : '';
								$map_image = isset($wp_rem_map_markers_data['map_image'][$key]) ? $wp_rem_map_markers_data['map_image'][$key] : '';
								$title = isset($wp_rem_map_markers_data['label'][$key]) ? $wp_rem_map_markers_data['label'][$key] : '';
								$type = isset($wp_rem_map_markers_data['type'][$key]) ? $wp_rem_map_markers_data['type'][$key] : '';
								$icon_val = isset($wp_rem_map_markers_data['icon'][$key]) ? $wp_rem_map_markers_data['icon'][$key] : '';
								$map_icon_type = isset($wp_rem_map_markers_data['icon_type'][$key]) && $wp_rem_map_markers_data['icon_type'][$key] != '' ? $wp_rem_map_markers_data['icon_type'][$key] : 'image';
								$icon_group = isset($wp_rem_map_markers_data['icon_group'][$key]) ? $wp_rem_map_markers_data['icon_group'][$key] : '';
								wp_enqueue_style('cs_icons_data_css_' . $icon_group);
								?>
								<li class="swiper-slide" data-placement="bottom" data-toggle="tooltip" title="<?php echo esc_html($title); ?>" >
									<?php
									$wp_rem_opt_array = array(
										'std' => '',
										'simple' => true,
										'cust_id' => 'sidebar_'.esc_html($type),
										'cust_name' => esc_html($type),
										'classes' => 'hidden show-poi-checkbox wp_rem_sidebar_show_nearby',
										'extra_atr' => ' data-label="' . esc_html($title) . '" data-image="' . wp_get_attachment_url($map_image) . '"',
									);
									$wp_rem_form_fields_frontend->wp_rem_form_checkbox_render($wp_rem_opt_array);
									if ( $map_icon_type == 'icon' ) {
										?>
										<label class="show-nearby-point-label" for="sidebar_<?php echo esc_html($type); ?>">
											<?php if( $icon_val != '' ){ ?>
												<i class="<?php echo esc_html($icon_val); ?>"></i>
											<?php }else{ ?>
												<?php echo esc_html($title); ?>
											<?php } ?>
										</label>
										<?php
									} else {
										?>
										<label class="show-nearby-point-label" for="sidebar_<?php echo esc_html($type); ?>"><img src="<?php echo wp_get_attachment_url($image); ?>" alt=""></label>
										<?php
									}
									?>
								</li>
								<?php
							endforeach;
							?>
						</ul> 
					</div>
					<div class="swiper-checkbox-next"><i class="icon-arrows"></i></div>
					<div class="swiper-checkbox-prev"><i class="icon-arrows-1"></i></div>
				</div>
				<?php
			endif;
		}
		
		

		public function wp_rem_sidebar_map_content($atts) {

			global $wp_rem_plugin_options, $wp_rem_form_fields_frontend;
			$distance_symbol = isset($wp_rem_plugin_options['wp_rem_distance_measure_by']) ? $wp_rem_plugin_options['wp_rem_distance_measure_by'] : 'km';
			$defaults = array(
				'map_rand_num' => '',
				'map_height' => '',
				'map_lat' => '51.507351',
				'map_lon' => '-0.127758',
				'map_zoom' => '10',
				'map_type' => '',
				'map_info' => '',
				'map_info_width' => '200',
				'map_info_height' => '200',
				'map_marker_icon' => '',
				'map_show_marker' => 'true',
				'map_controls' => 'true',
				'map_draggable' => 'true',
				'map_scrollwheel' => 'false',
				'map_border' => '',
				'map_border_color' => '',
				'wp_rem_map_style' => '',
				'wp_rem_map_class' => '',
				'wp_rem_map_directions' => 'off',
				'wp_rem_map_circle' => 'off',
				'wp_rem_nearby_places' => false,
				'wp_rem_branches_map' => false,
				'wp_rem_branches_markers' => array(),
				'map_det_view' => '',
			);
			extract(shortcode_atts($defaults, $atts));
			wp_enqueue_script('wp-rem-google-map-api');
			wp_enqueue_script('wp-rem-map-styles');
			if ( $map_info_width == '' || $map_info_height == '' ) {
				$map_info_width = '300';
				$map_info_height = '150';
			}
			if ( isset($map_height) && $map_height == '' ) {
				$map_height = '500';
			}

			$map_dynmaic_no = rand(1165480, 99999999);

			if ( $map_rand_num != '' ) {
				$map_dynmaic_no = $map_rand_num;
			}

			$border = '';
			if ( isset($map_border) && $map_border == 'yes' && $map_border_color != '' ) {
				$border = 'border:1px solid ' . $map_border_color . '; ';
			}

			$map_type = isset($map_type) ? $map_type : '';
			$radius_circle = isset($wp_rem_plugin_options['wp_rem_default_radius_circle']) ? $wp_rem_plugin_options['wp_rem_default_radius_circle'] : '10';
			$radius_circle = ($radius_circle * 1000);

			ob_start();

			$map_col_class = 'col-lg-12 col-md-12 col-sm-12 col-xs-12';
			echo '<div class="row">';
			echo '<div class="' . $map_col_class . '">';


			if ( $map_marker_icon == '' ) {
				$map_marker_icon = isset($wp_rem_plugin_options['wp_rem_map_marker_icon']) ? $wp_rem_plugin_options['wp_rem_map_marker_icon'] : '';
				$map_marker_icon = wp_get_attachment_url($map_marker_icon);
			}

			$html = ob_get_clean();


			$html .= '<div ' . $wp_rem_map_class . ' style="animation-duration:">';
			
			$html .= '<div class="clear"></div>';
			$html .= '<div class="cs-map-section" style="' . $border . ';">';
			$distance_symbol = isset($wp_rem_plugin_options['wp_rem_distance_measure_by']) ? $wp_rem_plugin_options['wp_rem_distance_measure_by'] : 'km';
			$html .= '<div class="sidebar-map-places-radius-box">
				<div>
					<ul class="sidebar-radius-val-dropdown">
						<li>
							<span class="sidebar-radius-val-km sidebar-dev-ch-radius-val" data-val="5">5 '. esc_html($distance_symbol) .'</span>
							<ul>
								<li><span class="sidebar-radius-val-km" data-val="1">1 '. esc_html($distance_symbol) .'</span></li>
								<li><span class="sidebar-radius-val-km" data-val="2">2 '. esc_html($distance_symbol) .'</span></li>
								<li><span class="sidebar-radius-val-km" data-val="3">3 '. esc_html($distance_symbol) .'</span></li>
								<li><span class="sidebar-radius-val-km" data-val="4">4 '. esc_html($distance_symbol) .'</span></li>
								<li><span class="sidebar-radius-val-km" data-val="5">5 '. esc_html($distance_symbol) .'</span></li>
							</ul>
						</li>
					</ul>
					<input type="hidden" id="sidebar-map-radius-input-'. esc_html($map_dynmaic_no) .'" value="5">
				</div>
			</div>';
			$html .= '<div class="cs-map">
			<span id="sidebar-click-map-view-changed" style="position:absolute;">&nbsp;</span>';
			$html .= '<div class="cs-map-content">';
			
			$html .= '<div class="mapcode iframe mapsection gmapwrapp" id="map_canvas' . $map_dynmaic_no . '" style="height:' . $map_height . 'px;"> </div>';


			$html .= '</div>';
			$html .= '</div>';

			$html .= "<script type='text/javascript'>
						jQuery(document).ready(function() {";



			$html .= "
			function mapZoomControlBtns(map, icon_plus, icon_minus) {
				'use strict';
				var controlDiv = document.createElement('div');
				controlDiv.className = 'wp-rem-map-zoom-controls';
				controlDiv.index = 1;
				//controlDiv.style.margin = '6px';
				var controlPlus = document.createElement('a');
				controlPlus.className = 'control-zoom-in';
				controlPlus.innerHTML = '<i class=\"'+icon_plus+'\"></i>';
				controlDiv.appendChild(controlPlus);
				var controlMinus = document.createElement('a');
				controlMinus.className = 'control-zoom-out';
				controlMinus.innerHTML = '<i class=\"'+icon_minus+'\"></i>';
				controlDiv.appendChild(controlMinus);

				google.maps.event.addDomListener(controlPlus, 'click', function () {
					var curZoom = map.getZoom();
					if (curZoom < 20) {
						var newZoom = curZoom+1;
						map.setZoom(newZoom);
						var mapZoomLvl = map.getZoom();
					}
				});
				google.maps.event.addDomListener(controlMinus, 'click', function () {
					var curZoom = map.getZoom();
					if (curZoom > 0) {
						var newZoom = curZoom-1;
						map.setZoom(newZoom);
						var mapZoomLvl = map.getZoom();
					}
				});
				return controlDiv;
			}";

			$html .= "
						var center = new google.maps.LatLng(" . $map_lat . ", " . $map_lon . ");  
						var panorama;
						function sidebar_initialize() {
							var myLatlng = new google.maps.LatLng(" . $map_lat . ", " . $map_lon . ");

							var mapOptions = {
								zoom: " . $map_zoom . ",
								scrollwheel: " . $map_scrollwheel . ",
								draggable: " . $map_draggable . ",
								gestureHandling: 'greedy',
								streetViewControl: false,";
								
			$html .= "
								center: center,
								disableDefaultUI: true,
								zoomControl: false,
								gestureHandling: 'greedy',
								mapTypeId: 'terrain',
								mapTypeControl: false,";
			$html .= "
							};";
			$html .= "var directionsDisplay;
			var directionsService = new google.maps.DirectionsService();
			directionsDisplay = new google.maps.DirectionsRenderer();";

			$html .= "var map = new google.maps.Map(document.getElementById('map_canvas" . $map_dynmaic_no . "'), mapOptions);";



				$html .= "


				map.controls[google.maps.ControlPosition.RIGHT_BOTTOM].push(mapZoomControlBtns(map, 'icon-plus', 'icon-minus'));";


			if ( $wp_rem_map_circle == 'on' ) {

				$html .= "var circle = new google.maps.Circle({
							center: center,
							map: map,
							radius: " . $radius_circle . ",          // IN METERS.
							fillColor: '#FF6600',
							fillOpacity: 0.3,
							strokeColor: '#FF6600',
							strokeWeight: 1         // CIRCLE BORDER.     
						});";
			}

			$html .= "
			directionsDisplay.setMap(map);
			directionsDisplay.setPanel(document.getElementById('map-direction-detail-" . $map_dynmaic_no . "'));
			directionsDisplay.setOptions( { suppressMarkers: true } );";

			$wp_rem_map_style = isset($wp_rem_plugin_options['wp_rem_def_map_style']) ? $wp_rem_plugin_options['wp_rem_def_map_style'] : '';
			$map_custom_style = isset($wp_rem_plugin_options['wp_rem_map_custom_style']) ? $wp_rem_plugin_options['wp_rem_map_custom_style'] : '';

			if ( $map_custom_style != '' ) {
				$map_custom_style = str_replace('&quot;', '"', $map_custom_style);
				$html .= "var style = " . $map_custom_style . ";
						if (style != '') {
							var styledMap = new google.maps.StyledMapType(style,
									{name: 'Styled Map'});
							map.mapTypes.set('map_style', styledMap);
							map.setMapTypeId('map_style');
						}";
			} else {
				$html .= "var style = '" . $wp_rem_map_style . "';
						if (style != '') { 
							var styles = wp_rem_map_select_style(style);
							if (styles != '') {
								var styledMap = new google.maps.StyledMapType(styles, {name: 'Styled Map'});
								map.mapTypes.set('map_style', styledMap);
								map.setMapTypeId('map_style');
							}
						}";
			}



				$html .= "var infowindow = new google.maps.InfoWindow({
					content: '" . $map_info . "',
					maxWidth: " . $map_info_width . ",
					maxHeight: " . $map_info_height . ",
				});
				var marker = new google.maps.Marker({
					position: myLatlng,
					map: map,
					animation: google.maps.Animation.DROP,
					title: '',
					draggable: true,
					icon: '" . $map_marker_icon . "',
					shadow: ''
				});
				if (infowindow.content != ''){
				  infowindow.open(map, marker);
				   map.panBy(1,-60);
				   google.maps.event.addListener(marker, 'click', function(event) {
					infowindow.open(map, marker);
				   });
				};";


			$html .= "panorama = map.getStreetView();
								panorama.setPosition(myLatlng);
								panorama.setPov(({
								  heading: 265,
								  pitch: 0
								}));
							";

			if ( isset($wp_rem_nearby_places) && $wp_rem_nearby_places == true ) {
				$distance_symbol = isset($wp_rem_plugin_options['wp_rem_distance_measure_by']) ? $wp_rem_plugin_options['wp_rem_distance_measure_by'] : 'km';
				ob_start();
				?>
				var markersArray = [];

				var map_slide_loader = $('.slide-loader');

				$(document).on('click', '.sidebar .sidebar-radius-val-dropdown ul > li', function() {
				var distance_symbol = '<?php echo $distance_symbol; ?>';
				var this_val = $(this).find('.sidebar-radius-val-km').attr('data-val');
				var this_val_org = $(this).find('.sidebar-radius-val-km').attr('data-val');
				if( distance_symbol == 'miles' ){
				this_val = parseInt(this_val) * 1.6093;
				}
				$('.sidebar-dev-ch-radius-val').attr('data-val', this_val);
				$('.sidebar-dev-ch-radius-val').html(this_val_org + '<?php echo esc_html($distance_symbol) ?>');
				$('#sidebar-map-radius-input-<?php echo esc_html($map_dynmaic_no) ?>').val(this_val);
				jQuery('#sidebar-map-radius-input-<?php echo $map_dynmaic_no; ?>').trigger('change');
				$('.sidebar .sidebar-radius-val-dropdown li > ul').hide();
				});

				jQuery(document).on('change', '#sidebar-map-radius-input-<?php echo esc_html($map_dynmaic_no) ?>', function(){
				if (!map_slide_loader.hasClass('loading')) {
				map_slide_loader.addClass('loading');
				}
				if ($('.sidebar input.wp_rem_sidebar_show_nearby:checked').length !== 0) {
				var datType = $('.sidebar input.wp_rem_sidebar_show_nearby:checked').attr('id');
				var map_center = map.getCenter();
				var datImage = $('.sidebar input.wp_rem_sidebar_show_nearby:checked').attr('data-image');
				var datLabel = $('.sidebar input.wp_rem_sidebar_show_nearby:checked').attr('data-label');
				sidebar_search_types(datType, map_center, datImage, datLabel);
				}
				});

				jQuery(document).ready(function(){
				$(document).on('hover', '.sidebar .sidebar-radius-val-dropdown li', function(){
				$(this).find('ul').show();
				});
				var stIntrval = setInterval(function(){
				$('.sidebar input.wp_rem_sidebar_show_nearby:first').trigger('click');
				$('.sidebar input.wp_rem_sidebar_show_nearby:first').prop('checked', true);
				$('.sidebar-map-places-radius-box, .map-places-detail-boxes').show();
				clearInterval(stIntrval);
				}, 1000);
				});


				$('.wp_rem_sidebar_show_nearby').click(function () {
				
				if (!map_slide_loader.hasClass('loading')) {
					map_slide_loader.addClass('loading');
				}
				
				$('.sidebar .wp_rem_sidebar_show_nearby').prop('checked', false);
				$('.sidebar .wp_rem_sidebar_show_nearby').removeAttr('checked');
				$(this).prop('checked', true);
				$(this).attr('checked', 'checked');

				var map_center = map.getCenter();
				if ( $(this).is(":checked") ) {

				directionsDisplay.setDirections({routes: []});
				sidebar_clearOverlays();
				sidebar_search_types( $(this).attr('id'), map_center, $(this).data('image'), $(this).data('label') );
				} else {
				sidebar_clearOverlays();
				$('.sidebar .wp_rem_sidebar_show_nearby:checked').each(function(key, elem) {
				sidebar_search_types( $(this).attr('id'), map_center, $(this).data('image'), $(this).data('label') );
				});
				}
				});

				sidebar_clearOverlays();
				$('.sidebar .wp_rem_sidebar_show_nearby:checked').each(function(key, elem) {
				sidebar_search_types( $(this).attr('id'), map.getCenter(), $(this).data('image'), $(this).data('label') );
				});

				function sidebar_search_types(type, latLng, image, label) {
				type = type.replace('sidebar_', '');
				LatLngList = [];
				
				latLng = new google.maps.LatLng(<?php echo ($map_lat) ?>, <?php echo ($map_lon) ?>);
				LatLngList.push(new google.maps.LatLng(<?php echo ($map_lat) ?>, <?php echo ($map_lon) ?>));
				if (!latLng) {
				var latLng = pyrmont;
				}
				var icon = image;

				var randNum = Math.random()*1000000;
				randNum = Math.ceil(randNum);

				var inpRadius<?php echo ($map_dynmaic_no); ?> = jQuery('#sidebar-map-radius-input-<?php echo $map_dynmaic_no; ?>').val();

				if ( typeof inpRadius<?php echo ($map_dynmaic_no); ?> === "undefined" || inpRadius<?php echo ($map_dynmaic_no); ?> < 1) {
				inpRadius<?php echo ($map_dynmaic_no); ?> = 1;
				}

				var newInpRadius<?php echo ($map_dynmaic_no); ?> = inpRadius<?php echo ($map_dynmaic_no); ?> * 1000;
				
				var request<?php echo ($map_dynmaic_no); ?> = {
				location: latLng,
				radius: newInpRadius<?php echo ($map_dynmaic_no); ?>,
				types: [type] //e.g. school,restaurant,bank,bar,city_hall,gym,night_club,park,zoo
				};

				sidebar_clearOverlays();

				var service<?php echo ($map_dynmaic_no); ?> = new google.maps.places.PlacesService(map);
				service<?php echo ($map_dynmaic_no); ?>.nearbySearch(request<?php echo ($map_dynmaic_no); ?>, function (results<?php echo ($map_dynmaic_no); ?>, status) {

				var preZoomLvl = map.getZoom();
				map.setZoom(preZoomLvl);
				if (status == google.maps.places.PlacesServiceStatus.OK) {

				for (var i = 0; i < results<?php echo ($map_dynmaic_no); ?>.length; i++) {
				results<?php echo ($map_dynmaic_no); ?>[i].html_attributions = '';

				var markerCountr = '<?php echo esc_html($map_dynmaic_no) ?>' + String(i);

				sidebar_createMarker(results<?php echo ($map_dynmaic_no); ?>[i], icon, markerCountr);

				if ((i+1) == results<?php echo ($map_dynmaic_no); ?>.length) {
				map_slide_loader.removeClass('loading');
				}
				}

				if (LatLngList.length > 0) {
				var latlngbounds = new google.maps.LatLngBounds();
				for (var i = 0; i < LatLngList.length; i++) {
				latlngbounds.extend(LatLngList[i]);
				}
				//map.setCenter(latlngbounds.getCenter(), map.fitBounds(latlngbounds));
				//map.panTo(latlngbounds.getCenter(), map.fitBounds(latlngbounds));
				//preZoomLvl = map.getZoom();
				//map.setZoom(preZoomLvl);
				map.setCenter(latLng);
				map.setZoom(preZoomLvl);
				}
				} else {
				//if (preZoomLvl > 9) {
				//preZoomLvl = 9;
				//}
				map.setCenter(latLng);
				map.setZoom(preZoomLvl);
				}
				});
				}
				function sidebar_createMarker(place, icon, countr) {
					var placeLoc = place.geometry.location;
					var marker = new google.maps.Marker({
					map: map,
					animation: google.maps.Animation.DROP,
					position: place.geometry.location,
					icon: icon,
					draggable: true,
					visible: true,
				});

				markersArray.push(marker);
				google.maps.event.addListener(marker, 'click', function () {
				infowindow.setContent("<b>" + place.name + "</b><br>" + place.vicinity);
				infowindow.open(map, this);
				});

				var placeLat = placeLoc.lat();
				var placeLng = placeLoc.lng();
				LatLngList.push(new google.maps.LatLng(placeLat, placeLng));
				}

				function calcDistanceBtwPlaces(fromLat, fromLng, toLat, toLng) {
				return google.maps.geometry.spherical.computeDistanceBetween(new google.maps.LatLng(fromLat, fromLng), new google.maps.LatLng(toLat, toLng));
				}

				function meterToKmConvert(numbr) {
				numbr = parseFloat(numbr);
				var dist = numbr;
				var unit = 'm';
				if (numbr > 999) {
				dist = numbr/1000;
				unit = '<?php echo esc_html($distance_symbol) ?>';
				}
				var roundDist = parseFloat(Math.round(dist * 100) / 100).toFixed(2);

				return String(roundDist) + ' ' + unit;
				}

				// Deletes all markers in the array by removing references to them
				function sidebar_clearOverlays() {
				if (markersArray) {
				for (i in markersArray) {
				infowindow.close();
				markersArray[i].setVisible(false);
				}
				}
				}
				<?php
				$html .= ob_get_clean();
			}
			$html .= "}
			function wp_rem_toggle_street_view(btn) {
			  var toggle = panorama.getVisible();
			  if (toggle == false) {
					if(btn == 'streetview'){
					  panorama.setVisible(true);
					}
			  } else {
					if(btn == 'mapview'){
					  panorama.setVisible(false);
					}
			  }
			}";

			// Setting zoom level
			$html .= "
			google.maps.event.addDomListener(document.getElementById('sidebar-click-map-view-changed'), 'click', function () {
				var getCurZoom = map.getZoom();
				if (getCurZoom < 3) {
					if (LatLngList.length > 0) {
						var latlngbounds = new google.maps.LatLngBounds();
						for (var io = 0; io < LatLngList.length; io++) {
							latlngbounds.extend(LatLngList[io]);
						}
						map.setCenter(latlngbounds.getCenter(), map.fitBounds(latlngbounds));
						map.setZoom(map.getZoom());
					} else {
						map.setZoom(" . $map_zoom . "); 
					}
					google.maps.event.trigger(map, 'resize');
				}
			});";
			//

			$html .= "
			google.maps.event.addDomListener(window, 'load', sidebar_initialize);";
			$html .= "});</script>";
				$html .= '</div>';
				$html .= '</div>';
				// col class end
				$html .= '</div>';
				// row end
				$html .= '</div>';

			echo $html;
		}
	
		
	}

	global $wp_rem_sidebar_map;
	$wp_rem_sidebar_map = new wp_rem_sidebar_map_element();
}