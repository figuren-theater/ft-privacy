<?php
/**
 * Figuren_Theater Privacy Surbma_GDPR_Multisite_Privacy.
 *
 * @package figuren-theater/privacy/surbma_gdpr_multisite_privacy
 */

namespace Figuren_Theater\Privacy\Surbma_GDPR_Multisite_Privacy;

use FT_VENDOR_DIR;

use function add_action;

const BASENAME   = 'surbma-gdpr-multisite-privacy/surbma-gdpr-multisite-privacy.php';
const PLUGINPATH = FT_VENDOR_DIR . '/wpackagist-plugin/' . BASENAME;

/**
 * Bootstrap module, when enabled.
 */
function bootstrap() {

	add_action( 'plugins_loaded', __NAMESPACE__ . '\\load_plugin' );
}

function load_plugin() {
	require_once PLUGINPATH;
}
