<?php get_header();
    while(have_posts()) {
        the_post(); 
        pageBanner();
        ?>

        <div class="container container--narrow page-section">         
        <div class="generic-content">
            <div class="row group">
                <div class= "one-third">
                    <?php the_post_thumbnail('professorPortrait'); ?>
                </div>
                <div class= "two-thirds">
                    <?php
                        $likeCount = new WP_Query(array( //this custom query will fetch all like post types that match post id and liked professor, we will need to display this in html in like-count class
                            'post_type' => 'like',
                            'meta_query' =>  array(//we need meta qeury , cuz we only wanna pull in like posts where the liked professor id value macthes the page that we're currently viewing 
                                array(
                                    'key'=> 'liked_professor_id',
                                    'compare' => '=', //we're looking for an exact match
                                    'value' => get_the_ID()
                                )

                            )   
                        ));
                        $existStatus = 'no';
                        if(is_user_logged_in()) { //without this if statement, anonymouse users would see data-exists=yes/aka, filled heart; reason is if user user isnt logged in, author line below will result in 0, which will be treated as non-existent and rest of array will run
                            //following query to show filled heart if current user has liked a prof
                            $existQuery = new WP_Query(array( 
                                'author' => get_current_user_id(),
                                'post_type' => 'like',
                                'meta_query' =>  array(
                                    array(
                                        'key'=> 'liked_professor_id',
                                        'compare' => '=', 
                                        'value' => get_the_ID()
                                    )
                                )   
                            ));
                            if ($existQuery->found_posts) {
                                $existStatus ='yes';
                            }
                        }

                    //in data-exists below, we have set the css in a way that if $existStatus is yes, then the heart like will fill-up
                    //in data-like below, we create this attribute to let ajax delete call know which like post to delete, by getting its ID
                    ?>
                    <span class="like-box" data-like="<?php echo $existQuery->posts[0]->ID;?>" data-professor="<?php the_ID(); ?>" data-exists="<?php echo $existStatus; ?>">
                        <i class="fa fa-heart-o" aria-hidden="true"></i>
                        <i class="fa fa-heart" aria-hidden="true"></i>
                        <span class="like-count"><?php echo $likeCount->found_posts; ?></span> 
                    </span>
                    <?php the_content(); ?>
                </div>
            </div>
        </div>
        <?php 
            $relatedPrograms = get_field('related_programs');
            // print_r($relatedPrograms); you can use print r in php wheneevr u wanna know whats inside a variable, now that we know result is an array, we need to loop in it
            if($relatedPrograms) {
                echo '<hr class="section-break">';
                echo '<h2 class="headline headline--medium">Subject(s) Taught</h2>';
                echo '<ul class= "link-list min-list">';
                    foreach($relatedPrograms as $program) { ?>
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