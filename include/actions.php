<?php
/**
 * Created by PhpStorm.
 * User: ifkoo
 * Date: 27.8.2018 г.
 * Time: 15:33
 */



add_action( "createBuy", "createBuy" );

/**
 * Copy buy.php from plugin to main folder
 */
function createBuy() {
	$originUrl = plugin_dir_path( __FILE__ ) . "buy.php";
	$buyURL    = $_SERVER['DOCUMENT_ROOT'] . "/buy.php";

	if ( copy( $originUrl, $buyURL ) ) {
		add_action( 'admin_notices', 'buyCreated' );
	} else {
		$errors = error_get_last();
		$msg    = $errors['message'];

		add_action( 'admin_notices', function () use ( $msg ) {
			?>
            <div class="notice notice-error is-dismissible">
                <p><?php _e( 'Cant copy file. ' . $msg ); ?></p>
            </div>
			<?php
		} );
	}


}

add_action( "checkPluginData", "checkBuy" );

/**
 * Check for exists of file buy.php
 */
function checkBuy() {
	$buyURL = $_SERVER['DOCUMENT_ROOT'] . "/buy.php";
	if ( ! file_exists( $buyURL ) ) {
		add_action( 'admin_notices', 'buyNotExists' );
	}
}




/**
 * Show message for successfully created BUY.PHP
 */
function buyCreated() {
	?>
    <div class="notice notice-success is-dismissible">
        <p><?php _e( 'buy.php is created successfully!' ); ?></p>
    </div>
	<?php
}

/**
 * Show message for missing BUY.PHP
 */
function buyNotExists() {
	$url = admin_url() . "?_gv=createBuy";
	?>
    <div class="notice notice-error is-dismissible">
        <p><?php _e( 'buy.php is Missing. Click <a href="' . $url . '" >here to create a file</a>' ); ?></p>
    </div>
	<?php
}