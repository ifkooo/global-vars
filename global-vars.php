<?php
/*
Plugin Name: Global vars
Description: Create global variables for your post
Version: 2.0
Author: IT Dev Pro ltd
Author URI: https://itdevpro.com/
Plugin URI: https://itdevpro.com/wp/global-vars
*/

/* Check & Quit */
defined( 'ABSPATH' ) OR exit;

define('ver', '2.0');

add_action( 'init', 'github_plugin_updater_test_init' );

function github_plugin_updater_test_init() {
	define( 'WP_GITHUB_FORCE_UPDATE', true );

	if ( is_admin() ) {
		include_once( 'updater.php' );
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
			'readme'             => 'README.md',
			// which file to use as the readme for the version number
			'access_token'       => '',
			// Access private repositories by authorizing under Appearance > GitHub Updates when this example plugin is installed
		);
		new WP_GitHub_Updater( $config );
	}
}
add_action( 'init', 'getVar' );





/**
 * Get values of all global variables
 *
 * @return array
 */
function getVar() {
	global $gv, $list;

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


add_filter( 'the_content', 'replace_text' );
add_action( 'admin_menu', 'optionGlobalVar' );


function optionGlobalVar() {
	global $gv;
	add_management_page( 'Set global variables', 'Set global variables', 'manage_options', __FILE__, 'showOptions' );
}

/**
 * Admin menu
 *
 */
function showOptions() {
	global $gv;
	global $list;




	/** On save button click */
	if ( isset( $_POST['change-clicked'] ) ) {
		update_option( 'gv', json_encode( $_POST ) );
		getVar();
	}


	/** Show form for saving */

	$style = 'gv';
	if ( ( ! wp_style_is( $style, 'queue' ) ) && ( ! wp_style_is( $style, 'done' ) ) ) {
		wp_enqueue_style( $style, plugins_url( '/assets/style.css', __FILE__ ) );
	}

	?>

    <style>

    </style>
    <div class="wrap">
        <h1>Global variables ver: <?echo ver; ?></h1>
        <div class="panel">
            <div class="panel-title">
                <h3>Here you can see list with your variables</h3>
            </div>
            <div class="panel-body">

                <form action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI'] ); ?>" method="post">


					<?php for ( $i = 0; $i < count( $list ); $i ++ ) { ?>

                        <div class="form-group">
                            <label><?php echo $list[ $i ]["title"]; ?></label>
                            <span class="help">Use [gv_<?php echo $list[ $i ]["name"]; ?>]</span>
							<?php createFormField( $list[ $i ]["type"], $list[ $i ]["name"], $gv[ $list[ $i ]["name"] ] ); ?>
                        </div>
					<?php } ?>
                    <input name="change-clicked" type="hidden" value="1"/>
                    <input type="submit" class="button-primary" value="Set values"/>
                </form>
            </div>
        </div>
    </div>
	<?php
}

/**
 * Replace global variables in contents
 *
 * @param $text string Text of post
 *
 * @return string Replaced text of post
 */
function replace_text( $text ) {
	global $gv;
	global $list;



	for ($i=0;$i<count($list);$i++) {
		if ($list[$i]["auto"]==1) {
			$text.=stripslashes($gv[$list[$i]["name"]]);
		}
	}

	foreach ( $gv as $var => $val ) {
		$text   = str_replace( "[gv_{$var}]", stripslashes($val), $text );
	}

	return $text;
}

/**
 * Create field type from list
 *
 * @param $type string current field type
 * @param $name string current field name
 * @param $val string current field value
 *
 * @return string Generated HTML field
 */
function createFormField( $type, $name, $val ) {


	if ( $type != "textarea" ) {
		echo '<input type="' . $type . '" name="' . $name . '" class="form-control" value="' . $val . '" />';
	} else {
		$settings = array(
			'textarea_rows' => 20,
			'tinymce' => true,
			'quicktags' => true,
		);
		wp_editor( stripslashes($val),  $name   );
		//$f = '<textarea  name="gv[' . $name . ']" class="form-control">' . $val . '</textarea>';
	}


}