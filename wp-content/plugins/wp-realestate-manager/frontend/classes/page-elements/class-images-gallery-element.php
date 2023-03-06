<?php
/**
 * File Type: Services Page Element
 */
if ( ! class_exists( 'Wp_rem_Images_Gallery_Element' ) ) {

	class Wp_rem_Images_Gallery_Element {

		/**
		 * Start construct Functions
		 */
		public function __construct() {
			add_action( 'wp_rem_images_gallery_element_html', array( $this, 'wp_rem_images_gallery_element_html_callback' ), 11, 1 );
		}

		/*
		 * Output features html for frontend on property detail page.
		 */

		public function wp_rem_images_gallery_element_html_callback( $post_id ) {
			global $wp_rem_plugin_options;

			$content_gallery = wp_rem_element_hide_show( $post_id, 'content_gallery' );
			if ( $content_gallery != 'on' ) {
				return;
			}

			$html = '';
			$gallery_limit = wp_rem_cred_limit_check( $post_id, 'wp_rem_transaction_property_pic_num' );
			$gallery_ids_list = get_post_meta( $post_id, 'wp_rem_detail_page_gallery_ids', true );
			$gallery_pics_allowed = get_post_meta( $post_id, 'wp_rem_transaction_property_pic_num', true );
			if ( is_array( $gallery_ids_list ) && sizeof( $gallery_ids_list ) > 0 && $gallery_pics_allowed > 0 ) {
				?>
				<div class="main-post">
					<div id="slider" class="property-flexslider cs-loading flexslider">
						<div class="wp-rem-button-loader spinner">
	                        <div class="bounce1"></div>
	                        <div class="bounce2"></div>
	                        <div class="bounce3"></div>
	                    </div>
						<ul class="slides">
							<?php
							$gallery_counterr = 1;
							foreach ( $gallery_ids_list as $gallery_idd ) {
								if ( isset( $gallery_idd ) && $gallery_idd != '' ) {
									if ( wp_get_attachment_url( $gallery_idd ) ) {
										$image = wp_get_attachment_image_src( $gallery_idd, 'wp_rem_media_9' );
										?>
										<li>
											<img src="<?php echo esc_url( $image[0] ); ?>" alt="" />
										</li>
										<?php
										if ( $gallery_limit == $gallery_counterr ) {
											break;
										}
										$gallery_counterr ++;
									}
								}
							}
							?>
						</ul>
					</div>
					<div id="carousel" class="property-carousel-flexslider flexslider">
						<ul class="slides">
							<?php
							$gallery_counter = 1;
							foreach ( $gallery_ids_list as $gallery_id ) {
								if ( isset( $gallery_id ) && $gallery_id != '' ) {
									if ( wp_get_attachment_url( $gallery_id ) ) {
										?>
										<li>
											<?php echo wp_get_attachment_image( $gallery_id, 'wp_rem_media_7' ); ?>
										</li>
										<?php
										if ( $gallery_limit == $gallery_counter ) {
											break;
										}
										$gallery_counter ++;
									}
								}
							}
							?>
						</ul>
					</div>
				</div>
				<?php
				$wp_rem_cs_inline_script = '
				jQuery(window).ready(function () {
					"use strict";
					jQuery(\'#slider\').flexslider({
						animation: "slide",
						controlNav: false,
						animationLoop: false,
						slideshow: false,
						drag: true,
						mousewheel: false,
						touch:true,
						smoothHeight: true,
						sync: "#carousel",
						start: function(slider){
							jQuery("#slider").removeClass("cs-loading");
						}
					});
					jQuery(\'#carousel\').flexslider({
						animation: "slide",
						controlNav: false,
						animationLoop: false,
						slideshow: false,
						directionNav: false,
						itemWidth: 102.9,
						drag: true,
						mousewheel: false,
						touch:true,
						itemMargin: 5,
						asNavFor: "#slider",
						start: function(slider){
							jQuery("#carousel").show();
						
						}
					});
					function sliderResize(){
						var slider2 = jQuery(\'#carousel\').data("flexslider");
						if($(window).width() < 1024 && $(window).width() > 767){
							slider2.vars.itemWidth = 99;
							slider2.doMath();
						}
						if($(window).width() < 768 && $(window).width() > 668){
							slider2.vars.itemWidth = 101;
							slider2.doMath();
						}
						if($(window).width() < 668 && $(window).width() > 500){
							slider2.vars.itemWidth = 102;
							slider2.doMath();
						}
						if($(window).width() < 500 && $(window).width() > 450){
							slider2.vars.itemWidth = 109;
							slider2.doMath();
						}
						if($(window).width() < 450){
							slider2.vars.itemWidth = 125;
							slider2.doMath();
						}
						if($(window).width() < 385 && $(window).width() > 360){
							slider2.vars.itemWidth = 104;
							slider2.doMath();
						}
						if($(window).width() < 360){
							slider2.vars.itemWidth = 93;
							slider2.doMath();
						}
					}
					sliderResize();
					$(window).bind("resize", function() { 
						setTimeout(function(){ 
						    var slider1 = jQuery(\'#slider\').data("flexslider");  
						    slider1.resize();
						}, 1000);
						sliderResize()
						
					});

				});';
				wp_rem_cs_inline_enqueue_script( $wp_rem_cs_inline_script, 'wp-rem-custom-inline' );
			}
			
			echo force_balance_tags( $html );
		}
	}

	global $wp_rem_images_gallery_element;
	$wp_rem_images_gallery_element = new Wp_rem_Images_Gallery_Element();
}