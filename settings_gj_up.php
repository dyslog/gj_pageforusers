<?php

function dbi_add_settings_page() {
    add_options_page( 'Example plugin page', 'Example Plugin Menu', 'manage_options', 'dbi-example-plugin', 'dbi_render_plugin_settings_page' );
}
add_action( 'admin_menu', 'dbi_add_settings_page' );





function dbi_render_plugin_settings_page() {
    ?>
    <h2>Example Plugin Settings</h2>
    <form action="options.php" method="post">
        <?php 
        settings_fields( 'dbi_example_plugin_options' );
        do_settings_sections( 'dbi_example_plugin' ); ?>
        <input name="submit" class="button button-primary" type="submit" value="<?php esc_attr_e( 'Save' ); ?>" />
    </form>
    <?php
}



?>