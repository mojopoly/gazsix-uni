
<?php //IN ORDER TO FIX SEARCH ONLY THRU THE TITLES BY WP, A WORKAROUND IS TO CREATE WYSWYG ACF AND SET ITS POSITION TO HIGH AND PASTE CONTENTS IN IT.
//--> ALSO MAKE SURE TO UPDATE SINGLE-PROGRAM FILE TO SHOW THE_FIELD INSTEAD OF THE_CONTENT 
function universityRegisterSearch() {
    register_rest_route('university/v1','search', array(
        'methods' => WP_REST_SERVER::READABLE, //to make this bulletproof, you can use WP_REST_SERVER::READABLE insetad of GET
        'callback' => 'universitySearchResults'
    )); //1st arg is the namespace like 'wp' in our http://localhost:3000/wp-json/wp/v2/posts, 2nd arg is the route, here 'posts'
}
add_action('rest_api_init', 'universityRegisterSearch');

function universitySearchResults($data) {//we're adding data here since we know wp passes info when calling this function on top
    // return 'you created a route!';
    // return array('red', 'orange', 'yellow'); //wp automatically converts php data into json data
//     return array(
//         'cat' => 'meow',
//         'dog' => 'bark'
//     );
    $mainQuery = new WP_Query(array(
        'post_type' => array('post', 'page' , 'professor', 'program', 'campus', 'event'),
        's' => sanitize_text_field($data['term'])//s stand for search, term is a madeup name that we chose, sanitize is a security measure
    ));
    // var_dump($professors);
    //since we dont need to generate html, we dont need to do perform regualr while loop
    $results = array(
        'generalInfo' => array(),
        'professors' => array(),
        'programs' => array(),
        'events' => array(),
        'campuses' => array()
    ); //we created this array to only filter the few properties that we need

    // return $professors->posts;// this prints all info about posts, which we will only need a few
    while($mainQuery->have_posts()) {
        $mainQuery->the_post(); //the_post gets all relevant data for posts ready and accessable
        
        if(get_post_type() == 'post' OR get_post_type() == 'page') {
            array_push($results['generalInfo'], array( //1st arg is the array the we want to add on to, 2nd is what u wanna add on to the array
                'title' => get_the_title(),  
                'permalink' => get_the_permalink(),
                'postType' => get_post_type(),
                'authorName' => get_the_author()
            ));
        }

        if(get_post_type() == 'professor' ) {
            array_push($results['professors'], array( //1st arg is the array the we want to add on to, 2nd is what u wanna add on to the array
                'title' => get_the_title(),  
                'permalink' => get_the_permalink(),
                'image' => get_the_post_thumbnail_url(0,'professorLandscape'),//0 refers to current img
            ));
        }
        if(get_post_type() == 'program') {
            $relatedCampuses=get_field('related_campus'); //we define this variable here since relationship between program and campus is 1 way from program to campus
            if ($relatedCampuses){
                foreach($relatedCampuses as $campus) {
                    array_push($results['campuses'], array(
                        'title' => get_the_title($campus),
                        'permalink' => get_the_permalink($campus)
                    ));
            }
        }
            array_push($results['programs'], array( //1st arg is the array the we want to add on to, 2nd is what u wanna add on to the array
                'title' => get_the_title(),  
                'permalink' => get_the_permalink(),
                'id' => get_the_id()
            ));

        }
        if(get_post_type() == 'campus') {
            array_push($results['campuses'], array( //1st arg is the array the we want to add on to, 2nd is what u wanna add on to the array
                'title' => get_the_title(),  
                'permalink' => get_the_permalink()
            ));
        }
        if(get_post_type() == 'event') {
            $eventDate = new DateTime(get_field('event_date', false, false));
            $description = null;
            if(has_excerpt()) {
                $description = get_the_excerpt();
            } else {
                $description = wp_trim_words(get_the_content(), 18);
            }
            array_push($results['events'], array( //1st arg is the array the we want to add on to, 2nd is what u wanna add on to the array
                'title' => get_the_title(),  
                'permalink' => get_the_permalink(),
                'month' => $eventDate->format('M'),
                'day' => $eventDate->format('d'),
                'description' => $description
            ));
        }
        }
    //this custom query for relationship between profs, programs, events
    if($results['programs']) { //this if statement to make sure to only print professors if search term is a program
        $programsMetaQuery = array('relation' => 'OR'); //we create this array to dynamically add sub-programs to our meta query 
        foreach($results['programs'] as $item) {
            array_push($programsMetaQuery, array(
                'key' => 'related_programs',
                'compare' => 'LIKE',
                'value' => '"' . $item['id'] . '"'
            ));
        }
        $programRelationshipQuery = new WP_Query(array(
            'post_type' => array('professor', 'event'),
            'meta_query' => $programsMetaQuery
            //below is the static way
            //array(//we need meta query to access values of a custom fields
            //     'relation' => 'OR', //this changes wp's default AND behavior for multiple arrays, we use or since we dont need all follwoing arrays to be true
            //     array(
            //     'key' => 'related_programs',
            //     'compare' => 'LIKE',
            //     'value' => '"' . $results['programs'][0]['id'] . '"'
            //     ),
            //     array(
            //         'key' => 'related_programs',
            //         'compare' => 'LIKE', 
            //         'value' => '"' . $results['programs'][1]['id'] . '"' //in case we have 2 sub programs in program array, this is how we add a 2nd array
            //     ),
            // ) 
    
        ));
        while($programRelationshipQuery->have_posts()) {
            $programRelationshipQuery->the_post();
            if(get_post_type() == 'event') {
                $eventDate = new DateTime(get_field('event_date', false, false));
                $description = null;
                if(has_excerpt()) {
                    $description = get_the_excerpt();
                } else {
                    $description = wp_trim_words(get_the_content(), 18);
                }
                array_push($results['events'], array( //1st arg is the array the we want to add on to, 2nd is what u wanna add on to the array
                    'title' => get_the_title(),  
                    'permalink' => get_the_permalink(),
                    'month' => $eventDate->format('M'),
                    'day' => $eventDate->format('d'),
                    'description' => $description
                ));
            }

            if(get_post_type() == 'professor' ) {
                array_push($results['professors'], array( //1st arg is the array the we want to add on to, 2nd is what u wanna add on to the array
                    'title' => get_the_title(),  
                    'permalink' => get_the_permalink(),
                    'image' => get_the_post_thumbnail_url(0,'professorLandscape'),//0 refers to current img
            ));

        }
    }
        $results['professors'] = array_values(array_unique($results['professors'], SORT_REGULAR)); //this will remove duplicates form the 2 custom queries, array_value will remove id numbers from results
        $results['events'] = array_values(array_unique($results['events'], SORT_REGULAR)); //this will remove duplicates form the 2 custom queries, array_value will remove id numbers from results

    }

    return $results;
}