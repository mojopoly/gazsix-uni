<?php get_header();
    while(have_posts()) {
        the_post();
        pageBanner();
         ?>
        <div class="container container--narrow page-section">
        <div class="metabox metabox--position-up metabox--with-home-link">
            <p><a class="metabox__blog-home-link" href="<?php echo get_post_type_archive_link('campus'); ?>"><i class="fa fa-home" aria-hidden="true"></i> All Campuses</a> 
            <span class="metabox__main"><?php the_title(); ?></span></p>
        </div>           
        <div class="generic-content">
                <?php the_content(); ?>
        </div>
        <?php $mapLocation = get_field('map_location') ?>
        <div class= "acf-map">
        <div data-lat="<?php echo $mapLocation['lat']?>" data-lng="<?php echo $mapLocation['lng'] ?>" class="marker">
            <h3><?php the_title(); ?></h3>
            <?php echo $mapLocation['address']; ?>
        </div>
      </div>
        <?php 
        //we need a custom query to show related programs and the acf field lives inside program page not campus
        $relatedPrograms = new WP_Query(array(
            'posts_per_page' => -1, //-1 will return all posts that meet following reqs
            'post_type' => 'program',
            'orderby' => 'title', //meta-value is how we tell wp that we wanna use meta-key and for numbers, we use num
            'order' => 'ASC',
            'meta_query' => array(
                array(
                'key' => 'related_campus',
                'compare' => 'LIKE', //means contains
                'value' => '"' . get_the_ID() . '"' //we need quotation cuz this is how post IDs are saved in WP
                )
             )
            ));
            if($relatedPrograms->have_posts()) {
                echo '<hr class="section-break">';
                echo '<h2 class"headline headline--medium">Programs Available at This Campus</h2>';
                echo '<ul class="min-list link-list">';
                while($relatedPrograms->have_posts()) {
                $relatedPrograms-> the_post(); ?>
                <li>
                    <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                </li>
                <?php }
                echo '</ul>';
            }

        wp_reset_postdata(); // resets global post object back to default url, use it in between custom queries like here we will need thsi cuz were calling get-the-id on line 63 and id changes at header and after calling above function, so we need to reset it

        
    ?>
        </div>
    <?php 
    }
    get_footer();
?>