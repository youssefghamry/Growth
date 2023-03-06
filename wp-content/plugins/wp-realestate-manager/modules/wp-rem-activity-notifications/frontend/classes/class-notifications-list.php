<?php

/**
 * File Type: Notifications List for frontend
 */
if ( ! class_exists('Wp_rem_Activity_Notifications_List') ) {

    class Wp_rem_Activity_Notifications_List {

        /**
         * Start Contructer Function
         */
        public function __construct() {
            $notification_status = apply_filters('wp_rem_get_plugin_options', 'wp_rem_activity_notifications_switch');
            if ( isset($notification_status) && $notification_status == 'on' ) {
                wp_enqueue_script('wp-rem-notifications-js');
                add_action('wp_rem_new_notifications', array( &$this, 'wp_rem_new_notifications_callback' ));
                add_action('wp_rem_all_notifications', array( &$this, 'wp_rem_all_notifications_callback' ));
                add_action('wp_ajax_wp_rem_hide_notification', array( &$this, 'wp_rem_hide_notification_callback' ));
                add_action('wp_ajax_wp_rem_clear_all_notification', array( &$this, 'wp_rem_clear_all_notification_callback' ));
                add_action('wp_ajax_wp_rem_notification_loadmore', array( &$this, 'wp_rem_notification_loadmore_callback' ));
            }
        }

        /**
         * Getting Notification list
         * @based on current User ID
         */
        public function wp_rem_new_notifications_callback() {
            global $wp_rem_form_fields_frontend;
            $user_id = get_current_user_id();
            $company_id = get_user_meta($user_id, 'wp_rem_company', true);
            $posts_per_page = 10;
            $args = array(
                'posts_per_page' => $posts_per_page,
                'post_type' => 'notifications',
                'orderby' => 'ID',
                'order' => 'DESC',
                'post_status' => 'publish',
                'meta_query' => array(
                    'relation' => 'AND',
                    array(
                        'key' => 'status',
                        'value' => 'new',
                        'compare' => '=',
                    ),
                    array(
                        'key' => 'reciever_id',
                        'value' => $company_id,
                        'compare' => '=',
                    ),
                ),
            );
            $custom_query = new WP_Query($args);
            $notifications = $custom_query->posts;

            if ( isset($notifications) && ! empty($notifications) ) {
                echo '<div class="user-notification"><div class="wp-rem-clear-notifications"><a href="javascript:;">' . wp_rem_plugin_text_srt('wp_rem_class_nofify_clear_all') . '</a></div><ul>';
                foreach ( $notifications as $notification_data ) {
                    $this->render_view($notification_data);
                }
                echo '</ul></div>';
                $wp_rem_opt_array = array(
                    'std' => 1,
                    'cust_id' => 'current_page',
                    'cust_name' => 'current_page',
                    'cust_type' => 'hidden',
                    'classes' => '',
                );
                $wp_rem_form_fields_frontend->wp_rem_form_text_render($wp_rem_opt_array);

                $wp_rem_opt_array = array(
                    'std' => $custom_query->max_num_pages,
                    'cust_id' => 'max_num_pages',
                    'cust_name' => 'max_num_pages',
                    'cust_type' => 'hidden',
                    'classes' => '',
                );
                $wp_rem_form_fields_frontend->wp_rem_form_text_render($wp_rem_opt_array);
            }
            if ( $custom_query->max_num_pages > 1 ) {
                echo '<div class="load-more-notifications-wrap"><div class="load-more-notifications input-button-loader">' . wp_rem_plugin_text_srt('wp_rem_activity_notifications_load_more') . '</div></div>';
            }
            wp_reset_postdata();
        }

        /**
         * Getting All Notification list
         * @based on current User ID
         */
        public function wp_rem_all_notifications_callback() {
            $user_id = get_current_user_id();
            $args = array(
                'posts_per_page' => "-1",
                'post_type' => 'notifications',
                'post_status' => 'publish',
                'meta_query' => array(
                    'relation' => 'AND',
                    array(
                        'key' => 'reciever_id',
                        'value' => $user_id,
                        'compare' => '=',
                    ),
                ),
            );
            $custom_query = new WP_Query($args);
            $notifications = $custom_query->posts;
            if ( isset($notifications) && ! empty($notifications) ) {
                foreach ( $notifications as $notification_data ) {
                    // $this->render_view( $notification_data );
                }
            }
            wp_reset_postdata();
        }

        /**
         * Render HTML for each notification
         */
        public function render_view($notification_data) {

            wp_enqueue_script('wp-rem-notifications-js');
            global $post;
            $post = $notification_data;
            setup_postdata($post);
            $user_id = get_post_meta(get_the_ID(), 'user_id', true);
            $message = get_post_meta(get_the_ID(), 'notification_content', true);
            $icon = get_post_meta(get_the_ID(), 'notification_icon', true);
            $user_info = get_userdata($user_id);

            echo '<li data-id="' . get_the_ID() . '">
                        <span class="icon-holder">' . $icon . '</span>
                        ' . $message . ' <em>' . human_time_diff(get_the_time('U'), current_time('timestamp', 1)) . ' ago</em>
                            <a href="javascript:void(0);" class="close hide_notification"><i class="icon-close"></i></a>
                 </li>';

            wp_reset_postdata();
        }

        /**
         * Hide notification by id
         */
        public function wp_rem_hide_notification_callback() {
            $id = wp_rem_get_input('id');
            wp_delete_post($id, true);

            $response_array = array(
                'type' => 'success',
                'msg' => wp_rem_plugin_text_srt('wp_rem_notification_removed'),
            );
            echo json_encode($response_array);
            wp_die();
        }

        /**
         * Clear All notifications for member
         */
        public function wp_rem_clear_all_notification_callback() {
            $user_id = get_current_user_id();
            $company_id = get_user_meta($user_id, 'wp_rem_company', true);

            $args = array(
                'posts_per_page' => "-1",
                'post_type' => 'notifications',
                'post_status' => 'publish',
                'meta_query' => array(
                    'relation' => 'AND',
                    array(
                        'key' => 'status',
                        'value' => 'new',
                        'compare' => '=',
                    ),
                    array(
                        'key' => 'reciever_id',
                        'value' => $company_id,
                        'compare' => '=',
                    ),
                ),
            );
            $custom_query = new WP_Query($args);
            $notifications = $custom_query->posts;
            if ( isset($notifications) && ! empty($notifications) ) {
                foreach ( $notifications as $notification_data ) {
                    wp_delete_post($notification_data->ID, true);
                }
            }
            $response_array = array(
                'type' => 'success',
                'msg' => wp_rem_plugin_text_srt('wp_rem_class_nofify_all_notific_rmvd_successfully'),
            );
            echo json_encode($response_array);
            wp_die();
        }

        /*
         * Load More Functionality
         */

        public function wp_rem_notification_loadmore_callback() {
            $user_id = get_current_user_id();
            $company_id = get_user_meta($user_id, 'wp_rem_company', true);
            $current_page = isset($_POST['current_page']) ? $_POST['current_page'] : 2;
            $posts_per_page = 10;
            $args = array(
                'posts_per_page' => $posts_per_page,
                'post_type' => 'notifications',
                'post_status' => 'publish',
                'orderby' => 'ID',
                'order' => 'DESC',
                'paged' => $current_page,
                'meta_query' => array(
                    'relation' => 'AND',
                    array(
                        'key' => 'status',
                        'value' => 'new',
                        'compare' => '=',
                    ),
                    array(
                        'key' => 'reciever_id',
                        'value' => $company_id,
                        'compare' => '=',
                    ),
                ),
            );
            $custom_query = new WP_Query($args);
            $notifications = $custom_query->posts;

            if ( isset($notifications) && ! empty($notifications) ) {
                foreach ( $notifications as $notification_data ) {
                    $this->render_view($notification_data);
                }
            }
            wp_reset_postdata();

            wp_die();
        }

    }

    // Initialize Object
    $wp_rem_activity_notifications_submission_object = new Wp_rem_Activity_Notifications_List();
}