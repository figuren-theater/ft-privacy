<?php
/**
 * Figuren_Theater Privacy WPTT_WebFont_Loader.
 *
 * @package figuren-theater/ft-privacy
 */

namespace Figuren_Theater\Privacy\WPTT_WebFont_Loader;

use function add_action;
use function add_filter;
use function is_network_admin;
use function is_user_admin;

/**
 * Bootstrap module, when enabled.
 *
 * @return void
 */
function bootstrap() :void {

	add_action( 'plugins_loaded', __NAMESPACE__ . '\\load_plugin' );
}

/**
 * Conditionally load the plugin itself and its modifications.
 *
 * @return void
 */
function load_plugin() :void {

	// Do only load in "normal" admin view
	// Not for:
	// - network-admin views
	// - user-admin views.
	if ( is_network_admin() || is_user_admin() ) {
		return;
	}

	add_filter( 'style_loader_tag', __NAMESPACE__ . '\\rereference_google_fonts', 10, 4 );
}

/**
 * Get a stylesheet URL for a webfont.
 *
 * @since 1.1.0
 *
 * @param string $url    The URL of the remote webfont.
 * @param string $format The font-format. If you need to support IE, change this to "woff".
 *
 * @return string Returns the CSS.
 */
function get_webfont_url( $url, $format = 'woff2' ) {
	$font = new FT_WPTT_WebFont_Loader( $url );
	$font->set_font_format( $format );
	return $font->get_url();
}

/**
 * Rereferences Google Fonts URLs to use a local URL for privacy reasons.
 *
 * This function checks if the provided font URL is from Google Fonts or fonts.gstatic.com.
 * If so, it modifies the URL to use a slightly modified webfont loader class to load and save the fonts locally.
 *
 * The modified 'webfont-loader' class disables the croned deletion of the fonts folder to prevent unintended font deletion.
 * It also performs cleanup on URL strings to ensure proper formatting for multiple font URLs.
 *
 * @param string $tag    The HTML tag for the font stylesheet.
 * @param string $handle The script/style handle.
 * @param string $href   The original URL of the font stylesheet.
 * @param string $media  The media attribute of the font stylesheet.
 *
 * @return string The modified HTML tag for the font stylesheet.
 */
function rereference_google_fonts( string $tag, string $handle, string $href, string $media ) :string {
	if ( ! strpos( $href, 'google' ) && ! strpos( $href, 'fonts.gstatic' ) ) {
		return $tag;
	}

	// Load the original webfont loader class.
	require_once __DIR__ . 'wptt-webfont-loader/wptt-webfont-loader.php';

	// Load the modified webfont loader class without croned font deletion.
	require_once __DIR__ . 'class-ft-wptt-webfont-loader.php';

	// Perform cleanup on URL strings to ensure proper formatting.
	//
	// In case we have multiple urls
	// to have them well-formed like in
	// https://github.com/WPTT/webfont-loader#build-url-for-multiple-fonts
	//
	// bad examples were in:
	// - pacer
	// - tove.
	//
	// Replace HTML entity with regular character '&'.
	$corrected_href = str_replace( '&#038;family', '&family', $href );

	// Generate the new font CSS URL using the modified webfont loader.
	$new_font_css = get_webfont_url( $corrected_href );

	// Replace the original font URL with the new font CSS URL in the HTML tag.
	$tag = str_replace( $href, $new_font_css, $tag );

	return $tag;
}

