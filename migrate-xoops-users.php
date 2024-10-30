<?php
/*
Plugin Name: Migrate Xoops Users
Description: A plugin to migrate users from Xoops to WordPress.
Author: Ramlal Solanki
Author URI: https://about.me/ramlal
Version: 1.0
*/
//to add custom page in admin section
add_action('admin_menu', 'migrate_xoops_users_plugin');
function migrate_xoops_users_plugin(){
	$plugins_url	=	plugin_dir_url( __FILE__ ) . 'images/xoopsWp.png' ;
	add_menu_page( 'Migrate Xoops Users', 'Migrate Xoops Users', 'manage_options', 'migrate-xoops-users-plugin', 'migrate_xoops_users_init', $plugins_url );
}

function migrate_xoops_users_init(){
	require plugin_dir_path( __FILE__ ) . 'migrate_xoops_users.php';
}
?>