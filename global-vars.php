<?php
/*
Plugin Name: Global vars
Description: Create global variables for your post
Author: IT Dev Pro ltd
Author URI: https://itdevpro.com/
Plugin URI: https://github.com/ifkooo/global-vars
License: GPLv2 or later
Version: 3.9
*/

/* Check & Quit */
defined( 'ABSPATH' ) OR exit;

define( 'ver', '3.9' );
define( 'buyVer', '1' );

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
add_action( "checkPluginData", "gitHubUpdate" );

/**
 * Git Hub Update
 */
function gitHubUpdate() {

	define( 'WP_GITHUB_FORCE_UPDATE', true );

	// note the use of is_admin() to double check that this is happening in the admin
	$config = array(
		'slug'               => plugin_basename( __FILE__ ),
		// this is the slug of your plugin
		'proper_folder_name' => 'global-vars',
		// this is the name of the folder your plugin lives in
		'api_url'            => 'https://api.github.com/repos/ifkooo/global-vars',
		// the GitHub API url of your GitHub repo
		'raw_url'            => 'https://raw.github.com/ifkooo/global-vars/master',
		// the GitHub raw url of your GitHub repo
		'github_url'         => 'https://github.com/ifkooo/global-vars',
		// the GitHub url of your GitHub repo
		'zip_url'            => 'https://github.com/ifkooo/global-vars/zipball/master',
		// the zip url of the GitHub repo
		'sslverify'          => true,
		// whether WP should check the validity of the SSL cert when getting an update, see https://github.com/jkudish/WordPress-GitHub-Plugin-Updater/issues/2 and https://github.com/jkudish/WordPress-GitHub-Plugin-Updater/issues/4 for details
		'requires'           => '4.6',
		// which version of WordPress does your plugin require?
		'tested'             => '4.9',
		// which version of WordPress is your plugin tested up to?
		'readme'             => 'readme.txt',
		// which file to use as the readme for the version number
		'access_token'       => '',
		// Access private repositories by authorizing under Appearance > GitHub Updates when this example plugin is installed
	);

	new WP_GitHub_Updater( $config );

}