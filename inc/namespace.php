<?php
/**
 * Figuren_Theater Privacy.
 *
 * @package figuren-theater/privacy
 */

namespace Figuren_Theater\Privacy;

use Altis;

/**
 * Register module.
 */
function register() {
	$default_settings = [
		'enabled' => true, // needs to be set
		'koko-analytics' => true,
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
 */
function bootstrap() {

	Embed_Privacy\bootstrap();
	Koko_Analytics\bootstrap();
}
