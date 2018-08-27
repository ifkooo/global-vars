<?php
/**
 * Created by PhpStorm.
 *
 * Redirecting to URL from Global Var options
 * 
 * User: ifkooo
 * Date: 27.8.2018 г.
 * Time: 15:33
 */

require_once( dirname( __FILE__ ) . '/wp-load.php' );

$gv = get_option( 'gv', null );
$gv = json_decode( $gv, true );

header('location: '.$gv["real_url"]);

?>