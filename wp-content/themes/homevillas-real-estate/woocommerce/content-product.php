<?php
/**
 * The template for displaying product content within loops
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.6.0
 */
if ( ! defined( 'ABSPATH' ) )
    exit; // Exit if accessed directly


$var_arrays = array( 'product', 'woocommerce_loop' );
$page_global_vars = WP_REM_CS_VAR_GLOBALS()->globalizing( $var_arrays );
extract( $page_global_vars );

// Store loop count we're currently on
if ( empty( $woocommerce_loop['loop'] ) )
    $woocommerce_loop['loop'] = 0;

// Store column count for displaying the grid
if ( empty( $woocommerce_loop['columns'] ) )
    $woocommerce_loop['columns'] = apply_filters( 'loop_shop_columns', 4 );

// Ensure visibility
if ( ! $product || ! $product->is_visible() )
    return;

// Increase loop count
$woocommerce_loop['loop'] ++;

// Extra post classes
$classes = array( 'col-sm-6 col-md-4 col-lg-4' );
if ( 0 == ( $woocommerce_loop['loop'] - 1 ) % $woocommerce_loop['columns'] || 1 == $woocommerce_loop['columns'] )
    $classes[] = 'first';
if ( 0 == $woocommerce_loop['loop'] % $woocommerce_loop['columns'] )
    $classes[] = 'last';

$wp_rem_cs_prod_attach_id = get_post_thumbnail_id( get_the_id() );
$wp_rem_cs_prod_attach_src = wp_rem_cs_attachment_image_src( $wp_rem_cs_prod_attach_id, 350, 350 );
?>

<li class="product">
    <a href="<?php esc_url( the_permalink() ) ?>">
        <?php
        if ( $wp_rem_cs_prod_attach_src != '' ) {
            ?>
            <img src="<?php echo esc_url( $wp_rem_cs_prod_attach_src ) ?>" alt="<?php esc_html( the_title() ) ?>">
            <?php
            woocommerce_template_loop_rating();
        } else {
            echo wc_placeholder_img();
            woocommerce_template_loop_rating();
        }
        ?>
        <h4><?php the_title() ?></h4>                                          
    </a>
    <?php echo woocommerce_template_loop_price() ?>
    <?php wp_rem_cs_loop_add_to_cart(); ?>

</li>
