<?php
/**
 * Property search box
 *
 */
global $wp_rem_post_property_types, $wp_rem_plugin_options;

$properties_title_alignment = isset($atts['properties_title_alignment']) ? $atts['properties_title_alignment'] : '';
$property_location_options = isset($atts['property_location']) ? $atts['property_location'] : '';
if ( $property_location_options != '' ) {
    $property_location_options = explode(',', $property_location_options);
}
$main_class = 'recent-property';
$columns_class = 'col-lg-12 col-md-12 col-sm-12 col-xs-12';
$recent_property_loop_obj = wp_rem_get_cached_obj('recent_property_result_cached_loop_obj', $recent_property_args, 12, false, 'wp_query');

$http_request = wp_rem_server_protocol();

if ( $recent_property_loop_obj->have_posts() ) {
    $flag = 1;
    ?>
    <div class="real-estate-recent-properties">
        <div class="row">
            <div class="<?php echo esc_html($columns_class); ?>">
                <div class="element-title <?php echo esc_html($properties_title_alignment); ?>">
                    <h2><?php echo wp_rem_plugin_text_srt('wp_rem_property_recent_heading') ?></h2> 
                </div>
            </div>
            <?php
            while ( $recent_property_loop_obj->have_posts() ) : $recent_property_loop_obj->the_post();
                global $post, $wp_rem_member_profile;
                $property_id = $post;
                $Wp_rem_Locations = new Wp_rem_Locations();
                $get_property_location = $Wp_rem_Locations->get_element_property_location($property_id, $property_location_options);
                $wp_rem_property_price_options = get_post_meta($property_id, 'wp_rem_property_price_options', true);
                $wp_rem_property_type = get_post_meta($property_id, 'wp_rem_property_type', true);
                // checking review in on in property type
                $wp_rem_property_type = isset($wp_rem_property_type) ? $wp_rem_property_type : '';
                if ( $property_type_post = get_page_by_path($wp_rem_property_type, OBJECT, 'property-type') )
                    $property_type_id = $property_type_post->ID;
                $property_type_id = isset($property_type_id) ? $property_type_id : '';
				$property_type_id = wp_rem_wpml_lang_page_id( $property_type_id, 'property-type' );
                $wp_rem_property_type_price_switch = get_post_meta($property_type_id, 'wp_rem_property_type_price', true);
                $wp_rem_property_price = '';
                if ( $wp_rem_property_price_options == 'price' ) {
                    $wp_rem_property_price = get_post_meta($property_id, 'wp_rem_property_price', true);
                } else if ( $wp_rem_property_price_options == 'on-call' ) {
                    $wp_rem_property_price = wp_rem_plugin_text_srt('wp_rem_properties_price_on_request');
                } 
                ?>
                <div class="<?php echo esc_html($columns_class); ?>">
                    <div class="property-medium <?php echo esc_html($main_class); ?> " itemscope itemtype="<?php echo force_balance_tags($http_request); ?>schema.org/Product">
                        <div class="text-holder">
                            <div class="post-title">
                                <h4 itemprop="name"><a href="<?php echo esc_url(get_permalink($property_id)); ?>"><?php echo esc_html(get_the_title($property_id)); ?></a></h4>
                            </div>
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
                            if ( ! empty($get_property_location) ) {
                                ?>
                                <ul class="property-location">
                                    <li><i class="icon-location-pin2"></i><span><?php echo esc_html(implode(', ', $get_property_location)); ?></span></li>
                                </ul>
                                <?php
                            }
                            if ( $wp_rem_property_type_price_switch == 'on' && $wp_rem_property_price != '' ) {
                                ?>
                                <span class="property-price" itemprop="offers" itemscope itemtype="<?php echo force_balance_tags($http_request); ?>schema.org/Offer">
                                    <?php
                                    if ( $wp_rem_property_price_options == 'on-call' ) {
                                        $phone_number = get_post_meta($property_id, 'wp_rem_phone_number_property', true);
                                        echo force_balance_tags($wp_rem_property_price).' '.$phone_number;
                                    } else {
                                        $property_info_price = wp_rem_property_price($property_id, $wp_rem_property_price, '<span class="guid-price">', '</span>', '<span class="price-type">', '</span>', 'right', '<span content="'.$wp_rem_property_price.'" itemprop="price">', '</span>' );
										$wp_rem_get_currency_sign = wp_rem_get_currency_sign('code');
																		echo '<span itemprop="priceCurrency" style="display:none;" content="'.$wp_rem_get_currency_sign.'"></span>';
                                        echo force_balance_tags($property_info_price);
                                    }
                                    ?>
                                </span>
                            <?php } ?>
                        </div> 
                    </div>
                </div>
                <?php
            endwhile;
            ?>
        </div>
    </div>
<?php }
?>
<!--Wp-rem recent Element End-->                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                       