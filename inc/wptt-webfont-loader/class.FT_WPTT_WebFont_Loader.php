<?php

namespace Figuren_Theater\Privacy\WPTT_WebFont_Loader;

use WPTT_WebFont_Loader as Original;


/**
 * 
 */
class FT_WPTT_WebFont_Loader extends Original
{

	/**
	 * Constructor.
	 *
	 * Get a new instance of the object for a new URL.
	 *
	 * @access public
	 * @since 1.1.0
	 * @param string $url The remote URL.
	 */
	public function __construct( $url = '' ) {
		$this->remote_url = $url;

		// DISABLED // Add a cleanup routine.
		// $this->schedule_cleanup();
		// add_action( 'delete_fonts_folder', array( $this, 'delete_fonts_folder' ) );
	}

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
