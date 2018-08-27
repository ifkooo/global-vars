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
			'readme'             => 'README.md',
			// which file to use as the readme for the version number
			'access_token'       => '',
			// Access private repositories by authorizing under Appearance > GitHub Updates when this example plugin is installed
		);
		new WP_GitHub_Updater( $config );

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