<?php get_header(); ?>

<div class="page-banner">
  <div class="page-banner__bg-image" style="background-image: url(<?php echo get_theme_file_uri('images/library-hero.jpg')?>);"></div>
    <div class="page-banner__content container t-center c-white">
      <h1 class="headline headline--large">Welcome!</h1>
      <h2 class="headline headline--medium">We think you&rsquo;ll like it here.</h2>
      <h3 class="headline headline--small">Why don&rsquo;t you check out the <strong>major</strong> you&rsquo;re interested in?</h3>
      <a href="<?php echo get_post_type_archive_link('program'); ?>" class="btn btn--large btn--blue">Find Your Major</a>
    </div>
  </div>

  <div class="full-width-split group">
    <div class="full-width-split__one">
      <div class="full-width-split__inner">
        <h2 class="headline headline--small-plus t-center">Upcoming Events</h2>
        <?php 
          $today = date('Ymd');
          $homepageEvents = new WP_Query(array(
            'posts_per_page' => 2, //-1 will return all posts that meet following reqs
            'post_type' => 'event', //we have already defined this post type in mu-plugins folder
            'meta_key' => 'event_date',//this goes hand in hand with orderby
            'orderby' => 'meta_value_num', //meta-value is how we tell wp that we wanna order by meta-key and for ordering by numbers, we use num, otherwise meta_value defaults to letters and words
            'order' => 'ASC', //order and orderby must be used together; by default, order is set to desc
            'meta_query' => array(//we use meta query to check that order is by upcoming datescompared with $today
              array(
                'key' => 'event_date',
                'compare' => '>=',
                'value' => $today,
                'type' => 'numeric'
              ),
            ),
          ));
          while($homepageEvents->have_posts()) {
            $homepageEvents-> the_post();
            get_template_part('template-parts/content', get_post_type()); //second argument adds to first with a dash, like content-event
         }
        ?>

        <p class="t-center no-margin"><a href="<?php echo get_post_type_archive_link('event'); ?>" class="btn btn--blue">View All Events</a></p>

      </div>
    </div>
    <div class="full-width-split__two">
      <div class="full-width-split__inner">
        <h2 class="headline headline--small-plus t-center">From Our Blogs</h2>
          <?php 
            $homepagePosts = new WP_Query(array(
              'posts_per_page' => 2,
              // 'category_name' => 'awards',
              // 'post_type' => 'page'
              
            )); //first step in writing custom queries is to define a varible/object in an OOP format
            //below we are customziing the default WP query to only for our custom defined $homepagePosts object
            while($homepagePosts->have_posts()) {
              $homepagePosts->the_post();?>
              <div class="event-summary">
                <a class="event-summary__date event-summary__date--beige t-center" href="<?php the_permalink(); ?>">
                  <span class="event-summary__month"><?php the_time('M');?></span>
                  <span class="event-summary__day"><?php the_time('d');?></span>  
                </a>
                <div class="event-summary__content">
                  <h5 class="event-summary__title headline headline--tiny"><a href="<?php the_permalink(); ?>"><?php the_title();?></a></h5>
                  <p><?php if(has_excerpt()) {//this will give you the option not to have to add custom excerpt to every single post
                      echo get_the_excerpt(); //if we use the_excerpt by default, it will add automatic spacing, which might not be desireable
                  } else {
                      echo wp_trim_words(get_the_content(), 18);
                  }
                     ?> <a href="<?php the_permalink(); ?>" class="nu gray">Read more</a></p>
                </div>
              </div>
            <?php } wp_reset_postdata(); //always use this after your custom query 
          ?>
        <p class="t-center no-margin"><a href="<?php echo site_url('/blog') ?>" class="btn btn--yellow">View All Blog Posts</a></p>
      </div>
    </div>
  </div>

  <div class="hero-slider">
  <div class="hero-slider__slide" style="background-image: url(<?php echo get_theme_file_uri('images/bus.jpg')?>);">
    <div class="hero-slider__interior container">
      <div class="hero-slider__overlay">
        <h2 class="headline headline--medium t-center">Free Transportation</h2>
        <p class="t-center">All students have free unlimited bus fare.</p>
        <p class="t-center no-margin"><a href="#" class="btn btn--blue">Learn more</a></p>
      </div>
    </div>
  </div>
  <div class="hero-slider__slide" style="background-image: url(<?php echo get_theme_file_uri('images/apples.jpg')?>);">
    <div class="hero-slider__interior container">
      <div class="hero-slider__overlay">
        <h2 class="headline headline--medium t-center">An Apple a Day</h2>
        <p class="t-center">Our dentistry program recommends eating apples.</p>
        <p class="t-center no-margin"><a href="#" class="btn btn--blue">Learn more</a></p>
      </div>
    </div>
  </div>
  <div class="hero-slider__slide" style="background-image: url(<?php echo get_theme_file_uri('images/bread.jpg')?>);">
    <div class="hero-slider__interior container">
      <div class="hero-slider__overlay">
        <h2 class="headline headline--medium t-center">Free Food</h2>
        <p class="t-center">Fictional University offers lunch plans for those in need.</p>
        <p class="t-center no-margin"><a href="#" class="btn btn--blue">Learn more</a></p>
      </div>
    </div>
  </div>
</div>
    
    <?php get_footer();
?>