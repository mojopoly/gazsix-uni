<?php 
  get_header();
  pageBanner(array(
    'title' => 'Search Results',
    'subtitle' => 'You searched for &ldquo;' . esc_html(get_search_query(false)) .'&rdquo;' //html code for left angled double quotes(ldquo); esc_html will output the value as html and not executable code
  ));
  ?>

    <div class="container container--narrow page-section">
    <?php 
    if(have_posts()){
      while(have_posts()) {
        the_post();
        get_template_part('template-parts/content', get_post_type());
      } 
      echo paginate_links();
    } else {
        echo '<h2 class="headline headline--small-plus">No results match that search. </h2>';
      }
    get_search_form(); //this will fetch searchform.php

      ?>

    </div>
  <?php
  get_footer();
  ?>