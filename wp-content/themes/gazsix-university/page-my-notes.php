<?php get_header(); 

if (!is_user_logged_in()) {
    wp_redirect(esc_url(site_url('/')));
    exit; //we do this to stop wp use server resources further after redirecting
}
while(have_posts()) {
    the_post();
    pageBanner();
    ?>

<div class="container container--narrow page-section">
    <div class="create-note">
        <h2>Create New Note</h2>
        <input class="new-note-title" placeholder="Title">
        <textarea class="new-note-body" placeholder="Your Note Here..."></textarea>
        <span class="submit-note">Create Note</span>
        <span class="note-limit-message">Note limit reached; delete an exisitng note to make room for a new one.</span>
    </div>
    <ul class="min-list link-list" id="my-notes">
        <?php 
            $userNotes = new WP_Query(array(
                'post_type' => 'note',
                'posts_per_page' => -1,
                'author' => get_current_user()->user_login //this line will make the notes user specific
            ));
            //MAKE SURE TO USE APPRIPRIATE ESC FUNCTIONS FOR DIFFERENT PARTS OF FORMS TO STAY SECURE
            while($userNotes->have_posts()) {
                $userNotes->the_post(); 
                // whener using dB info as value for html attribute, you'll wrap it up in esc 
                ?>
                    <li data-id="<?php the_ID(); ?>"> 
                    <!-- data-id will get each note ID and pass to js for CRUD operations -->
                        <input readonly class="note-title-field" value="<?php echo str_replace('Private: ', '',esc_attr(wp_strip_all_tags(get_the_title()))); ?>"> 
                        <span class="edit-note"><i class="fa fa-pencil" aria-hidden="true"></i>Edit</span>
                        <span class="delete-note"><i class="fa fa-trash-o" aria-hidden="true"></i>Delete</span>
                        <textarea readonly class="note-body-field"><?php echo esc_textarea(wp_strip_all_tags(get_the_content())); ?></textarea>
                        <span class="update-note btn btn--blue btn--small"><i class="fa fa-arrow-right" aria-hidden="true"></i>Save</span>
                  
                    </li>
            <?php }
        ?>
    </ul>
</div>
<?php 
}
get_footer();
?>  