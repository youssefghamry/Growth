<?php
/**
 * File Type: Opening Hours
 */
if ( ! class_exists('Wp_rem_faqs') ) {

    class Wp_rem_faqs {

        public function __construct() {
            add_action('property_options_sidebar_tab', array( $this, 'wp_rem_faqs_admin_sidebar_tab' ), 11);
            add_action('property_options_tab_container', array( $this, 'wp_rem_faqs_admin_tab_container' ), 11);
            add_action('property_type_faq_frontend', array( $this, 'property_type_faq_frontend_callback' ), 11, 1);
            add_action('save_post', array( $this, 'wp_rem_save_post_faqs' ), 11);
            add_action('wp_rem_property_type_detail_options', array( $this, 'wp_rem_property_type_detail_options' ), 10, 1);

            /**/
        }

        public function wp_rem_property_type_detail_options($property_type_id = 0) {
            global $wp_rem_html_fields;

            $wp_rem_opt_array = array(
                'name' => wp_rem_plugin_text_srt('wp_rem_property_type_backend_faqs'),
                'desc' => '',
                'hint_text' => '',
                'echo' => true,
                'field_params' => array(
                    'std' => 'on',
                    'id' => 'faqs_options_element',
                    'return' => true,
                ),
            );

            $wp_rem_html_fields->wp_rem_checkbox_field($wp_rem_opt_array);
        }

        public function wp_rem_faqs_admin_sidebar_tab() {
            global $post;

            $property_type_slug = get_post_meta($post->ID, 'wp_rem_property_type', true);
            $property_type = get_page_by_path($property_type_slug, OBJECT, 'property-type');
            $property_type_id = isset($property_type->ID) ? $property_type->ID : 0;

            $property_type_faqs = get_post_meta($property_type_id, 'wp_rem_faqs_options_element', true);
            ?>
            <li id="property-types-faqs-side-tab" style="display: <?php echo ($property_type_faqs == 'on' ? 'block' : 'none') ?>;"><a href="javascript:void(0);" name="#tab-property_types-settings-faqs"><i class="icon-question_answer"></i><?php echo wp_rem_plugin_text_srt('wp_rem_property_type_backend_faqs'); ?></a></li>
            <?php
        }

        public function wp_rem_faqs_admin_tab_container() {
            global $post;
            ?>
            <div id="tab-property_types-settings-faqs" class="wp_rem_tab_block" data-title="<?php echo wp_rem_plugin_text_srt('wp_rem_property_type_backend_faqs'); ?>">
                <?php $this->wp_rem_faqs_items($post); ?>
            </div>
            <?php
        }

        public function property_type_faq_frontend_callback($property_id = '') {
            $wp_rem_faqs_switch = get_post_meta($property_id, 'wp_rem_faqs_switch', true);

            $property_type_slug = get_post_meta($property_id, 'wp_rem_property_type', true);
            $property_type = get_page_by_path($property_type_slug, OBJECT, 'property-type');
            $property_type_id = isset($property_type->ID) ? $property_type->ID : 0;

            $property_type_faqs = get_post_meta($property_type_id, 'wp_rem_faqs_options_element', true);
            if ( $property_type_faqs != 'on' ) {
                return;
            }

            $faqs_data = get_post_meta($property_id, 'faqs_label', true);
            if ( is_array($faqs_data) && count($faqs_data) < 0 ) {
                return;
            }
            $faqs_data = get_post_meta($property_id, 'faqs_label', true);
            $faq_html = '';
            if ( is_array($faqs_data) && count($faqs_data) > 0 ) {
                foreach ( $faqs_data as $key => $faq ) {


                    $faq_html .= '<div class="panel">
                        <div class="panel-heading">
                            <strong class="panel-title">
                                <a data-toggle="collapse" class="collapsed" href="#collapse' . $key . '">' . $faq['faq_title'] . '</a>
                            </strong>
                        </div>
                        <div id="collapse' . $key . '" class="panel-collapse collapse">
                            <div class="panel-body">
								<p>' . force_balance_tags(str_replace("<br/>", '</p><p>', str_replace("<br />", '</p><p>', nl2br($faq['faq_description'])))) . '</p>
                            </div>
                        </div>
                    </div>';
                }
            }
            ?>
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="property-detail-faqs">
                        <div class="faq panel-group">
                            <div class="element-title">
                                <h3><?php echo wp_rem_plugin_text_srt('wp_rem_property_type_faqs_element_title'); ?></h3>
                            </div>
                            <?php echo force_balance_tags($faq_html); ?>
                        </div>
                    </div>
                </div>
            </div>

            <?php
        }

        public function wp_rem_faqs_items($post) {
            global $post, $wp_rem_form_fields, $wp_rem_html_fields, $wp_rem_plugin_static_text;

            $post_id = $post->ID;
            $faqsd_lables = get_post_meta($post_id, 'faqs_label', true);
            $wp_rem_faqs_switch = get_post_meta($post_id, 'wp_rem_faqs_switch', true);
            ?>
            <div id="tab-faqs_settings">
                <?php
                $post_meta = get_post_meta(get_the_id());
                $faqs_data = array();
                if ( isset($post_meta['wp_rem_property_type_faqs']) && isset($post_meta['wp_rem_property_type_faqs'][0]) ) {
                    $faqs_data = json_decode($post_meta['wp_rem_property_type_faqs'][0], true);
                }
                 if ((is_object($faqsd_lables) || is_array($faqsd_lables)) && count($faqsd_lables) > 0) {
                    $wp_rem_opt_array = array(
                        'name' => wp_rem_plugin_text_srt('wp_rem_show_all_faqs_switch'),
                        'desc' => '',
                        'hint_text' => wp_rem_plugin_text_srt('wp_rem_show_all_faqs_switch_desc'),
                        'echo' => true,
                        'field_params' => array(
                            'std' => $wp_rem_faqs_switch,
                            'id' => 'faqs_switch',
                            'return' => true,
                        ),
                    );
                    $wp_rem_html_fields->wp_rem_checkbox_field($wp_rem_opt_array);
                }
                ?>

                <div class="wp-rem-list-wrap wp-rem-faqs-list-wrap">
                    <ul class="wp-rem-list-layout">
                        <li class="wp-rem-list-label">
                            <div class="col-lg-1 col-md-1 col-sm-6 col-xs-12">
                                <div class="element-label">
                                    <label></label>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                                <div class="element-label">
                                    <label><?php echo wp_rem_plugin_text_srt('wp_rem_property_type_faqs_help_title'); ?></label>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                                <div class="element-label">
                                    <label><?php echo wp_rem_plugin_text_srt('wp_rem_property_type_faqs_help_descripion'); ?> </label>
                                </div>
                            </div>
                        </li>


                        <?php
                        $counter = 0;
                        if ( is_array($faqsd_lables) && sizeof($faqsd_lables) > 0 ) {
                            foreach ( $faqsd_lables as $key => $lable ) {
                                ?>
                                <li class="wp-rem-list-item">
                                    <div class="col-lg-1 col-md-1 col-sm-6 col-xs-12">
                                        <!--For Simple Input Element-->
                                        <div class="input-element">
                                            <div class="input-holder">
                                                <span class="cntrl-drag-and-drop"><i class="icon-menu2"></i></span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                                        <!--For Simple Input Element-->
                                        <div class="input-element">
                                            <div class="input-holder">
                                                <?php
                                                $wp_rem_opt_array = array(
                                                    'std' => isset($lable['faq_title']) ? esc_html($lable['faq_title']) : '',
                                                    'cust_name' => 'faqs_label[title][]',
                                                    'extra_atr' => 'placeholder="' . wp_rem_plugin_text_srt('wp_rem_property_type_faqs_label') . '"',
                                                    'classes' => 'input-field',
                                                );
                                                $wp_rem_form_fields->wp_rem_form_text_render($wp_rem_opt_array);
                                                ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                                        <?php
                                        $wp_rem_opt_array = array(
                                            'std' => isset($lable['faq_description']) ? esc_html($lable['faq_description']) : '',
                                            'cust_name' => 'faqs_label[description][]',
                                            'extra_atr' => 'placeholder="' . wp_rem_plugin_text_srt('wp_rem_property_type_faqs_description') . '"',
                                            'classes' => '',
                                        );
                                        $wp_rem_form_fields->wp_rem_form_textarea_render($wp_rem_opt_array);
                                        ?>
                                    </div>


                                    <a href="javascript:void(0);" class="wp-rem-remove wp-rem-parent-li-remove"><i class="icon-close2"></i></a>
                                </li>
                                <?php
                                $counter ++;
                            }
                        }
                        ?>
                    </ul>        
                    <ul class="wp-rem-list-button-ul">
                        <li class="wp-rem-list-button">
                            <div class="input-element">
                                <a href="javascript:void(0);" id="click-more" class="wp-rem-add-more cntrl-add-new-row" onclick="duplicate_faq()"><?php echo wp_rem_plugin_text_srt('wp_rem_property_type_meta_faqs_add_row'); ?></a>
                            </div>
                        </li>
                    </ul>
                </div>

            </div>

            <script type="text/javascript">
                jQuery(document).ready(function () {
                    var table_class = ".wp-rem-faqs-list-wrap .wp-rem-list-layout";
                    jQuery(table_class).sortable({
                        //items: "> tr:not(:last)",
                        cancel: "input,textarea, .wp-rem-list-label"
                    });


                });
                function duplicate_faq() {
                    $(".wp-rem-faqs-list-wrap .wp-rem-list-layout").append('<li class="wp-rem-list-item"><div class="col-lg-1 col-md-1 col-sm-6 col-xs-12"><div class="input-element"><div class="input-holder"><span class="cntrl-drag-and-drop"><i class="icon-menu2"></i></span></div></div></div><div class="col-lg-4 col-md-4 col-sm-6 col-xs-12"><div class="input-element"><div class="input-holder"><input type="text" placeholder="<?php echo wp_rem_plugin_text_srt('wp_rem_property_type_faqs_label'); ?>" class="input-field" name="faqs_label[title][]" value=""></div></div></div><div class="col-lg-4 col-md-4 col-sm-6 col-xs-12"><textarea placeholder="<?php echo wp_rem_plugin_text_srt('wp_rem_property_type_faqs_description'); ?>" name="faqs_label[description][]" value=""></textarea></div><a href="javascript:void(0);" class="wp-rem-remove wp-rem-parent-li-remove"><i class="icon-close2"></i></a></li>');
                }
                jQuery(document).on('click', '.cntrl-delete-rows', function () {
                    delete_row_top_faq(this);
                    return false;
                });
                function delete_row_top_faq(delete_link) {
                    $(delete_link).parent().parent().remove();
                }

            </script>
            <?php
        }

        public function wp_rem_save_post_faqs($post_id) {

            if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) {
                return;
            }

            if ( get_post_type() == 'properties' ) {

                if ( ! isset($_POST['faqs_label']['title']) || count($_POST['faqs_label']['title']) < 1 ) {
                    delete_post_meta($post_id, 'faqs_label');
                }

                if ( isset($_POST['faqs_label']['title']) && count($_POST['faqs_label']['title']) > 0 ) {
                    foreach ( $_POST['faqs_label']['title'] as $key => $lablel ) {
                        $faqs_array[] = array(
                            'faq_title' => $lablel,
                            'faq_description' => $_POST['faqs_label']['description'][$key],
                        );
                    }
                    update_post_meta($post_id, 'faqs_label', $faqs_array);
                }

            }
        }

    }

    global $wp_rem_faqs;
    $wp_rem_faqs = new Wp_rem_faqs();
}