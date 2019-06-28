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
      $theParent = wp_get_post_parent_id(get_the_ID()); //checks to get ID of parent page of current page if it exists aka checks to see whether current page has a parent
      if($theParent){ //this if to only show Back To for pages that have parent pages like Our Goals aka is a Child page, before this step, we create child pages and set parent to parent page in dashboard?> 
        <div class="metabox metabox--position-up metabox--with-home-link">
        <p><a class="metabox__blog-home-link" href="<?php echo get_permalink($theParent); ?>"><i class="fa fa-home" aria-hidden="true"></i> Back to <?php echo get_the_title($theParent); ?></a> <span class="metabox__main"><?php the_title(); ?></span></p>
      </div>
      <?php //difference between get_ and the_ functions is we can pass and arguement to get_, but not the_; the_ echos current page's info
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
            'title_li' => NULL, //this is generic and required as it stops sipitting out word "page" on the list
            'child_of' => $findChildrenOf, //child of a specific parent page
            'sort_column' => 'menu_order' //you need to change page orders in their edit menu to make this ordering effective

          )) ?>
        </ul>

      </div>
    <?php }
    ?>
    <div class="generic-content">
        <?php the_content(); ?>
    </div>

  </div>
    <?php 
    }
    get_footer();
?>  