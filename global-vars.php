<?php
/*
Plugin Name: Global vars
Description: Create global variables for your post
Author: IT Dev Pro ltd
Author URI: https://itdevpro.com/
Plugin URI: https://github.com/ifkooo/global-vars
License: GPLv2 or later
Version: 3.1
*/

/* Check & Quit */
defined( 'ABSPATH' ) OR exit;

define( 'ver', '3.1' );

include( plugin_dir_path( __FILE__ ) . 'include/admin.php');
include( plugin_dir_path( __FILE__ ) . 'include/front-end.php');
include( plugin_dir_path( __FILE__ ) . 'include/actions.php');
include( plugin_dir_path( __FILE__ ) . 'include/updater.php');


// Check for admin commands
add_action( 'init', '_requests' );

// Add admin page settings
add_action( 'admin_menu', 'optionGlobalVar' );

// Run replace Text in front-end part
add_filter( 'the_content', 'replace_text' );


/**
 * Create Buy PHP file for redirection
 */
function _requests() {
	global $gv, $list;

	if ( is_admin() ) {
		$action = $_GET["_gv"];
		switch ( $action ) {
			case "createBuy":
				do_action( "createBuy" );
				break;
			default:
				do_action( "checkPluginData" );
				break;
		}
	}


	// Get values of all global variables for frond-end and back-end
	$gv = get_option( 'gv', null );
	$gv = json_decode( $gv, true );

	$list[0]["title"] = "REDIRECT FROM BUY.PHP";
	$list[0]["name"]  = "real_url";
	$list[0]["type"]  = "text";
	$list[0]["auto"]  = 0;


	$list[1]["title"] = "Affiliate url";
	$list[1]["name"]  = "url";
	$list[1]["type"]  = "text";
	$list[1]["auto"]  = 0;

	$list[2]["title"] = "Affiliate Banner";
	$list[2]["name"]  = "banner";
	$list[2]["type"]  = "textarea";
	$list[2]["auto"]  = 1;

}
