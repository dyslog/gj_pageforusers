<?php
add_action( 'admin_menu', 'gj_up_add_admin_menu' );
add_action( 'admin_init', 'gj_up_settings_init' );


function gj_up_add_admin_menu(  ) { 

	add_options_page( 'GJ User Pages', 'GJ User Pages', 'manage_options', 'gj_user_pages', 'gj_up_options_page' );

}


function gj_up_settings_init(  ) { 

	register_setting( 'pluginPage', 'gj_up_settings' );

	//add_filter( 'pre_update_option_' . 'gj_up_settings' , 'my_callback', 10, 2 );

	add_settings_section(
		'gj_up_pluginPage_section', 
		__( 'Settings', 'Settings GJ UP' ), 
		'gj_up_settings_section_callback', 
		'pluginPage'
	);

	add_settings_field( 
		'gj_up_select_field_0', 
		__( 'Choose what to do on Login', 'Settings GJ UP' ), 
		'gj_up_select_field_0_render', 
		'pluginPage', 
		'gj_up_pluginPage_section' 
	);

	add_settings_field( 
		'gj_up_checkbox_field_1', 
		__( 'Check this box to create the pages for current users', 'Settings GJ UP' ), 
		'gj_up_checkbox_field_1_render', 
		'pluginPage', 
		'gj_up_pluginPage_section' 
	);


}
function sanitize_wpse38180( $options )
{
    
    return $options;
}

function gj_up_select_field_0_render(  ) { 

	$options = get_option( 'gj_up_settings' );
	?>
	<select name='gj_up_settings[gj_up_select_field_0]'>
		<option value='1' <?php selected( $options['gj_up_select_field_0'], 1 ); ?>>Login to User page</option>
		<option value='2' <?php selected( $options['gj_up_select_field_0'], 2 ); ?>>Default</option>
	</select>

<?php

}

function gj_up_checkbox_field_1_render(  ) { 

	$options = get_option( 'gj_up_settings' );
	?>
	<input type='checkbox' name='gj_up_settings[gj_up_checkbox_field_1]' <?php checked( $options['gj_up_checkbox_field_1'], 1 ); ?> value='1'>
	<?php

}


function gj_up_settings_section_callback(  ) { 

	echo __( 'Options after Login', 'Settings GJ UP' );

}


function gj_up_options_page(  ) { 
	
		?>
		<form action='options.php' method='post'>

			<h2>GJ User Pages</h2>

			<?php
			$options = get_option( 'gj_up_settings' );
			echo $options['gj_up_select_field_0'];

			settings_fields( 'pluginPage' );
			do_settings_sections( 'pluginPage' );
			
			submit_button();

			if (isset($_POST['test_button'])){
				echo $options['gj_up_checkbox_field_1'].' check';
			}

			?>

		</form>
		<?php




if($options['gj_up_checkbox_field_1']==1){
echo 'Pages created';




//register_activation_hook(__FILE__, function(){
    // Create a Parent Page for all Author Pages
  /*  if(!($parent = get_page_by_title('Authors'))){
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
    }*/
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
        $title = "{$user->display_name}";
        if(!($child = get_page_by_title($title, OBJECT, 'userspages'))){
            $child = array(
                'post_type' => 'userspages',
                'post_title' => $title,
                'post_name' => $user->display_name,
                'post_content' => 'Write stuff about the Author.',
                'post_status' => 'publish', // Or publish
                'post_parent' => $parent,
				'post_author' => $ID,
            );
            // Setup according Metas (for further tracking)
			update_usermeta( $ID, 'twitter', wp_insert_post($child) );
           // update_post_meta($child, 'about_author', $user->user_login);
           // update_post_meta($child, 'about_author_ID', $user->ID);
        }
    }
    // Done! WILL RUN JUST ONCE, deactivates itself afterwards.
  //  deactivate_plugins(__FILE__, true);
  //  die;
//});







}

}