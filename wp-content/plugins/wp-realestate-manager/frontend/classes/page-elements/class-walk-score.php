<?php
/**
 * File Type: Yelp List results for property
 */
if (!class_exists('wp_rem_walk_score_list_results')) {

    class wp_rem_walk_score_list_results {

        public function __construct() {
            add_action('wp_rem_property_walk_score_results_html', array($this, 'wp_rem_property_walk_score_results_html_callback'), 10, 2);
            add_action('wp_ajax_wp_rem_property_walk_score_results', array($this, 'wp_rem_property_walk_score_results_callback'));
            add_action('wp_ajax_nopriv_wp_rem_property_walk_score_results', array($this, 'wp_rem_property_walk_score_results_callback')); 
        }

        /**
         * Yelp places result html
         * */
        public function wp_rem_property_walk_score_results_html_callback($property_id = '', $view = 'default') {
            
            ?>
            <div class="scoring-holder property-detail-section-loader" id="property_detail_walk_score_result_<?php echo absint($property_id); ?>" style="min-height:120px;">
                <script>
                    jQuery(document).ready(function () {
                        wp_rem_load_walk_score(<?php echo absint($property_id); ?>, '<?php echo esc_html($view); ?>');
                    });
                </script>
            </div><?php 
        }

        /**
         * Yelp places ajax function
         * */
        public function wp_rem_property_walk_score_results_callback() {
            global $wp_rem_plugin_options;
            $property_id = wp_rem_get_input('property_id');
            $view = wp_rem_get_input('view');
            $result = '';
            $response['status'] = false;
            $response['result'] = '';
            if (true || isset($wp_rem_plugin_options['wp_rem_walkscore_api_key']) && $wp_rem_plugin_options['wp_rem_walkscore_api_key'] != '') :
                $wp_rem_post_loc_address_property = get_post_meta($property_id, 'wp_rem_post_loc_address_property', true);
                $wp_rem_post_loc_latitude = get_post_meta($property_id, 'wp_rem_post_loc_latitude_property', true);
                $wp_rem_post_loc_longitude = get_post_meta($property_id, 'wp_rem_post_loc_longitude_property', true);

                $response = wp_rem_get_walk_score($wp_rem_post_loc_latitude, $wp_rem_post_loc_longitude, $wp_rem_post_loc_address_property);
                if (is_array($response)) {
                    $response = json_decode($response['body'], true);
                    if ($view == 'fancy') {
                        $result .= $this->wp_rem_property_walk_score_fancy_html($response);
                    } else {
                        $result .= $this->wp_rem_property_walk_score_default_html($response);
                    }



                    $response['status'] = true;
                    $response['result'] = $result;
                } else {
                    echo wp_rem_plugin_text_srt('wp_rem_property_score_error_occured');
                }

            endif;



            echo json_encode($response);
            wp_die();
        }

        public function wp_rem_property_walk_score_fancy_html($response) {
            $result = '';

            $result .= '<ul class="scoring-list">';
            if (isset($response['status']) && $response['status'] == 1) :
                if (isset($response['walkscore'])) :
                    $result .= '<li class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                        <div class="img-holder"> <span class="socres-lable"><a href="' . $response['ws_link'] . '"><strong>' . wp_rem_plugin_text_srt('wp_rem_property_walk_scores') . '</strong></a><strong>' . $response['walkscore'] . '</strong></span></div>
                                                        <div class="text-holder">
                                                            <address>
                                                                ' . $response['description'] . '
                                                            </address>
                                                            <a href="' . $response['ws_link'] . '" class="moredetail-btn">' . wp_rem_plugin_text_srt('wp_rem_property_walk_scores_more_detail_simple') . '</a>
                                                        </div>
                                                    </li>';

                endif;
                if (isset($response['transit']) && !empty($response['transit']['score'])) :
                    $result .= '<li class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                        <div class="img-holder"> <span class="socres-lable"><a href="' . $response['ws_link'] . '"><strong>' . wp_rem_plugin_text_srt('wp_rem_property_transit_score') . '</strong></a>' . $response['transit']['score'] . '</span> </div>
                                                        <div class="text-holder">

                                                            <address>
                                                                ' . $response['transit']['description'] . '
                                                            </address>
                                                            <a href="' . $response['ws_link'] . '" class="moredetail-btn">' . wp_rem_plugin_text_srt('wp_rem_property_walk_scores_more_detail_simple') . '</a>
                                                        </div>
                                                    </li>';
                endif;
                if (isset($response['bike']) && !empty($response['bike']['score'])) :
                    $result .= '<li class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                        <div class="img-holder"> <span class="socres-lable"><a href="' . $response['ws_link'] . '"><strong>' . wp_rem_plugin_text_srt('wp_rem_property_bike_score') . '</strong></a>' . $response['bike']['score'] . '</span> </div>
                                                        <div class="text-holder">
                                                            <address>
                                                                ' . $response['bike']['description'] . '
                                                            </address>
                                                            <a href="' . $response['ws_link'] . '" class="moredetail-btn">' . wp_rem_plugin_text_srt('wp_rem_property_walk_scores_more_detail_simple') . '</a>
                                                        </div>
                                                    </li>';
                endif;
            endif;
            $result .= '</ul>';


            return $result;
        }

        public function wp_rem_property_walk_score_default_html($response) {
            $result = '';
            $result .= '<div class="element-title">
    <h3>' . wp_rem_plugin_text_srt('wp_rem_property_walk_scores') . '</h3>
    <div class="walkscore-logo">
        <a href="https://www.walkscore.com" target="_blank">
            <img src="https://cdn.walk.sc/images/api-logo.png" alt="' . wp_rem_plugin_text_srt('wp_rem_property_walk_scores') . '">
        </a>
    </div>
</div>
<ul class="scoring-list">';
            if (isset($response['status']) && $response['status'] == 1) :

                if (isset($response['walkscore'])) :
                    $result .='<li class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="img-holder"> <span class="socres-lable">' . $response['walkscore'] . '</span></div>
        <div class="text-holder">
            <a href="' . $response['ws_link'] . '"><strong>' . wp_rem_plugin_text_srt('wp_rem_property_walk_scores') . '</strong></a>
            <address>
                ' . $response['description'] . '
            </address>
            <a href="' . $response['ws_link'] . '" class="moredetail-btn">' . wp_rem_plugin_text_srt('wp_rem_property_walk_scores_more_detail') . '</a>
        </div>
    </li>';
                endif;
                if (isset($response['transit']) && !empty($response['transit']['score'])) :
                    $result .='<li class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="img-holder"> <span class="socres-lable">' . $response['transit']['score'] . '</span> </div>
        <div class="text-holder">
            <a href="' . $response['ws_link'] . '"><strong>' . wp_rem_plugin_text_srt('wp_rem_property_transit_score') . '</strong></a>
            <address>
                ' . $response['transit']['description'] . '
            </address>
            <a href="' . $response['ws_link'] . '" class="moredetail-btn">' . wp_rem_plugin_text_srt('wp_rem_property_walk_scores_more_detail') . '</a>
        </div>
    </li>';
                endif;
                if (isset($response['bike']) && !empty($response['bike']['score'])) :
                    $result .='<li class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="img-holder"> <span class="socres-lable">' . $response['bike']['score'] . '</span> </div>
        <div class="text-holder">
            <a href="' . $response['ws_link'] . '"><strong>' . wp_rem_plugin_text_srt('wp_rem_property_bike_score') . '</strong></a>
            <address>
                ' . $response['bike']['description'] . '
            </address>
            <a href="' . $response['ws_link'] . '" class="moredetail-btn">' . wp_rem_plugin_text_srt('wp_rem_property_walk_scores_more_detail') . '</a>
        </div>
    </li>';
                endif;

            else:
                $result .='<li class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        ' . wp_rem_plugin_text_srt('wp_rem_property_score_error_occured') . '
    </li>';
            endif;
            $result .='</ul>';
            return $result;
        }

    }

    $wp_rem_walk_score_list_results = new wp_rem_walk_score_list_results();
}