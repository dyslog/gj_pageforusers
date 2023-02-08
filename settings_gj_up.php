<?php
add_action( 'admin_menu', 'gj_up_add_admin_menu' );
add_action( 'admin_init', 'gj_up_settings_init' );


function gj_up_add_admin_menu(  ) { 

	add_options_page( 'GJ User Pages', 'GJ User Pages', 'manage_options', 'gj_user_pages', 'gj_up_options_page' );

}


function gj_up_settings_init(  ) { 

	register_setting( 'pluginPage', 'gj_up_settings' );

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
			?>

		</form>
		<?php

}