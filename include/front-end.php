<?php
/**
 * Created by PhpStorm.
 * User: ifkoo
 * Date: 27.8.2018 г.
 * Time: 15:28
 */

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

	//exclude pages;
	if (is_page()) {
		return $text;
	}

	for ( $i = 0; $i < count( $list ); $i ++ ) {
		if ( $list[ $i ]["auto"] == 1 ) {
			$text .= stripslashes( $gv[ $list[ $i ]["name"] ] );
		}
	}

	foreach ( $gv as $var => $val ) {
		$text = str_replace( "[gv_{$var}]", stripslashes( $val ), $text );
	}

	return $text;
}