<?php
/**
 * Download webfonts locally.
 *
 * @package figuren-theater/ft-privacy
 */

namespace Figuren_Theater\Privacy\WPTT_WebFont_Loader;

use WPTT_WebFont_Loader;

/**
 * Download webfonts locally.
 *
 * This modified 'webfont-loader' class disables the croned deletion
 * of the fonts folder to prevent unintended font deletion.
 */
class FT_WPTT_WebFont_Loader extends WPTT_WebFont_Loader {

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

		// Disabled the croned deletion of the fonts folder to prevent unintended font deletion.
		//
		// Add a cleanup routine.
		// $this->schedule_cleanup();
		// add_action( 'delete_fonts_folder', array( $this, 'delete_fonts_folder' ) ); // !
	}
}
