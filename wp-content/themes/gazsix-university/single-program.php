<?php get_header();
    while(have_posts()) {
        the_post();
        pageBanner();
         ?>
        <div class="container container--narrow page-section">
        <div class="metabox metabox--position-up metabox--with-home-link">
            <p><a class="metabox__blog-home-link" href="<?php echo get_post_type_archive_link('program'); ?>"><i class="fa fa-home" aria-hidden="true"></i> All Programs</a> 
            <span class="metabox__main"><?php the_title(); ?></span></p>
        </div>           
        <div class="generic-content">
                <?php the_field('main_body_content'); ?>
        </div>
        <?php 
        $relatedProfessors = new WP_Query(array(
            'posts_per_page' => -1, //-1 will return all posts that meet following reqs
            'post_type' => 'professor',
            'orderby' => 'title', //meta-value is how we tell wp that we wanna use meta-key and for numbers, we use num
            'order' => 'ASC',
            'meta_query' => array(
                array(
                'key' => 'related_programs',
                'compare' => 'LIKE', //means contains
                'value' => '"' . get_the_ID() . '"' //we need quotation cuz this is how post IDs are saved in WP
                )
             )
            ));
            if($relatedProfessors->have_posts()) {
                echo '<hr class="section-break">';
                echo '<h2 class"headline headline--medium">' . get_the_title() . ' Professors </h2>';
                echo '<ul class="professor-cards">';
                while($relatedProfessors->have_posts()) {
                $relatedProfessors-> the_post(); ?>
                <li class="professor-card__list-item">
                    <a class="professor-card" href="<?php the_permalink(); ?>">
                        <img class="professor-card__image" src="<?php the_post_thumbnail_url('professorLandscape'); ?>">
                        <span class="professor-card__name"><?php the_title(); ?></span>
                    </a>
                </li>
                <?php }
                echo '</ul>';
            }

        wp_reset_postdata(); // resets global post object back to default url, use it in between custom queries like here we will need thsi cuz were calling get-the-id on line 63 and id changes at header and after calling above function, so we need to reset it

        $today = date('Ymd');
        $homepageEvents = new WP_Query(array(
        'posts_per_page' => 2, //-1 will return all posts that meet following reqs
        'post_type' => 'event',
        'meta_key' => 'event_date',
        'orderby' => 'meta_value_num', //meta-value is how we tell wp that we wanna use meta-key and for numbers, we use num
        'order' => 'ASC',
        'meta_query' => array(
            array( //this inner array is like a filter and you can have multiple filters
            'key' => 'event_date', //key must be an array
            'compare' => '>=',
            'value' => $today,
            'type' => 'numeric',
            ),
            array(
            'key' => 'related_programs',
            'compare' => 'LIKE', //means contains
            'value' => '"' . get_the_ID() . '"' //we need quotation cuz this is how post IDs are saved in WP
            )
         )
        ));
        if($homepageEvents->have_posts()) {
            echo '<hr class="section-break">';
            echo '<h2 class"headline headline--medium">Upcoming ' . get_the_title() . ' Events </h2>';
            while($homepageEvents->have_posts()) {
            $homepageEvents-> the_post();
            get_template_part('template-parts/content-event');
        }
        }

        //to create the relationship to related campus, we dont need to write custom query, using acf custom field properties will suffice
        //as campuses field live inside program page
        wp_reset_postdata();
        $relatedCampuses=get_field('related_campus');
        if($relatedCampuses) {
            echo '<hr class="section-break">';
            echo '<h2 class="headline headline--medium">' . get_the_title(). ' is Available at These Campuses:</h2>';
            echo '<ul class="min-list link-list">';
            foreach($relatedCampuses as $campus) {
                ?> <li>
                    <a href="<?php echo get_the_permalink($campus); ?>">
                        <?php echo get_the_title($campus) ?>
                    </a>
                    </li>
                <?php
            }
            echo '</ul>';
        }

    ?>
        </div>
    <?php 
    }
    get_footer();
?>