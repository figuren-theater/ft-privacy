<?php
/**
 * Figuren_Theater Privacy Compressed_Emoji.
 *
 * @package figuren-theater/ft-privacy
 */

namespace Figuren_Theater\Privacy\Compressed_Emoji;

use Figuren_Theater;

use FT_VENDOR_DIR;
use function add_action;

const BASENAME   = 'compressed-emoji/compressed-emoji.php';
const PLUGINPATH = '/wpackagist-plugin/' . BASENAME;

/**
 * Bootstrap module, when enabled.
 *
 * @return void
 */
function bootstrap(): void {

	add_action( 'plugins_loaded', __NAMESPACE__ . '\\load_plugin' );
}

/**
 * Conditionally load the plugin itself and its modifications.
 *
 * @return void
 */
function load_plugin(): void {

	$config = Figuren_Theater\get_config()['modules']['privacy'];
	if ( ! $config['compressed-emoji'] ) {
		return;
	}

	require_once FT_VENDOR_DIR . PLUGINPATH; // phpcs:ignore WordPressVIPMinimum.Files.IncludingFile.UsingCustomConstant
}
