<?php
/**
* Plugin Name: User Pages Creator
* Plugin URI: https://www.gabrieljoffe.com/
* Description: Adds a User Page for every user users
* Version: 0.1
* Author: Gabriel Joffe
* Author URI: https://www.gabrieljoffe.com/
**/



//Create new User post-type

// Our custom post type function
function create_posttype() {
  
    register_post_type( 'userspages',
    // CPT Options
        array(
            'labels' => array(
                'name' => __( 'Users Pages' ),
                'singular_name' => __( 'User' )
            ),
             'supports'            => array( 'title', 'editor', 'excerpt', 'author', 'thumbnail', 'comments', 'revisions', 'custom-fields', ),
        // You can associate this CPT with a taxonomy or custom taxonomy. 
        'taxonomies'          => array( 'audio','luci' ),
            'public' => true,
            'has_archive' => true,
            'rewrite' => array('slug' => 'userspages'),
            'show_in_rest' => true,
  
        )
    );
}
// Hooking up our function to theme setup
add_action( 'init', 'create_posttype' );





// Hook activation to create new Author Pages
register_activation_hook(__FILE__, function(){
    // Create a Parent Page for all Author Pages
    if(!($parent = get_page_by_title('Authors'))){
        $parent = wp_insert_post(array(
            'post_type' => 'userspages',
            'post_title' => 'Authors',
            'post_content' => 'Authors are children of this page.',
            'post_status' => 'draft', // Or publish
        ));
    }
    if(!$parent){
        // Bad... ERROR!
        return;
    }
    // Get user IDs, get_users() returns too much data
    global $wpdb;
    $IDs = $wpdb->get_col("SELECT `ID` FROM {$wpdb->users} ORDER BY `user_registered` DESC;");
    // Loop IDs and create subpages for Authors+ (not Subscribers)
    foreach($IDs as $ID){
        // Get user
        $user = new WP_User($ID);
        // Only create pages for Authors!
        if(!$user->has_cap('edit_posts')) continue;
        // Create page for Author
        $title = "About Author: {$user->display_name}";
        if(!($child = get_page_by_title($title, OBJECT, 'userspages'))){
            $child = wp_insert_post(array(
                'post_type' => 'userspages',
                'post_title' => $title,
                'post_name' => $user->display_name,
                'post_content' => 'Write stuff about the Author.',
                'post_status' => 'draft', // Or publish
                'post_parent' => $parent,
            ));
            // Setup according Metas (for further tracking)
            update_post_meta($child, 'about_author', $user->user_login);
            update_post_meta($child, 'about_author_ID', $user->ID);
        }
    }
    // Done! WILL RUN JUST ONCE, deactivates itself afterwards.
  //  deactivate_plugins(__FILE__, true);
  //  die;
});


 add_action('user_register', 'create_authors_page');
function create_authors_page($user_id) {

    $the_user      = get_userdata($user_id);
    $new_user_name = $the_user->user_login;
    $my_post       = array();
    $my_post['post_title']   = $new_user_name;
    $my_post['post_type']    = 'userspages';
    $my_post['post_content'] = 'hello';
    $my_post['post_status']  = 'publish';
    $my_post['post_theme']   = 'user-profile';

    wp_insert_post( $my_post );

  }

?>