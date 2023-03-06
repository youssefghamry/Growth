<?php

/**
 * Wp_rem_cs_Twitter_Widget Class
 *
 * @package Wp_rem_cs
 */
if (!class_exists('Wp_rem_cs_Twitter_Widget')) {

    /**
      Wp_rem_cs_Weather class used to implement the custom weather widget.
     */
    class Wp_rem_cs_Twitter_Widget extends WP_Widget {

	/**
	 * Sets up a new wp_rem_cs twitter widget instance.
	 */
	public function __construct() {
	    global $wp_rem_cs_var_static_text;
	    parent::__construct(
		    'wp_rem_cs_var_twitter_widget', // Base ID.
		    wp_rem_cs_var_theme_text_srt('wp_rem_cs_var_twitter_widget'), // Name.
		    array('classname' => 'twitter-post', 'description' => wp_rem_cs_var_theme_text_srt('wp_rem_cs_var_twitter_widget_desciption'))
	    );
	}

	/**
	 * Outputs the wp_rem_cs twitter widget settings form.
	 *
	 * @param array $instance Current settings.
	 */
	function form($instance) {
	    global $wp_rem_cs_var_form_fields, $wp_rem_cs_var_html_fields, $wp_rem_cs_var_static_text;
	    $strings = new wp_rem_cs_theme_all_strings;
	    $strings->wp_rem_cs_short_code_strings();
	    $instance = wp_parse_args((array) $instance, array('title' => ''));
	    $title = $instance['title'];
	    $username = isset($instance['username']) ? esc_attr($instance['username']) : '';
	    $numoftweets = isset($instance['numoftweets']) ? esc_attr($instance['numoftweets']) : '';
            $select_data_format = isset($instance['select_data_format']) ? esc_attr($instance['select_data_format']) : '';
            
            $wp_rem_api_access_token_secret_key = isset($instance['wp_rem_api_access_token_secret_key']) ? esc_attr($instance['wp_rem_api_access_token_secret_key']) : '';
            $wp_rem_api_access_token_key = isset($instance['wp_rem_api_access_token_key']) ? esc_attr($instance['wp_rem_api_access_token_key']) : '';
            $wp_rem_api_consumer_secret_key = isset($instance['wp_rem_api_consumer_secret_key']) ? esc_attr($instance['wp_rem_api_consumer_secret_key']) : '';
            $wp_rem_api_consumer_key = isset($instance['wp_rem_api_consumer_key']) ? esc_attr($instance['wp_rem_api_consumer_key']) : '';
            $tweets_cache_time = isset($instance['tweets_cache_time']) ? esc_attr($instance['tweets_cache_time']) : '';
            
	    $wp_rem_cs_opt_array = array(
		'name' => wp_rem_cs_var_theme_text_srt('wp_rem_cs_var_title_field'),
		'desc' => '',
		'hint_text' => '',
		'echo' => true,
		'field_params' => array(
		    'std' => esc_html($title),
		    'id' => '',
		    'cust_name' => wp_rem_cs_allow_special_char($this->get_field_name('title')),
		    'cust_id' => wp_rem_cs_allow_special_char($this->get_field_name('title')),
		    'return' => true,
		),
	    );
	    $wp_rem_cs_var_html_fields->wp_rem_cs_var_text_field($wp_rem_cs_opt_array);

	    $wp_rem_cs_opt_array = array(
		'name' => wp_rem_cs_var_theme_text_srt('wp_rem_cs_var_twitter_widget_user_name'),
		'desc' => '',
		'hint_text' => '',
		'echo' => true,
		'field_params' => array(
		    'std' => esc_html($username),
		    'id' => '',
		    'cust_name' => wp_rem_cs_allow_special_char($this->get_field_name('username')),
		    'cust_id' => wp_rem_cs_allow_special_char($this->get_field_name('username')),
		    'return' => true,
		),
	    );
	    $wp_rem_cs_var_html_fields->wp_rem_cs_var_text_field($wp_rem_cs_opt_array);

	    $wp_rem_cs_opt_array = array(
		'name' => wp_rem_cs_var_theme_text_srt('wp_rem_cs_var_twitter_widget_tweets_num'),
		'desc' => '',
		'hint_text' => '',
		'echo' => true,
		'field_params' => array(
		    'std' => esc_html($numoftweets),
		    'id' => '',
		    'cust_name' => wp_rem_cs_allow_special_char($this->get_field_name('numoftweets')),
		    'cust_id' => wp_rem_cs_allow_special_char($this->get_field_name('numoftweets')),
		    'return' => true,
		),
	    );
	    $wp_rem_cs_var_html_fields->wp_rem_cs_var_text_field($wp_rem_cs_opt_array);
            
            $wp_rem_cs_opt_array = array(
		'name' => wp_rem_cs_var_theme_text_srt('wp_rem_cs_var_widget_cache_time_limit'),
		'desc' => '',
		'hint_text' => wp_rem_cs_var_theme_text_srt('wp_rem_cs_var_widget_cache_time_limit_hint'),
		'echo' => true,
		'field_params' => array(
		    'std' => esc_html($tweets_cache_time),
		    'id' => '',
		    'cust_name' => wp_rem_cs_allow_special_char($this->get_field_name('tweets_cache_time')),
		    'cust_id' => wp_rem_cs_allow_special_char($this->get_field_name('tweets_cache_time')),
		    'return' => true,
		),
	    );
	    $wp_rem_cs_var_html_fields->wp_rem_cs_var_text_field($wp_rem_cs_opt_array);
            
            $date_time_format_array = array(
                'default'=>'November 06 2012',
                'eng_suff'=>' 6th November',
                'ddmm'=>' 06 Nov',
                'ddmmyy'=>'06 Nov 2012',
                'full_date'=>'Tues 06 Nov 2012',
                'time_since'=>' hours, minutes etc',

            );
            
            $wp_rem_cs_opt_array = array(
		'name' => wp_rem_cs_var_theme_text_srt('wp_rem_cs_var_widget_date_time_format'),
		'desc' => '',
		'hint_text' => wp_rem_cs_var_theme_text_srt('wp_rem_cs_var_widget_date_time_format_hint'),
		'echo' => true,
		'field_params' => array(
		    'std' => $select_data_format,
		    'cust_name' => wp_rem_cs_allow_special_char($this->get_field_name('select_data_format')),
		    'cust_id' => wp_rem_cs_allow_special_char($this->get_field_id('select_data_format')),
		    'id' => '',
		    'options' => $date_time_format_array,
		    'return' => true,
		),
	    );

	    $wp_rem_cs_var_html_fields->wp_rem_cs_var_select_field($wp_rem_cs_opt_array);
            
            
            
            
            
            
                        /*
                         * Add Twitter Api Keys
                         */

                        $wp_rem_cs_opt_array = array(
                            'name' => esc_html(wp_rem_cs_var_theme_text_srt('wp_rem_cs_var_twitter_element_api_consumer_key')),
                            'desc' => '',
                            'hint_text' => wp_rem_cs_var_theme_text_srt('wp_rem_cs_var_twitter_element_api_consumer_key_hint'),
                            'echo' => true,
                            'field_params' => array(
                                'std' => esc_html($wp_rem_api_consumer_key),
                                'cust_name' => wp_rem_cs_allow_special_char($this->get_field_name('wp_rem_api_consumer_key')),
                                'return' => true,
                            ),
                        );
                        $wp_rem_cs_var_html_fields->wp_rem_cs_var_text_field($wp_rem_cs_opt_array);

                        $wp_rem_cs_opt_array = array(
                            'name' => esc_html(wp_rem_cs_var_theme_text_srt('wp_rem_cs_var_twitter_element_api_consumer_secret_key')),
                            'desc' => '',
                            'hint_text' => wp_rem_cs_var_theme_text_srt('wp_rem_cs_var_twitter_element_api_consumer_secret_key_hint'),
                            'echo' => true,
                            'field_params' => array(
                                'std' => esc_html($wp_rem_api_consumer_secret_key),
                                'cust_name' => wp_rem_cs_allow_special_char($this->get_field_name('wp_rem_api_consumer_secret_key')),
                                'return' => true,
                            ),
                        );
                        $wp_rem_cs_var_html_fields->wp_rem_cs_var_text_field($wp_rem_cs_opt_array);
                        $wp_rem_cs_opt_array = array(
                            'name' => esc_html(wp_rem_cs_var_theme_text_srt('wp_rem_cs_var_twitter_element_api_access_token_key')),
                            'desc' => '',
                            'hint_text' => wp_rem_cs_var_theme_text_srt('wp_rem_cs_var_twitter_element_api_access_token_key_hint'),
                            'echo' => true,
                            'field_params' => array(
                                'std' => esc_html($wp_rem_api_access_token_key),
                                'cust_name' => wp_rem_cs_allow_special_char($this->get_field_name('wp_rem_api_access_token_key')),
                                'return' => true,
                            ),
                        );
                        $wp_rem_cs_var_html_fields->wp_rem_cs_var_text_field($wp_rem_cs_opt_array);
                         $wp_rem_cs_opt_array = array(
                            'name' => esc_html(wp_rem_cs_var_theme_text_srt('wp_rem_cs_var_twitter_element_api_access_token_secret_key')),
                            'desc' => '',
                            'hint_text' => wp_rem_cs_var_theme_text_srt('wp_rem_cs_var_twitter_element_api_access_token_secret_key_hint'),
                            'echo' => true,
                            'field_params' => array(
                                'std' => esc_html($wp_rem_api_access_token_secret_key),
                                'cust_name' => wp_rem_cs_allow_special_char($this->get_field_name('wp_rem_api_access_token_secret_key')),
                                'return' => true,
                            ),
                        );
                        $wp_rem_cs_var_html_fields->wp_rem_cs_var_text_field($wp_rem_cs_opt_array);
            
	}

	/**
	 * Handles updating settings for the current wp_rem_cs twitter widget instance.
	 *
	 * @param array $new_instance New settings for this instance as input by the user.
	 * @param array $old_instance Old settings for this instance.
	 * @return array Settings to save or bool false to cancel saving.
	 */
	function update($new_instance, $old_instance) {

	    $instance = $old_instance;
	    $instance['title'] = $new_instance['title'];
	    $instance['username'] = $new_instance['username'];
	    $instance['numoftweets'] = $new_instance['numoftweets'];
	    $instance['wp_rem_api_access_token_secret_key'] = $new_instance['wp_rem_api_access_token_secret_key'];
	    $instance['wp_rem_api_access_token_key'] = $new_instance['wp_rem_api_access_token_key'];
	    $instance['wp_rem_api_consumer_secret_key'] = $new_instance['wp_rem_api_consumer_secret_key'];
	    $instance['wp_rem_api_consumer_key'] = $new_instance['wp_rem_api_consumer_key'];
	    $instance['tweets_cache_time'] = $new_instance['tweets_cache_time'];
	    $instance['select_data_format'] = $new_instance['select_data_format'];
	    
            return $instance;
	}

	/**
	 * Outputs the content for the current wp_rem_cs twitter widget instance.
	 *
	 * @param array $args Display arguments including 'before_title', 'after_title',
	 *                        'before_widget', and 'after_widget'.
	 * @param array $instance Settings for the current twitter widget instance.
	 */
	function widget($args, $instance) {
	    global $wp_rem_cs_var_options, $wp_rem_cs_twitter_arg;

            $wp_rem_cs_twitter_datetime_formate =  isset($instance['select_data_format']) ? $instance['select_data_format'] : '';
            $wp_rem_cs_cache_limit_time=  isset($instance['tweets_cache_time']) ? $instance['tweets_cache_time'] : '';
            $wp_rem_cs_twitter_arg['accesstokensecret'] =  isset($instance['wp_rem_api_access_token_secret_key']) ? $instance['wp_rem_api_access_token_secret_key'] : '';
            $wp_rem_cs_twitter_arg['accesstoken'] =  isset($instance['wp_rem_api_access_token_key']) ? $instance['wp_rem_api_access_token_key'] : '';
            $wp_rem_cs_twitter_arg['consumersecret'] =  isset($instance['wp_rem_api_consumer_secret_key']) ? $instance['wp_rem_api_consumer_secret_key'] : '';
            $wp_rem_cs_twitter_arg['consumerkey'] =  isset($instance['wp_rem_api_consumer_key']) ? $instance['wp_rem_api_consumer_key'] : '';
	    if ('' === intval($wp_rem_cs_cache_limit_time)) {
		$wp_rem_cs_cache_limit_time = 60;
	    }
	    if ('' === $wp_rem_cs_twitter_datetime_formate) {
		$wp_rem_cs_twitter_datetime_formate = 'time_since';
	    }
            $wp_rem_cs_tweet_num_from_twitter = '';
	    if ('' === intval($wp_rem_cs_tweet_num_from_twitter)) {
		$wp_rem_cs_tweet_num_from_twitter = 5;
	    }
	    extract($args, EXTR_SKIP);
	    $title = empty($instance['title']) ? '' : apply_filters('widget_title', $instance['title']);
	    $title = wp_specialchars_decode(stripslashes($title));
	    $username = $instance['username'];
	    $numoftweets = $instance['numoftweets'];

	    if ('' === intval($numoftweets)) {
		$numoftweets = 2;
	    }

	    echo wp_rem_cs_allow_special_char($before_widget);
	    if (!empty($title) && ' ' !== $title) {
		echo wp_rem_cs_allow_special_char($args['before_title'] . esc_html($title) . $args['after_title']);
	    }
	    if (class_exists('wp_rem_real_estate_framework')) {
		if (strlen($username) > 1) {
		    wp_rem_cs_include_file(wp_rem_real_estate_framework::plugin_path() . '/includes/cs-twitter/display-tweets.php');
		    display_tweets($username, $wp_rem_cs_twitter_datetime_formate, $wp_rem_cs_tweet_num_from_twitter, $numoftweets, $wp_rem_cs_cache_limit_time);
		}
	    }
	    echo wp_rem_cs_allow_special_char($after_widget);
	}
    }
}
add_action('widgets_init', function() {
     function_exists('homevillas_widget_register') ? homevillas_widget_register('Wp_rem_cs_Twitter_Widget') : '';
    });











