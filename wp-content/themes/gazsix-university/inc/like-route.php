<?php


function universityLikeRoutes()  { //a route is a rest api url
    register_rest_route('university/v1', 'manageLike', array(
        'methods' => 'POST', //post method is a type of http request that this route is responsible for
        'callback' => 'createLike'
    ));
    register_rest_route('university/v1', 'manageLike', array(
        'methods' => 'DELETE',
        'callback' => 'deleteLike'
    ));
}
add_action('rest_api_init', 'universityLikeRoutes');


function createLike($data) {
    if (is_user_logged_in()) { //without a nonce code, this always returns false
        $professor = sanitize_text_field($data['professorId']); //via our ajax request in Like.js, wp smooshes all sent info in an array, which we can access by adding $data inside paranthesis
        // return 'thanks for creating a like';
        
        $existQuery = new WP_Query(array( //this piece is code has been copied from single-professor.php with one modification in its 'value'
            'author' => get_current_user_id(),
            'post_type' => 'like',
            'meta_query' =>  array(
                array(
                    'key'=> 'liked_professor_id',
                    'compare' => '=', 
                    'value' => $professor
                )
            )   
        ));

        if ($existQuery->found_posts == 0 AND get_post_type($professor) == 'professor') { //if(like does not already exist), second piece makes sure post type is only professor
            // create new like post
            return wp_insert_post(array(//this function lets us programatically create post right from our PHP, this function returns post_id and that is why we add return in the beginning
                'post_type' => 'like',
                'post_status' => 'publish',
                'post_title' => '2nd PHP Create Post Test',
                'meta_input' => array( //this will create wp custom/meta fields
                    'liked_professor_id' => $professor, 
                )
                // 'post_content' => 'Hellow world 123'
            )); 
        } else {
            die("Invalid professor id");
        }

    } else {
        die("only logged-in users can create a like");
    }
   
}

function deleteLike($data) {
    // return 'thanks for deleting a like';
    $likeId = sanitize_text_field($data['like']);
    if (get_current_user_id() == get_post_field('post_author', $likeId) AND get_post_type($likeId) == 'like') {
        wp_delete_post($likeId, true);//true will skip trash and cpletely deletes it, 
        return 'Congrats, like deleted';
    } else {
        die("you do not have permission to do that");   
    }
}