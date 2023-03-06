<?php
/**
 * File Type: Property Sidebar Member info Page Element
 */
if ( ! class_exists('wp_rem_sidebar_member_info_element') ) {

    class wp_rem_sidebar_member_info_element {

        /**
         * Start construct Functions
         */
        public function __construct() {
            add_action('wp_rem_sidebar_member_info_html', array( $this, 'wp_rem_sidebar_member_info_html_callback' ), 11, 2);
        }

        public function wp_rem_sidebar_member_info_html_callback($property_id = '', $view = '') {
            global $wp_rem_plugin_options, $wp_rem_author_info;

            $sidebar_member_info = wp_rem_element_hide_show($property_id, 'sidebar_member_info');
            if ( $sidebar_member_info != 'on' ) {
                return;
            }
            $wp_rem_property_member_id = get_post_meta($property_id, 'wp_rem_property_member', true);
            $wp_rem_property_member_id = isset($wp_rem_property_member_id) ? $wp_rem_property_member_id : '';
            $wp_rem_post_loc_address_member = get_post_meta($wp_rem_property_member_id, 'wp_rem_post_loc_address_member', true);
            $wp_rem_member_title = '';
            if ( isset($wp_rem_property_member_id) && $wp_rem_property_member_id <> '' ) {
                $wp_rem_member_title = get_the_title($wp_rem_property_member_id);
            }
            $wp_rem_member_link = 'javascript:void(0)';
            if ( isset($wp_rem_property_member_id) && $wp_rem_property_member_id <> '' ) {
                $wp_rem_member_link = get_the_permalink($wp_rem_property_member_id);
            }
            $member_image_id = get_post_meta($wp_rem_property_member_id, 'wp_rem_profile_image', true);
            $member_image = wp_get_attachment_url($member_image_id);
            $wp_rem_member_phone_num = get_post_meta($wp_rem_property_member_id, 'wp_rem_phone_number', true);
            $wp_rem_member_email_address = get_post_meta($wp_rem_property_member_id, 'wp_rem_email_address', true);
            $wp_rem_member_email_address = isset($wp_rem_member_email_address) ? $wp_rem_member_email_address : '';
            $http_request = wp_rem_server_protocol();
            ?>
            <div itemscope itemtype="<?php echo force_balance_tags($http_request); ?>schema.org/Person" class="profile-info boxed">
                <?php if ( isset($member_image) && $member_image != '' ) { ?>
                    <div class="img-holder">
                        <figure>
                            <a href="<?php echo esc_url($wp_rem_member_link); ?>">
                                <img itemprop="image" src="<?php echo esc_url($member_image); ?>" alt="" />
                            </a>
                        </figure>
                    </div>
                <?php } ?>
                <div class="text-holder">
                    <?php if ( isset($wp_rem_member_title) && $wp_rem_member_title != '' ) { ?>
                        <a href="<?php echo esc_url($wp_rem_member_link); ?>">
                            <h5 itemprop="name"><?php echo esc_html($wp_rem_member_title); ?></h5>
                        </a>
                    <?php } ?>
                    <?php
                    if ( isset($wp_rem_member_phone_num) && $wp_rem_member_phone_num != '' ) {
                        $all_data = '';
                        $all_data = $wp_rem_author_info->wp_rem_member_member_phone_num($wp_rem_member_phone_num, '', '<strong itemprop="telephone">', '</strong>');
                        echo force_balance_tags($all_data);
                        ?>
                    <?php } ?>
                    <?php if ( isset($wp_rem_post_loc_address_member) && $wp_rem_post_loc_address_member != '' ) { ?>	
                        <ul>
                            <li itemprop="address"><?php echo esc_html($wp_rem_post_loc_address_member); ?></li>
                        </ul>
                    <?php } ?>

                    <div class="field-select-holder">
                        <?php echo do_action('wp_rem_opening_hours_element_html', $wp_rem_property_member_id, 'property-v2'); ?>  
                    </div>
                    <?php
                    $target_modal = 'data-toggle="modal" data-target="#sign-in"';
                    $target_class = ' wp-rem-open-signin-tab';
                    if ( is_user_logged_in() ) {
                        $target_class = '';
                        $target_modal = ' data-toggle="modal" data-target="#enquiry-modal"';
                    }
                    ?>    
                    <a href="javascript:void(0);" class="submit-btn bgcolor<?php echo esc_attr($target_class); ?>" <?php echo $target_modal; ?>><?php echo wp_rem_plugin_text_srt('wp_rem_prop_detail_contact_cnt_agent'); ?></a>
                </div>
            </div>
            <?php
        }

    }

    global $wp_rem_sidebar_member_info;
    $wp_rem_sidebar_member_info = new wp_rem_sidebar_member_info_element();
}