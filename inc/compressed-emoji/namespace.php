<?php
/**
 * Figuren_Theater Privacy Compressed_Emoji.
 *
 * @package figuren-theater/ft-privacy
 */

namespace Figuren_Theater\Privacy\Compressed_Emoji;

use FT_VENDOR_DIR;

use Figuren_Theater;
use function Figuren_Theater\get_config;

use function add_action;
use function is_network_admin;
use function is_user_admin;

const BASENAME   = 'compressed-emoji/compressed-emoji.php';
const PLUGINPATH = FT_VENDOR_DIR . '/wpackagist-plugin/' . BASENAME;

/**
 * Bootstrap module, when enabled.
 */
function bootstrap() {

	add_action( 'plugins_loaded', __NAMESPACE__ . '\\load_plugin' );
}

function load_plugin() {

	$config = Figuren_Theater\get_config()['modules']['privacy'];
	if ( ! $config['compressed-emoji'] )
		return; // early

	require_once PLUGINPATH;
}
