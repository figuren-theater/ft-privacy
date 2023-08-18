<?php
/**
 * Figuren_Theater Privacy WPTT_WebFont_Loader.
 *
 * @package figuren-theater/ft-privacy
 */

namespace Figuren_Theater\Privacy\WPTT_WebFont_Loader;

// use FT_VENDOR_DIR;

use function add_action;
use function add_filter;
use function is_network_admin;
use function is_user_admin;


/**
 * Bootstrap module, when enabled.
 */
function bootstrap() {

	add_action( 'plugins_loaded', __NAMESPACE__ . '\\load_plugin' );
}

function load_plugin() {

	// Do only load in "normal" admin view
	// Not for:
	// - network-admin views
	// - user-admin views
	if ( is_network_admin() || is_user_admin() )
		return;

	add_filter( 'style_loader_tag', __NAMESPACE__ . '\\rereference_google_fonts', 10, 4 );
}


function rereference_google_fonts( string $tag, string $handle, string $href, string $media ) : string {

	if ( ! strpos( $href, 'google') && ! strpos( $href, 'fonts.gstatic') )
		return $tag;

	// load original class
	require_once  'wptt-webfont-loader/wptt-webfont-loader.php';

	// load slightly modified version
	// which comes without croned deletion
	// of the fonts folder
	require_once  'class.FT_WPTT_WebFont_Loader.php';

	// Whoop whoop
	//
	// some cleanup on the url strings,
	// in case we have multiple urls
	// to have them well-formed like in
	// https://github.com/WPTT/webfont-loader#build-url-for-multiple-fonts
	//
	// bad examples were in:
	// - pacer
	// - tove
	//
	$corrected_href = str_replace( '&#038;family', '&family', $href );

	$new_font_css = get_webfont_url( $corrected_href );

	//
	$tag = str_replace( $href, $new_font_css, $tag );

	//
	return $tag;
}
