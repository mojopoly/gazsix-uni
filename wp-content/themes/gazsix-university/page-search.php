<?php get_header(); 

    while(have_posts()) {
        the_post();
        pageBanner(array(
          // 'title' => 'hello there this is the title',
          // 'subtitle' => 'this is the subtitle',
          // 'photo' => 
        ));
        ?>

    <div class="container container--narrow page-section">
    <?php   
      $theParent = wp_get_post_parent_id(get_the_ID()); //checks to see if the current page has a parent
      if($theParent){ ?>
        <div class="metabox metabox--position-up metabox--with-home-link">
        <p><a class="metabox__blog-home-link" href="<?php echo get_permalink($theParent); ?>"><i class="fa fa-home" aria-hidden="true"></i> Back to <?php echo get_the_title($theParent); ?></a> <span class="metabox__main"><?php the_title(); ?></span></p>
      </div>
      <?php 
        } ?>
    
    <?php 
    $testArray = get_pages(array(
      'child_of' => get_the_ID()
    )); //checks to see if the current page is a parent; get_pages does exactly what wp_list_pages do except it doesn't show it on screen
    if($theParent or $testArray) { ?>
      <div class="page-links"> 
        <h2 class="page-links__title"><a href="<?php echo get_permalink($theParent); ?>"><?php echo get_the_title($theParent); ?></a></h2>
        <ul class="min-list">
          <?php 
          if($theParent) {
            $findChildrenOf = $theParent;
          } else {
            $findChildrenOf = get_the_ID();
          }
          wp_list_pages(array(
            'title_li' => NULL,
            'child_of' => $findChildrenOf,
            'sort_column' => 'menu_order' //you need to change page orders in their edit menu to make this ordering effective

          )) ?>
        </ul>

      </div>
    <?php }
    ?>
    <div class="generic-content">
        <?php get_search_form(); ?>
    </div>

  </div>
    <?php 
    }
    get_footer();
?>  