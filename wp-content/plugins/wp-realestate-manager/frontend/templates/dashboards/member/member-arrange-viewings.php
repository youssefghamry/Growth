<?php
/**
 * Member Properties
 *
 */
if ( ! class_exists('Wp_rem_Member_Viewings') ) {

    class Wp_rem_Member_Viewings {

        /**
         * Start construct Functions
         */
        public function __construct() {
            add_action('wp_ajax_wp_rem_member_viewings', array( $this, 'wp_rem_member_viewings_callback' ), 11, 1);
            add_action('wp_ajax_wp_rem_member_received_viewings', array( $this, 'wp_rem_member_received_viewings_callback' ), 11, 1);
        }

        public function wp_rem_member_viewings_callback($member_id = '') {
            // Member ID.
            if ( ! isset($member_id) || $member_id == '' ) {
                $member_id = get_current_user_id();
            }

            $member_company_id = wp_rem_company_id_form_user_id($member_id);
            $args = array(
                'post_type' => 'property_viewings',
                'post_status' => 'publish',
                'posts_per_page' => -1,
                'meta_query' => array(
                    'relation' => 'AND',
                    array(
                        'key' => 'wp_rem_viewing_member',
                        'value' => $member_company_id,
                        'compare' => '=',
                    )
                ),
            );

            $viewing_query = new WP_Query($args);
            echo force_balance_tags($this->render_view_viewings($viewing_query, 'my'));
            wp_reset_postdata();
            wp_die();
        }

        public function wp_rem_member_received_viewings_callback($member_id = '') {
            // Member ID.
            if ( ! isset($member_id) || $member_id == '' ) {
                $member_id = get_current_user_id();
            }

            $member_company_id = wp_rem_company_id_form_user_id($member_id);
            $property_id = wp_rem_get_input('data_param', '');
            $qry_filtr = '';
            if ( $property_id != '' ) {
                $qry_filtr = array(
                    'key' => 'wp_rem_property_id',
                    'value' => $property_id,
                    'compare' => '=',
                );
            }
            $args = array(
                'post_type' => 'property_viewings',
                'post_status' => 'publish',
                'posts_per_page' => -1,
                'meta_query' => array(
                    'relation' => 'AND',
                    array(
                        'key' => 'wp_rem_property_member',
                        'value' => $member_company_id,
                        'compare' => '=',
                    ),
                    $qry_filtr,
                ),
            );

            $viewing_query = new WP_Query($args);
            echo force_balance_tags($this->render_view_viewings($viewing_query, 'received'));
            wp_reset_postdata();
            wp_die();
        }

        public function render_view_viewings($viewing_query = '', $type = 'my') {

            $has_border = ' has-border';
            if ( $viewing_query->have_posts() ) {
                $has_border = '';
            }
            $property_id = wp_rem_get_input('data_param', '');
            $property_title = '';
            if ( $property_id != '' ) {
                $property_title = get_the_title($property_id) . ' > ';
            }
            ?>
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="row">
                    <div class="element-title<?php echo wp_rem_allow_special_char($has_border); ?>">
                        <h4>
                            <?php
                            if ( $type == 'my' ) {
                                echo wp_rem_plugin_text_srt('wp_rem_member_register_requested_viewings');
                            } else {
                                echo $property_title . wp_rem_plugin_text_srt('wp_rem_member_received_viewings');
                            }
                            ?>
                        </h4>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="user-orders-list">
                        <ul class="orders-list viewings-list" id="portfolio">
                            <?php if ( $viewing_query->have_posts() ) : ?>
                                <?php echo force_balance_tags($this->render_list_item_view($viewing_query, $type)); ?>
                            <?php else: ?>
                                <li class="no-order-list-found">
                                    <?php
                                    if ( $type == 'received' ) {
                                        echo wp_rem_plugin_text_srt('wp_rem_member_register_no_viewing');
                                    } else {
                                        echo wp_rem_plugin_text_srt('wp_rem_member_viewings_not_requested_viewing');
                                    }
                                    ?>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </div>
                </div>
            </div>
            <?php
        }

        public function render_list_item_view($viewing_query, $type = 'my') {
            while ( $viewing_query->have_posts() ) : $viewing_query->the_post();
                $viewing_property_id = get_post_meta(get_the_ID(), 'wp_rem_property_id', true);
                $viewing_status = get_post_meta(get_the_ID(), 'wp_rem_viewing_status', true);
                $buyer_read_status = get_post_meta(get_the_ID(), 'buyer_read_status', true);
                $seller_read_status = get_post_meta(get_the_ID(), 'seller_read_status', true);

                if ( $type == 'my' ) {
                    $member_name = get_post_meta(get_the_ID(), 'wp_rem_property_member', true);
                    if ( $buyer_read_status == 1 ) {
                        $read_unread = 'read';
                    } else {
                        $read_unread = 'unread';
                    }
                    $read_status = $buyer_read_status;
                } else {
                    $member_name = get_post_meta(get_the_ID(), 'wp_rem_viewing_member', true);
                    if ( $seller_read_status == 1 ) {
                        $read_unread = 'read';
                    } else {
                        $read_unread = 'unread';
                    }
                    $read_status = $seller_read_status;
                }
                ?>
                <li class="<?php echo esc_html($read_unread); ?>">
                    <div class="img-holder">
                        <figure>
                            <?php
                            if ( function_exists('property_gallery_first_image') ) {
                                $gallery_image_args = array(
                                    'property_id' => $viewing_property_id,
                                    'size' => 'thumbnail',
                                    'class' => '',
                                    'default_image_src' => esc_url(wp_rem::plugin_url() . 'assets/frontend/images/no-image4x3.jpg')
                                );
                                echo $property_gallery_first_image = property_gallery_first_image($gallery_image_args);
                            }
                            ?>
                        </figure>
                    </div>
                    <div class="orders-title">
                        <h6 class="order-title"><a href="javascript:void(0);" onclick="javascript:wp_rem_arrange_viewing_detail('<?php the_ID(); ?>', '<?php echo esc_html($type); ?>', '<?php echo esc_html($read_status); ?>');"><?php echo get_the_title($viewing_property_id); ?></a><span>( #<?php echo get_the_ID(); ?> )</span></h6>
                    </div>
                    <div class="orders-date">
                        <span><?php echo get_the_date('M, d Y'); ?></span>
                    </div>
                    <div class="orders-type">
                        <span><a href="<?php echo get_the_permalink($member_name); ?>"><?php echo get_the_title($member_name); ?></a></span>
                    </div>
                    <div class="orders-status">
                        <?php
                        $order_color = '';
                        $order_status_color = $this->order_status_color($viewing_status);
                        if ( $order_status_color ) {
                            $order_color = 'style="background-color: ' . $order_status_color . ';"';
                        }
                        ?>
						<?php 
						$array_viewing_status = array(
						'Processing'=>esc_html__('Processing','wp-rem'),
						'Completed'=>esc_html__('Completed','wp-rem'),
						'Closed'=>esc_html__('Closed','wp-rem'),
						
					);
    
	                if ( array_key_exists($viewing_status, $array_viewing_status) ) {
                    $req_viewing_status = $array_viewing_status[$viewing_status];
                }
						?>
                <span class="<?php echo isset($order_status) ? strtolower($order_status) : ''; ?>" <?php echo force_balance_tags($order_color); ?>><?php echo $req_viewing_status; ?></span>
                    </div>

                </li>
                <?php
            endwhile;
        }

        public function order_status_color($order_name = 'processing') {
            global $wp_rem_plugin_options;

            $orders_status = isset($wp_rem_plugin_options['orders_status']) ? $wp_rem_plugin_options['orders_status'] : array();
            $orders_color = isset($wp_rem_plugin_options['orders_color']) ? $wp_rem_plugin_options['orders_color'] : array();
            if ( is_array($orders_status) && sizeof($orders_status) > 0 ) {
                foreach ( $orders_status as $key => $lable ) {
                    if ( strtolower($lable) == strtolower($order_name) ) {
                        return $order_color = isset($orders_color[$key]) ? $orders_color[$key] : '';
                        break;
                    }
                }
            }
        }

    }

    global $viewings;
    $viewings = new Wp_rem_Member_Viewings();
}
