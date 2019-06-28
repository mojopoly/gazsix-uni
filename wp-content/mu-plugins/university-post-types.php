<?php 
function university_post_types() {
    //Campus Post Type
        register_post_type('campus', array(
            'capability_type' => 'campus',
            'map_meta_cap' => true,
            'supports' => array('title', 'editor', 'excerpt'),
            'rewrite' => array('slug' => 'campuses'),
            'has_archive' => true,
            'public' => true,
            'labels' => array(
                'name' => 'Campuses',
                'add_new_item' => 'Add New Campus',
                'edit_item' => 'Edit Campus',
                'all_items' => 'All Campuses',
                'singular_name' => 'Campus'
            ),
            'menu_icon' => 'dashicons-location-alt'
        ));
    
    //Event Post Type
    register_post_type('event', array(
        'capability_type' => 'event', //by default type is post, this line is to grant custom user permission to only modify events
        'map_meta_cap' => true, //this line is required to activate the top line
        'supports' => array('title', 'editor', 'excerpt'),
        'rewrite' => array('slug' => 'events'),
        'has_archive' => true,
        'public' => true,
        'labels' => array(
            'name' => 'Events',
            'add_new_item' => 'Add New Event',
            'edit_item' => 'Edit Event',
            'all_items' => 'All Events',
            'singular_name' => 'Event'
        ),
        'menu_icon' => 'dashicons-calendar'
    ));

    //Program Post Type

    register_post_type('program', array(
        'supports' => array('title'), //we removed 'editor', which is the 2nd argument to remove content field
        'rewrite' => array('slug' => 'programs'),
        'has_archive' => true,
        'public' => true,
        'labels' => array(
            'name' => 'Programs',
            'add_new_item' => 'Add New Program',
            'edit_item' => 'Edit Program',
            'all_items' => 'All Programs',
            'singular_name' => 'Program'
        ),
        'menu_icon' => 'dashicons-awards'
    ));

        //Professor Post Type

        register_post_type('professor', array(
            'show_in_rest' => true, //this will show this custom post type in rest url
            'supports' => array('title', 'editor', 'thumbnail'),
            // 'rewrite' => array('slug' => 'professors'),
            // 'has_archive' => true, we dont need archive for professors as its not its own menu item, its related to programs
            'public' => true,
            'labels' => array(
                'name' => 'Professors',
                'add_new_item' => 'Add New Professor',
                'edit_item' => 'Edit Professor',
                'all_items' => 'All Professors',
                'singular_name' => 'Professor'
            ),
            'menu_icon' => 'dashicons-welcome-learn-more'
        ));

        //Note Post Type

        register_post_type('note', array(
            'capability_type' => 'note', //doesnt have to be named note, anything that's not called post.. we do this to give permission to subscribers the right to post notes
            'map_meta_cap' => true, //this along with above line will create a new row in the custom role plugin that we installed
            'show_in_rest' => true, //this will show this custom post type in rest API url
            'supports' => array('title', 'editor', 'author'),
            // 'rewrite' => array('slug' => 'professors'),
            // 'has_archive' => true, we dont need archive for professors as its not its own menu item, its related to programs
            'public' => false, //we wants our notes to be private for each user account; setting it to false wont show notes in public queries or search results
            'show_ui' => true, //above line will also hide my-notes from nav bar, and this line will bring it back to business
            'labels' => array(
                'name' => 'Notes',
                'add_new_item' => 'Add New Note',
                'edit_item' => 'Edit Note',
                'all_items' => 'All Notes',
                'singular_name' => 'Note'
            ),
            'menu_icon' => 'dashicons-welcome-write-blog'
        ));

        //Like Post Type
        register_post_type('like', array(
            // 'capability_type' => 'note', we dont need these 2 lines, since we will handle this on our own
            // 'map_meta_cap' => true, 
            'show_in_rest' => false, //since we want to create our own custom endpoint, we will set this to false
            'supports' => array('title'),
            'public' => false, 
            'show_ui' => true, 
            'labels' => array(
                'name' => 'Likes',
                'add_new_item' => 'Add New Like',
                'edit_item' => 'Edit Like',
                'all_items' => 'All Likes',
                'singular_name' => 'Like'
            ),
            'menu_icon' => 'dashicons-heart'
        ));
}

add_action('init', 'university_post_types'); 
?>