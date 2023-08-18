<?php
/**
 * Figuren_Theater Privacy.
 *
 * @package figuren-theater/ft-privacy
 */

namespace Figuren_Theater\Privacy;

use Altis;

/**
 * Register module.
 *
 * @return void
 */
function register() :void {

	$default_settings = [
		'enabled'          => true,  // Needs to be set.
		'compressed-emoji' => false, // Disabled for the planet.
		'koko-analytics'   => false, // Disabled for the planet.
	];

	$options = [
		'defaults' => $default_settings,
	];

	Altis\register_module(
		'privacy',
		DIRECTORY,
		'Privacy',
		$options,
		__NAMESPACE__ . '\\bootstrap'
	);
}

/**
 * Bootstrap module, when enabled.
 *
 * @return void
 */
function bootstrap() :void {

	// Plugins.
	Compressed_Emoji\bootstrap();
	Embed_Privacy\bootstrap();
	Koko_Analytics\bootstrap();
	Surbma_GDPR_Multisite_Privacy\bootstrap();

	// Best practices.
	WPTT_WebFont_Loader\bootstrap();
}
