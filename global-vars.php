<?php

/*
Plugin Name: Global Vars
Plugin URI: Create global variables for your post
Description: A brief description of the Plugin.
Version: 1.7
Author: ifkooo
Author URI: http://URI_Of_The_Plugin_Author
License: A "Slug" license name e.g. GPL2
*/

/* Check & Quit */
defined( 'ABSPATH' ) OR exit;


getVar();


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
		<h1>Global variables</h1>
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