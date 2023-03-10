<?php
// Template Name: Idx Page
// Wp Estate Pack
get_header();
?>
<div class="main-section wp-rem-idx-property-single">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <?php
                get_template_part('templates/breadcrumbs');
                get_template_part('templates/ajax_container');
                while (have_posts()) : the_post();
                    if (esc_html(get_post_meta($post->ID, 'post_show_title', true)) != 'no') {
                        ?> 
                        <h1 class="entry-title single-title" ><?php the_title(); ?></h1>
                        <?php
                    }
                    ?>
                    <div class="meta-info"> 
                        <?php
                        _e('Posted by ', 'homevillas-real-estate');
                        print ' ' . get_the_author() . ' ';
                        _e('on', 'homevillas-real-estate');
                        print' ' . the_date('', '', '', FALSE);
                        ?>
                        <?php
                        print ' | <i class="fa fa-file-o"></i> ';
                        the_category(', ')
                        ?>
                        <?php
                        print ' | <i class="fa fa-comment-o"></i> ';
                        comments_number('0', '1');
                        ?>      
                    </div> 
                    <div class="single-content omgidx">
                        <?php
                        global $more;
                        $more = 0;
                        get_template_part('templates/postslider');
                        the_content('Continue Reading');
                        $args = array(
                            'before' => '<p>' . __('Pages:', 'homevillas-real-estate'),
                            'after' => '</p>',
                            'link_before' => '',
                            'link_after' => '',
                            'next_or_number' => 'number',
                            'nextpagelink' => __('Next page', 'homevillas-real-estate'),
                            'previouspagelink' => __('Previous page', 'homevillas-real-estate'),
                            'pagelink' => '%',
                            'echo' => 1
                        );
                        wp_link_pages($args);
                        ?>                           
                    </div>    
                    <!-- #related posts start-->    
                    <?php get_template_part('templates/related_posts'); ?>    
                    <!-- #end related posts -->   
                    <!-- #comments start-->
                    <?php comments_template('', true); ?> 	
                    <!-- end comments -->   
                    <?php
                endwhile; // end of the loop.    
                get_template_part('sidebar.php');
                ?>
            </div>  
        </div>  
        <div class="hidden-idx">
            <?php
            $instance = array('title' => 'title', 'listingsToShow' => 50, 'sort' => 'DateAdded|DESC', 'defaultDisplay' => 'listed', 'querySource' => 'city');
            the_widget('dsSearchAgent_ListingsWidget', $instance);
            ?>
        </div>
    </diV>
</div>
<?php get_footer(); ?>