<?php
/**
 * Created by PhpStorm.
 *
 * Admin Menu options
 *
 *
 * User: ifkoo
 * Date: 27.8.2018 г.
 * Time: 15:30
 */


function optionGlobalVar() {
	global $gv;
	add_management_page( 'Set global variables', 'Set global variables', 'manage_options', __FILE__, 'showOptions' );
}

/**
 * Option page
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
		wp_enqueue_style( $style, plugins_url( '../assets/style.css', __FILE__ ) );
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