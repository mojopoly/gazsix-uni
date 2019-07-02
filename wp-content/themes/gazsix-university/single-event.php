<?php get_header();
    while(have_posts()) {
        the_post();
        pageBanner();
        ?>
        </div>
        <div class="container container--narrow page-section">
        <div class="metabox metabox--position-up metabox--with-home-link">
            <p><a class="metabox__blog-home-link" href="<?php echo get_post_type_archive_link('event'); //we could instead use site_url function, but if we change the slug in future, it will crash?>"><i class="fa fa-home" aria-hidden="true"></i> Events Home</a> 
            <span class="metabox__main"><?php the_title(); ?></span></p>
        </div>           
        <div class="generic-content">
                <?php the_content(); ?>
        </div>
        <?php 
            $relatedPrograms = get_field('related_programs');//in order to add relation, we add a new field in ACF and set type to relational
            // print_r($relatedPrograms); you can use print r in php wheneevr u wanna know whats inside a variable, now that we know result is an array, we need to loop in it
            if($relatedPrograms) {
                echo '<hr class="section-break">';
                echo '<h2 class="headline headline--medium">Related Program(s)</h2>';
                echo '<ul class= "link-list min-list">';
                    foreach($relatedPrograms as $program) { //we use a foreach since from above print_r, we realized that $relatedPrograms is an array ?>
                        <!-- echo get_the_title($program); -->
                        <li><a href="<?php echo get_the_permalink($program); ?>"><?php echo get_the_title($program); ?></a></li>
                    <?php }
                echo '</ul>';
            }
            ?>
        </div>
    <?php 
    }
    get_footer();
?>