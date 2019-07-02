<?php 
  get_header();
  pageBanner(array(
    'title' => 'Past Events',
    'subtitle' => 'Recap of past events.'
  ));
  ?>

    <div class="container container--narrow page-section">
    <?php $today = date('Ymd');
          $pastEvents = new WP_Query(array(
            'paged' => get_query_var('paged', 1),//tell which page number the results should be in; 1 is the default page number, get_query_var gives a bunch of info about page url; we need page number in this case, and 1 is fallback number in case there is no page number which happens for first page usually
            // 'posts_per_page' => 1, we will need this to test whether pagination works
            'post_type' => 'event',
            'meta_key' => 'event_date',
            'orderby' => 'meta_value_num', //meta-value is how we tell wp that we wanna use meta-key and for numbers, we use num
            'order' => 'ASC',
            'meta_query' => array(
              array(
                'key' => 'event_date',
                'compare' => '<',
                'value' => $today,
                'type' => 'numeric'
              ),
            ),
          )); ?>
      <?php while($pastEvents->have_posts()) {
        $pastEvents->the_post();
        get_template_part('template-parts/content-event');
      } 
        echo paginate_links(array(
            'total' => $pastEvents->max_num_pages //this line would make pagination for custom queries
        )); //paginate_links only works with default wp loop; for this case, since we are writing a custom WP 
      ?>

    </div>
  <?php
  get_footer();
  ?>