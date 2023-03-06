<?php
/**
 * This file handles Reviews functionality which register post type for that and
 * handle AJAX requests and also handle UI rendering for reviews.
 *
 */
if ( ! defined('ABSPATH') ) {
    exit('No direct script access allowed');
}

if ( ! class_exists('Wp_rem_Reviews') ) {

    /**
     * This class register post type for Reviews. Also register options for reviews 
     * in property type and fontend UI.
     *
     */
    class Wp_rem_Reviews {

        public static $post_type_name = 'wp_rem_reviews';
        public static $posts_per_page = 10;

        public function __construct() {
            add_action('add_meta_boxes', array( &$this, 'reviews_add_meta_boxes_callback' ));
            add_action('init', array( $this, 'register_reviews_post_type_callback' ), 15);
            add_action('init', array( $this, 'register_reviews_flag_taxonomy' ));
            add_action('init', array( $this, 'admin_init_callback' ));
            add_action('save_post', array( $this, 'wp_rem_review_fields_data_save' ));
            add_action('wp_enqueue_scripts', array( $this, 'enqueue_scripts' ));
            add_action('publish_' . Wp_rem_Reviews::$post_type_name, array( $this, 'wp_rem_reviews_publish_callback' ), 10, 2);
            add_filter('user_can_richedit', array( $this, 'reviews_wysiwyg_for_RPT' ));
            add_action('admin_head', array( $this, 'reviews_media_btn_remove' ));
        }

        public function reviews_wysiwyg_for_RPT($default) {
            global $post_type;
            if ( 'wp_rem_reviews' == $post_type ) {
                return false;
            }
            return $default;
        }

        public function reviews_text_filter($text) {
            $text = convert_smilies($text);
            $text = nl2br($text);
            $allowed_tags = array(
                'a' => array(
                    'href' => array(),
                    'title' => array()
                ),
                'abbr' => array(
                    'title' => array()
                ),
                'acronym' => array(
                    'title' => array()
                ),
                'blockquote' => array(
                    'cite' => array()
                ),
                'q' => array(
                    'cite' => array()
                ),
                'del' => array(
                    'datetime' => array()
                ),
                'cite' => array(),
                'code' => array(),
                'pre' => array(),
                'b' => array(),
                'i' => array(),
                'br' => array(),
                'em' => array(),
                'strike' => array(),
                'strong' => array(),
            );
            $text = wp_kses($text, $allowed_tags);
            return $text;
        }

        function reviews_media_btn_remove() {
            global $post_type;
            if ( 'wp_rem_reviews' == $post_type ) {
                remove_action('media_buttons', 'media_buttons');
            }
        }

        static function enable_comments($post_id) {
            $show_ratings = 'on';
            if ( $post_id != '' ) {
                $property_type_slug = get_post_meta($post_id, 'wp_rem_property_type', true);
                if ( $property_type_slug != '' ) {
                    $property_type_id = get_page_by_path($property_type_slug, 'OBJECT', 'property-type');
                    $property_type_id = isset($property_type_id->ID) ? $property_type_id->ID : '';
                }
                if ( $property_type_id != '' ) {
                    $reviews_comments = get_post_meta($property_type_id, 'wp_rem_enable_review_comment', true);
                }
                if ( isset($reviews_comments) && $reviews_comments == 'on' ) {
                    $show_ratings = 'off';
                }
            }
            return $show_ratings;
        }

        public function enqueue_scripts() {
            wp_enqueue_script('ajax-pagination', wp_rem::plugin_url() . 'assets/frontend/scripts/jquery.twbsPagination.min.js', array( 'jquery' ), '1.0');
        }

        /*
         * review meta call back
         */

        public function reviews_add_meta_boxes_callback() {
            add_meta_box('wp_rem_meta_reviews', esc_html(wp_rem_plugin_text_srt('wp_rem_reviews_detail')), array( $this, 'wp_rem_meta_reviews' ), 'wp_rem_reviews', 'normal', 'high');
        }

        /*
         * Update Ratings on reviews publishing
         */

        public function wp_rem_reviews_publish_callback($post_ID, $post) {
            $existing_ratings = get_post_meta($post_ID, 'existing_ratings', true);
            if ( ! empty($existing_ratings) ) {
                $user_name = get_the_title($post_ID);
                $property_slug = get_post_meta($post_ID, 'post_id', true);
                $property_id = get_page_by_path($property_slug, OBJECT, 'properties');
                update_post_meta($property_id->ID, 'wp_rem_ratings', $existing_ratings);
                delete_post_meta($post_ID, 'existing_ratings');

                /*
                 * Adding Notification
                 */
                $notification_array = array(
                    'type' => 'review',
                    'element_id' => $property_id->ID,
                    'message' => sprintf(wp_rem_plugin_text_srt('wp_rem_reviews_num_of_reviews_on_your_property'), $user_name, get_the_permalink($property_id->ID), wp_trim_words(get_the_title($property_id->ID), 5)),
                );
                do_action('wp_rem_add_notification', $notification_array);
            }
        }

        public function wp_rem_meta_reviews() {

            global $post, $wp_rem_html_fields, $wp_rem_form_fields;

            $overall_rating = get_post_meta($post->ID, 'overall_rating', true);
            $rating = get_post_meta($post->ID, 'ratings', true);
            $post_name = get_post_meta($post->ID, 'user_name', true);
            $post_name = isset($post_name) ? $post_name : '';
            $overall_rating = isset($overall_rating) ? $overall_rating : '';
            $post_slugg = get_post_meta($post->ID, 'post_id', true);
            /*
             * usiing slug get post of specific reviews
             */

            $get_post = array(
                'name' => $post_slugg,
                'post_type' => 'properties',
            );
            $required_post = get_posts($get_post);

            /*
             * usiing required post id get that property type
             */
            $required_post_id = '';
            if ( isset($required_post[0]->ID) && $required_post[0]->ID != '' ) {
                $required_post_id = $required_post[0]->ID;
            }
            $property_type = get_post_meta($required_post_id, 'wp_rem_property_type', true);
            $args_properties_type = array(
                'name' => $property_type,
                'post_type' => 'property-type',
            );
            $post_properties_type = get_posts($args_properties_type);
            /*
             * using property type id get the required labels 
             */

            if ( isset($post_properties_type[0]->ID) && $post_properties_type[0]->ID != '' ) {
                $post_properties_type_id = $post_properties_type[0]->ID;
            }

            $required_reviews_labels = get_post_meta($post_properties_type_id, 'wp_rem_reviews_labels', true);
            $labels_array = json_decode($required_reviews_labels);

            /*
             * using labels getting keys for label name and id 
             */
            if ( is_array($labels_array) && $labels_array != '' ) {
                foreach ( $labels_array as $value ) {
                    $lower_case_label = strtolower($value);
                    $final = str_replace(' ', '_', $lower_case_label);
                    $combile_keys[] = $final;
                }
            }
            $combine = array_combine($combile_keys, $labels_array);

            $wp_rem_opt_array = array(
                'name' => wp_rem_plugin_text_srt('wp_rem_reviews_overall_rating'),
                'desc' => '',
                //'hint_text' => wp_rem_var_theme_text_srt('wp_rem_var_accordion_view_hint'),
                'echo' => true,
                'field_params' => array(
                    'std' => $overall_rating,
                    'id' => '',
                    'cust_id' => 'overall_rating',
                    'cust_name' => 'overall_rating',
                    'classes' => 'service_postion chosen-select-no-single select-medium',
                    'options' => array(
                        '1' => '1',
                        '2' => '2',
                        '3' => '3',
                        '4' => '4',
                        '5' => '5',
                    ),
                    'return' => true,
                ),
            );
            $wp_rem_html_fields->wp_rem_select_field($wp_rem_opt_array);

            /*
             * Display all fields set in reviews settings property type
             */
            if ( is_array($combine) && $combine != '' ) {
                foreach ( $combine as $key => $value ) {

                    $wp_rem_opt_array = array(
                        'name' => $value,
                        'desc' => '',
                        'echo' => true,
                        'field_params' => array(
                            'std' => isset($rating[$value]) ? $rating[$value] : '',
                            'id' => '',
                            'cust_id' => $key,
                            'cust_name' => $key,
                            'classes' => 'service_postion chosen-select-no-single select-medium',
                            'options' => array(
                                '1' => '1',
                                '2' => '2',
                                '3' => '3',
                                '4' => '4',
                                '5' => '5',
                            ),
                            'return' => true,
                        ),
                    );
                    $wp_rem_html_fields->wp_rem_select_field($wp_rem_opt_array);
                }
            }
            $wp_rem_opt_array = array(
                'name' => wp_rem_plugin_text_srt('wp_rem_reviews_username'),
                'desc' => '',
                'echo' => true,
                'field_params' => array(
                    'std' => $post_name,
                    'id' => 'user_name',
                    'cust_name' => 'user_name',
                    'classes' => '',
                    'return' => true,
                ),
            );
            $wp_rem_html_fields->wp_rem_text_field($wp_rem_opt_array);


            $all_reviews = array( '' => wp_rem_plugin_text_srt('wp_rem_select_review_reply_for') );

            $args = array(
                'post_type' => Wp_rem_Reviews::$post_type_name,
                'posts_per_page' => -1,
                'meta_query' => array(
                    'relation' => 'OR',
                    array(
                        'key' => 'wp_rem_parent_review',
                        'compare' => 'NOT EXISTS',
                    ),
                    array(
                        'key' => 'wp_rem_parent_review',
                        'value' => '',
                        'compare' => '=',
                    ),
                ),
            );
            $query = new WP_Query($args);

            if ( ! empty($query->posts) ) {
                foreach ( $query->posts as $post_data ) {
                    $all_reviews[$post_data->ID] = $post_data->post_title;
                }
            }

            $wp_rem_opt_array = array(
                'name' => wp_rem_plugin_text_srt('wp_rem_reviews_reply_for'),
                'desc' => '',
                'echo' => true,
                'field_params' => array(
                    'std' => isset($rating[$key]) ? $rating[$key] : '',
                    'id' => 'parent_review',
                    'name' => 'parent_review',
                    'classes' => 'chosen-select-no-single select-medium',
                    'options' => $all_reviews,
                    'return' => true,
                ),
            );
            $wp_rem_html_fields->wp_rem_select_field($wp_rem_opt_array);
            ?>
            <div class="form-elements">
                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                    <label><?php echo wp_rem_plugin_text_srt('wp_rem_reviews_flags_text') ?></label>
                </div>
                <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
                    <?php
                    $flag_terms = wp_get_post_terms($post->ID, 'review_flag');
                    if ( is_array($flag_terms) && sizeof($flag_terms) > 0 ) {
                        echo '<div class="flags-list-box-detail">' . "\n";
                        echo '<div class="all-boxes-con">' . "\n";
                        echo '<ul class="flag-list-item list-head">' . "\n";
                        echo '<li class="flag-list-reason">' . wp_rem_plugin_text_srt('wp_rem_reviews_flags_reason_text') . '</li>' . "\n";
                        echo '<li class="flag-list-user">' . wp_rem_plugin_text_srt('wp_rem_reviews_flags_username_text') . '</li>' . "\n";
                        echo '<li class="flag-list-email">' . wp_rem_plugin_text_srt('wp_rem_reviews_flags_email_text') . '</li>' . "\n";
                        echo '</ul>' . "\n";
                        foreach ( $flag_terms as $flag_term ) {
                            if ( is_object($flag_term) && isset($flag_term->term_id) ) {
                                $flag_id = $flag_term->term_id;
                                $flag_reason = get_term_meta($flag_id, 'flag_reason', true);
                                $flag_username = get_term_meta($flag_id, 'flag_username', true);
                                $flag_username = $flag_username != '' ? $flag_username : '-';
                                $flag_user_email = get_term_meta($flag_id, 'flag_user_email', true);
                                $flag_user_email = $flag_user_email != '' ? $flag_user_email : '-';
                                $flag_seen = get_term_meta($flag_id, 'flag_seen', true);
                                $show_box = true;
                                if ( $flag_seen == '0' ) {
                                    $new_flags_count = ' new-given-flags';
                                } else {
                                    $new_flags_count = '';
                                }
                                echo '<ul class="flag-list-item' . $new_flags_count . '">' . "\n";
                                echo '<li class="flag-list-reason">' . $flag_reason . '</li>' . "\n";
                                echo '<li class="flag-list-user">' . $flag_username . '</li>' . "\n";
                                echo '<li class="flag-list-email">' . $flag_user_email . '</li>' . "\n";
                                echo '</ul>' . "\n";
                            }
                        }
                        echo '</div>' . "\n";
                        echo '</div>' . "\n";
                        ?>
                        <script>
                            jQuery(document).ready(function () {
                                $.ajax({
                                    method: "POST",
                                    url: '<?php echo admin_url('admin-ajax.php') ?>',
                                    dataType: "json",
                                    data: {
                                        action: "mark_review_flags_as_seen",
                                        review_id: '<?php echo absint($post->ID) ?>',
                                    },
                                    success: function (data) {},
                                });
                            });
                        </script>
                        <?php
                    } else {
                        echo wp_rem_plugin_text_srt('wp_rem_reviews_flags_no_flag_found');
                    }
                    ?>
                </div>
            </div>
            <?php
        }

        /*
         * save review post
         */

        public function wp_rem_review_fields_data_save() {
            global $post;
            $combile_keys = array();
            $post_idd = isset($post->ID) ? $post->ID : '';

            $post_slugg = get_post_meta($post_idd, 'post_id', true);
            /*
             * usiing slug get post of specific reviews
             */
            $get_post = array(
                'name' => $post_slugg,
                'post_type' => 'properties',
            );
            $required_post = get_posts($get_post);
            /*
             * usiing required post id get that property type
             */
            $required_post_id = '';
            if ( isset($required_post[0]->ID) && $required_post[0]->ID != '' ) {
                $required_post_id = $required_post[0]->ID;
            }

            $property_type = get_post_meta($required_post_id, 'wp_rem_property_type', true);
            $args_properties_type = array(
                'name' => $property_type,
                'post_type' => 'property-type',
            );

            $post_properties_type = get_posts($args_properties_type);
            /*
             * using property type id get the required labels 
             */
            $post_properties_type_id = '';
            if ( isset($post_properties_type[0]->ID) && $post_properties_type[0]->ID != '' ) {
                $post_properties_type_id = $post_properties_type[0]->ID;
            }
            $required_reviews_labels = get_post_meta($post_properties_type_id, 'wp_rem_reviews_labels', true);
            $labels_array = json_decode($required_reviews_labels);

            /*
             * using labels getting keys for label name and id 
             */
            if ( is_array($labels_array) && $labels_array != '' ) {
                foreach ( $labels_array as $value ) {
                    $lower_case_label = strtolower($value);
                    $final = str_replace(' ', '_', $lower_case_label);
                    $combile_keys[] = $final;
                }
            }
            if ( ! is_array($combile_keys) ) {
                $combile_keys = array();
            }
            if ( ! is_array($labels_array) ) {
                $labels_array = array();
            }

            $rating = array_combine($combile_keys, $labels_array);
            /*
             * using keys accessing values and generating array that contain key against values..
             */
            if ( is_array($rating) ) {
                foreach ( $rating as $key => $value ) {
                    $rating[$key] = isset($_POST[$key]) ? $_POST[$key] : '';
                }
            }
            if ( is_admin() ) {

                if ( is_array($rating) ) {
                    foreach ( $rating as $key => $value ) {
                        update_post_meta($post_idd, 'ratings', isset($rating) ? $rating : '' );
                    }
                }
                update_post_meta($post_idd, 'overall_rating', isset($_POST['overall_rating']) ? $_POST['overall_rating'] : '' );
                update_post_meta($post_idd, 'user_name', isset($_POST['user_name']) ? $_POST['user_name'] : '' );
            }
        }

        /**
         * Init.
         */
        public function admin_init_callback() {
            add_action('property_type_options_sidebar_tab', array( $this, 'property_type_options_sidebar_tab_callback' ), 10, 1);
            add_action('property_type_options_tab_container', array( $this, 'property_type_options_tab_container_callback' ), 10, 1);

            add_action('wp_rem_reviews_ui', array( $this, 'reviews_ui_callback' ), 100, 2);

            add_filter('have_user_added_review_for_this_post', array( $this, 'have_user_added_review_for_this_post_callback' ), 10, 3);
            add_filter('is_this_user_owner_of_this_post', array( $this, 'is_this_user_owner_of_this_post_callback' ), 10, 3);

            add_filter('reviews_ratings_data', array( $this, 'reviews_ratings_data_callback' ), 10, 2);

            // Remove "Add Review" button from property page and admin menu.
            add_action('admin_head', array( $this, 'disable_new_posts_capability_callback' ), 11);
            // Custom columns
            add_filter('manage_' . Wp_rem_Reviews::$post_type_name . '_posts_columns', array( $this, 'custom_columns_callback' ), 20, 1);
            add_action('manage_' . Wp_rem_Reviews::$post_type_name . '_posts_custom_column', array( $this, 'manage_posts_custom_column_callback' ), 20, 1);

            // Custom Sort Columns
            add_filter('manage_edit-' . Wp_rem_Reviews::$post_type_name . '_sortable_columns', array( $this, 'wp_rem_reviews_sortable' ));
            add_filter('request', array( $this, 'wp_rem_reviews_column_orderby' ));

            // Custom Filter
            add_action('restrict_manage_posts', array( $this, 'wp_rem_admin_reviews_filter_restrict_manage_reviews' ), 11);
            add_filter('parse_query', array( &$this, 'wp_rem_reviews_filter' ), 11, 1);

            /*
             * AJAX Handlers.
             */
            // Add user review.
            add_action('wp_ajax_post_user_review', array( $this, 'post_user_review_callback' ));
            add_action('wp_ajax_nopriv_post_user_review', array( $this, 'post_user_review_callback' ));
            // Get user reviews for frontend.
            add_action('wp_ajax_get_user_reviews', array( $this, 'get_user_reviews_callback' ));
            add_action('wp_ajax_nopriv_get_user_reviews', array( $this, 'get_user_reviews_callback' ));
            // Get user reviews for frontend.
            add_action('wp_ajax_get_user_reviews_for_dashboard', array( $this, 'get_user_reviews_for_dashboard_callback' ));
            add_action('wp_ajax_nopriv_get_user_reviews_for_dashboard', array( $this, 'get_user_reviews_for_dashboard_callback' ));
            // Get user given reviews for dashboard.
            add_action('wp_ajax_wp_rem_publisher_reviews', array( $this, 'dashboard_reviews_ui_callback' ));
            // Get user post's reviews for dashboard.
            add_action('wp_ajax_wp_rem_publisher_my_reviews', array( $this, 'dashboard_my_reviews_ui_callback' ));
            // Delete user review from user dashboard.
            add_action('wp_ajax_delete_user_review', array( $this, 'delete_user_review_callback' ));
            // Delete user review from Admin.
            add_action('before_delete_post', array( $this, 'delete_user_review_on_trash_callback' ));
            // Delete Review Permanently from admin.
            add_action('post_row_actions', array( $this, 'post_row_actions_callback' ), 10, 2);

            add_filter('bulk_actions-edit-' . Wp_rem_Reviews::$post_type_name . '', array( $this, 'bulk_actions_callback' ));


            add_action('wp_ajax_mark_reviews_as_helpful', array( $this, 'review_helpful_add' ));
            add_action('wp_ajax_nopriv_mark_reviews_as_helpful', array( $this, 'review_helpful_add' ));

            add_action('wp_ajax_mark_reviews_as_flag', array( $this, 'review_flag_add' ));
            add_action('wp_ajax_nopriv_mark_reviews_as_flag', array( $this, 'review_flag_add' ));

            add_action('wp_ajax_mark_review_flags_as_seen', array( $this, 'review_flags_seen' ));
        }

        /**
         * Remove Trash option from bulk dropdown
         */
        public function bulk_actions_callback($actions) {
            unset($actions['trash']);
            return $actions;
        }

        /**
         * Review Flag seen
         */
        public function review_flags_seen() {
            $review_id = isset($_POST['review_id']) ? $_POST['review_id'] : '';
            $flag_terms = wp_get_post_terms($review_id, 'review_flag');
            if ( is_array($flag_terms) && sizeof($flag_terms) > 0 ) {
                foreach ( $flag_terms as $flag_term ) {
                    if ( is_object($flag_term) && isset($flag_term->term_id) ) {
                        $flag_id = $flag_term->term_id;
                        update_term_meta($flag_id, 'flag_seen', '1');
                    }
                }
            }
            die;
        }

        /**
         * Review Flag
         */
        public function review_flag_add() {
            $review_id = isset($_POST['review_id']) ? $_POST['review_id'] : '';
            $flag_reason = isset($_POST['flag_reason']) ? $_POST['flag_reason'] : '';

            if ( isset($_COOKIE['review_marked_flag_' . $review_post_id]) && $_COOKIE['review_marked_flag_' . $review_post_id] == '1' ) {
                $msg = 'There is some problem.';
                echo json_encode(array( 'type' => 'error', 'msg' => $msg ));
                die;
            } else {
                $rand_id = rand(1000000000, 9999999999);
                $flag_term = wp_insert_term(
                        'Report ' . $rand_id, 'review_flag', array(
                    'slug' => 'report-' . $rand_id
                        )
                );
                if ( isset($flag_term['term_id']) ) {
                    $flag_term_id = $flag_term['term_id'];
                    wp_set_post_terms($review_id, array( $flag_term_id ), 'review_flag', true);
                    update_term_meta($flag_term_id, 'flag_reason', $flag_reason);
                    if ( is_user_logged_in() ) {
                        $current_user = wp_get_current_user();
                        $get_user_dn = $current_user->display_name;
                        $get_user_email = $current_user->user_email;
                        update_term_meta($flag_term_id, 'flag_username', $get_user_dn);
                        update_term_meta($flag_term_id, 'flag_user_email', $get_user_email);
                    }
                    update_term_meta($flag_term_id, 'flag_seen', '0');

                    //
                    $total_reviews = get_post_meta($review_id, 'review_marked_flag_count', true);
                    if ( empty($total_reviews) ) {
                        $total_reviews = 0;
                    }
                    $total_reviews = absint($total_reviews);
                    $new_total_reviews = $total_reviews + 1;
                    update_post_meta($review_id, 'review_marked_flag_count', $new_total_reviews);
                    //

                    setcookie('review_marked_flag_' . $review_id, '1', time() + (86400 * 120), "/"); // 120 days

                    $msg = wp_rem_plugin_text_srt('wp_rem_reviews_flags_report_submit_successfully');
                    echo json_encode(array( 'type' => 'success', 'msg' => $msg ));
                } else {
                    $msg = wp_rem_plugin_text_srt('wp_rem_reviews_flags_there_is_problem');
                    echo json_encode(array( 'type' => 'error', 'msg' => $msg ));
                }
                die;
            }
        }

        /**
         * Review Helpful
         */
        public function review_helpful_add() {

            $review_id = isset($_POST['review_id']) ? $_POST['review_id'] : '';
            $pre_do = isset($_POST['pre_do']) ? $_POST['pre_do'] : '';

            if ( $review_id > 0 ) {

                if ( $pre_do == 'marking' ) {
                    setcookie('review_marked_helpful_' . $review_id, '1', time() + (86400 * 30), "/"); // 30 days
                    $total_reviews = get_post_meta($review_id, 'review_marked_helpful_count', true);
                    if ( empty($total_reviews) ) {
                        $total_reviews = 0;
                    }
                    $total_reviews = absint($total_reviews);
                    $new_total_reviews = $total_reviews + 1;
                    update_post_meta($review_id, 'review_marked_helpful_count', $new_total_reviews);

                    $msg = wp_rem_plugin_text_srt('wp_rem_reviews_marked_as_helpful');
                    $pre_do = 'marked';
                    echo json_encode(array( 'type' => 'success', 'msg' => $msg, 'counts' => $new_total_reviews, 'pre_do' => $pre_do ));
                } else {
                    unset($_COOKIE['review_marked_helpful_' . $review_id]);
                    setcookie('review_marked_helpful_' . $review_id, '', time() + (86400 * 30), "/"); // 30 days
                    $total_reviews = get_post_meta($review_id, 'review_marked_helpful_count', true);
                    if ( empty($total_reviews) ) {
                        $total_reviews = 0;
                        $new_total_reviews = $total_reviews;
                    } else {
                        $total_reviews = absint($total_reviews);
                        $new_total_reviews = $total_reviews - 1;
                    }
                    update_post_meta($review_id, 'review_marked_helpful_count', $new_total_reviews);

                    $msg = wp_rem_plugin_text_srt('wp_rem_reviews_marked_not_as_helpful');
                    $pre_do = 'marking';
                    echo json_encode(array( 'type' => 'success', 'msg' => $msg, 'counts' => $new_total_reviews, 'pre_do' => $pre_do ));
                }
            }
            die;
        }

        /**
         * Delete Review Permanently
         */
        public function post_row_actions_callback($actions, $post) {

            if ( $post->post_type == "wp_rem_reviews" ) {
                unset($actions['trash']);
                unset($actions['view']);
                $post_type_object = get_post_type_object($post->post_type);
                $actions['trash'] = "<a class='submitdelete' title='" . esc_attr(wp_rem_plugin_text_srt('wp_rem_reviews_delete_this_item')) . "' href='" . get_delete_post_link($post->ID, '', true) . "'>" . wp_rem_plugin_text_srt('wp_rem_reviews_delete') . "</a>";
            }
            return $actions;
        }

        /**
         * Register RFlag Taxonomy
         */
        public function register_reviews_flag_taxonomy() {
            $labels = array(
                'name' => wp_rem_plugin_text_srt('wp_rem_reviews_flags_text'),
                'singular_name' => wp_rem_plugin_text_srt('wp_rem_reviews_single_flag_text'),
                'search_items' => wp_rem_plugin_text_srt('wp_rem_reviews_flags_all_flags'),
                'all_items' => wp_rem_plugin_text_srt('wp_rem_reviews_flags_all_flags'),
                'edit_item' => wp_rem_plugin_text_srt('wp_rem_reviews_flags_edit_flag'),
                'update_item' => wp_rem_plugin_text_srt('wp_rem_reviews_flags_update_flag'),
                'add_new_item' => wp_rem_plugin_text_srt('wp_rem_reviews_flags_add_new_flag'),
                'new_item_name' => wp_rem_plugin_text_srt('wp_rem_reviews_flags_new_flag_name'),
                'menu_name' => wp_rem_plugin_text_srt('wp_rem_reviews_flags_text'),
            );

            $args = array(
                'public' => false,
                'hierarchical' => false,
                'show_in_menu' => false,
                'labels' => $labels,
                'show_ui' => false,
                'show_admin_column' => false,
                'query_var' => true,
                'rewrite' => array( 'slug' => 'review_flag' ),
            );

            register_taxonomy('review_flag', array( Wp_rem_Reviews::$post_type_name ), $args);
        }

        /**
         * Register Reviews Post Type.
         */
        public function register_reviews_post_type_callback() {
            $labels = array(
                'name' => wp_rem_plugin_text_srt('wp_rem_reviews_name'),
                'singular_name' => wp_rem_plugin_text_srt('wp_rem_review_singular_name'),
                'menu_name' => wp_rem_plugin_text_srt('wp_rem_reviews_name'),
                'name_admin_bar' => wp_rem_plugin_text_srt('wp_rem_reviews_singular_name'),
                'add_new' => wp_rem_plugin_text_srt('wp_rem_reviews_add_review'),
                'add_new_item' => wp_rem_plugin_text_srt('wp_rem_reviews_add_new_review'),
                'new_item' => wp_rem_plugin_text_srt('wp_rem_reviews_new_review'),
                'edit_item' => wp_rem_plugin_text_srt('wp_rem_reviews_edit_review'),
                'view_item' => wp_rem_plugin_text_srt('wp_rem_reviews_view_review'),
                'all_items' => wp_rem_plugin_text_srt('wp_rem_reviews_name'),
                'search_items' => wp_rem_plugin_text_srt('wp_rem_reviews_search_reviews'),
                'not_found' => wp_rem_plugin_text_srt('wp_rem_reviews_not_found_reviews'),
                'not_found_in_trash' => wp_rem_plugin_text_srt('wp_rem_reviews_not_found_in_trash_reviews'),
            );

            $args = array(
                'labels' => $labels,
                'description' => wp_rem_plugin_text_srt('wp_rem_reviews_description'),
                'public' => true,
                'publicly_queryable' => true,
                'menu_position' => 27,
                'menu_icon' => wp_rem::plugin_url() . 'assets/backend/images/reviews.png',
                'show_ui' => true,
                //'show_in_menu' => 'edit.php?post_type=properties',
                'query_var' => false,
                'capability_type' => 'post',
                'has_archive' => false,
                'supports' => '',
                'exclude_from_search' => true,
            );

            register_post_type(Wp_rem_Reviews::$post_type_name, $args);
        }

        /**
         * Disable capibility to create new review.
         */
        public function disable_new_posts_capability_callback() {
            global $post;

            // Hide link on property page.
            if ( get_post_type() == Wp_rem_Reviews::$post_type_name ) {
                ?>
                <style type="text/css">
                    .wrap .page-title-action, 
                    #edit-slug-box, 
                    .submitbox .preview.button,
                    .submitbox .misc-pub-visibility,
                    .submitbox .edit-timestamp {
                        display:none;
                    }
                    .post-type-wp_rem_reviews .column-review_id { width:100px !important; overflow:hidden }
                    .post-type-wp_rem_reviews .column-helpful { width:100px !important; overflow:hidden }
                    .post-type-wp_rem_reviews .column-flag { width:100px !important; overflow:hidden }
                </style>
                <?php
            }
        }

        /**
         * Add new columns to reviews backend property.
         *
         * @param	array	$columns
         * @return	array
         */
        public function custom_columns_callback($columns) {
            unset($columns['title']);
            unset($columns['date']);

            foreach ( $columns as $key => $value ) {
                if ( $key == 'cb' ) {

                    $new_columns[$key] = $value;
                    $new_columns['review_id'] = wp_rem_plugin_text_srt('wp_rem_review_id_column');
                } else {
                    $new_columns[$key] = $value;
                }
            }

            $new_columns['property_name'] = wp_rem_plugin_text_srt('wp_rem_review_property_name_column');
            $new_columns['member_name'] = wp_rem_plugin_text_srt('wp_rem_review_member_name_column');
            $new_columns['status'] = wp_rem_plugin_text_srt('wp_rem_reviews_status');
            $new_columns['review_date'] = wp_rem_plugin_text_srt('wp_rem_reviews_date');
            $new_columns['helpful'] = wp_rem_plugin_text_srt('wp_rem_review_helpful_column');
            $new_columns['flag'] = wp_rem_plugin_text_srt('wp_rem_review_flag_column');
            $new_columns['overall_rating'] = wp_rem_plugin_text_srt('wp_rem_reviews_overall_rating');
            return $new_columns;
        }

        /**
         * Output data for custom columns.
         *
         * @param	string	$column_name
         */
        public function manage_posts_custom_column_callback($column_name) {
            global $post;
            switch ( $column_name ) {
                case 'review_id':
                    echo '#' . $post->ID;
                    break;
                case 'status':
                    echo get_post_status($post->ID);
                    break;
                case 'review_date':
                    echo get_the_date();
                    break;
                case 'property_name':
                    remove_filter('parse_query', array( &$this, 'wp_rem_reviews_filter' ), 11, 1);
                    $post_slug = get_post_meta($post->ID, 'post_id', true);
                    $args = array(
                        'name' => $post_slug,
                        'post_type' => 'properties',
                    );
                    $posts = get_posts($args);
                    if ( 0 < count($posts) ) {
                        echo '<a href="' . get_edit_post_link($posts[0]->ID) . '">  ' . $posts[0]->post_title . ' </a>';
                    } else {
                        echo wp_rem_plugin_text_srt('wp_rem_reviews_post_dosnt_exist');
                    }
                    break;

                case 'member_name':
                    $user_name = get_post_meta($post->ID, 'user_name', true);
                    $company_id = get_post_meta($post->ID, 'company_id', true);
                    if ( $company_id ) {
                        echo '<a href="' . get_edit_post_link($company_id) . '">  ' . $user_name . ' </a>';
                    } else {
                        echo $user_name;
                    }
                    break;
                case 'overall_rating':

                    $ratings_output = '';
                    $ratings = get_post_meta($post->ID, 'ratings', true);
                    if ( is_array($ratings) && $ratings != '' ) {
                        foreach ( $ratings as $key => $rating ) {
                            if ( $key == '' ) {
                                $key = '';
                            } else {
                                $key = $key . ' : ';
                            }
                            $rating_summary = '';
                            $rating_summary = ($rating / 5) * 100;
                            $ratings_output .= '<div class="reviews-rating-holder"><em>' . $key . '</em><div class="rating-star">
							<span class="rating-box" style="width:' . $rating_summary . '%;"></span>
						    </div></div>';
                        }
                    }
                    $ratings_output = htmlentities($ratings_output);
                    //$overall_rting = get_post_meta($post->ID, 'overall_rating', true) . '/5';

                    $oral_rting = get_post_meta($post->ID, 'overall_rating', true);
                    if ( empty($oral_rting) ) {
                        $oral_rting = 0;
                    }
                    $overall_rting = $oral_rting . '/5';
                    $total = ($oral_rting / 5) * 100;
                    echo '<div class="reviews-rating-holder"><div class="rating-star" data-toggle="tooltip" data-html="true" data-placement="top" title="' . $ratings_output . '">
						<span class="rating-box" style="width:' . $total . '%;"></span>
					</div></div>';
                    break;
                case 'helpful':
                    $helpful_count = get_post_meta($post->ID, 'review_marked_helpful_count', true);
                    $helpful_count = (isset($helpful_count) && $helpful_count != '') ? $helpful_count : 0;
                    echo $helpful_count;
                    break;
                case 'flag':
                    $flag_count = get_post_meta($post->ID, 'review_marked_flag_count', true);
                    $flag_count = (isset($flag_count) && $flag_count != '') ? $flag_count : 0;
                    $flag_terms = wp_get_post_terms($post->ID, 'review_flag');
                    $show_box = false;
                    $have_new_flags = false;
                    if ( is_array($flag_terms) && sizeof($flag_terms) > 0 ) {
                        $flag_lis = '';
                        foreach ( $flag_terms as $flag_term ) {
                            if ( is_object($flag_term) && isset($flag_term->term_id) ) {
                                $flag_id = $flag_term->term_id;
                                $flag_reason = get_term_meta($flag_id, 'flag_reason', true);
                                $flag_username = get_term_meta($flag_id, 'flag_username', true);
                                $flag_username = $flag_username != '' ? $flag_username : '-';
                                $flag_user_email = get_term_meta($flag_id, 'flag_user_email', true);
                                $flag_user_email = $flag_user_email != '' ? $flag_user_email : '-';
                                $flag_seen = get_term_meta($flag_id, 'flag_seen', true);
                                $show_box = true;
                                if ( $flag_seen == '0' ) {
                                    $new_flags_count = ' new-given-flags';
                                    $have_new_flags = true;
                                } else {
                                    $new_flags_count = '';
                                }
                                $flag_lis .= '<ul class="flag-list-item' . $new_flags_count . '">' . "\n";
                                $flag_lis .= '<li class="flag-list-reason">' . $flag_reason . '</li>' . "\n";
                                $flag_lis .= '<li class="flag-list-user">' . $flag_username . '</li>' . "\n";
                                $flag_lis .= '<li class="flag-list-email">' . $flag_user_email . '</li>' . "\n";
                                $flag_lis .= '</ul>' . "\n";
                            }
                        }
                    }
                    ?>
                    <div class="flag-listing-count">
                        <?php
                        if ( $show_box ) {
                            echo '<div class="flags-list-box" style="display:none;">' . "\n";
                            echo '<span class="close-list-box"><i class="icon-close"></i></span>';
                            echo '<div class="all-list-boxes-con">' . "\n";
                            echo '<ul class="flag-list-item list-head">' . "\n";
                            echo '<li class="flag-list-reason">' . wp_rem_plugin_text_srt('wp_rem_reviews_flags_reason_text') . '</li>' . "\n";
                            echo '<li class="flag-list-user">' . wp_rem_plugin_text_srt('wp_rem_reviews_flags_username_text') . '</li>' . "\n";
                            echo '<li class="flag-list-email">' . wp_rem_plugin_text_srt('wp_rem_reviews_flags_email_text') . '</li>' . "\n";
                            echo '</ul>' . "\n";
                            echo $flag_lis;
                            echo '</div>' . "\n";
                            echo '</div>' . "\n";
                            ?>
                            <a href="javascript:void(0)" data-id="<?php echo absint($post->ID) ?>" class="see-new-flags<?php echo ($have_new_flags ? ' have-new-flags' : ''); ?>"><?php echo absint($flag_count) ?></a>
                            <?php
                        } else {
                            ?>
                            <a class="all-seen-flags"><?php echo absint($flag_count) ?></a>
                            <?php
                        }
                        ?>
                    </div>
                    <?php
                    break;
            }
        }

        public function wp_rem_reviews_sortable($columns) {
            $columns['review_id'] = 'review_id';
            $columns['property_name'] = 'property_name';
            $columns['member_name'] = 'member_name';
            $columns['overall_rating'] = 'overall_rating';
            $columns['review_date'] = 'review_date';
            $columns['helpful'] = 'helpful';
            $columns['flag'] = 'flag';
            return $columns;
        }

        public function wp_rem_reviews_column_orderby($vars) {
            if ( isset($vars['orderby']) && 'review_id' == $vars['orderby'] ) {
                $vars = array_merge($vars, array(
                    'orderby' => 'ID',
                ));
            }
            if ( isset($vars['orderby']) && 'property_name' == $vars['orderby'] ) {
                $vars = array_merge($vars, array(
                    'meta_key' => 'post_id',
                    'orderby' => 'meta_value',
                ));
            }
            if ( isset($vars['orderby']) && 'member_name' == $vars['orderby'] ) {
                $vars = array_merge($vars, array(
                    'meta_key' => 'user_name',
                    'orderby' => 'meta_value',
                ));
            }
            if ( isset($vars['orderby']) && 'overall_rating' == $vars['orderby'] ) {
                $vars = array_merge($vars, array(
                    'meta_key' => 'overall_rating',
                    'orderby' => 'meta_value',
                ));
            }
            if ( isset($vars['orderby']) && 'helpful' == $vars['orderby'] ) {
                $vars = array_merge($vars, array(
                    'meta_key' => 'review_marked_helpful_count',
                    'orderby' => 'meta_value',
                ));
            }
            if ( isset($vars['orderby']) && 'flag' == $vars['orderby'] ) {
                $vars = array_merge($vars, array(
                    'meta_key' => 'review_marked_flag_count',
                    'orderby' => 'meta_value',
                ));
            }
            if ( isset($vars['orderby']) && 'review_date' == $vars['orderby'] ) {
                $vars = array_merge($vars, array(
                    'orderby' => 'date',
                ));
            }
            return $vars;
        }

        public function wp_rem_admin_reviews_filter_restrict_manage_reviews() {
            global $wp_rem_form_fields, $post_type;

            //only add filter to post type you want
            if ( $post_type == Wp_rem_Reviews::$post_type_name ) {

                $member_name = isset($_GET['member_name']) ? $_GET['member_name'] : '';
                $wp_rem_opt_array = array(
                    'id' => 'member_name',
                    'cust_name' => 'member_name',
                    'std' => $member_name,
                    'classes' => 'filter-member-name',
                    'extra_atr' => ' placeholder="' . wp_rem_plugin_text_srt('wp_rem_property_filter_search_for_member') . '"',
                    'return' => false,
                    'force_std' => true,
                );
                $wp_rem_form_fields->wp_rem_form_text_render($wp_rem_opt_array);

                $wp_rem_review_start_date = isset($_GET['wp_rem_review_start_date']) ? $_GET['wp_rem_review_start_date'] : '';
                echo '<div class="review-start-date" style="height: 28px; width: auto;float: left; margin-right: 0px; margin-left: 0px;">';
                $wp_rem_review_date = isset($_GET['wp_rem_review_date']) ? $_GET['wp_rem_review_date'] : '';
                $wp_rem_opt_array = array(
                    'id' => 'review_start_date',
                    'cust_name' => 'review_start_date',
                    'classes' => '',
                    'strtotime' => true,
                    'std' => $wp_rem_review_start_date,
                    'description' => '',
                    'hint' => '',
                    'format' => 'Y-m-d',
                    'return' => false,
                    'force_std' => true,
                    'extra_atr' => ' placeholder="' . wp_rem_plugin_text_srt('wp_rem_review_start_date_field_label') . '"',
                );
                $wp_rem_form_fields->wp_rem_form_date_render($wp_rem_opt_array);
                echo '</div>';

                echo '<script>
						jQuery(function(){
							jQuery("#wp_rem_review_start_date").datetimepicker({
								format:"Y-m-d",
							}).on("change", function(){
								jQuery(\'.xdsoft_datetimepicker\').hide();
							});
						});
				</script>';


                echo '<div class="review-end-date" style="height: 28px; width: auto;float: left; margin-right: 6px; margin-left: 6px;">';
                $wp_rem_review_end_date = isset($_GET['wp_rem_review_end_date']) ? $_GET['wp_rem_review_end_date'] : '';
                $wp_rem_review_date = isset($_GET['wp_rem_review_date']) ? $_GET['wp_rem_review_date'] : '';
                $wp_rem_opt_array = array(
                    'id' => 'review_end_date',
                    'cust_name' => 'review_end_date',
                    'classes' => '',
                    'strtotime' => true,
                    'std' => $wp_rem_review_end_date,
                    'description' => '',
                    'hint' => '',
                    'format' => 'Y-m-d',
                    'return' => false,
                    'force_std' => true,
                    'extra_atr' => ' placeholder="' . wp_rem_plugin_text_srt('wp_rem_review_end_date_field_label') . '"',
                );
                $wp_rem_form_fields->wp_rem_form_date_render($wp_rem_opt_array);
                echo '</div>';

                echo '<script>
						jQuery(function(){
							jQuery("#wp_rem_review_end_date").datetimepicker({
								format:"Y-m-d",
							}).on("change", function(){
								jQuery(\'.xdsoft_datetimepicker\').hide();
							});
						});
				</script>';
            }
        }

        function wp_rem_reviews_filter($query) {
            global $pagenow;
            $custom_filter_arr = array();
            if ( is_admin() && $pagenow == 'edit.php' && isset($_GET['post_type']) && $_GET['post_type'] == Wp_rem_Reviews::$post_type_name && isset($_GET['member_name']) && $_GET['member_name'] != '' ) {
                remove_filter('parse_query', array( &$this, 'wp_rem_reviews_filter' ), 11, 1);
                $members_args = array(
                    'post_type' => 'members',
                    'posts_per_page' => -1,
                    's' => $_GET['member_name'],
                    'fields' => 'ids',
                );
                $members_ids = get_posts($members_args);
                wp_reset_postdata();
                add_filter('parse_query', array( &$this, 'wp_rem_reviews_filter' ), 11, 1);
                if ( empty($members_ids) ) {
                    $members_ids = array( 0 );
                }
                $custom_filter_arr[] = array(
                    'key' => 'company_id',
                    'value' => $members_ids,
                    'compare' => 'IN',
                );
            }
            if ( is_admin() && $pagenow == 'edit.php' && isset($_GET['post_type']) && $_GET['post_type'] == Wp_rem_Reviews::$post_type_name && ((isset($_GET['wp_rem_review_start_date']) && $_GET['wp_rem_review_start_date'] != '') || (isset($_GET['wp_rem_review_end_date']) && $_GET['wp_rem_review_end_date'] != '')) ) {

                $wp_rem_review_start_date = isset($_GET['wp_rem_review_start_date']) ? $_GET['wp_rem_review_start_date'] : '';
                $wp_rem_review_end_date = isset($_GET['wp_rem_review_end_date']) ? $_GET['wp_rem_review_end_date'] : '';

                $after = $before = '';

                if ( $wp_rem_review_start_date != '' ) {
                    $after = $wp_rem_review_start_date;
                }
                if ( $wp_rem_review_end_date != '' ) {
                    $before = $wp_rem_review_end_date;
                }

                $date = array(
                    array(
                        'after' => $after,
                        'before' => $before, //remove this line if no upper limit
                        'inclusive' => true,
                    )
                );

                $query->set('date_query', $date);
            }
            if ( is_admin() && $pagenow == 'edit.php' && isset($_GET['post_type']) && $_GET['post_type'] == Wp_rem_Reviews::$post_type_name && ! empty($custom_filter_arr) ) {
                $query->set('meta_query', $custom_filter_arr);
            }
        }

        public function publisher_get_profile_image($member_id) {
            $member_image_id = get_post_meta($member_id, 'wp_rem_profile_image', true);
            $member_image = wp_get_attachment_url($member_image_id);
            if ( $member_image == '' ) {
                $member_image = esc_url(wp_rem::plugin_url() . 'assets/frontend/images/member-no-image.jpg');
            }
            return $member_image;
        }

        /**
         * Get user reviews for specified post with limit and rating.
         *
         * @param	int		$id			ID of the company or post.
         * @param	int		$start		Start or Offset from which reviews will be selected.
         * @param	int		$count		Number of reviews to be selected.
         * @param	int		$order_by	Order by clause.
         * @param	bool	$is_company	Is this request to search by company or by post
         */
        public function get_user_reviews_for_post($id, $start = 0, $count = 10, $order_by = 'newest', $is_company = false, $my_reviews = false, $is_child = true) {
            global $wp_rem_publisher_profile;
            $args = array(
                'post_type' => Wp_rem_Reviews::$post_type_name,
                'offset' => $start,
                'posts_per_page' => $count,
                'post_status' => 'publish',
            );

            /*
             * Set meta query for the query by checking if this request is to
             * select reviews by post or for any company.
             */
            if ( $is_company == true ) {
                if ( $is_child == false ) {
                    $child_fetch = array(
                        'relation' => 'OR',
                        array(
                            'key' => 'wp_rem_parent_review',
                            'compare' => 'NOT EXISTS',
                        ),
                        array(
                            'key' => 'wp_rem_parent_review',
                            'value' => '',
                            'compare' => '=',
                        ),
                    );
                }
                $args['meta_query'] = array(
                    array(
                        'key' => 'company_id',
                        'value' => $id,
                    ),
                    $child_fetch,
                );
            } else {

                $post = get_post($id);
                $slug = '';
                if ( $post == null ) {
                    return array();
                }
                $slug = $post->post_name;

                if ( $my_reviews == true ) {

                    $properties_args = array(
                        'post_type' => 'properties',
                        'posts_per_page' => -1,
                        'post_status' => 'publish',
                        'meta_query' => array(
                            array(
                                'key' => 'wp_rem_property_member',
                                'value' => $id,
                                'compare' => '=',
                            ),
                            array(
                                'key' => 'wp_rem_property_status',
                                'value' => 'delete',
                                'compare' => '!=',
                            ),
                        ),
                    );

                    $properties_query = new WP_Query($properties_args);
                    $slug_array = array( 0 );
                    $all_properties = $properties_query->get_posts();
                    if ( ! empty($all_properties) ) {
                        foreach ( $all_properties as $property_key => $property_data ) {
                            $slug_array[] = $property_data->post_name;
                        }
                    }
                    $post_meta_query = array(
                        'key' => 'post_id',
                        'value' => $slug_array,
                        'compare' => 'IN'
                    );
                } else {
                    $post_meta_query = array(
                        'key' => 'post_id',
                        'value' => $slug,
                    );
                }
                $args['meta_query'] = array(
                    'relation' => 'AND',
                    $post_meta_query,
                    /*
                     * Check if the review is replied or the parent
                     */
                    array(
                        'relation' => 'OR',
                        array(
                            'key' => 'wp_rem_parent_review',
                            'compare' => 'NOT EXISTS',
                        ),
                        array(
                            'key' => 'wp_rem_parent_review',
                            'value' => '',
                            'compare' => '=',
                        ),
                    ),
                );
            }
            /*
             * Set ordery by clause for query.
             */
            if ( $order_by == 'newest' ) {
                $args['orderby'] = 'date';
            } elseif ( $order_by == 'highest' ) {
                $args['meta_key'] = 'overall_rating';
                $args['orderby'] = 'meta_value_num';
                $args['order'] = 'DESC';
            } elseif ( $order_by == 'lowest' ) {
                $args['meta_key'] = 'overall_rating';
                $args['orderby'] = 'meta_value_num';
                $args['order'] = 'ASC';
            }

            $query = new WP_Query($args);
            $reviews = $query->get_posts();

            $reviews_data = array();

            foreach ( $reviews as $key => $review ) {
                $review_parent_data = array();
                $data = array(
                    'id' => $review->ID,
                    'user_name' => $review->post_title,
                    'description' => $review->post_content,
                    'overall_rating' => get_post_meta($review->ID, 'overall_rating', true),
                    'username' => get_post_meta($review->ID, 'user_name', true),
                    'review_title' => $review->post_title,
                    'dated' => $review->post_date,
                );
                $user_id = get_post_meta($review->ID, 'user_id', true);
                $company_id = get_user_meta($user_id, 'wp_rem_company', true);
                $data['img'] = '';
                //if ( $user_id != '' && $user_id > 0 ) {
                $wp_rem_profile_image = $this->publisher_get_profile_image($company_id);
                //$wp_rem_profile_image = '';
                //if ( $wp_rem_profile_image != '' ) {
                //$data['img'] = get_avatar_url($user_id, array('size' => 32));
                //$data['img'] = wp_get_attachment_url( $wp_rem_profile_image );
                $data['img'] = $wp_rem_profile_image;
                //}
                //}

                if ( $data['img'] == '' ) {
                    $data['img'] = get_avatar_url(0, array( 'size' => 32 ));
                }
                $data['is_reply'] = false;
                $review_parent_data = $data;
                $review_parent_data['is_already_replied'] = false;


                /*
                 * Checking child reviews
                 */
                $review_child_data = array();
                $query_args = array(
                    'post_type' => Wp_rem_Reviews::$post_type_name,
                    'posts_per_page' => 1,
                    'post_status' => 'publish',
                    'meta_query' => array(
                        array(
                            'key' => 'wp_rem_parent_review',
                            'value' => $review->ID,
                            'compare' => '=',
                        ),
                    ),
                );

                $review_query = new WP_Query($query_args);
                $child_reviews = $review_query->get_posts();
                if ( ! empty($child_reviews) ) {
                    foreach ( $child_reviews as $child_key => $child_review ) {
                        $data = array(
                            'id' => $child_review->ID,
                            'user_name' => $child_review->post_title,
                            'description' => $child_review->post_content,
                            'overall_rating' => get_post_meta($child_review->ID, 'overall_rating', true),
                            'username' => get_post_meta($child_review->ID, 'user_name', true),
                            'review_title' => $child_review->post_title,
                            'dated' => $child_review->post_date,
                        );
                        $user_id = get_post_meta($child_review->ID, 'user_id', true);
                        $company_id = get_user_meta($user_id, 'wp_rem_company', true);
                        $data['img'] = '';
                        if ( $user_id != '' && $user_id > 0 ) {
                            $wp_rem_profile_image = $this->publisher_get_profile_image($company_id);
                            //$wp_rem_profile_image = '';
                            if ( $wp_rem_profile_image != '' ) {
                                //$data['img'] = get_avatar_url($user_id, array('size' => 32));
                                //$data['img'] = wp_get_attachment_url( $wp_rem_profile_image );
                                $data['img'] = $wp_rem_profile_image;
                            }
                        }

                        if ( $data['img'] == '' ) {
                            $data['img'] = get_avatar_url(0, array( 'size' => 32 ));
                        }

                        $data['is_reply'] = true;
                        $data['parent_id'] = $review_parent_data['id'];
                        $review_parent_data['is_already_replied'] = true;
                        $review_child_data = $data;
                    }
                }

                $reviews_data[] = $review_parent_data;
                $reviews_data[] = $review_child_data;
            }
            return $reviews_data;
        }

        /**
         * Get user reviews for specified post with limit and rating.
         *
         * @param	int		$id			ID of the company or post.
         * @param	bool	$is_company	Is this request to search by company or by post
         */
        public function get_user_reviews_count($id, $is_company = false, $is_child = true, $my_reviews = false) {
            $args = array(
                'post_type' => Wp_rem_Reviews::$post_type_name,
                'post_status' => 'publish',
            );

            /*
             * Set meta query for the query by checking if this request is to
             * select reviews by post or for any company.
             */
            if ( $is_company == true ) {
                if ( $is_child == false ) {
                    $child_fetch = array(
                        'relation' => 'OR',
                        array(
                            'key' => 'wp_rem_parent_review',
                            'compare' => 'NOT EXISTS',
                        ),
                        array(
                            'key' => 'wp_rem_parent_review',
                            'value' => '',
                            'compare' => '=',
                        ),
                    );
                }
                $args['meta_query'] = array(
                    array(
                        'key' => 'company_id',
                        'value' => $id,
                    ),
                    $child_fetch,
                );
            } else {
                $post = get_post($id);
                $slug = '';
                if ( $post == null ) {
                    return array();
                }
                $slug = $post->post_name;

                if ( $my_reviews == true ) {

                    $properties_args = array(
                        'post_type' => 'properties',
                        'posts_per_page' => -1,
                        'post_status' => 'publish',
                        'meta_query' => array(
                            array(
                                'key' => 'wp_rem_property_member',
                                'value' => $id,
                                'compare' => '=',
                            ),
                            array(
                                'key' => 'wp_rem_property_status',
                                'value' => 'delete',
                                'compare' => '!=',
                            ),
                        ),
                    );

                    $properties_query = new WP_Query($properties_args);
                    $slug_array = array( 0 );
                    $all_properties = $properties_query->get_posts();

                    if ( ! empty($all_properties) ) {
                        foreach ( $all_properties as $property_key => $property_data ) {
                            $slug_array[] = $property_data->post_name;
                        }
                    }
                    $post_meta_query = array(
                        'key' => 'post_id',
                        'value' => $slug_array,
                        'compare' => 'IN'
                    );
                } else {
                    $post_meta_query = array(
                        'key' => 'post_id',
                        'value' => $slug,
                    );
                }


                $args['meta_query'] = array(
                    $post_meta_query
                );
            }

            $query = new WP_Query($args);
            return $query->found_posts;
        }

        /**
         * Handle AJAX request to fetch user reviews for a post
         */
        public function get_user_reviews_callback() {
            $return = array( 'success' => true, 'data' => '', 'count' => 0, 'ratings_summary_ui' => '', 'overall_ratings_ui' => '' );
            $post_id = isset($_POST['post_id']) ? $_POST['post_id'] : '';
            $offset = isset($_POST['offset']) ? $_POST['offset'] : 0;
            $sort_by = isset($_POST['sort_by']) ? $_POST['sort_by'] : 0;
            $all_data = isset($_POST['all_data']) ? $_POST['all_data'] : 0;


            $current_user = wp_get_current_user();
            if ( 0 < $current_user->ID ) {
                $company_id = get_user_meta($current_user->ID, 'wp_rem_company', true);
            }
            $is_user_post_owner = false;

            if ( 0 < $company_id ) {
                $is_user_post_owner = $this->is_this_user_owner_of_this_post_callback(false, $company_id, $post_id);
            }

            //$is_review_response_enable = get_post_meta($post_id, 'wp_rem_transaction_property_ror', true);
            //$is_review_response_enable = ( isset($is_review_response_enable) && $is_review_response_enable == 'on' ) ? true : false;

            $is_review_response_enable = true;

            $property_type_slug = get_post_meta($post_id, 'wp_rem_property_type', true);
            $args = array(
                'name' => $property_type_slug,
                'post_type' => 'property-type',
                'post_status' => 'publish',
                'numberposts' => 1,
            );
            $property_types = get_posts($args);
            $property_type_id = $property_types[0]->ID;
            if ( 0 != $property_type_id ) {
                $wp_rem_review_number_of_reviews = get_post_meta($property_type_id, 'wp_rem_review_number_of_reviews', true);
                Wp_rem_Reviews::$posts_per_page = ( $wp_rem_review_number_of_reviews == '' ? Wp_rem_Reviews::$posts_per_page : $wp_rem_review_number_of_reviews );
            }

            $reviews = $this->get_user_reviews_for_post($post_id, $offset, Wp_rem_Reviews::$posts_per_page, $sort_by);

            $reviews_count = count($reviews);
            foreach ( $reviews as $review_data ) {
                if ( ! isset($review_data['is_reply']) || $review_data['is_reply'] == '' ) {
                    $reviews_count_array[] = $review_data;
                }
            }
            //$reviews_count = count( $reviews );

            if ( $all_data == 1 && $property_type_id > 0 ) {
                $ratings_summary = array();
                $overall_ratings = array(
                    5 => 0,
                    4 => 0,
                    3 => 0,
                    2 => 0,
                    1 => 0,
                );

                $wp_rem_reviews_labels = get_post_meta($property_type_id, 'wp_rem_reviews_labels', true);
                $wp_rem_reviews_labels = ( $wp_rem_reviews_labels == '' ? array() : json_decode($wp_rem_reviews_labels, true) );

                // Get existing ratings for this post.
                $existing_ratings_data = get_post_meta($post_id, 'wp_rem_ratings', true);
                if ( '' != $existing_ratings_data ) {
                    $reviews_count = $existing_ratings_data['reviews_count'];

                    $existing_ratings = $existing_ratings_data['ratings'];
                    foreach ( $wp_rem_reviews_labels as $key => $val ) {
                        if ( isset($existing_ratings[$val]) ) {
                            $value = $existing_ratings[$val];
                        } else {
                            $value = 0;
                        }
                        $ratings_summary[] = array( 'label' => $val, 'value' => $value );
                    }
                    $existing_overall_ratings = $existing_ratings_data['overall_rating'];
                    foreach ( $existing_overall_ratings as $key => $val ) {
                        if ( isset($overall_ratings[$key]) ) {
                            $overall_ratings[$key] = $val;
                        }
                    }
                } else {
                    foreach ( $wp_rem_reviews_labels as $key => $val ) {
                        $ratings_summary[] = array( 'label' => $val, 'value' => 0 );
                    }
                }

                ob_start();
                $this->get_ratings_summary_ui($ratings_summary, $reviews_count);
                $return['ratings_summary_ui'] = ob_get_clean();

                ob_start();
                $this->get_overall_rating_ui($overall_ratings, $reviews_count);
                $return['overall_ratings_ui'] = ob_get_clean();
            }

            ob_start();
            ?>
            <?php if ( 0 < count($reviews) ) : ?>
                <?php foreach ( $reviews as $key => $review ) : ?>
                    <?php
                    if ( ! empty($review) ) {
                        $review_post_id = isset($review['id']) ? $review['id'] : '';
                        $ratings_ser_list = get_post_meta($review_post_id, 'ratings', true);
                        $tooltip_html = '';
                        if ( is_array($ratings_ser_list) && sizeof($ratings_ser_list) > 0 ) {
                            $tooltip_html .= '<ul class="ratings-popover-listing">';
                            foreach ( $ratings_ser_list as $ser_key => $rating_ser_list ) {

                                if ( $ser_key == '' ) {
                                    $ser_key = '';
                                } else {
                                    $ser_key = $ser_key . ' : ';
                                }

                                $rating_ser_list = absint($rating_ser_list);

                                $tooltip_html .= '<li>' . $ser_key . $rating_ser_list . '</li>';
                            }
                            $tooltip_html .= '</ul>';
                        }
                        ?>
                        <?php $reply_class = ( isset($review['is_reply']) && $review['is_reply'] == true ) ? 'review_reply' : ''; ?>
                        <?php $review_title = ( isset($review['review_title']) && $review['review_title'] != '' ) ? '| ' . $review['review_title'] : ''; ?>
                        <li class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="list-holder <?php echo $reply_class; ?>">
                                <div class="img-holder"><figure><img src="<?php echo $review['img']; ?>" alt="<?php echo $review['user_name']; ?>" /></figure></div>
                                <div class="img-holder-content">
                                    <div class="review-title">
                                        <p><?php echo $review['username']; ?></p>
                                        <?php
                                        $show_ratings = $this->enable_comments($post_id);

                                        if ( $show_ratings == 'on' ) {
                                            ?>

                                            <div class="rating-holder">
                                                <em><?php echo date('M Y', strtotime($review['dated'])); ?></em>
                                                <?php if ( isset($review['is_reply']) && $review['is_reply'] != true ) { ?>
                                                    <div class="rating-star"<?php if ( $tooltip_html != '' ) { ?> data-toggle="popover_html"<?php } ?>>
                                                        <span style="width: <?php echo ( $review['overall_rating'] / 5 ) * 100; ?>%;" class="rating-box"></span>
                                                    </div>
                                                    <?php if ( $tooltip_html != '' ) { ?>
                                                        <div class="ratings-popover-content" style="display:none;"><?php echo ($tooltip_html); ?></div>
                                                    <?php } ?>
                                                <?php } ?>
                                            </div>
                                        <?php } ?>
                                    </div>
                                    <?php
                                    if ( $reply_class == '' ) {
                                        ?>
                                        <div id="review-helpful-holder-<?php echo absint($review_post_id) ?>" class="review-helpful-holder">
                                            <?php
                                            $total_helpful_count = get_post_meta($review_post_id, 'review_marked_helpful_count', true);
                                            if ( isset($_COOKIE['review_marked_helpful_' . $review_post_id]) && $_COOKIE['review_marked_helpful_' . $review_post_id] == '1' ) {
                                                ?>
                                                <a id="mark-review-helpful-<?php echo absint($review_post_id) ?>" data-id="<?php echo absint($review_post_id) ?>" href="javascript:void(0)" class="mark-review-helpful active-mark"><i class="icon-thumbs-o-up"></i> <span class="marked-helpful-txt"><?php echo wp_rem_plugin_text_srt('wp_rem_reviews_marked_helpful'); ?></span> <div class="marked-helpful-counts"><span><?php echo absint($total_helpful_count) ?></span></div></a>
                                                <?php
                                            } else {
                                                ?>
                                                <a id="mark-review-helpful-<?php echo absint($review_post_id) ?>" data-id="<?php echo absint($review_post_id) ?>" href="javascript:void(0)" class="mark-review-helpful"><i class="icon-thumbs-o-up"></i> <span class="marked-helpful-txt"><?php echo wp_rem_plugin_text_srt('wp_rem_reviews_marked_helpful'); ?></span> <div class="marked-helpful-counts"><span><?php echo absint($total_helpful_count) ?></span></div></a>
                                                <?php
                                            }
                                            ?>
                                        </div>
                                        <div id="review-flag-holder-<?php echo absint($review_post_id) ?>" class="review-flag-holder">
                                            <?php
                                            $total_flag_count = get_post_meta($review_post_id, 'review_marked_flag_count', true);
                                            if ( isset($_COOKIE['review_marked_flag_' . $review_post_id]) && $_COOKIE['review_marked_flag_' . $review_post_id] == '1' ) {
                                                ?>
                                                <a id="mark-review-flag-<?php echo absint($review_post_id) ?>" data-id="<?php echo absint($review_post_id) ?>" class="mark-review-flag active-mark"><i class="icon-flag-o"></i> <span class="marked-flag-txt"><?php echo wp_rem_plugin_text_srt('wp_rem_reviews_marked_flag'); ?></span></a>
                                                <?php
                                            } else {
                                                ?>
                                                <a id="mark-review-flag-<?php echo absint($review_post_id) ?>" data-id="<?php echo absint($review_post_id) ?>" href="javascript:void(0)" class="mark-review-flag"><i class="icon-flag-o"></i> <span class="marked-flag-txt"><?php echo wp_rem_plugin_text_srt('wp_rem_reviews_marked_flag'); ?></span></a>
                                                <?php
                                            }
                                            ?>
                                        </div>
                                        <?php
                                    }
                                    ?>
                                </div>
                                <div class="review-text">
                                    <p>
                                        <?php echo $this->reviews_text_filter($review['description']); ?>
                                    </p>
                                    <?php
                                    if ( $is_review_response_enable == true && $is_user_post_owner == true ) {
                                        echo $this->posting_review_reply($review);
                                    }
                                    ?>

                                </div>
                            </div>
                        </li>
                    <?php } ?>
                <?php endforeach; ?>
                <script>
                    $('[data-toggle="popover_html"]').popover({
                        placement: 'bottom',
                        container: 'body',
                        trigger: 'hover',
                        html: true,
                        content: function () {
                            return $(this).next('.ratings-popover-content').html();
                        }
                    });
                </script>
            <?php else: ?>
                <li class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="list-holder"><?php echo wp_rem_plugin_text_srt('wp_rem_reviews_no_more_reviews_text'); ?></div>
                </li>
            <?php endif; ?>
            <?php
            $output = ob_get_clean();
            $return['data'] = $output;
            $return['count'] = count($reviews_count_array);
            echo json_encode($return);
            wp_die();
        }

        /*
         * Posting Review Reply by Listing's Owner
         */

        public function posting_review_reply_dashboard($review, $posts) {
            if ( ( ! isset($review['is_reply']) || $review['is_reply'] == false ) && ( ! isset($review['is_already_replied']) || $review['is_already_replied'] == false ) ) {

                $post_id = isset($posts[0]->ID) ? $posts[0]->ID : 0;
                $property_type = get_post_meta($post_id, 'wp_rem_property_type', true);
                $the_slug = $property_type;
                $args = array(
                    'name' => $the_slug,
                    'post_type' => 'property-type',
                    'post_status' => 'publish',
                    'numberposts' => 1
                );
                $property_types = get_posts($args);
                $property_type_id = $property_types[0]->ID;
                $wp_rem_review_min_length = get_post_meta($property_type_id, 'wp_rem_review_min_length', true);
                $wp_rem_review_min_length = ( $wp_rem_review_min_length == '' ? 10 : $wp_rem_review_min_length );
                $wp_rem_review_max_length = get_post_meta($property_type_id, 'wp_rem_review_max_length', true);
                $wp_rem_review_max_length = ( $wp_rem_review_max_length == '' ? 200 : $wp_rem_review_max_length );
                ?>
                <a href="javascript:void(0)" data-id="<?php echo ($review['id']) ?>" data-postid="<?php echo ($post_id) ?>" data-posttypeid="<?php echo ($property_type_id) ?>" data-maxlen="<?php echo ($wp_rem_review_max_length) ?>" data-minlen="<?php echo ($wp_rem_review_min_length) ?>" class="review-reply-btn"><?php echo wp_rem_plugin_text_srt('wp_rem_post_reply'); ?><i class="icon-reply"></i></a>
                <script>
                    $(document).on('click', '.review-reply-btn', function () {
                        var reply_modal = $('#review-reply-modal-box');
                        var review_ID = $(this).attr('data-id');

                        $("#parent_review_id").val(review_ID);

                        var postid = $(this).attr('data-postid');
                        var posttypeid = $(this).attr('data-posttypeid');
                        var maxlen = $(this).attr('data-maxlen');
                        var minlen = $(this).attr('data-minlen');

                        reply_modal.find('#pt-post-id').val(postid);
                        reply_modal.find('#pt-post-type-id').val(posttypeid);
                        reply_modal.find('#pt-review-id').val(review_ID);
                        reply_modal.find('#pt-review-max-length').val(maxlen);
                        reply_modal.find('textarea#review_description').attr('maxlength', maxlen);
                        reply_modal.find('#pt-review-min-length').val(minlen);

                        reply_modal.modal('show');
                        return false;
                    });
                </script>
                <?php
            }
        }

        /*
         * Posting Review Reply by Listing's Owner
         */

        public function posting_review_reply($review) {
            if ( ( ! isset($review['is_reply']) || $review['is_reply'] == false ) && ( ! isset($review['is_already_replied']) || $review['is_already_replied'] == false ) ) {
                ?>
                <a href="#" data-id="<?php echo $review['id']; ?>" class="review-reply-btn"><?php echo wp_rem_plugin_text_srt('wp_rem_post_reply'); ?><i class="icon-reply"></i></a>
                <?php
            }
            ?>
            <script>
                $(document).on('click', '.review-reply-btn', function () {
                    var reply_modal = $('#review-reply-modal-box');
                    var review_ID = $(this).data('id');

                    $('#review-rating-form-title').hide();
                    $('#review-rating-fields').hide();
                    var hidden_area = $('.add-new-review-holder');
                    hidden_area.show();

                    var review_textarea = $('#review-textarea-field');
                    var review_btn_area = $('#review-submit-fields-area');
                    var review_textarea_modal = $('#review-textarea-field-modal');
                    var review_btn_area_modal = $('#review-submit-fields-area-modal');
                    var textarea_html = review_textarea.html();
                    var btn_area_html = review_btn_area.html();
                    review_textarea_modal.html(textarea_html);
                    review_btn_area_modal.html(btn_area_html);

                    var remTimeInt = setInterval(function () {
                        clearInterval(remTimeInt);
                        review_textarea.html('');
                        review_btn_area.html('');
                        $("#parent_review_id").val(review_ID);
                    }, 100);
                    reply_modal.modal('show');
                    return false;
                });

                $('#review-reply-modal-box').on('hidden.bs.modal', function () {
                    var review_textarea = $('#review-textarea-field');
                    var review_btn_area = $('#review-submit-fields-area');
                    var review_textarea_modal = $('#review-textarea-field-modal');
                    var review_btn_area_modal = $('#review-submit-fields-area-modal');
                    var textarea_modal_html = review_textarea_modal.html();
                    var btn_area_modal_html = review_btn_area_modal.html();
                    review_textarea.html(textarea_modal_html);
                    review_btn_area.html(btn_area_modal_html);

                    var remTimeIntM = setInterval(function () {
                        clearInterval(remTimeIntM);
                        review_textarea_modal.html('');
                        review_btn_area_modal.html('');
                    }, 100);
                    $(".add-new-review-holder").css("display", "none");
                    $('#review-rating-form-title').show();
                    $('#review-rating-fields').show();
                    $(".ajax-message").html('');
                    return false;
                });
            </script>
            <?php
        }

        /**
         * Handle AJAX request to fetch user reviews for a company for dashboard.
         */
        public function get_user_reviews_for_dashboard_callback() {
            $success = false;
            $msg = wp_rem_plugin_text_srt('wp_rem_reviews_incomplete_data_msg');
            $company_id = isset($_POST['company_id']) ? $_POST['company_id'] : '';
            $offset = isset($_POST['offset']) ? $_POST['offset'] : 0;
            $sort_by = isset($_POST['sort_by']) ? $_POST['sort_by'] : 0;
            $my_review = isset($_POST['my_review']) ? $_POST['my_review'] : false;
            $my_review = ( $my_review == 'yes' ) ? true : false;
            $is_child = isset($_POST['is_child']) ? $_POST['is_child'] : true;
            $is_child = ( $is_child == 'no' ) ? false : true;
            $is_company = isset($_POST['is_company']) ? $_POST['is_company'] : true;
            $is_company = ( $is_company == 'no' ) ? false : true;
            $reviews = $this->get_user_reviews_for_post($company_id, $offset, Wp_rem_Reviews::$posts_per_page, $sort_by, $is_company, $my_review, $is_child);

            ob_start();
            ?>
            <script>
                $(document).ready(function () {
                    // Configure/customize these variables.
                    var showChar = 220;  // How many characters are shown by default
                    var ellipsestext = ".";
                    var moretext = "<?php echo wp_rem_plugin_text_srt('wp_rem_reviews_show_more') ?>";
                    var lesstext = "<?php echo wp_rem_plugin_text_srt('wp_rem_reviews_show_less') ?>";
                    $('.more').each(function () {
                        var content = $(this).html();
                        if (content.length > showChar) {
                            var c = content.substr(0, showChar);
                            var h = content.substr(showChar, content.length - showChar);
                            var html = c + '<span class="moreellipses">' + ellipsestext + '&nbsp;</span><span class="morecontent"><span>' + h + '</span>&nbsp;&nbsp;<a href="" class="morelink">' + moretext + '</a></span>';
                            $(this).html(html);
                        }

                    });
                    $(".morelink").click(function () {
                        if ($(this).hasClass("less")) {
                            $(this).removeClass("less");
                            $(this).html(moretext);
                        } else {
                            $(this).addClass("less");
                            $(this).html(lesstext);
                        }
                        //$(this).parent().prev().toggle();
                        $(this).prev().toggle();
                        return false;
                    });
                });

            </script> 
            <?php
            if ( 0 < count($reviews) ) :
                /* $post_slug = get_post_meta( $reviews[0]['id'], 'post_id', true );
                  $args = array(
                  'name' => $post_slug,
                  'post_type' => 'properties',
                  );
                  $posts = get_posts( $args ); */

                foreach ( $reviews as $key => $review ) :
                     if ( ! empty($review) ) {
                $post_slug = get_post_meta($reviews[$key]['id'], 'post_id', true);
                $args = array(
                    'name' => $post_slug,
                    'post_type' => 'properties',
                );
                $posts = get_posts($args);
                $review_post_id = isset($review['id']) ? $review['id'] : '';
                $ratings_ser_list = get_post_meta($review_post_id, 'ratings', true);

                $is_review_response_enable = true;
                ?>
                <?php $reply_class = ( isset($review['is_reply']) && $review['is_reply'] == true ) ? 'review_reply' : ''; ?>
                <?php $review_title = ( isset($review['review_title']) && $review['review_title'] != '' ) ? '| ' . $review['review_title'] : ''; ?>
                <li id="alert-review-<?php echo $review_post_id; ?>" class="parent_review_<?php echo esc_html($review_post_id); ?>  col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="list-holder <?php echo $reply_class; ?>">
                        <div class="img-holder"><figure><img src="<?php echo $review['img']; ?>" alt="<?php echo $review['user_name']; ?>" /></figure></div>
                        <div class="img-holder-content">
                            <div class="review-title">
                                <p><?php echo $review['username']; ?></p>
                                <div class="rating-holder">
                                    <em><?php echo date('M Y', strtotime($review['dated'])); ?></em>
                                    <?php if ( isset($review['is_reply']) && $review['is_reply'] != true ) { ?>
                                        <div class="rating-star">
                                            <span style="width: <?php echo ( $review['overall_rating'] / 5 ) * 100; ?>%;" class="rating-box"></span>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                        <div class="review-text">
                            <p class="more">
                                <?php echo $this->reviews_text_filter($review['description']); ?>
                            </p>
                        </div>
                        <?php
                        
                        if ( isset($review['is_reply']) && $review['is_reply'] == true ) {
                            ?>
                            <span class="delete-this-user-review close" data-dismiss="alert" data-review-id="<?php echo $review['id']; ?>"><i class="icon-close"></i></span>
                            <?php
                        }
                        ?>
                    </div>
                </li>
                <?php
            }endforeach; ?>
            <?php else : ?>
                <li class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="list-holder"><?php echo wp_rem_plugin_text_srt('wp_rem_reviews_no_more_reviews_text'); ?></div>
                </li>
            <?php endif; ?>
            <?php
            $output = ob_get_clean();
            echo json_encode(array( 'success' => true, 'data' => $output, 'count' => count($reviews) ));
            wp_die();
        }

        /**
         *  Handle AJAX request to add user review.
         */
        public function post_user_review_callback() {
            global $wp_rem_plugin_options;
            $success = false;
            $msg = wp_rem_plugin_text_srt('wp_rem_reviews_incomplete_data_msg');
            $post_id = isset($_POST['post_id']) ? $_POST['post_id'] : 0;
            $property_type_id = isset($_POST['property_type_id']) ? $_POST['property_type_id'] : 0;
            $user_name = isset($_POST['user_name']) ? $_POST['user_name'] : '';
            $user_id = isset($_POST['user_id']) ? $_POST['user_id'] : 0;
            $review_title = isset($_POST['review_title']) ? $_POST['review_title'] : 0;
            $company_id = isset($_POST['company_id']) ? $_POST['company_id'] : 0;
            $user_email = isset($_POST['user_email']) ? $_POST['user_email'] : '';
            $dash_reply = isset($_POST['dash_reply']) && $_POST['dash_reply'] == '1' ? true : false;
            $description = isset($_POST['description']) ? $_POST['description'] : '';
            $ratings = isset($_POST['ratings']) ? $_POST['ratings'] : '[]';
            $ratings = json_decode(stripslashes($ratings), true);
            $average_of_ratings = array_sum($ratings) / count($ratings);

            //  $overall_rating = isset($_POST['overall_rating']) ? $_POST['overall_rating'] : 1;
            $overall_rating = round($average_of_ratings);

            $wp_rem_captcha_switch_option = isset($wp_rem_plugin_options['wp_rem_captcha_switch']) ? $wp_rem_plugin_options['wp_rem_captcha_switch'] : '';
            $wp_rem_captcha = isset($_POST['g-recaptcha-response']) ? $_POST['g-recaptcha-response'] : '';

            $property_type = get_post_meta($post_id, 'wp_rem_property_type', true);
            $the_slug = $property_type;
            $args = array(
                'name' => $the_slug,
                'post_type' => 'property-type',
                'post_status' => 'publish',
                'numberposts' => 1,
            );
            $property_types = get_posts($args);

            $child_rep_html = '';
            if ( 0 == count($property_types) ) {
                // Incomplete data msg.
            } else {

                if ( $dash_reply ) {

                    $post = get_post($post_id);
                    if ( $post != null ) {

                        // Gather post data.
                        $review_post = array(
                            'post_title' => $review_title,
                            'post_content' => $description,
                            'post_status' => 'publish',
                            'post_type' => Wp_rem_Reviews::$post_type_name,
                        );

                        // Insert the post into the database.
                        $review_id = wp_insert_post($review_post);

                        $upd_post = array(
                            'ID' => $review_id,
                            'post_title' => $review_id,
                        );
                        wp_update_post($upd_post);

                        add_post_meta($review_id, 'post_id', $post->post_name, true);

                        // Add user id to post meta.
                        add_post_meta($review_id, 'user_id', $user_id, true);

                        // Add company id to post meta.
                        add_post_meta($review_id, 'company_id', $company_id, true);

                        // Add user name to post meta.
                        add_post_meta($review_id, 'user_name', $user_name, true);

                        // Add user email to post meta.
                        add_post_meta($review_id, 'user_email', $user_email, true);

                        if ( isset($_POST['parent_review_id']) && $_POST['parent_review_id'] != '' ) {
                            add_post_meta($review_id, 'wp_rem_parent_review', $_POST['parent_review_id'], true);
                        }

                        $reply_post = get_post($review_id);
                        $rep_user_id = get_post_meta($review_id, 'user_id', true);
                        $rep_company_id = get_user_meta($rep_user_id, 'wp_rem_company', true);
                        $rep_user_img = '';
                        if ( $rep_user_id != '' && $rep_user_id > 0 ) {
                            $wp_rem_profile_image = $this->publisher_get_profile_image($rep_company_id);
                            if ( $wp_rem_profile_image != '' ) {
                                $rep_user_img = $wp_rem_profile_image;
                            }
                        }

                        if ( $rep_user_img == '' ) {
                            $rep_user_img = get_avatar_url(0, array( 'size' => 32 ));
                        }
                        $child_rep_html = '
						<li class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<div class="list-holder review_reply">
								<div class="img-holder"><figure><img src="' . $rep_user_img . '" alt=""></figure></div>
								<div class="img-holder-content">
									<div class="review-title">
										<p>' . get_the_title($post_id) . '</p>
										<div class="rating-holder">
											<em>' . date_i18n('M, Y', strtotime($reply_post->post_date)) . '</em>
										</div>
									</div>
								</div>
								<div class="review-text">
									<p class="more">' . ($reply_post->post_content) . '</p>
								</div>
							</div>
						</li>';

                        echo json_encode(array( 'type' => 'success', 'msg' => '', 'child_rep_html' => $child_rep_html ));
                        die;
                    }
                }

                $property_type_id = $property_types[0]->ID;
                $wp_rem_review_captcha_for_reviews = get_post_meta($property_type_id, 'wp_rem_review_captcha_for_reviews', true);

                $wp_rem_review_captcha_for_reviews = ( $wp_rem_review_captcha_for_reviews == '' ? 'off' : $wp_rem_review_captcha_for_reviews );

                if ( $wp_rem_review_captcha_for_reviews == 'on' && $wp_rem_captcha_switch_option == 'on' && $wp_rem_captcha == '' && ( ! is_user_logged_in() ) ) {
                    $success = false;
                    $msg = wp_rem_plugin_text_srt('wp_rem_reviews_recaptcha_error_msg');
                } else {
                    $have_already_added = false;
                    $is_user_post_owner = false;

                    $child_review = false;

                    if ( isset($_POST['parent_review_id']) && $_POST['parent_review_id'] != '' ) {
                        $child_review = true;
                    }

                    if ( $company_id > 0 ) {
                        $have_already_added = $this->have_user_added_review_for_this_post_callback(false, $company_id, $post_id);
                        $is_user_post_owner = $this->is_this_user_owner_of_this_post_callback(false, $company_id, $post_id);
                    } else {
                        // Check if review added by this email to this post already.
                        $have_already_added = $this->have_user_added_review_for_this_post_callback(false, $user_email, $post_id, true);
                    }

                    if ( $have_already_added && $child_review == false ) {
                        // Set reponse message to false.
                        $success = false;
                        $msg = wp_rem_plugin_text_srt('wp_rem_reviews_already_added_review0_msg');
                    } else if ( $is_user_post_owner && $child_review == false ) {
                        // Set reponse message to true.
                        $success = false;
                        $msg = $_POST['parent_review_id'];
                    } else {

                        $post = get_post($post_id);
                        if ( $post != null ) {
                            $is_auto_approve_reviews = get_post_meta($property_type_id, 'wp_rem_auto_approve_reviews', true);
                            $is_auto_approve_reviews = ( $is_auto_approve_reviews == '' ? 'off' : $is_auto_approve_reviews );
                            $post_status = ( $is_auto_approve_reviews == 'on' ? 'publish' : 'pending' );

                            // Gather post data.
                            $review_post = array(
                                'post_title' => $review_title,
                                'post_content' => $description,
                                'post_status' => $post_status,
                                'post_type' => Wp_rem_Reviews::$post_type_name,
                            );

                            // Insert the post into the database.
                            $review_id = wp_insert_post($review_post);

                            $upd_post = array(
                                'ID' => $review_id,
                                'post_title' => $review_id,
                            );
                            wp_update_post($upd_post);

                            if ( $child_review != true ) {
                                // Get existing ratings for this post.
                                $existing_ratings = get_post_meta($post_id, 'wp_rem_ratings', true);

                                $new_ratings = array_diff_key($ratings, isset($existing_ratings['ratings']) ? $existing_ratings['ratings'] : array());

                                if ( $existing_ratings == '' ) {
                                    $existing_ratings = array(
                                        'ratings' => array(),
                                        'overall_rating' => array( 1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0 ),
                                        'reviews_count' => 0,
                                    );

                                    foreach ( $ratings as $key => $val ) {
                                        $existing_ratings['ratings'][$key] = 0;
                                    }
                                } else {
                                    $new_keys = array_keys($new_ratings);
                                    foreach ( $new_keys as $key ) {
                                        $existing_ratings['ratings'][$key] = 0;
                                    }
                                }


                                // Add new ratings to existing.
                                foreach ( $existing_ratings['ratings'] as $key => $val ) {
                                    if ( isset($ratings[$key]) ) {
                                        $existing_ratings['ratings'][$key] += floatval($ratings[$key]);
                                    }
                                }
                                $existing_ratings['reviews_count'] ++;

                                $existing_ratings['overall_rating'][$overall_rating] ++;

                                if ( $post_status == 'pending' ) {
                                    update_post_meta($review_id, 'existing_ratings', $existing_ratings);
                                } else {
                                    // Do not updated ratings if its a reply from owner.
                                    update_post_meta($post_id, 'wp_rem_ratings', $existing_ratings);
                                }
                            }
                            // Keep slug of the post for which reviews is added keep this in review meta.
                            add_post_meta($review_id, 'post_id', $post->post_name, true);

                            // Add Overall Rating to post meta.
                            add_post_meta($review_id, 'overall_rating', $overall_rating, true);

                            // Add Ratings to post meta.
                            add_post_meta($review_id, 'ratings', $ratings, true);

                            // Add user id to post meta.
                            add_post_meta($review_id, 'user_id', $user_id, true);

                            // Add company id to post meta.
                            add_post_meta($review_id, 'company_id', $company_id, true);

                            // Add user name to post meta.
                            add_post_meta($review_id, 'user_name', $user_name, true);

                            // Add user email to post meta.
                            add_post_meta($review_id, 'user_email', $user_email, true);

                            // Listing overall review ratings
                            $ratings_data = array(
                                'overall_rating' => 0.0,
                                'count' => 0,
                            );
                            $ratings_data = apply_filters('reviews_ratings_data', $ratings_data, $post_id);
                            if ( $ratings_data['count'] > 0 ) {
                                update_post_meta($review_id, 'property_overall_rating', $ratings_data['overall_rating']);
                            }

                            // Add Review Parent if any
                            if ( isset($_POST['parent_review_id']) && $_POST['parent_review_id'] != '' ) {
                                add_post_meta($review_id, 'wp_rem_parent_review', $_POST['parent_review_id'], true);
                            }

                            if ( ! is_wp_error($review_id) ) {
                                $user_data = wp_get_current_user();
                                if ( $user_data->ID < 1 ) {
                                    $user_data = new stdClass();
                                    $user_data->ID = 0;
                                    $user_data->display_name = $user_name;
                                    $user_data->user_email = $user_email;
                                }

                                if ( $child_review == true ) {
                                    // do_action('wp_rem_review_reply_added_email', $user_data, $review_id);
                                } else {
                                    // do_action('wp_rem_review_added_email', $user_data, $review_id);
                                }
                            }

                            // Set reponse message to true.
                            $success = true;
                            $msg = wp_rem_plugin_text_srt('wp_rem_reviews_success_msg');
                            $publisher_name = get_the_title($company_id);

                            if ( $post_status != 'pending' ) {
                                if ( $child_review != true ) {
                                    /*
                                     * Adding Notification
                                     */
                                    $notification_array = array(
                                        'type' => 'review',
                                        'element_id' => $post_id,
                                        'message' => sprintf(wp_rem_plugin_text_srt('wp_rem_reviews_num_of_reviews_on_your_property'), $user_name, get_the_permalink($post_id), wp_trim_words(get_the_title($post_id), 5)),
                                    );
                                    //do_action('wp_rem_add_notification', $notification_array);
                                }
                            }
                        }
                    }
                }
            }
            echo json_encode(array( 'success' => $success, 'msg' => $msg ));
            wp_die();
        }

        /**
         * Handle AJAX request and render Dashboard My Reviews Tab Container.
         */
        public function dashboard_my_reviews_ui_callback() {
            $user_id = get_current_user_id();
            if ( $user_id == 0 ) {
                echo json_encode(array( 'success' => false, 'msg' => wp_rem_plugin_text_srt('wp_rem_reviews_invalid_user') ));
                wp_die();
            }
            $company_id = get_user_meta($user_id, 'wp_rem_company', true);
            $company_id = ( $company_id != '' ? $company_id : 0 );

            $user_email = get_post_meta($company_id, 'wp_rem_email_address', true);

            $publisher_display_name = get_the_title($company_id);

            $reviews_count = $this->get_user_reviews_count($company_id, false, true, true);
            $reviews = $this->get_user_reviews_for_post($company_id, 0, Wp_rem_Reviews::$posts_per_page, 'newest', false, true);
            ob_start();
            ?>
            <div class="row">
                <div class = "col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class = "element-title has-border">
                        <h4><?php echo wp_rem_plugin_text_srt('wp_rem_reviews_name') ?></h4>
                        <div class="col-lg-8 col-md-8 col-sm-12 pull-right">
                            <ul class="dashboard-nav sub-nav">
                                <?php if ( true === Wp_rem_Member_Permissions::check_permissions('company_profile') ) { ?>
                                    <li id="wp_rem_publisher_reviews" class="user_dashboard_ajax" data-queryvar="dashboard=reviews"><a href="javascript:void(0);" class="btn-edit-profile"><?php echo wp_rem_plugin_text_srt('wp_rem_given_reviews_dashboard_heading') ?></a></li>
                                    <li class="user_dashboard_ajax active" id="wp_rem_publisher_my_reviews" data-queryvar="dashboard=my_reviews"><a href="javascript:void(0)"><?php echo wp_rem_plugin_text_srt('wp_rem_reviews_dashboard_heading'); ?></a></li>
                                    <?php
                                }
                                ?>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="user-reviews-list reviews-rating-main-con">
                        <div class=" review-list">
                            <div class="elements-title">
                                <span><?php echo wp_rem_plugin_text_srt('wp_rem_reviews_dashboard_heading'); ?></span>
                                <span class="element-slogan"><?php echo sprintf(wp_rem_plugin_text_srt('wp_rem_reviews_dashboard_stats'), $reviews_count); ?></span>
                                <div class="sort-by">
                                    <ul class="reviews-sortby">
                                        <li> 
                                            <span><?php echo wp_rem_plugin_text_srt('wp_rem_reviews_dashboard_sort_by_label'); ?>: <strong class="active-sort"><?php echo wp_rem_plugin_text_srt('wp_rem_reviews_sort_by_newest_reviews_option'); ?></strong></span>
                                            <div class="reviews-sort-dropdown">
                                                <form>
                                                    <div class="input-reviews">
                                                        <div class="radio-field">
                                                            <input name="review" id="check-1" type="radio" value="newest">
                                                            <label for="check-1"><?php echo wp_rem_plugin_text_srt('wp_rem_reviews_dashboard_sort_by_newest_reviews_option'); ?></label>
                                                        </div>
                                                        <div class="radio-field">
                                                            <input name="review" id="check-2" type="radio" value="highest">
                                                            <label for="check-2"><?php echo wp_rem_plugin_text_srt('wp_rem_reviews_dashboard_sort_by_highest_rating_option'); ?></label>
                                                        </div>
                                                        <div class="radio-field">
                                                            <input name="review" id="check-3" type="radio" value="lowest">
                                                            <label for="check-3"><?php echo wp_rem_plugin_text_srt('wp_rem_reviews_dashboard_sort_by_lowest_rating_option'); ?></label>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <script>

                            jQuery(document).ready(function () {

                                jQuery('.reviews-sortby > li').on('click', function () {
                                    jQuery('.reviews-sortby > li').toggleClass('reviews-sortby-active');
                                    jQuery('.reviews-sortby > li').siblings();
                                    jQuery('.reviews-sortby > li').siblings().removeClass('reviews-sortby-active');
                                });
                                jQuery('.input-reviews > .radio-field label').on('click', function () {
                                    jQuery(this).parent().toggleClass('active');
                                    jQuery(this).parent().siblings();
                                    jQuery(this).parent().siblings().removeClass('active');
                                    /*replace inner Html*/
                                    var radio_field_active = jQuery(this).html();
                                    jQuery(".active-sort").html(radio_field_active);
                                    jQuery('.reviews-sortby > li').removeClass('reviews-sortby-active');
                                });
                                // Configure/customize these variables.
                                var showChar = 220;  // How many characters are shown by default
                                var ellipsestext = ".";
                                var moretext = "<?php echo wp_rem_plugin_text_srt('wp_rem_reviews_show_more') ?>";
                                var lesstext = "<?php echo wp_rem_plugin_text_srt('wp_rem_reviews_show_less') ?>";
                                $('.more').each(function () {
                                    var content = $(this).html();
                                    if (content.length > showChar) {
                                        var c = content.substr(0, showChar);
                                        var h = content.substr(showChar, content.length - showChar);
                                        var html = c + '<span class="moreellipses">' + ellipsestext + '&nbsp;</span><span class="morecontent"><span>' + h + '</span>&nbsp;&nbsp;<a href="" class="morelink">' + moretext + '</a></span>';
                                        $(this).html(html);
                                    }

                                });
                                $(".morelink").click(function () {
                                    if ($(this).hasClass("less")) {
                                        $(this).removeClass("less");
                                        $(this).html(moretext);
                                    } else {
                                        $(this).addClass("less");
                                        $(this).html(lesstext);
                                    }
                                    //$(this).parent().prev().toggle();
                                    $(this).prev().toggle();
                                    return false;
                                });
                            });

                        </script>   

                        <div class="wp_rem-add-review-data add-new-review-holder">
                            <script>
                                $(document).on('click', '#send_your_review', function () {

                                    var reply_modal = $('#review-reply-modal-box');
                                    var is_processing = false;
                                    var review_min_length = reply_modal.find('#pt-review-min-length').val();
                                    var review_max_length = reply_modal.find('#pt-review-max-length').val();

                                    var pt_post_id = reply_modal.find('#pt-post-id').val();
                                    var pt_review_id = reply_modal.find('#pt-review-id').val();
                                    var pt_post_type_id = reply_modal.find('#pt-post-type-id').val();

                                    var returnType = wp_rem_validation_process(jQuery(".wp_rem-add-review-data"));
                                    if (returnType == false) {
                                        return false;
                                    }
                                    if (is_processing == true) {
                                        return false;
                                    }
                                    var ratings = {};

                                    var user_id = $("#review_user_id").val();

                                    var user_email = $("#review_email_address").val();

                                    var user_full_name = $("#review_full_name").val();

                                    var parent_review_id = $("#parent_review_id").val();
                                    var review_description = $("#review_description").val();
                                    if (review_description.length < review_min_length || review_description.length > review_max_length) {
                                        jQuery(".wp_rem-add-review-data").find("#review_description").addClass('frontend-field-error');
                                        var response = {
                                            type: "error",
                                            msg: '<?php echo wp_rem_plugin_text_srt('wp_rem_reviews_review_desc_length_must_be'); ?>' + review_min_length + ' <?php echo wp_rem_plugin_text_srt('wp_rem_reviews_review_desc_length_must_be_to'); ?> ' + review_max_length + ' <?php echo wp_rem_plugin_text_srt('wp_rem_reviews_review_desc_length_must_be_to_long'); ?>.',
                                        };
                                        wp_rem_show_response(response);
                                        return false;
                                    }
                                    var overall_rating = '';

                                    $(".ajax-message").text("<?php echo wp_rem_plugin_text_srt('wp_rem_reviews_request_processing_text'); ?>").css("color", "#555555");
                                    is_processing = true;
                                    $.ajax({
                                        method: "POST",
                                        url: "<?php echo admin_url('admin-ajax.php'); ?>",
                                        dataType: "json",
                                        data: {
                                            action: "post_user_review",
                                            ratings: JSON.stringify(ratings),
                                            post_id: pt_post_id,
                                            user_id: user_id,
                                            company_id: "<?php echo $company_id; ?>",
                                            property_type_id: pt_post_type_id,
                                            user_email: user_email,
                                            user_name: user_full_name,
                                            overall_rating: overall_rating,
                                            description: review_description,
                                            parent_review_id: parent_review_id,
                                            dash_reply: '1',
                                        },
                                        success: function (data) {

                                            reply_modal.modal('hide');
                                            reply_modal.find('.ajax-message').html('');
                                            reply_modal.find('#review_description').val('');
                                            reply_modal.find('#review_description').html('');
                                            $('#alert-review-' + pt_review_id).after(data.child_rep_html);
                                            $('#alert-review-' + pt_review_id).find('.review-reply-btn').remove();

                                            is_processing = false;
                                        },
                                    });
                                    return false;
                                });
                            </script>
                            <div id="review-reply-modal-box" class="modal fade review-reply-modal" role="dialog">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            <h4 class="modal-title"><?php echo wp_rem_plugin_text_srt('wp_rem_reviews_leave_reply') ?></h4>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row">
                                                <div id="review-rating-fields" class="review-rating-fields">

                                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12" style="display:none;">
                                                        <div class="form-element">
                                                            <i class="icon-user4"></i>
                                                            <input onkeypress="wp_rem_contact_form_valid_press(this, 'text')"  class="wp-rem-dev-req-field" type="text" placeholder="<?php echo wp_rem_plugin_text_srt('wp_rem_reviews_name_field') ?>" name="review_full_name" id="review_full_name" value="<?php echo $publisher_display_name; ?>">
                                                            <input type="hidden" name="review_user_id" id="review_user_id" value="<?php echo $user_id; ?>">
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12" style="display:none;">
                                                        <div class="form-element">
                                                            <i class="icon-envelope3"></i>
                                                            <input onkeypress="wp_rem_contact_form_valid_press(this, 'email')" class="wp-rem-dev-req-field wp-rem-email-field" type="text" placeholder="<?php echo wp_rem_plugin_text_srt('wp_rem_reviews_email_field') ?>" name="review_email_address" id="review_email_address" value="<?php echo $user_email; ?>">
                                                        </div>
                                                    </div>

                                                    <div id="review-textarea-field" class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                        <div class="form-element">
                                                            <i class="icon-message"></i>
                                                            <textarea onkeypress="wp_rem_contact_form_valid_press(this, 'text')"  class="wp-rem-dev-req-field" placeholder="<?php echo wp_rem_plugin_text_srt('wp_rem_reviews_tell_your_experience') ?>" cols="30" rows="10" name="review_description" id="review_description" maxlength="500"></textarea>
                                                        </div>
                                                    </div>
                                                    <div id="review-submit-fields-area" class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                        <div class="form-element">
                                                            <input type="hidden" id="parent_review_id" name="parent_review_id" value="">

                                                            <input type="hidden" id="pt-review-min-length">
                                                            <input type="hidden" id="pt-review-max-length">
                                                            <input type="hidden" id="pt-post-id">
                                                            <input type="hidden" id="pt-post-type-id">
                                                            <input type="hidden" id="pt-review-id">

                                                            <input type="button" class="bgcolor" name="send_your_review" id="send_your_review" value="<?php echo wp_rem_plugin_text_srt('wp_rem_reviews_send_your_review_btn'); ?>">

                                                            &nbsp;&nbsp;<span class="ajax-message"></span>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <ul class="review-property">
                            <?php
                            if ( 0 < count($reviews) ) :

                                foreach ( $reviews as $key => $review ) :

                                    if ( ! empty($review) ) {
                                        $post_slug = get_post_meta($reviews[$key]['id'], 'post_id', true);
                                        $args = array(
                                            'name' => $post_slug,
                                            'post_type' => 'properties',
                                        );
                                        $posts = get_posts($args);
                                        $review_post_id = isset($review['id']) ? $review['id'] : '';
                                        $ratings_ser_list = get_post_meta($review_post_id, 'ratings', true);

                                        $is_review_response_enable = true;
                                        ?>
                                        <?php $reply_class = ( isset($review['is_reply']) && $review['is_reply'] == true ) ? 'review_reply' : ''; ?>
                                        <?php $review_title = ( isset($review['review_title']) && $review['review_title'] != '' ) ? '| ' . $review['review_title'] : ''; ?>
                                        <li id="alert-review-<?php echo $review_post_id; ?>" class="parent_review_<?php echo esc_html($review_post_id); ?>  col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <div class="list-holder <?php echo $reply_class; ?>">
                                                <div class="img-holder"><figure><img src="<?php echo $review['img']; ?>" alt="<?php echo $review['user_name']; ?>" /></figure></div>
                                                <div class="img-holder-content">
                                                    <div class="review-title">
                                                        <p><?php echo $review['username']; ?></p>
                                                        <div class="rating-holder">
                                                            <em><?php echo date('M Y', strtotime($review['dated'])); ?></em>
                                                            <?php if ( isset($review['is_reply']) && $review['is_reply'] != true ) { ?>
                                                                <div class="rating-star">
                                                                    <span style="width: <?php echo ( $review['overall_rating'] / 5 ) * 100; ?>%;" class="rating-box"></span>
                                                                </div>
                                                            <?php } ?>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="review-text">
                                                    <p class="more">
                                                        <?php echo $this->reviews_text_filter($review['description']); ?>
                                                    </p>
                                                </div>
                                                <?php
                                                if ( $is_review_response_enable == true ) {
                                                    echo $this->posting_review_reply_dashboard($review, $posts);
                                                }

                                                if ( isset($review['is_reply']) && $review['is_reply'] == true ) {
                                                    ?>
                                                    <span class="delete-this-user-review close" data-dismiss="alert" data-review-id="<?php echo $review['id']; ?>"><i class="icon-close"></i></span>
                                                    <?php
                                                }
                                                ?>
                                            </div>
                                        </li>
                                        <?php
                                    }

                                endforeach;
                                ?>
                            <?php else: ?>
                                <li class="alert">
                                    <div class="review-text">
                                        <i class="icon-pencil4"></i>
                                        <?php echo wp_rem_plugin_text_srt('wp_rem_reviews_dashboard_no_reviews_text'); ?>
                                    </div>
                                </li>
                            <?php endif; ?>
                        </ul>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div id="my-reviews-more-btn-holder" class="btn-more-holder">
                                <a href="#" class="btn-load-more"><?php echo wp_rem_plugin_text_srt('wp_rem_reviews_read_more_reviews_text'); ?></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <script type="text/javascript">
                (function ($) {
                    $(function () {
                        $(".chosen-select").chosen();

                        bind_delete_review_event();

                        var reviews_count = <?php echo $reviews_count; ?>;
                        var reviews_shown_count = <?php echo count($reviews); ?>;
                        var start = reviews_shown_count;
                        if (reviews_shown_count < reviews_count) {
                            $(".btn-load-more").click(function () {
                                wp_rem_show_loader();
                                $.ajax({
                                    method: "POST",
                                    url: "<?php echo admin_url('admin-ajax.php'); ?>",
                                    dataType: "json",
                                    data: {
                                        action: "get_user_reviews_for_dashboard",
                                        company_id: "<?php echo $company_id; ?>",
                                        offset: start,
                                        my_review: 'yes',
                                        is_child: 'yes',
                                        is_company: 'no',
                                        sorty_by: $(".slct-sort-by-dashboard-reviews").val(),
                                        security: "<?php echo wp_create_nonce('wp_rem-get-reviews'); ?>",
                                    },
                                    success: function (data) {
                                        wp_rem_hide_loader();
                                        if (data.success == true) {
                                            $("ul.reviews-list").append(data.data);

                                            // Bind delete event for new reviews.
                                            bind_rest_auth_event();
                                            bind_delete_review_event();

                                            start += data.count;
                                        }
                                        if (data.count == 0) {
                                            $(".btn-more-holder").hide();
                                        }
                                    },
                                });
                                return false;
                            });
                        } else {
                            $(".btn-more-holder").hide();
                        }

                        $(".ajax-loader-sort-by").hide();
                        $("input[name='review']").click(function () {
                            start = 0;
                            $(".ajax-loader-sort-by").show();
                            wp_rem_show_loader('.loader-holder');
                            $.ajax({
                                method: "POST",
                                url: "<?php echo admin_url('admin-ajax.php'); ?>",
                                dataType: "json",
                                data: {
                                    action: "get_user_reviews_for_dashboard",
                                    company_id: "<?php echo $company_id; ?>",
                                    offset: start,
                                    my_review: 'yes',
                                    is_child: 'yes',
                                    is_company: 'no',
                                    sort_by: $(this).val(),
                                    security: "<?php echo wp_create_nonce('wp_rem-get-reviews'); ?>",
                                },
                                success: function (data) {
                                    wp_rem_hide_loader();
                                    if (data.success == true) {
                                        $("ul.reviews-list li").remove();
                                        $("ul.reviews-list").append(data.data);

                                        // Bind delete event for new reviews.
                                        bind_rest_auth_event();
                                        bind_delete_review_event();

                                        start += data.count;
                                    }
                                    if (data.count == 0) {
                                        $(".btn-more-holder").hide();
                                    }
                                    $(".ajax-loader-sort-by").hide();
                                },
                            });
                        });

                        function bind_delete_review_event() {
                            $(".delete-this-user-review").click(function (e) {
                                wp_rem_show_loader('.loader-holder');
                                e.preventDefault();
                                var review_id = $(this).data("review-id");
                                $.ajax({
                                    method: "POST",
                                    url: "<?php echo admin_url('admin-ajax.php'); ?>",
                                    dataType: "json",
                                    data: {
                                        action: "delete_user_review",
                                        review_id: $(this).data("review-id"),
                                        security: "<?php echo wp_create_nonce('wp_rem-delete-review'); ?>",
                                    },
                                    success: function (data) {
                                        wp_rem_show_response(data)
                                        if (data.type == 'success') {
                                            $(".parent_review_" + review_id).hide('slow');
                                            reviews_count--;
                                            $(".user-reviews-list .element-slogan").html("(" + reviews_count + ")");
                                        }
                                    },
                                });
                            });
                        }
                    });
                })(jQuery);
            </script>
            <?php
            $output = ob_get_clean();
            echo $output;
            wp_die();
        }

        /**
         * Handle AJAX request and render Dashboard Given Reviews Tab Container.
         */
        public function dashboard_reviews_ui_callback() {
            $user_id = get_current_user_id();
            if ( $user_id == 0 ) {
                echo json_encode(array( 'success' => false, 'msg' => wp_rem_plugin_text_srt('wp_rem_reviews_invalid_user') ));
                wp_die();
            }
            $company_id = get_user_meta($user_id, 'wp_rem_company', true);
            $company_id = ( $company_id != '' ? $company_id : 0 );

            $reviews_count = $this->get_user_reviews_count($company_id, true, false);
            $reviews = $this->get_user_reviews_for_post($company_id, 0, Wp_rem_Reviews::$posts_per_page, 'newest', true, false, false);
            ob_start();
            ?>
            <div class="row">
                <div class = "col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class = "element-title has-border">
                        <h4><?php echo wp_rem_plugin_text_srt('wp_rem_reviews_slass_coments') ?></h4>
                        <div class="col-lg-8 col-md-8 col-sm-12 pull-right">
                            <ul class="dashboard-nav sub-nav">
                                <?php if ( true === Wp_rem_Member_Permissions::check_permissions('company_profile') ) { ?>
                                    <li id="wp_rem_publisher_reviews" class="user_dashboard_ajax active" data-queryvar="dashboard=reviews"><a href="javascript:void(0);" class="btn-edit-profile"><?php echo wp_rem_plugin_text_srt('wp_rem_given_reviews_dashboard_heading') ?></a></li>
                                    <li class="user_dashboard_ajax" id="wp_rem_publisher_my_reviews" data-queryvar="dashboard=my_reviews"><a href="javascript:void(0)"><?php echo wp_rem_plugin_text_srt('wp_rem_reviews_dashboard_heading'); ?></a></li>
                                    <?php
                                }
                                ?>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="user-reviews-list reviews-rating-main-con">
                        <div class="review-list">
                            <div class="elements-title">
                                <span><?php echo wp_rem_plugin_text_srt('wp_rem_given_reviews_dashboard_heading'); ?></span>
                                <span class="element-slogan"><?php echo sprintf(wp_rem_plugin_text_srt('wp_rem_reviews_dashboard_stats'), $reviews_count); ?></span>
                                <div class="sort-by">
                                    <ul class="reviews-sortby">
                                        <li> 
                                            <span><?php echo wp_rem_plugin_text_srt('wp_rem_reviews_dashboard_sort_by_label'); ?>: <strong class="active-sort"><?php echo wp_rem_plugin_text_srt('wp_rem_reviews_sort_by_newest_reviews_option') ?></strong></span>
                                            <div class="reviews-sort-dropdown">
                                                <form>
                                                    <div class="input-reviews">
                                                        <div class="radio-field">
                                                            <input name="review" id="check-1" type="radio" value="newest">
                                                            <label for="check-1"><?php echo wp_rem_plugin_text_srt('wp_rem_reviews_dashboard_sort_by_newest_reviews_option'); ?></label>
                                                        </div>
                                                        <div class="radio-field">
                                                            <input name="review" id="check-2" type="radio" value="highest">
                                                            <label for="check-2"><?php echo wp_rem_plugin_text_srt('wp_rem_reviews_dashboard_sort_by_highest_rating_option'); ?></label>
                                                        </div>
                                                        <div class="radio-field">
                                                            <input name="review" id="check-3" type="radio" value="lowest">
                                                            <label for="check-3"><?php echo wp_rem_plugin_text_srt('wp_rem_reviews_dashboard_sort_by_lowest_rating_option'); ?></label>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <script>

                            jQuery(document).ready(function () {

                                jQuery('.reviews-sortby > li').on('click', function () {
                                    jQuery('.reviews-sortby > li').toggleClass('reviews-sortby-active');
                                    jQuery('.reviews-sortby > li').siblings();
                                    jQuery('.reviews-sortby > li').siblings().removeClass('reviews-sortby-active');
                                });
                                jQuery('.input-reviews > .radio-field label').on('click', function () {
                                    jQuery(this).parent().toggleClass('active');
                                    jQuery(this).parent().siblings();
                                    jQuery(this).parent().siblings().removeClass('active');
                                    /*replace inner Html*/
                                    var radio_field_active = jQuery(this).html();
                                    jQuery(".active-sort").html(radio_field_active);
                                    jQuery('.reviews-sortby > li').removeClass('reviews-sortby-active');
                                });
                                // Configure/customize these variables.
                                var showChar = 220;  // How many characters are shown by default
                                var ellipsestext = ".";
                                var moretext = "<?php echo wp_rem_plugin_text_srt('wp_rem_reviews_show_more'); ?>";
                                var lesstext = "<?php echo wp_rem_plugin_text_srt('wp_rem_reviews_show_less'); ?>";
                                $('.more').each(function () {
                                    var content = $(this).text();
                                    content = content.replace(/<\/?[^>]+(>|$)/g, "");
                                    if (content.length > showChar) {
                                        var c = content.substr(0, showChar);
                                        var h = content.substr(showChar, content.length - showChar);
                                        var data_check = h.replace(/\s+/, "");
                                        if (data_check != '') {
                                            var html = c + '<span class="moreellipses">' + ellipsestext + '&nbsp;</span><span class="morecontent"><span>' + h + '</span>&nbsp;&nbsp;<a href="" class="morelink">' + moretext + '</a></span>';
                                            $(this).html(html);
                                        }
                                    }

                                });
                                $(".morelink").click(function () {
                                    if ($(this).hasClass("less")) {
                                        $(this).removeClass("less");
                                        $(this).html(moretext);
                                    } else {
                                        $(this).addClass("less");
                                        $(this).html(lesstext);
                                    }
                                    //$(this).parent().prev().toggle();
                                    $(this).prev().toggle();
                                    return false;
                                });
                            });

                        </script>

                        <ul class="review-property">
                            <?php
                            if ( 0 < count($reviews) ) :
                                foreach ( $reviews as $key => $review ) :

                                    if ( ! empty($review) ) {
                                        $post_slug = get_post_meta($reviews[$key]['id'], 'post_id', true);
                                        $args = array(
                                            'name' => $post_slug,
                                            'post_type' => 'properties',
                                        );
                                        $posts = get_posts($args);
                                        $review_post_id = isset($review['id']) ? $review['id'] : '';
                                        $ratings_ser_list = get_post_meta($review_post_id, 'ratings', true);

                                        $is_review_response_enable = true;
                                        $parent_id = isset($review['parent_id']) ? $review['parent_id'] : $review_post_id;
                                        ?>
                                        <?php $reply_class = ( isset($review['is_reply']) && $review['is_reply'] == true ) ? 'review_reply' : ''; ?>
                                        <?php $review_title = ( isset($review['review_title']) && $review['review_title'] != '' ) ? '| ' . $review['review_title'] : ''; ?>
                                        <li id="alert-review-<?php echo $review_post_id; ?>" class="parent_review_<?php echo esc_html($parent_id); ?> col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <div class="list-holder <?php echo $reply_class; ?>">
                                                <div class="img-holder"><figure><img src="<?php echo $review['img']; ?>" alt="<?php echo $review['user_name']; ?>" /></figure></div>
                                                <div class="img-holder-content">
                                                    <div class="review-title">
                                                        <p><?php echo $review['username']; ?></p>
                                                        <div class="rating-holder">
                                                            <em><?php echo date('M Y', strtotime($review['dated'])); ?></em>
                                                            <?php if ( isset($review['is_reply']) && $review['is_reply'] != true ) { ?>
                                                                <div class="rating-star">
                                                                    <span style="width: <?php echo ( $review['overall_rating'] / 5 ) * 100; ?>%;" class="rating-box"></span>
                                                                </div>
                                                            <?php } ?>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="review-text">
                                                    <p class="more">
                                                        <?php echo $this->reviews_text_filter($review['description']); ?>
                                                    </p>
                                                </div>
                                                <?php
                                                if ( ! isset($review['is_reply']) || $review['is_reply'] != true ) {
                                                    ?>
                                                    <span class="delete-this-user-review close" data-dismiss="alert" data-review-id="<?php echo $review['id']; ?>"><i class="icon-close"></i></span>
                                                    <?php
                                                }
                                                ?>
                                            </div>
                                        </li>
                                        <?php
                                    }
                                    ?>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <li class="alert">
                                    <div class="review-text">
                                        <i class="icon-pencil4"></i>
                                        <?php echo wp_rem_plugin_text_srt('wp_rem_reviews_dashboard_no_reviews_text'); ?>
                                    </div>
                                </li>
                            <?php endif; ?>
                        </ul>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="btn-more-holder">
                                <a href="#" class="btn-load-more"><?php echo wp_rem_plugin_text_srt('wp_rem_reviews_read_more_reviews_text'); ?></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <script type="text/javascript">
                (function ($) {
                    $(function () {
                        $(".chosen-select").chosen();

                        bind_delete_review_event();

                        var reviews_count = <?php echo $reviews_count; ?>;
                        var reviews_shown_count = <?php echo count($reviews); ?>;
                        var start = reviews_shown_count;
                        if (reviews_shown_count < reviews_count) {
                            $(".btn-load-more").click(function () {
                                wp_rem_show_loader();
                                $.ajax({
                                    method: "POST",
                                    url: "<?php echo admin_url('admin-ajax.php'); ?>",
                                    dataType: "json",
                                    data: {
                                        action: "get_user_reviews_for_dashboard",
                                        company_id: "<?php echo $company_id; ?>",
                                        offset: start,
                                        my_review: 'no',
                                        is_child: 'no',
                                        is_company: 'yes',
                                        sorty_by: $(".slct-sort-by-dashboard-reviews").val(),
                                        security: "<?php echo wp_create_nonce('wp_rem-get-reviews'); ?>",
                                    },
                                    success: function (data) {
                                        wp_rem_hide_loader();
                                        if (data.success == true) {
                                            $("ul.reviews-list").append(data.data);

                                            // Bind delete event for new reviews.
                                            bind_delete_review_event();

                                            start += data.count;
                                        }
                                        if (data.count == 0) {
                                            $(".btn-more-holder").hide();
                                        }
                                    },
                                });
                                return false;
                            });
                        } else {
                            $(".btn-more-holder").hide();
                        }

                        $(".ajax-loader-sort-by").hide();
                        $("input[name='review']").click(function () {
                            start = 0;
                            $(".ajax-loader-sort-by").show();
                            wp_rem_show_loader('.loader-holder');
                            $.ajax({
                                method: "POST",
                                url: "<?php echo admin_url('admin-ajax.php'); ?>",
                                dataType: "json",
                                data: {
                                    action: "get_user_reviews_for_dashboard",
                                    company_id: "<?php echo $company_id; ?>",
                                    offset: start,
                                    my_review: 'no',
                                    is_child: 'no',
                                    is_company: 'yes',
                                    sort_by: $(this).val(),
                                    security: "<?php echo wp_create_nonce('wp_rem-get-reviews'); ?>",
                                },
                                success: function (data) {
                                    wp_rem_hide_loader();
                                    if (data.success == true) {
                                        $("ul.review-property li").remove();
                                        $("ul.review-property").append(data.data);

                                        // Bind delete event for new reviews.
                                        bind_delete_review_event();

                                        start += data.count;
                                    }
                                    if (data.count == 0) {
                                        $(".btn-more-holder").hide();
                                    }
                                    $(".ajax-loader-sort-by").hide();
                                },
                            });
                        });

                        function bind_delete_review_event() {
                            $(".delete-this-user-review").click(function (e) {
                                wp_rem_show_loader('.loader-holder');
                                e.preventDefault();
                                var review_id = $(this).data("review-id");
                                $.ajax({
                                    method: "POST",
                                    url: "<?php echo admin_url('admin-ajax.php'); ?>",
                                    dataType: "json",
                                    data: {
                                        action: "delete_user_review",
                                        review_id: $(this).data("review-id"),
                                        security: "<?php echo wp_create_nonce('wp_rem-delete-review'); ?>",
                                    },
                                    success: function (data) {
                                        wp_rem_show_response(data);
                                        if (data.type == 'success') {
                                            $("#parent_review_" + review_id).hide();
                                            $(".parent_review_" + review_id).hide('slow');
                                            reviews_count--;
                                            $(".user-reviews-list .element-slogan").html("(" + reviews_count + ")");
                                        }
                                    },
                                });
                            });
                        }
                    });
                })(jQuery);
            </script>
            <?php
            $output = ob_get_clean();
            echo $output;
            wp_die();
        }

        /**
         * Render reviews settings tab for reviews on property type add/edit page.
         *
         * @param WP_Post $post
         */
        public function property_type_options_sidebar_tab_callback($post) {
            ?>
            <li>
                <a href="javascript:;" name="#tab-reviews_settings">
                    <i class="icon-star"></i>
                    <?php echo wp_rem_plugin_text_srt('wp_rem_reviews_settings_tab_text'); ?>
                </a>
            </li>
            <?php
        }

        /**
         * Render reviwes settings container for reviews on property type add/edit page.
         *
         * @param WP_Post $post
         */
        public function property_type_options_tab_container_callback($post) {
            global $wp_rem_html_fields, $wp_rem_plugin_options;
            $post_meta = get_post_meta(get_the_id());

            $ratings = array();
            ?>
            <div id="tab-reviews_settings" class="wp_rem_tab_block" data-title="<?php echo wp_rem_plugin_text_srt('wp_rem_reviews_settings_tab_text'); ?>">
                <?php
                $wp_rem_opt_array = array(
                    'name' => wp_rem_plugin_text_srt('wp_rem_reviews_enable_user_reviews'),
                    'desc' => '',
                    'hint_text' => wp_rem_plugin_text_srt('wp_rem_reviews_enable_user_reviews_hint'),
                    'echo' => true,
                    'field_params' => array(
                        'std' => '',
                        'id' => 'user_reviews',
                        'return' => true,
                    ),
                );
                $wp_rem_html_fields->wp_rem_checkbox_field($wp_rem_opt_array);

                $wp_rem_opt_array = array(
                    'name' => wp_rem_plugin_text_srt('wp_rem_reviews_auto_approve_reviews'),
                    'desc' => '',
                    'hint_text' => wp_rem_plugin_text_srt('wp_rem_reviews_auto_approve_reviews_hint'),
                    'echo' => true,
                    'field_params' => array(
                        'std' => '',
                        'id' => 'auto_approve_reviews',
                        'return' => true,
                    ),
                );
                $wp_rem_html_fields->wp_rem_checkbox_field($wp_rem_opt_array);

                $wp_rem_opt_array = array(
                    'name' => wp_rem_plugin_text_srt('wp_rem_reviews_without_login_user_reviews'),
                    'desc' => '',
                    'hint_text' => '',
                    'echo' => true,
                    'field_params' => array(
                        'std' => '',
                        'id' => 'review_without_login',
                        'return' => true,
                    ),
                );
                $wp_rem_html_fields->wp_rem_checkbox_field($wp_rem_opt_array);
                $wp_rem_opt_array = array(
                    'name' => wp_rem_plugin_text_srt('wp_rem_reviews_enable_review_comment'),
                    'desc' => '',
                    'hint_text' => '',
                    'echo' => true,
                    'field_params' => array(
                        'std' => '',
                        'id' => 'enable_review_comment',
                        'return' => true,
                    ),
                );
                $wp_rem_html_fields->wp_rem_checkbox_field($wp_rem_opt_array);

                $wp_rem_opt_array = array(
                    'name' => wp_rem_plugin_text_srt('wp_rem_reviews_enable_multiple_reviews'),
                    'desc' => '',
                    'hint_text' => '',
                    'echo' => true,
                    'field_params' => array(
                        'std' => '',
                        'id' => 'enable_multiple_reviews',
                        'return' => true,
                    ),
                );
                //$wp_rem_html_fields->wp_rem_checkbox_field($wp_rem_opt_array);

                $wp_rem_opt_array = array(
                    'name' => wp_rem_plugin_text_srt('wp_rem_reviews_load_more_reviews'),
                    'desc' => '',
                    //'hint_text' => wp_rem_plugin_text_srt('wp_rem_reviews_load_more_reviews_hint'),
                    'echo' => true,
                    'field_params' => array(
                        'std' => '',
                        'id' => 'review_load_more_option',
                        'return' => true,
                    ),
                );
                $wp_rem_html_fields->wp_rem_checkbox_field($wp_rem_opt_array);

                $wp_rem_captcha_switch_option = isset($wp_rem_plugin_options['wp_rem_captcha_switch']) ? $wp_rem_plugin_options['wp_rem_captcha_switch'] : '';
                if ( $wp_rem_captcha_switch_option == 'on' ) {
                    $wp_rem_opt_array = array(
                        'name' => wp_rem_plugin_text_srt('wp_rem_reviews_captcha_for_reviews'),
                        'desc' => '',
                        'hint_text' => wp_rem_plugin_text_srt('wp_rem_reviews_configure_captcha_for_reviews'),
                        'echo' => true,
                        'field_params' => array(
                            'std' => '',
                            'id' => 'review_captcha_for_reviews',
                            'return' => true,
                        ),
                    );
                    $wp_rem_html_fields->wp_rem_checkbox_field($wp_rem_opt_array);
                }

                $wp_rem_opt_array = array(
                    'name' => wp_rem_plugin_text_srt('wp_rem_reviews_min_length'),
                    'desc' => '',
                    'hint_text' => '',
                    'echo' => true,
                    'field_params' => array(
                        'std' => '200',
                        'id' => 'review_min_length',
                        'classes' => 'wp_rem-dev-req-field-admin wp_rem-number-field',
                        'return' => true,
                    ),
                );
                $wp_rem_html_fields->wp_rem_text_field($wp_rem_opt_array);

                $wp_rem_opt_array = array(
                    'name' => wp_rem_plugin_text_srt('wp_rem_reviews_max_length'),
                    'desc' => '',
                    'hint_text' => '',
                    'echo' => true,
                    'field_params' => array(
                        'std' => '500',
                        'id' => 'review_max_length',
                        'classes' => 'wp_rem-dev-req-field-admin wp_rem-number-field',
                        'return' => true,
                    ),
                );
                $wp_rem_html_fields->wp_rem_text_field($wp_rem_opt_array);

                $wp_rem_opt_array = array(
                    'name' => wp_rem_plugin_text_srt('wp_rem_reviews_number_of_reviews'),
                    'desc' => '',
                    'hint_text' => '',
                    'echo' => true,
                    'field_params' => array(
                        'std' => '10',
                        'id' => 'review_number_of_reviews',
                        'classes' => 'wp_rem-dev-req-field-admin wp_rem-number-field',
                        'return' => true,
                    ),
                );
                $wp_rem_html_fields->wp_rem_text_field($wp_rem_opt_array);

                $wp_rem_html_fields->wp_rem_heading_render(array( 'name' => wp_rem_plugin_text_srt('wp_rem_reviews_score_values') ));

                $reviews_data = array();

                if ( isset($post_meta['wp_rem_reviews_labels']) && isset($post_meta['wp_rem_reviews_labels'][0]) ) {
                    $reviews_data = json_decode($post_meta['wp_rem_reviews_labels'][0], true);
                }
                ?>
                <div class="form-elements">
                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                        <label for="txt-rating-top-heading"><b><?php echo wp_rem_plugin_text_srt('wp_rem_reviews_settings_labels'); ?></b></label>
                    </div>
                    <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
                        <table class="rating-templates-wrapper">
                            <thead>
                                <tr>
                                    <th style="width: 20px;">&nbsp;</th>
                                    <th><?php echo wp_rem_plugin_text_srt('wp_rem_reviews_settings_labels_label'); ?></th>
                                    <!--<th style="width: 140px;"><?php echo wp_rem_plugin_text_srt('wp_rem_reviews_settings_labels_rating'); ?></th>-->
                                    <th style="width: 45px;">&nbsp;</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ( count($reviews_data) > 0 ) : ?>
                                    <?php foreach ( $reviews_data as $key => $value ) :
                                        ?>
                                        <tr>
                                            <td>
                                                <span class="cntrl-drag-and-drop"><i class="icon-menu2"></i></span>
                                            </td>
                                            <td>
                                                <input type="text" name="review_label[]" value="<?php echo $value; ?>" class="review_label">
                                            </td>
                                            <td style="text-align: center;">
                                                <a href="#" class="cntrl-delete-row" title="<?php echo wp_rem_plugin_text_srt('wp_rem_reviews_settings_labels_cntrl_delete_row'); ?>"><i class="icon-cancel2"></i></a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else : ?>
                                    <tr>
                                        <td>
                                            <span class="cntrl-drag-and-drop"><i class="icon-menu2"></i></span>
                                        </td>
                                        <td>
                                            <input type="text" value="Service" name="review_label[]" class="review_label">
                                        </td>
                                        <td style="text-align: center;">
                                            <a href="javascript:void(0);" class="cntrl-delete-row" title=""><i class="icon-cancel2"></i></a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <span class="cntrl-drag-and-drop"><i class="icon-menu2"></i></span>
                                        </td>
                                        <td>
                                            <input type="text" value="Quality" name="review_label[]" class="review_label">
                                        </td>
                                        <td style="text-align: center;">
                                            <a href="javascript:void(0);" class="cntrl-delete-row" title=""><i class="icon-cancel2"></i></a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <span class="cntrl-drag-and-drop"><i class="icon-menu2"></i></span>
                                        </td>
                                        <td>
                                            <input type="text" value="Value" name="review_label[]" class="review_label">
                                        </td>
                                        <td style="text-align: center;">
                                            <a href="javascript:void(0);" class="cntrl-delete-row" title=""><i class="icon-cancel2"></i></a>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                        <a href="javascript:void(0);" class="cntrl-add-new-row adding_review_scores"><?php echo wp_rem_plugin_text_srt('wp_rem_reviews_settings_labels_cntrl_add_row'); ?></a>
                    </div>
                </div>
            </div>
            <script type="text/javascript">
                (function ($) {
                    $(function () {
                        var table_class = ".rating-templates-wrapper";
                        //if ($(table_class).length != '' && $(table_class).length != '1') {
                            $(table_class + " tbody").sortable({
                                //items: "> tr:not(:last)",
                                cancel: "input"
                            });
                        //}

                        $(".adding_review_scores").click(function () {
                            $(table_class + " tbody tr:last").after($(
                                    '<tr><td><span class="cntrl-drag-and-drop"><i class="icon-menu2"></i></span></td><td><input type="text" value="" name="review_label[]" class="review_label"></td><td style="text-align: center;"><a href="#" class="cntrl-delete-row" title="Delate Row"><i class="icon-cancel2"></i></a></td></tr>'
                                    ));
                            $(".cntrl-delete-row").click(function () {
                                delete_row(this);
                                return false;
                            });
                            return false;
                        });

                        $(".cntrl-delete-row").click(function () {
                            delete_row(this);
                            return false;
                        });

                        function delete_row(delete_link) {
                            $(delete_link).parent().parent().remove();
                        }

                        var reviews_data = <?php echo json_encode($reviews_data); ?>;
                        var reviews_count = <?php echo count($reviews_data); ?>;
                        if (reviews_count > 0) {
                            /*$.each(reviews_data, function (key, value) {
                             $(table_class + " tbody tr:last").before($(
                             '<tr><td><span class="cntrl-drag-and-drop"><i class="icon-menu2"></i></span></td><td><input type="text" value="'+ value + '" name="review_label[]" class="review_label"></td><td style="text-align: center;"><a href="#" class="cntrl-delete-row" title="Delate Row"><i class="icon-cancel2"></i></a></td></tr>'
                             ));
                             });
                             $(table_class + " tbody tr").not(":last").remove();*/
                            $(".cntrl-delete-row").click(function () {
                                delete_row(this);
                                return false;
                            });
                        }
                        $("form#post").submit(function () {
                            var labels = [];
                            $(table_class + " tbody input.review_label").each(function () {
                                labels.push($(this).val());
                            });
                            var asJSON = JSON.stringify(labels);
                            var hdnField = $('<input type="hidden" value="" name="wp_rem_reviews_labels">');
                            hdnField.val(asJSON);
                            $(this).append(hdnField);
                        });
                    });
                })(jQuery);
            </script>
            <?php
        }

        /**
         * Output UI for reviews property and add new review for details page of a post.
         *
         * @param type $post_id
         */
        public function reviews_ui_callback($post_id, $show_ratings_div = 'yes') {
            global $wp_rem_plugin_options;

            wp_enqueue_script('wp-rem-validation-script'); // add validation js file
            $property_limits = get_post_meta($post_id, 'wp_rem_trans_all_meta', true);
            $http_request = wp_rem_server_protocol();
            //$review_key = array_search('wp_rem_transaction_property_reviews', array_column($property_limits, 'key'));
            //if ( isset($property_limits[$review_key]['value']) && $property_limits[$review_key]['value'] == 'on' ) {
            $show_ratings = $this->enable_comments($post_id);
            $is_reviews_enabled = 'off';
            $is_reviews_without_login = 'off';
            $property_type = get_post_meta($post_id, 'wp_rem_property_type', true);
            $the_slug = $property_type;
            $args = array(
                'name' => $the_slug,
                'post_type' => 'property-type',
                'post_status' => 'publish',
                'numberposts' => 1
            );
            $property_types = get_posts($args);
            // If no property type found then skip reviews section.
            if ( 1 > count($property_types) ) {
                return;
            }
            $reviews_count = 0;
            $ratings_summary = array();
            $overall_ratings = array(
                5 => 0,
                4 => 0,
                3 => 0,
                2 => 0,
                1 => 0,
            );
            $property_type_id = $property_types[0]->ID;
            $is_reviews_enabled = get_post_meta($property_type_id, 'wp_rem_user_reviews', true);
            $is_reviews_enabled = ( $is_reviews_enabled == '' ? 'off' : $is_reviews_enabled );
            if ( $is_reviews_enabled == 'off' ) {
                return;
            }
            $is_reviews_without_login = get_post_meta($property_type_id, 'wp_rem_review_without_login', true);
            $is_reviews_without_login = ( $is_reviews_without_login == '' ? 'off' : $is_reviews_without_login );

            //$is_review_response_enable = get_post_meta($post_id, 'wp_rem_transaction_property_ror', true);
            //$is_review_response_enable = ( isset($is_review_response_enable) && $is_review_response_enable == 'on' ) ? true : false;

            $is_review_response_enable = true;

            $wp_rem_reviews_labels = get_post_meta($property_type_id, 'wp_rem_reviews_labels', true);
            $wp_rem_reviews_labels = ( $wp_rem_reviews_labels == '' ? array() : json_decode($wp_rem_reviews_labels, true) );
            $wp_rem_review_min_length = get_post_meta($property_type_id, 'wp_rem_review_min_length', true);
            $wp_rem_review_min_length = ( $wp_rem_review_min_length == '' ? 10 : $wp_rem_review_min_length );
            $wp_rem_review_max_length = get_post_meta($property_type_id, 'wp_rem_review_max_length', true);
            $wp_rem_review_max_length = ( $wp_rem_review_max_length == '' ? 200 : $wp_rem_review_max_length );
            $wp_rem_review_number_of_reviews = get_post_meta($property_type_id, 'wp_rem_review_number_of_reviews', true);
            $wp_rem_review_number_of_reviews = ( $wp_rem_review_number_of_reviews == '' ? 10 : $wp_rem_review_number_of_reviews );
            Wp_rem_Reviews::$posts_per_page = $wp_rem_review_number_of_reviews;
            $wp_rem_review_load_more_option = get_post_meta($property_type_id, 'wp_rem_review_load_more_option', true);
            $wp_rem_review_load_more_option = ( $wp_rem_review_load_more_option == '' ? 'off' : $wp_rem_review_load_more_option );
            $wp_rem_review_captcha_for_reviews = get_post_meta($property_type_id, 'wp_rem_review_captcha_for_reviews', true);
            $wp_rem_review_captcha_for_reviews = ( $wp_rem_review_captcha_for_reviews == '' ? 'off' : $wp_rem_review_captcha_for_reviews );

            $wp_rem_reviews_without_login = get_post_meta($property_type_id, 'wp_rem_review_without_login', true);

            // Get all reviews for this post.
            $reviews = $this->get_user_reviews_for_post($post_id, 0, Wp_rem_Reviews::$posts_per_page);
            $reviews = array_filter($reviews);

            // Get existing ratings for this post.
            $existing_ratings_data = get_post_meta($post_id, 'wp_rem_ratings', true);

            if ( '' != $existing_ratings_data && 0 < count($reviews) ) {
                $reviews_count = $existing_ratings_data['reviews_count'];
                $existing_ratings = $existing_ratings_data['ratings'];
                foreach ( $wp_rem_reviews_labels as $key => $val ) {
                    if ( isset($existing_ratings[$val]) ) {
                        $value = $existing_ratings[$val];
                    } else {
                        $value = 0;
                    }
                    $ratings_summary[] = array( 'label' => $val, 'value' => $value );
                }
                $existing_overall_ratings = $existing_ratings_data['overall_rating'];
                foreach ( $existing_overall_ratings as $key => $val ) {
                    if ( isset($overall_ratings[$key]) ) {
                        $overall_ratings[$key] = $val;
                    }
                }
            } else {
                foreach ( $wp_rem_reviews_labels as $key => $val ) {
                    $ratings_summary[] = array( 'label' => $val, 'value' => 0 );
                }
                $reviews = array();
            }
            $user_id = 0;
            $company_id = 0;
            $user_email = '';
            $user_full_name = '';
            $current_user = wp_get_current_user();
            if ( 0 < $current_user->ID ) {
                $user_id = $current_user->ID;
                $user_full_name = $current_user->user_firstname . ' ' . $current_user->user_lastname;
                $company_id = get_user_meta($user_id, 'wp_rem_company', true);
                $user_email = get_post_meta($company_id, 'wp_rem_email_address', true);
                if ( ! isset($user_email) || $user_email == '' ) {
                    $user_email = $current_user->user_email;
                }
            }

            $publisher_display_name = '';

            // If company id is 0 it means this review is without login requirement.
            $have_review_added = false;
            $is_user_post_owner = false;
            if ( 0 < $company_id ) {
                $have_review_added = apply_filters('have_user_added_review_for_this_post', $have_review_added, $company_id, $post_id);
                $is_user_post_owner = $this->is_this_user_owner_of_this_post_callback(false, $company_id, $post_id);
                $publisher_display_name = get_the_title($company_id);
            } else if ( '' != $user_email ) {
                $have_review_added = $this->have_user_added_review_for_this_post_callback(false, $user_email, $post_id, true);
            }

            if ( $is_user_post_owner == true ) {
                $have_review_added = false;
            }

            $existing_ratings = get_post_meta($post_id, 'wp_rem_ratings', true);

            $avg_rate = $avg_rate_percent = 0;

            if ( 0 < count($reviews) ) {
                foreach ( $reviews as $key => $review ) {
                    if ( ! empty($review) ) {
                        $single_avg_rate = 0;
                        $review_post_id = isset($review['id']) ? $review['id'] : '';
                        $review_is_reply = isset($review['is_reply']) ? $review['is_reply'] : false;
                        $review_overall_rate = isset($review['overall_rating']) && $review['overall_rating'] > 0 ? $review['overall_rating'] : 0;

                        if ( ! $review_is_reply ) {
                            $review_overall_rate;
                            $avg_rate += $review_overall_rate;
                        }
                    }
                }
            }
            $avg_rate = ($avg_rate > 0 && $reviews_count > 0 ? $avg_rate / $reviews_count : 0);
            if ( $avg_rate > 0 ) {
                $avg_rate_percent = ($avg_rate * 100) / 5;
            }
            ?>
            <div class="reviews-holder reviews-rating-main-con">
                <?php
                if ( $show_ratings_div == 'no' ) {
                    echo '<button type="button" class="bgcolor post-reviews-btn-detail  discussion-submit">' . wp_rem_plugin_text_srt('wp_rem_select_review_post_new_reviews') . '</button>';
                }
                ?>
                <div class="add-new-review-holder" style="display:none;">
                    <div class="row">

                        <div id="review-rating-form-title" class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

                            <div class="elements-title">
                                <?php
                                $show_stars = Wp_rem_Reviews::enable_comments($post_id);
                                if ( $show_stars == 'on' ) {
                                    ?>
                                    <h3><?php echo wp_rem_plugin_text_srt('wp_rem_reviews_rate_and_write_a_review_label'); ?></h3>
                                <?php } else {
                                    ?>
                                    <h3><?php echo wp_rem_plugin_text_srt('wp_rem_reviews_rate_and_write_a_comment_label'); ?></h3>
                                    <?php
                                }
                                ?>
                                <a href="#" class="close-post-new-reviews-btn"><?php echo wp_rem_plugin_text_srt('wp_rem_reviews_add_new_reviews_close_button'); ?></a>
                            </div>
                        </div>
                        <?php
                        if ( $have_review_added == true ) :
                        // do nothing
                        elseif ( ( is_user_logged_in() ) && true !== Wp_rem_Member_Permissions::check_permissions('reviews') ) :
                        // do nothing
                        elseif ( ( ! is_user_logged_in() ) && $is_reviews_without_login == 'off' ) :
                        // do nothing
                        else :
                            ?>
                            <div class="wp_rem-add-review-data">
                                <div id="review-reply-modal-box" class="modal fade review-reply-modal" role="dialog">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                <h4 class="modal-title"><?php echo wp_rem_plugin_text_srt('wp_rem_reviews_leave_reply') ?></h4>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div id="review-textarea-field-modal" class="col-lg-12 col-md-12 col-sm-12 col-xs-12"></div>
                                                    <div id="review-submit-fields-area-modal" class="col-lg-12 col-md-12 col-sm-12 col-xs-12"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="wp_rem-added-review-string" style="display:none;">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="text-align: center;"></div>
                                </div>
                                <?php ?>
                                <?php
                                if ( $show_ratings == 'on' ) {
                                    if ( $is_user_post_owner != true ) {
                                        ?>
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <div class="rating-stars-box">
                                                <div class="row">
                                                    <?php
                                                    if ( $show_ratings_div == 'yes' ) {
                                                        ?>
                                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 rating-property">
                                                            <ul class="star-rating-list">
                                                                <?php foreach ( $ratings_summary as $key => $rating ): ?>
                                                                    <li class="rating_summary_item" data-selected-rating="1" data-label="<?php echo $rating['label']; ?>">
                                                                        <span><?php echo $rating['label']; ?></span>
                                                                        <div class="stars">
                                                                            <input type="radio" name="star<?php echo $key; ?>" class="star-1" checked="checked">
                                                                            <label class="star-1" for="star-1">1</label>
                                                                            <input type="radio" name="star<?php echo $key; ?>" class="star-2">
                                                                            <label class="star-2" for="star-2">2</label>
                                                                            <input type="radio" name="star<?php echo $key; ?>" class="star-3">
                                                                            <label class="star-3" for="star-3">3</label>
                                                                            <input type="radio" name="star<?php echo $key; ?>" class="star-4">
                                                                            <label class="star-4" for="star-4">4</label>
                                                                            <input type="radio" name="star<?php echo $key; ?>" class="star-5">
                                                                            <label class="star-5" for="star-5">5</label>
                                                                            <span></span>
                                                                        </div>
                                                                        <!--<em>(4.3)</em>-->
                                                                    </li>
                                                                <?php endforeach; ?>
                                                            </ul>
                                                        </div>
                                                    <?php } ?>

                                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 user-rating-container">
                                                        <div class="total-rating overall-rating" data-overall-rating="1">
                                                            <span class="your-overall-rating-label"><?php echo wp_rem_plugin_text_srt('wp_rem_reviews_your_overall_rating_label'); ?></span>
                                                            <div class="rating-star">
                                                                <input type="radio" name="star" class="star-1" checked="checked">
                                                                <label class="star-1" for="star-1">1</label>
                                                                <input type="radio" name="star" class="star-2">
                                                                <label class="star-2" for="star-2">2</label>
                                                                <input type="radio" name="star" class="star-3">
                                                                <label class="star-3" for="star-3">3</label>
                                                                <input type="radio" name="star" class="star-4">
                                                                <label class="star-4" for="star-4">4</label>
                                                                <input type="radio" name="star" class="star-5">
                                                                <label class="star-5" for="star-5">5</label>
                                                                <span></span>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <?php
                                    }
                                }
                                ?>

                                <div id="review-rating-fields" class="review-rating-fields">

                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12"<?php echo ($wp_rem_reviews_without_login != 'on' ? ' style="display:none;"' : '') ?>>
                                        <div class="form-element">
                                            <i class="icon-user4"></i>
                                            <input onkeypress="wp_rem_contact_form_valid_press(this, 'text')"  class="wp-rem-dev-req-field" type="text" placeholder="<?php echo wp_rem_plugin_text_srt('wp_rem_reviews_name_field') ?>" name="review_full_name" id="review_full_name" value="<?php echo $publisher_display_name; ?>">
                                            <input type="hidden" name="review_user_id" id="review_user_id" value="<?php echo $user_id; ?>">
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12"<?php echo ($wp_rem_reviews_without_login != 'on' ? ' style="display:none;"' : '') ?>>
                                        <div class="form-element">
                                            <i class="icon-envelope3"></i>
                                            <input onkeypress="wp_rem_contact_form_valid_press(this, 'email')" class="wp-rem-dev-req-field wp-rem-email-field" type="text" placeholder="<?php echo wp_rem_plugin_text_srt('wp_rem_reviews_email_field') ?>" name="review_email_address" id="review_email_address" value="<?php echo $user_email; ?>">
                                        </div>
                                    </div>

                                    <div id="review-textarea-field" class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <div class="form-element mb-0">
                                            <i class="icon-message"></i>
                                            <textarea onkeypress="wp_rem_contact_form_valid_press(this, 'text')"  class="wp-rem-dev-req-field" placeholder="<?php echo wp_rem_plugin_text_srt('wp_rem_reviews_tell_your_experience') ?>" cols="30" rows="10" name="review_description" id="review_description" maxlength="<?php echo $wp_rem_review_max_length; ?>"></textarea>
                                        </div>
                                    </div>

                                    <?php
                                    if ( isset($wp_rem_plugin_options['wp_rem_captcha_switch']) && $wp_rem_plugin_options['wp_rem_captcha_switch'] == 'on' ) {
                                        if ( $wp_rem_review_captcha_for_reviews == 'on' && ( ! is_user_logged_in() ) ) {
                                            $wp_rem_sitekey = isset($wp_rem_plugin_options['wp_rem_sitekey']) ? $wp_rem_plugin_options['wp_rem_sitekey'] : '';

                                            if ( class_exists('Wp_rem_Captcha') ) {
                                                global $Wp_rem_Captcha;
                                                ?>
                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                    <div class="form-element">
                                                        <div class="recaptcha-reload" id="recaptcha1_div">
                                                            <?php //echo $Wp_rem_Captcha->wp_rem_generate_captcha_form_callback( 'recaptcha-reviews', 'true' );      ?>
                                                            <div class="g-recaptcha" data-theme="light" id="recaptcha-reviews" data-sitekey="<?php echo $wp_rem_sitekey; ?>" style=""></div>
                                                            <a class="recaptcha-reload-a" href="javascript:void(0);" onclick="captcha_reload('<?php echo admin_url('admin-ajax.php') ?>', 'recaptcha-reviews');">
                                                                <i class="icon-refresh2"></i><?php echo wp_rem_plugin_text_srt('wp_rem_reviews_captcha_reload') ?>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <?php
                                            }
                                        }
                                    }
                                    ?>
                                    <div id="review-submit-fields-area" class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <div class="rating-help-text">
                                            <span><?php echo wp_rem_plugin_text_srt('wp_rem_reviews_min_chars') ?> <?php echo $wp_rem_review_min_length; ?></span>
                                            <span><?php echo wp_rem_plugin_text_srt('wp_rem_reviews_max_chars') ?> <?php echo $wp_rem_review_max_length; ?></span>
                                            <div id="textarea_feedback"></div>
                                        </div>
                                        <div class="form-element">
                                            <input type="hidden" id="parent_review_id" name="parent_review_id" value="">
                                            <?php
                                            if ( $show_ratings == 'on' ) {
                                                ?>

                                                <input type="button" class="bgcolor" name="send_your_review" id="send_your_review" value="<?php echo wp_rem_plugin_text_srt('wp_rem_reviews_send_your_review_btn'); ?>">
                                                <?php
                                            } else {
                                                ?>

                                                <input type="button" class="bgcolor" name="send_your_review" id="send_your_review" value="<?php echo wp_rem_plugin_text_srt('wp_rem_reviews_send_your_comment_btn'); ?>">
                                            <?php } ?>

                                            &nbsp;&nbsp;<span class="ajax-message"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>    
                        <?php endif; ?>
                    </div>
                </div>
                <?php
                if ( $show_ratings_div == 'yes' ) {
                    ?>

                    <div class="reviwes-property-holder row">

                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="element-title">
                                <?php
                                if ( $show_ratings == 'on' ) {
                                    ?>
                                    <h3><?php echo wp_rem_plugin_text_srt('wp_rem_reviews_total_reviews_label'); ?></h3>

                                    <a href="javascript:void(0)" class="post-reviews-btn<?php echo ( ! is_user_logged_in() && $wp_rem_reviews_without_login != 'on' ? ' is-login-modal' : '') ?><?php echo ( $is_user_post_owner ? ' is-user-property' : '') ?><?php echo ( $have_review_added ? ' is-review-add' : '') ?><?php echo ( is_user_logged_in() && true !== Wp_rem_Member_Permissions::check_permissions('reviews') ? ' review-not-allowd' : '') ?>"><?php echo wp_rem_plugin_text_srt('wp_rem_reviews_post_new_reviews_button'); ?></a>
                                    <?php
                                } else {
                                    ?>
                                    <h3><?php echo sprintf(wp_rem_plugin_text_srt('wp_rem_reviews_total_comments_label'), $reviews_count); ?></h3>
                                    <a href="javascript:void(0)" class="post-reviews-btn<?php echo ( ! is_user_logged_in() && $wp_rem_reviews_without_login != 'on' ? ' is-login-modal' : '') ?><?php echo ( $is_user_post_owner ? ' is-user-property' : '') ?><?php echo ( $have_review_added ? ' is-review-add' : '') ?><?php echo ( is_user_logged_in() && true !== Wp_rem_Member_Permissions::check_permissions('reviews') ? ' review-not-allowd' : '') ?>"><?php echo wp_rem_plugin_text_srt('wp_rem_reviews_post_new_reviews_comments'); ?></a>
                                    <?php
                                }
                                ?>

                            </div>
                        </div>
                        <?php
                        if ( $show_ratings == 'on' ) {
                            ?>
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="rating-sumary-holder">
                                    <div class="row">
                                        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12 ratings-summary-container">
                                            <?php $this->get_ratings_summary_ui($ratings_summary, $reviews_count, $avg_rate, $avg_rate_percent); ?>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 overall-ratings-container">
                                            <?php $this->get_overall_rating_ui($overall_ratings, $reviews_count); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                        <div class="review-list">
                            <?php
                            if ( $show_ratings == 'on' ) {
                                ?>
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="elements-title">
                                        <h5><?php echo ($reviews_count) . ' ' . wp_rem_plugin_text_srt('wp_rem_reviews_all_reviews_heading'); ?></h5>

                                        <div class="sort-by">
                                            <span class="ajax-loader-sorty-by"><img src="<?php echo wp_rem::plugin_url(); ?>assets/frontend/images/ajax-loader.gif" alt="" /></span>

                                            <ul class="reviews-sortby">

                                                <li> 
                                                    <span><?php echo wp_rem_plugin_text_srt('wp_rem_reviews_dashboard_sort_by_label'); ?>: <strong class="active-sort"><?php echo wp_rem_plugin_text_srt('wp_rem_reviews_dashboard_sort_by_newest_reviews_option'); ?></strong></span>
                                                    <div class="reviews-sort-dropdown">
                                                        <form>
                                                            <div class="input-reviews">
                                                                <div class="radio-field">
                                                                    <input name="review" id="check-1" type="radio" value="newest">
                                                                    <label for="check-1"><?php echo wp_rem_plugin_text_srt('wp_rem_reviews_dashboard_sort_by_newest_reviews_option'); ?></label>
                                                                </div>
                                                                <div class="radio-field">
                                                                    <input name="review" id="check-2" type="radio" value="highest">
                                                                    <label for="check-2"><?php echo wp_rem_plugin_text_srt('wp_rem_reviews_dashboard_sort_by_highest_rating_option'); ?></label>
                                                                </div>
                                                                <div class="radio-field">
                                                                    <input name="review" id="check-3" type="radio" value="lowest">
                                                                    <label for="check-3"><?php echo wp_rem_plugin_text_srt('wp_rem_reviews_dashboard_sort_by_lowest_rating_option'); ?></label>
                                                                </div>				
                                                            </div>
                                                        </form>
                                                    </div>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                            <ul class="review-property">
                                <?php if ( 0 < count($reviews) ) : ?>
                                    <?php foreach ( $reviews as $key => $review ) : ?>
                                        <?php
                                        if ( ! empty($review) ) {
                                            $review_post_id = isset($review['id']) ? $review['id'] : '';
                                            $ratings_ser_list = get_post_meta($review_post_id, 'ratings', true);
                                            $tooltip_html = '';
                                            if ( is_array($ratings_ser_list) && sizeof($ratings_ser_list) > 0 ) {
                                                $tooltip_html .= '<ul class="ratings-popover-listing">';
                                                foreach ( $ratings_ser_list as $ser_key => $rating_ser_list ) {

                                                    if ( $ser_key == '' ) {
                                                        $ser_key = '';
                                                    } else {
                                                        $ser_key = $ser_key . ' : ';
                                                    }

                                                    $rating_ser_list = absint($rating_ser_list);

                                                    $tooltip_html .= '<li>' . $ser_key . $rating_ser_list . '</li>';
                                                }
                                                $tooltip_html .= '</ul>';
                                            }
                                            ?>
                                            <?php $reply_class = ( isset($review['is_reply']) && $review['is_reply'] == true ) ? 'review_reply' : ''; ?>
                                            <?php $review_title = ( isset($review['review_title']) && $review['review_title'] != '' ) ? '| ' . $review['review_title'] : ''; ?>
                                            <li class="col-lg-12 col-md-12 col-sm-12 col-xs-12" itemprop="review" itemscope itemtype="<?php echo force_balance_tags($http_request); ?>schema.org/Review">
                                                <div class="list-holder <?php echo $reply_class; ?>">
                                                    <div class="img-holder"><figure><img src="<?php echo $review['img']; ?>" alt="<?php echo $review['user_name']; ?>" /></figure></div>
                                                    <div class="img-holder-content">
                                                        <div class="review-title">
                                                            <p itemprop="author"><?php echo $review['username']; ?></p>
                                                            <?php
                                                            if ( $show_ratings == 'on' ) {
                                                                ?>

                                                                <div class="rating-holder">
                                                                    <em><?php echo date('M Y', strtotime($review['dated'])); ?></em>
                                                                    <?php if ( isset($review['is_reply']) && $review['is_reply'] != true ) { ?>
                                                                        <div class="rating-star"<?php if ( $tooltip_html != '' ) { ?> data-toggle="popover_html"<?php } ?>>
                                                                            <span style="width: <?php echo ( $review['overall_rating'] / 5 ) * 100; ?>%;" class="rating-box"></span>
                                                                        </div>
                                                                        <?php if ( $tooltip_html != '' ) { ?>
                                                                            <div class="ratings-popover-content" style="display:none;"><?php echo ($tooltip_html); ?></div>
                                                                        <?php } ?>
                                                                    <?php } ?>
                                                                </div>
                                                            <?php } ?>
                                                        </div>
                                                        <?php
                                                        if ( $reply_class == '' ) {
                                                            ?>
                                                            <div id="review-helpful-holder-<?php echo absint($review_post_id) ?>" class="review-helpful-holder">
                                                                <?php
                                                                $total_helpful_count = get_post_meta($review_post_id, 'review_marked_helpful_count', true);
                                                                if ( isset($_COOKIE['review_marked_helpful_' . $review_post_id]) && $_COOKIE['review_marked_helpful_' . $review_post_id] == '1' ) {
                                                                    ?>
                                                                    <a id="mark-review-helpful-<?php echo absint($review_post_id) ?>" data-id="<?php echo absint($review_post_id) ?>" href="javascript:void(0)" class="mark-review-helpful active-mark"><i class="icon-thumbs-o-up"></i> <span class="marked-helpful-txt"><?php echo wp_rem_plugin_text_srt('wp_rem_reviews_marked_helpful'); ?></span> <div class="marked-helpful-counts"><span><?php echo absint($total_helpful_count) ?></span></div></a>
                                                                    <?php
                                                                } else {
                                                                    ?>
                                                                    <a id="mark-review-helpful-<?php echo absint($review_post_id) ?>" data-id="<?php echo absint($review_post_id) ?>" href="javascript:void(0)" class="mark-review-helpful"><i class="icon-thumbs-o-up"></i> <span class="marked-helpful-txt"><?php echo wp_rem_plugin_text_srt('wp_rem_reviews_marked_helpful'); ?></span> <div class="marked-helpful-counts"><span><?php echo absint($total_helpful_count) ?></span></div></a>
                                                                    <?php
                                                                }
                                                                ?>
                                                            </div>
                                                            <div id="review-flag-holder-<?php echo absint($review_post_id) ?>" class="review-flag-holder">
                                                                <?php
                                                                $total_flag_count = get_post_meta($review_post_id, 'review_marked_flag_count', true);
                                                                if ( isset($_COOKIE['review_marked_flag_' . $review_post_id]) && $_COOKIE['review_marked_flag_' . $review_post_id] == '1' ) {
                                                                    ?>
                                                                    <a id="mark-review-flag-<?php echo absint($review_post_id) ?>" data-id="<?php echo absint($review_post_id) ?>" class="mark-review-flag active-mark"><i class="icon-flag-o"></i> <span class="marked-flag-txt"><?php echo wp_rem_plugin_text_srt('wp_rem_reviews_marked_flag'); ?></span></a>
                                                                    <?php
                                                                } else {
                                                                    ?>
                                                                    <a id="mark-review-flag-<?php echo absint($review_post_id) ?>" data-id="<?php echo absint($review_post_id) ?>" href="javascript:void(0)" class="mark-review-flag"><i class="icon-flag-o"></i> <span class="marked-flag-txt"><?php echo wp_rem_plugin_text_srt('wp_rem_reviews_marked_flag'); ?></span></a>
                                                                    <?php
                                                                }
                                                                ?>
                                                            </div>
                                                            <?php
                                                        }
                                                        ?>
                                                    </div>
                                                    <div class="review-text">
                                                        <p itemprop="description">
                                                            <?php echo $this->reviews_text_filter($review['description']); ?>
                                                        </p>
                                                    </div>
                                                    <?php
                                                    if ( $is_review_response_enable == true && $is_user_post_owner == true ) {
                                                        echo $this->posting_review_reply($review);
                                                    }
                                                    ?>
                                                </div>
                                            </li>
                                        <?php } ?>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <li class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <div class="list-holder"><?php echo wp_rem_plugin_text_srt('wp_rem_reviews_no_reviews_text'); ?></div>
                                    </li>
                                <?php endif; ?>
                            </ul>
                            <?php if ( $wp_rem_review_load_more_option == 'on' && $reviews_count > Wp_rem_Reviews::$posts_per_page ) : ?>
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                                    <div id="reviews-more-btn-holder" class="btn-more-holder">
                                        <?php
                                        if ( $show_ratings == 'on' ) {
                                            ?>
                                            <a href="#" class="btn-load-more"><?php echo wp_rem_plugin_text_srt('wp_rem_reviews_read_more_reviews_text'); ?></a>
                                            <?php
                                        } else {
                                            ?>
                                            <a href="#" class="btn-load-more"><?php echo wp_rem_plugin_text_srt('wp_rem_reviews_read_more_comments_text'); ?></a>
                                            <?php
                                        }
                                        ?>
                                    </div>
                                </div>
                            <?php else: ?>
                                <ul id="reviews-pagination" class="pagination-sm"></ul>
                            <?php endif; ?>
                        </div>

                        <div id="review-flag-reason-modal" class="modal fade review-flag-reason-modal" role="dialog">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        <h4 class="modal-title"><?php echo wp_rem_plugin_text_srt('wp_rem_select_review_report_this_review') ?></h4>
                                    </div>
                                    <div class="modal-body">
                                        <?php
                                        $review_flag_options = isset($wp_rem_plugin_options['review_flag_opts']) ? $wp_rem_plugin_options['review_flag_opts'] : '';
                                        ?>
                                        <p class="flag-modal-txt"><?php echo wp_rem_plugin_text_srt('wp_rem_select_review_choose_report_reason') ?></p>
                                        <ul class="flag-modal-reasons">
                                            <?php
                                            $flag_opts_counter = 1;
                                            if ( is_array($review_flag_options) && sizeof($review_flag_options) > 0 ) {

                                                foreach ( $review_flag_options as $review_flag_option ) {
                                                    ?>
                                                    <li><label><input id="flag-reason-option-<?php echo absint($flag_opts_counter) ?>" name="flag-cont-reason" data-id="<?php echo absint($flag_opts_counter) ?>" type="radio" value="<?php echo esc_html($review_flag_option) ?>"><span for="flag-reason-option-<?php echo absint($flag_opts_counter) ?>"><?php echo esc_html($review_flag_option) ?></span></label></li>
                                                    <?php
                                                    $flag_opts_counter ++;
                                                }
                                            } else {
                                                ?>
                                                <li><label><input id="flag-reason-option-<?php echo absint($flag_opts_counter) ?>" data-id="<?php echo absint($flag_opts_counter) ?>" name="flag-cont-reason" type="radio" value="<?php echo wp_rem_plugin_text_srt('wp_rem_select_review_flag_no_reason') ?>"><span for="flag-reason-option-<?php echo absint($flag_opts_counter) ?>"><?php echo wp_rem_plugin_text_srt('wp_rem_select_review_flag_no_reason') ?></span></label></li>
                                                <?php
                                            }
                                            ?>
                                        </ul>
                                        <input id="flag-submit-review-id" type="hidden" value="0">
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                <?php } ?>
            </div>
            <script type="text/javascript">
                jQuery(document).on("click", '.mark-review-flag', function () {
                    var _this = $(this);
                    var this_id = _this.attr('data-id');
                    var thsID = document.getElementById("mark-review-flag-" + this_id);
                    if (thsID.hasAttribute('href')) {
                        var flag_modal = $('#review-flag-reason-modal');
                        var flag_review = flag_modal.find('#flag-submit-review-id');
                        flag_review.val(this_id);
                        flag_modal.modal('show');
                    }
                    return false;
                });

                jQuery(document).on("click", '#review-flag-reason-modal input[name="flag-cont-reason"]', function () {
                    var _this = $(this);
                    _this.parents('label').addClass('flag-temp-loader');
                    var flag_modal = $('#review-flag-reason-modal');
                    var flag_review = flag_modal.find('#flag-submit-review-id');
                    var flag_review_id = flag_review.val();

                    var flag_reason = flag_modal.find('input[type="radio"]:checked').val();

                    var _this_flag_btn = $('#mark-review-flag-' + flag_review_id);

                    wp_rem_show_loader('#review-flag-reason-modal .flag-temp-loader span', '', 'button_loader', _this);
                    $.ajax({
                        method: "POST",
                        url: "<?php echo admin_url('admin-ajax.php'); ?>",
                        dataType: "json",
                        data: {
                            action: "mark_reviews_as_flag",
                            review_id: flag_review_id,
                            flag_reason: flag_reason
                        },
                        success: function (data) {
                            wp_rem_show_response(data, '', _this);
                            _this.parents('label').removeClass('flag-temp-loader');
                            flag_modal.modal('hide');
                            flag_review.val('0');
                            _this_flag_btn.addClass('active-mark');
                            _this_flag_btn.removeAttr('href');
                            flag_modal.find('input[type="radio"]').prop('checked', false);
                            flag_modal.find('input[type="radio"]').removeAttr('checked');
                        },
                    });
                    return false;
                });

                jQuery(document).on("click", ".mark-review-helpful", function () {
                    var _this = $(this);
                    var this_id = _this.attr('data-id');
                    var pre_do = 'marking';
                    if (_this.hasClass('active-mark')) {
                        pre_do = 'marked';
                    }
                    var thsID = document.getElementById("mark-review-helpful-" + this_id);
                    if (thsID.hasAttribute('id')) {
                        var this_holder_obj = $('#review-helpful-holder-' + this_id);
                        wp_rem_show_loader('#review-helpful-holder-' + this_id, '', 'button_loader', this_holder_obj);
                        $.ajax({
                            method: "POST",
                            url: "<?php echo admin_url('admin-ajax.php'); ?>",
                            dataType: "json",
                            data: {
                                action: "mark_reviews_as_helpful",
                                review_id: this_id,
                                pre_do: pre_do,
                            },
                            success: function (data) {
                                wp_rem_show_response(data, '', this_holder_obj);
                                if (data.pre_do == 'marked') {
                                    _this.addClass('active-mark');
                                } else {
                                    _this.removeClass('active-mark');
                                }
                                var setAnimateTimeAdd = setInterval(function () {
                                    _this.find('.marked-helpful-counts').find('span').html(data.counts);
                                    _this.find('.marked-helpful-counts').find('span').addClass('animated slideInDown');
                                    clearInterval(setAnimateTimeAdd);
                                }, 500);
                                var setAnimateTimeRem = setInterval(function () {
                                    _this.find('.marked-helpful-counts').find('span').removeClass('animated');
                                    _this.find('.marked-helpful-counts').find('span').removeClass('slideInDown');
                                    clearInterval(setAnimateTimeRem);
                                }, 3000);
                            },
                        });
                    }
                    return false;
                });
                function getParameterByName(name) {
                    var match = RegExp('[?&]' + name + '=([^&]*)').exec(window.location.search);
                    return match && decodeURIComponent(match[1].replace(/\+/g, ' '));
                }
                jQuery(document).on("click", ".stars label", function () {
                    var array = new Array();
                    var checked = 0;
                    var unchecked = 0
                    $('.stars input:radio:checked').each(function (index) {
                        var className = $(this).attr('class');
                        //var starValue   = $('.stars label.'+className).text();

                        var starValue = $(this).next(' label').text();
                        checked += parseInt(starValue);

                    });

                    var number_of_items = $('.star-rating-list li').length;
                    var over_all_value = checked / number_of_items;
                    over_all_value_rounded = Math.round(over_all_value);

                    var span_width = "20%";
                    if (over_all_value_rounded == 1) {
                        span_width = "20%";
                    } else if (over_all_value_rounded == 2) {
                        span_width = "40%";
                    } else if (over_all_value_rounded == 3) {
                        span_width = "60%";
                    } else if (over_all_value_rounded == 4) {
                        span_width = "80%";
                    } else if (over_all_value_rounded == 5) {
                        span_width = "100%";
                    }
                    $(".rating-star span").css("width", span_width);


                });
                function removeParam(parameter) {
                    var url = document.location.href;
                    var urlparts = url.split('?');

                    if (urlparts.length >= 2)
                    {
                        var urlBase = urlparts.shift();
                        var queryString = urlparts.join("?");

                        var prefix = encodeURIComponent(parameter) + '=';
                        var pars = queryString.split(/[&;]/g);
                        for (var i = pars.length; i-- > 0; )
                            if (pars[i].lastIndexOf(prefix, 0) !== -1)
                                pars.splice(i, 1);
                        url = urlBase + '?' + pars.join('&');
                        window.history.pushState('', document.title, url); // added this line to push the new url directly to url bar .

                    }
                    return url;
                }

                (function ($) {

                    $(function () {
                        var is_review_added = false;
                        var is_user_review_owner = false;
            <?php
            if ( $is_user_post_owner ) {
                ?>
                            is_user_review_owner = true;
                <?php
            }
            ?>
                        var is_processing = false;
                        var review_min_length = "<?php echo $wp_rem_review_min_length; ?>";
                        var review_max_length = "<?php echo $wp_rem_review_max_length; ?>";
                        var posts_per_page = "<?php echo $wp_rem_review_number_of_reviews; ?>";
                        var load_more_option = "<?php echo $wp_rem_review_load_more_option; ?>";
                        $(document).on('click', '#send_your_review', function () {

                            var returnType = wp_rem_validation_process(jQuery(".wp_rem-add-review-data"));
                            if (returnType == false) {
                                return false;
                            }
                            if (is_processing == true) {
                                return false;
                            }
                            if (is_review_added == true && is_user_review_owner == false) {
                                show_msg(<?php echo ($show_ratings == 'on' ? wp_rem_plugin_text_srt('wp_rem_reviews_already_added_review1_msg') : wp_rem_plugin_text_srt('wp_rem_reviews_already_added_comment1_msg')); ?>, false);
                                return false;
                            }
                            var ratings = {};
                            $(".add-new-review-holder .rating_summary_item").each(function (key, elem) {
                                rating = $(elem).data('selected-rating');
                                label = $(elem).data('label');
                                ratings[label] = rating;
                            });
                            var user_id = $("#review_user_id").val();

                            var user_email = $("#review_email_address").val();
                            if (is_email_valid(user_email) == false) {
                                jQuery(".wp_rem-add-review-data").find("#review_email_address").addClass('frontend-field-error');
                                var response = {
                                    type: "error",
                                    msg: '<?php echo wp_rem_plugin_text_srt('wp_rem_reviews_error_provide_email_address'); ?>',
                                };
                                wp_rem_show_response(response);
                                return false;
                            }
                            var user_full_name = $("#review_full_name").val();
                            if (user_full_name.length < 3) {
                                jQuery(".wp_rem-add-review-data").find("#review_full_name").addClass('frontend-field-error');
                                var response = {
                                    type: "error",
                                    msg: '<?php echo wp_rem_plugin_text_srt('wp_rem_reviews_error_provide_full_name'); ?>',
                                };
                                wp_rem_show_response(response);
                                return false;
                            }
                            var parent_review_id = $("#parent_review_id").val();
                            var review_description = $("#review_description").val();
                            if (review_description.length < review_min_length || review_description.length > review_max_length) {
                                jQuery(".wp_rem-add-review-data").find("#review_description").addClass('frontend-field-error');
                                var response = {
                                    type: "error",
                                    msg: '<?php echo wp_rem_plugin_text_srt('wp_rem_reviews_review_desc_length_must_be'); ?>' + review_min_length + ' <?php echo wp_rem_plugin_text_srt('wp_rem_reviews_review_desc_length_must_be_to'); ?> ' + review_max_length + ' <?php echo wp_rem_plugin_text_srt('wp_rem_reviews_review_desc_length_must_be_to_long'); ?>.',
                                };
                                wp_rem_show_response(response);
                                return false;
                            }
                            var overall_rating = $(".overall-rating").data('overall-rating');
                            $(".ajax-message").text("<?php echo wp_rem_plugin_text_srt('wp_rem_reviews_request_processing_text'); ?>").css("color", "#555555");
                            is_processing = true;
                            $.ajax({
                                method: "POST",
                                url: "<?php echo admin_url('admin-ajax.php'); ?>",
                                dataType: "json",
                                data: {
                                    action: "post_user_review",
                                    ratings: JSON.stringify(ratings),
                                    post_id: "<?php echo $post_id; ?>",
                                    user_id: user_id,
                                    company_id: "<?php echo $company_id; ?>",
                                    property_type_id: "<?php echo $property_type_id; ?>",
                                    user_email: user_email,
                                    user_name: user_full_name,
                                    overall_rating: overall_rating,
                                    description: review_description,
                                    parent_review_id: parent_review_id,
                                    'g-recaptcha-response': jQuery.data(document.body, 'recaptcha'),
                                    security: "<?php echo wp_create_nonce('wp_rem-add-reviews'); ?>",
                                },
                                success: function (data) {

                                    var response = {
                                        type: "success",
                                        msg: data.msg,
                                    };
                                    wp_rem_show_response(response);
                                    reviews_count = parseInt(reviews_count) + 1;
                                    $(".post-reviews-btn-detail").css("display", "block");
                                    // Reset form.
                                    if (data.success == true) {
                                        if ($("#review_full_name").is(":enabled")) {
                                            $("#review_full_name").val('');
                                        }
                                        if ($("#review_email_address").is(":enabled")) {
                                            $("#review_email_address").val('');
                                        }
                                        $("#review_description").val('');
                                        if (is_user_review_owner == false) {
                                            $(".star-rating-list .stars").each(function (key, elem) {
                                                var css_class = $("input[name='star']:checked", $(elem)).attr('class');
                                                if (css_class == undefined) {
                                                    $("input[name='star']:eq(0)", $(elem)).prop("checked", true);
                                                    css_class = "star-1";
                                                }
                                                set_width_of_span(css_class, $(elem).find("span"));
                                            });
                                            var elem = $(".rating-star input[name='star']:eq(0)").prop("checked", true);
                                            set_width_of_span('star-1', elem);
                                        }
                                        is_review_added = true;
                                        $(".btn-more-holder").show();

                                        reload_reviews(1);

                                        if (is_user_review_owner == false) {
                                            $(".add-new-review-holder").slideUp();
                                            $(".reviwes-property-holder").slideDown();
                                        }

                                        var review_added_string = $(".wp_rem-added-review-string").html();
                                        if (is_user_review_owner == false) {
                                            $(".wp_rem-add-review-data").html(review_added_string);
                                            $('.post-reviews-btn').addClass('is-review-add');
                                        } else {
                                            if ($('#review-reply-modal-box').hasClass('in')) {
                                                $('#review-reply-modal-box').modal('hide');
                                                is_processing = false;
                                            }
                                        }

                                        return false;
                                    }
                                    $(".post-reviews-btn-detail").css("display", "block");
                                    is_processing = false;
                                },
                            });
                        });
                        function is_email_valid(email) {
                            var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
                            return re.test(email);
                        }
                        function show_msg(msg, status) {
                            var color = status == true ? "#55ff55" : "#ff5555";
                            $(".ajax-message").css("color", color).text(msg).show();
                        }

                        /*
                         * Posting a Reply
                         */
                        var review_id = getParameterByName("review_id");
                        removeParam("review_id");

                        if (review_id != null) {
                            $("#parent_review_id").val(review_id);
                            $(".reviwes-property-holder").css("display", "none");
                            $(".add-new-review-holder").css("display", "block");
                            $('html,body').animate({
                                scrollTop: $(".add-new-review-holder").offset().top
                            }, 'slow');
                        }

                        $(".post-reviews-btn, .post-reviews-btn-detail").click(function () {
                            if ($(this).hasClass('is-user-property')) {
                                var response = {
                                    type: 'error',
                                    msg: '<?php echo ($show_ratings == 'on' ? wp_rem_plugin_text_srt('wp_rem_reviews_cannot_write_review_on_own_property') : wp_rem_plugin_text_srt('wp_rem_reviews_cannot_write_comment_on_own_property')) ?>'
                                };
                                wp_rem_show_response(response);
                            } else if ($(this).hasClass('is-review-add')) {
                                var response = {
                                    type: 'error',
                                    msg: '<?php echo ($show_ratings == 'on' ? wp_rem_plugin_text_srt('wp_rem_reviews_already_added_review_msg') : wp_rem_plugin_text_srt('wp_rem_reviews_already_added_comment1_msg')) ?>'
                                };
                                wp_rem_show_response(response);
                            } else if ($(this).hasClass('review-not-allowd')) {
                                var response = {
                                    type: 'error',
                                    msg: '<?php echo ($show_ratings == 'on' ? wp_rem_plugin_text_srt('wp_rem_reviews_post_notallowed_review_msg') : wp_rem_plugin_text_srt('wp_rem_reviews_post_notallowed_comment_msg')) ?>'
                                };
                                wp_rem_show_response(response);
                            } else if ($(this).hasClass('is-login-modal')) {
                                $('#sign-in').modal('show');
                                $('#sign-in').find('div[id^="user-login-tab-"]').addClass('active in');
                                $('#sign-in').find('div[id^="user-register-"]').removeClass('active in');
                            } else {
                                $("#parent_review_id").val('');
                                //$(".reviwes-property-holder").css("display", "none");
                                //$(".add-new-review-holder").css("display", "block");
                                //$(".post-reviews-btn-detail").css("display", "none");

                                $(".reviwes-property-holder").slideUp();
                                $(".add-new-review-holder").slideDown();
                                $(".post-reviews-btn-detail").slideUp();
                            }
                            return false;
                        });


                        $(".close-post-new-reviews-btn").click(function () {
                            //$(".add-new-review-holder").css("display", "none");
                            //$(".reviwes-property-holder").css("display", "block");
                            //$(".post-reviews-btn-detail").css("display", "block");

                            $(".add-new-review-holder").slideUp();
                            $(".reviwes-property-holder").slideDown();
                            $(".post-reviews-btn-detail").slideDown();
                            return false;
                        });
                        var reviews_count = <?php echo $reviews_count; ?>;
                        var reviews_shown_count = <?php echo count($reviews); ?>;
                        var start = posts_per_page;
                        if (reviews_shown_count < reviews_count && load_more_option == 'on') {

                            $(".btn-load-more").click(function () {
                                var thisObj = jQuery('#reviews-more-btn-holder');
                                wp_rem_show_loader('#reviews-more-btn-holder', '', 'button_loader', thisObj);
                                $.ajax({
                                    method: "POST",
                                    url: "<?php echo admin_url('admin-ajax.php'); ?>",
                                    dataType: "json",
                                    data: {
                                        action: "get_user_reviews",
                                        post_id: "<?php echo $post_id; ?>",
                                        offset: start,
                                        sorty_by: $(".slct-sort-by").val(),
                                        security: "<?php echo wp_create_nonce('wp_rem-get-reviews'); ?>",
                                    },
                                    success: function (data) {
                                        wp_rem_show_response('', '', thisObj);
                                        if (data.success == true) {
                                            $("ul.review-property").append(data.data);
                                            start = parseInt(start) + parseInt(posts_per_page);
                                        }
                                        if (start >= reviews_count) {
                                            $(".btn-more-holder").hide();
                                        }
                                    },
                                });
                                return false;
                            });
                        } else {
                            $(".btn-more-holder").hide();
                            if (reviews_shown_count < reviews_count) {
                                $('#reviews-pagination').twbsPagination({
                                    totalPages: Math.ceil(reviews_count / posts_per_page),
                                    visiblePages: 3,
                                    onPageClick: function (event, page) {
                                        page--;
                                        reload_reviews(0, page * posts_per_page);
                                    }
                                });
                            }
                        }

                        $(".ajax-loader-sorty-by").hide();
                        $("input[name='review']").click(function () {

                            $(".btn-more-holder").show();
                            reload_reviews();
                        });

                        function reload_reviews(reload_all_data, new_start) {
                            var reload_al_data = (typeof reload_all_data !== 'undefined') ? reload_all_data : 0;
                            start = (typeof new_start !== 'undefined') ? new_start : 0;

                            $(".ajax-loader-sorty-by").show();
                            $.ajax({
                                method: "POST",
                                url: "<?php echo admin_url('admin-ajax.php'); ?>",
                                dataType: "json",
                                data: {
                                    action: "get_user_reviews",
                                    post_id: "<?php echo $post_id; ?>",
                                    offset: start,
                                    sort_by: $("input[name='review']:checked").val(),
                                    all_data: reload_al_data,
                                    security: "<?php echo wp_create_nonce('wp_rem-get-reviews'); ?>",
                                },
                                success: function (data) {
                                    if (data.success == true) {
                                        $("ul.review-property li").remove();
                                        $("ul.review-property").append(data.data);
                                        if (data.ratings_summary_ui.length > 0) {
                                            $(".ratings-summary-container").html(data.ratings_summary_ui);
                                        }
                                        if (data.overall_ratings_ui.length > 0) {
                                            $(".overall-ratings-container").html(data.overall_ratings_ui);
                                        }
                                        start = parseInt(start) + parseInt(posts_per_page);
                                        //start += data.count;
                                        $("#button").click(function () {
                                            $('html, body').animate({
                                                scrollTop: $(".review-property").offset().top
                                            }, 1000);
                                        });
                                    }
                                    /*if (data.count == 0) {
                                     $(".btn-more-holder").hide();
                                     }*/
                                    if (start >= reviews_count) {
                                        $(".btn-more-holder").hide();
                                    }
                                    $(".ajax-loader-sorty-by").hide();
                                },
                            });
                        }

                        //$(".star-rating-list .stars input[type='radio']:eq(0)").attr("checked", true);
                        $(".star-rating-list .stars").each(function (key, elem) {
                            var css_class = $("input[name='star']:checked", $(elem)).attr('class');
                            if (css_class == undefined) {
                                $("input[name='star']:eq(0)", $(elem)).prop("checked", true);
                                css_class = "star-1";
                            }
                            set_width_of_span(css_class, $(elem).find("span"));

                            $("label", $(elem)).click(function (e) {
                                e.preventDefault();
                                var css_class = $(this).attr("class");
                                $("input." + css_class, $(this).parent()).prop("checked", true);
                                var parts = css_class.split("-");
                                $(this).parent().parent().data("selected-rating", parts[1]);
                            });
                        });

                        // For overall ratings.
                        var elem = $(".rating-star input[name='star']:eq(0)").prop("checked", true);
                        set_width_of_span('star-1', elem);
                        $(".rating-star label").click(function (e) {
                            e.preventDefault();
                            var css_class = $(this).attr("class");
                            $("input." + css_class, $(this).parent()).prop("checked", true);
                            var parts = css_class.split("-");
                            $(this).parent().parent().data("overall-rating", parts[1]);
                        });

                        $(".star-rating-list .stars label").hover(
                                function () {
                                    var css_class = $(this).attr('class');

                                    set_width_of_span(css_class, this);
                                },
                                function () {
                                    var css_class = $("input[name^='star']:checked", $(this).parent()).attr('class');
                                    set_width_of_span(css_class, this);
                                }
                        );

                        function set_width_of_span(css_class, elem) {
                            var span_width = "20%";
                            if (css_class == "star-1") {
                                span_width = "20%";
                            } else if (css_class == "star-2") {
                                span_width = "40%";
                            } else if (css_class == "star-3") {
                                span_width = "60%";
                            } else if (css_class == "star-4") {
                                span_width = "80%";
                            } else if (css_class == "star-5") {
                                span_width = "100%";
                            }

                            $(elem).parent().find("span").css("width", span_width);
                        }
                    });
                })(jQuery);


                // Characters Counter with limit
                $(document).ready(function () {
                    var text_max = <?php echo $wp_rem_review_max_length; ?>;
                    $('#textarea_feedback').html('<?php echo wp_rem_plugin_text_srt('wp_rem_reviews_characters_remaining'); ?>: ' + text_max);

                    $(document).on('keyup', '#review_description', function () {
                        var text_length = $('#review_description').val().length;
                        var text_remaining = text_max - text_length;

                        $('#textarea_feedback').html('<?php echo wp_rem_plugin_text_srt('wp_rem_reviews_characters_remaining'); ?>: ' + text_remaining);
                    });
                });


                // Words Counter For Reviews textarea
                /*
                 $(document).ready(function () {
                 $("#review_description").on('keydown', function (e) {
                 var words = $.trim(this.value).length ? this.value.match(/\S+/g).length : 0;
                 $('#display_count').text(words);
                 if (words < <?php echo $wp_rem_review_min_length; ?> || words > <?php echo $wp_rem_review_max_length; ?>) {
                 $(".words_count").css("color", "red");
                 } else {
                 $(".words_count").css("color", "green");
                 }
                 });
                 });
                 */



            </script>
            <?php
            //}
        }

        /**
         * 
         * @param type $ratings_summary
         * @param type $reviews_count
         */
        public function get_ratings_summary_ui($ratings_summary, $reviews_count, $avg_rate = 0, $avg_rate_percent = 0) {
            $avg_rate_m = round($avg_rate, 1);
            $avg_rate_m = strlen($avg_rate_m) == 1 ? $avg_rate_m . '.0' : $avg_rate_m;
            $http_request = wp_rem_server_protocol();
            // check count if greate
            $schema_url = '';
            $schema_rating_count = '';
            $schema_review_count = '';
            if ( $reviews_count > 0 && $avg_rate_m > 0 ) {
                $schema_url = 'itemprop="aggregateRating" itemscope itemtype="' . force_balance_tags($http_request) . 'schema.org/AggregateRating"';
                $schema_rating_count = 'itemprop="ratingValue"';
                $schema_review_count = 'itemprop="reviewCount"';
            }
            ?>
            <div class="rating-summary" <?php echo force_balance_tags($schema_url); ?>>
                <div class="overall-rate-big" <?php echo force_balance_tags($schema_rating_count); ?> ><?php echo ($avg_rate_m) ?></div>
                <div style="display:none;" <?php echo force_balance_tags($schema_review_count); ?>><?php echo ($reviews_count) ?></div>
                <div class="overall-heading-holder">
                    <span class="overall-heading-txt"><?php echo wp_rem_plugin_text_srt('wp_rem_reviews_overall_rating_heading'); ?></span>
                    <div class="rating-holder">
                        <div class="rating-star">
                            <span style="width: <?php echo ($avg_rate_percent) ?>%;" class="rating-box"></span>
                        </div>
                    </div>
                    <span class="rating-based-txt"><?php echo wp_rem_plugin_text_srt('wp_rem_reviews_based_on_all_ratings'); ?></span>
                </div>
                <ul class="all-service-list">
                    <?php
                    //var_dump($ratings_summary);
                    $overall_rating = 0;
                    foreach ( $ratings_summary as $key => $rating ):
                        ?>
                        <li><span><?php echo $rating['label']; ?></span> <strong><?php echo ( $rating['value'] <= 0 || $reviews_count <= 0 ? 0 : ( round(( $rating['value'] / ( $reviews_count * 5) ) * 100, 1) ) ); ?>%</strong></li>
                        <?php
                        $overall_rating += $rating['value'] <= 0 ? 0 : $rating['value'];
                    endforeach;
                    //var_dump(( round(( $overall_rating / ( $reviews_count * 5) ) * 100, 1) ));
                    ?>
                </ul>
            </div>
            <?php
        }

        public function get_overall_rating_ui($overall_ratings, $reviews_count) {
            ?>
            <div class="overall-rating">
                <ul class="reviews-box">
                    <?php foreach ( $overall_ratings as $key => $val ): ?>
                        <li>
                            <span class="label"><?php echo $key; ?></span>
                            <span class="item-list">
                                <?php if ( $reviews_count > 0 ) { ?>
                                    <span style="width: <?php echo ($val > 0 && $reviews_count > 0 ? (round(( $val / $reviews_count ) * 100, 2)) : 0); ?>%"></span>
                                <?php } else { ?>
                                    <span style="width: 0%"></span>
                                <?php } ?>
                            </span>
                            <span class="label"><?php echo ($val > 0 && $reviews_count > 0 ? (round(( $val / $reviews_count ) * 100, 2)) : 0); ?>%</span>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <?php
        }

        /*
         * Get all child reviews and Delete them by Review ID
         */

        public function delete_child_reviews($review_id) {

            $query_args = array(
                'post_type' => Wp_rem_Reviews::$post_type_name,
                'posts_per_page' => 1,
                'post_status' => 'publish',
                'meta_query' => array(
                    array(
                        'key' => 'wp_rem_parent_review',
                        'value' => $review_id,
                        'compare' => '=',
                    ),
                ),
            );

            $review_query = new WP_Query($query_args);
            $child_reviews = $review_query->get_posts();
            if ( ! empty($child_reviews) && count($child_reviews) > 0 ) {
                foreach ( $child_reviews as $child_key => $child_review ) {
                    $this->delete_user_review_on_trash_callback($child_review->ID, true);
                }
            }
        }

        /**
         * Delete user review by provided review id from admin.
         */
        public function delete_user_review_on_trash_callback($review_id = '', $is_child_review = false) {
            if ( isset($review_id) && $review_id != '' ) {
                $post_type = get_post_type($review_id);
                if ( $post_type == 'wp_rem_reviews' ) {
                    $post_slug = get_post_meta($review_id, 'post_id', true);
                    $ratings = get_post_meta($review_id, 'ratings', true);
                    $is_child = get_post_meta($review_id, 'wp_rem_parent_review', true);
                    $is_child = ( isset($is_child) && $is_child != '' ) ? true : false;
                    $overall_rating = get_post_meta($review_id, 'overall_rating', true);

                    $args = array(
                        'name' => $post_slug,
                        'post_type' => 'properties',
                        'post_status' => 'publish',
                        'numberposts' => 1
                    );
                    $properties = get_posts($args);
                    // If property found.
                    if ( 0 < count($properties) ) {
                        if ( $is_child == false ) {
                            $this->delete_child_reviews($review_id);

                            $post_id = $properties[0]->ID;

                            // Get existing ratings for this post and minus ratings stats from parent post.
                            $existing_ratings = get_post_meta($post_id, 'wp_rem_ratings', true);
                            if ( $existing_ratings != '' ) {
                                $existing_ratings['reviews_count'] --;
                                $existing_ratings['overall_rating'][$overall_rating] --;
                                foreach ( $existing_ratings['ratings'] as $key => $val ) {
                                    if ( isset($ratings[$key]) ) {
                                        $existing_ratings['ratings'][$key] -= floatval($ratings[$key]);
                                    }
                                }
                                update_post_meta($post_id, 'wp_rem_ratings', $existing_ratings);
                            }

                            // Finally delete reviews post meta and post.
                            $all_meta = get_post_meta($review_id);
                            foreach ( $all_meta as $key => $val ) {
                                delete_post_meta($review_id, $key);
                            }
                        }
                        if ( $is_child_review == true ) {
                            wp_delete_post($review_id, true);
                        }
                    }
                }
            }
        }

        /**
         * Delete user review by provided review id.
         */
        public function delete_user_review_callback() {
            $success = false;
            $type = 'error';
            $msg = wp_rem_plugin_text_srt('wp_rem_reviews_incomplete_data_msg');
            $review_id = isset($_POST['review_id']) ? $_POST['review_id'] : 0;
            if ( $review_id != 0 ) {

                $review_user_id = get_post_meta($_POST['review_id'], 'user_id', true);
                $review_company_id = get_user_meta($review_user_id, 'wp_rem_company', true);
                $user_data = get_currentuserinfo();
                $user_company = get_user_meta($user_data->ID, 'wp_rem_company', true);

                if ( $review_company_id != $user_company ) {
                    $response_array = array(
                        'type' => 'error',
                        'msg' => wp_rem_plugin_text_srt('wp_rem_reviews_no_perm_to_del_review'),
                    );
                    echo json_encode($response_array);
                    wp_die();
                }

                $post_slug = get_post_meta($review_id, 'post_id', true);
                $ratings = get_post_meta($review_id, 'ratings', true);
                $overall_rating = get_post_meta($review_id, 'overall_rating', true);
                $is_child = get_post_meta($review_id, 'wp_rem_parent_review', true);
                $is_child = ( isset($is_child) && $is_child != '' ) ? true : false;
                $args = array(
                    'name' => $post_slug,
                    'post_type' => 'properties',
                    'post_status' => 'publish',
                    'numberposts' => 1
                );
                $properties = get_posts($args);
                // If property found.
                if ( 0 < count($properties) ) {
                    if ( $is_child == false ) {

                        $this->delete_child_reviews($review_id);

                        $post_id = $properties[0]->ID;

                        // Get existing ratings for this post and minus ratings stats from parent post.
                        $existing_ratings = get_post_meta($post_id, 'wp_rem_ratings', true);
                        if ( $existing_ratings != '' ) {
                            $existing_ratings['reviews_count'] --;
                            $existing_ratings['overall_rating'][$overall_rating] --;
                            foreach ( $existing_ratings['ratings'] as $key => $val ) {
                                if ( isset($ratings[$key]) ) {
                                    $existing_ratings['ratings'][$key] -= floatval($ratings[$key]);
                                }
                            }
                            update_post_meta($post_id, 'wp_rem_ratings', $existing_ratings);
                        }

                        // Finally delete reviews post meta and post.
                        $all_meta = get_post_meta($review_id);
                        foreach ( $all_meta as $key => $val ) {
                            delete_post_meta($review_id, $key);
                        }
                    }
                    wp_delete_post($review_id, true);

                    $success = true;
                    $type = 'success';
                    $msg = wp_rem_plugin_text_srt('wp_rem_reviews_dashboard_delete_success_msg');
                }
            }

            $response_array = array(
                'type' => $type,
                'msg' => $msg,
            );
            echo json_encode($response_array);
            wp_die();
        }

        /**
         * A filter which is used whether user have added review for a post or not.
         */
        public function have_user_added_review_for_this_post_callback($have_added, $filter, $post_id, $is_email = false) {
            if ( $post_id != '' ) {
                $property_type_slug = get_post_meta($post_id, 'wp_rem_property_type', true);
                if ( $property_type_slug != '' ) {
                    $property_type_id = get_page_by_path($property_type_slug, 'OBJECT', 'property-type');
                    $property_type_id = $property_type_id->ID;
                }
                if ( $property_type_id != '' ) {
                    $multiple_reviews = get_post_meta($property_type_id, 'wp_rem_enable_multiple_reviews', true);
                }
                if ( isset($multiple_reviews) && $multiple_reviews == 'on' ) {
                    return false;
                }
            }
            $post = get_post($post_id);
            $slug = '';
            if ( $post == null ) {
                return $have_added;
            }
            $slug = $post->post_name;

            $args = array(
                'post_type' => Wp_rem_Reviews::$post_type_name,
                'post_status' => array( 'publish', 'pending' ),
                'meta_query' => array(
                    'relation' => 'AND',
                    array(
                        'key' => 'post_id',
                        'value' => $slug,
                    ),
                ),
            );
            if ( $is_email == false ) {
                $args['meta_query'][] = array(
                    'key' => 'company_id',
                    'value' => $filter,
                );
            } else {
                $args['meta_query'][] = array(
                    'key' => 'user_email',
                    'value' => $filter,
                );
            }
            $query = new WP_Query($args);
            // Return True if there is already an property with by this user.

            return ( 0 < $query->found_posts );
        }

        /**
         * Check if this user is owner then also consider that this user
         * have already added review in short he/she will not be allowed to add
         * review he/she to own property.
         * 
         * @param	boolean	$have_added
         * @param	int		$company_id
         * @param	int		$post_id
         * @return	boolean
         */
        public function is_this_user_owner_of_this_post_callback($have_added, $company_id, $post_id) {
            $post_company_id = get_post_meta($post_id, 'wp_rem_property_member', true);
            return ( $post_company_id == $company_id );
        }

        /**
         * Get Reviews Ratings Data for specified post.
         *
         * @param	array		$data
         * @param	int		$post_id
         */
        public function reviews_ratings_data_callback($data, $post_id) {
            $reviews_count = 0;
            $overall_ratings_sum = 0;
            $overall_ratings = array(
                5 => 0,
                4 => 0,
                3 => 0,
                2 => 0,
                1 => 0,
            );
            // Get existing ratings for this post.
            $existing_ratings_data = get_post_meta($post_id, 'wp_rem_ratings', true);
            if ( '' != $existing_ratings_data ) {
                $existing_overall_ratings = $existing_ratings_data['overall_rating'];

                foreach ( $existing_overall_ratings as $key => $val ) {
                    $overall_ratings_sum += ( $key * $val );
                }
                if ( isset($existing_ratings_data['reviews_count']) && $existing_ratings_data['reviews_count'] > 0 ) {
                    $overall_rating_percentage = ( $overall_ratings_sum / ( $existing_ratings_data['reviews_count'] * 5 ) ) * 100;
                    $data['overall_rating'] = round($overall_rating_percentage, 2);
                }
                $data['count'] = $existing_ratings_data['reviews_count'];
            }

            return $data;
        }

    }

    new Wp_rem_Reviews();
}

// add analytic for reviews

add_filter('views_edit-wp_rem_reviews', function( $views ) {
    $args = array(
        'post_type' => 'wp_rem_reviews',
        'posts_per_page' => "-1",
    );
    $custom_query = new WP_Query($args);
    $total_reviews = 0;
    $overall_rating = 0;
    $total_active = 0;
    $total_pending = 0;
    $rating_sum = 0;
    while ( $custom_query->have_posts() ) : $custom_query->the_post();
        global $post;
        $review_status = get_post_status($post->ID);
        if ( isset($review_status) && ! empty($review_status) ) {
            if ( $review_status == 'publish' ) {
                $total_active ++;
            } else if ( $review_status == 'pending' ) {
                $total_pending ++;
            }
        }
        $overall_rting = get_post_meta($post->ID, 'overall_rating', true);
        $overall_rting = isset($overall_rting) ? $overall_rting : 0;
        if ( empty($overall_rting) ) {
            $overall_rting = 0;
        }
        $rating_sum = $rating_sum + $overall_rting;
        $total_reviews ++;
    endwhile;
    wp_reset_postdata();
    if ( $total_active != 0 ) {
        $overall_rating = $rating_sum / $total_active;
    }

    //$overall_rating = floatval($overall_rating);

    $total = ($overall_rating / 5) * 100;
    $tating_stars = '<div class="reviews-rating-holder"><div class="rating-star">
	    <span class="rating-box" style="width:' . $total . '%;"></span>
		</div></div>';

    echo '
    <ul class="total-wp-rem-property row">
	<li class="col-lg-3 col-md-3 col-sm-6 col-xs-12"><div class="wp-rem-text-holder"><strong>' . wp_rem_plugin_text_srt('wp_rem_reviews_total_reviews') . '</strong><em>' . $total_reviews . '</em><i class="icon-comments-o"></i><i class="icon-plus4 custom-plus"></i></div></li>
	<li class="col-lg-3 col-md-3 col-sm-6 col-xs-12"><div class="wp-rem-text-holder"><strong>' . wp_rem_plugin_text_srt('wp_rem_reviews_active_reviews') . '</strong><em>' . $total_active . '</em><i class="icon-check_circle"></i></div></li>
	<li class="col-lg-3 col-md-3 col-sm-6 col-xs-12"><div class="wp-rem-text-holder"><strong>' . wp_rem_plugin_text_srt('wp_rem_reviews_pend_reviews') . '</strong><em>' . $total_pending . '</em><i class="icon-back-in-time"></i></div></li>
	</ul>';
    return $views;
});
