<?php
/**
 * Figuren_Theater Privacy Embed_Privacy.
 *
 * @package figuren-theater/ft-privacy
 */

namespace Figuren_Theater\Privacy\Embed_Privacy;

use Figuren_Theater\Options;
use Figuren_Theater\Privacy;
use FT_VENDOR_DIR;

use function add_action;
use function add_filter;
use function is_network_admin;
use function remove_submenu_page;
use WPMU_PLUGIN_URL;

const BASENAME   = 'embed-privacy/embed-privacy.php';
const PLUGINPATH = '/wpackagist-plugin/' . BASENAME;

/**
 * Bootstrap module, when enabled.
 *
 * @return void
 */
function bootstrap() :void {

	add_action( 'Figuren_Theater\loaded', __NAMESPACE__ . '\\filter_options', 11 );

	add_action( 'plugins_loaded', __NAMESPACE__ . '\\load_plugin', 0 );
}

/**
 * Conditionally load the plugin itself and its modifications.
 *
 * @return void
 */
function load_plugin() :void {

	if ( is_network_admin() ) {
		return;
	}

	if ( ! defined( 'EPI_EMBED_PRIVACY_BASE' ) ) {
		define( 'EPI_EMBED_PRIVACY_BASE', FT_VENDOR_DIR . '/wpackagist-plugin/embed-privacy/' );
	}

	require_once FT_VENDOR_DIR . PLUGINPATH; // phpcs:ignore WordPressVIPMinimum.Files.IncludingFile.UsingCustomConstant

	// Remove plugins menu.
	add_action( 'admin_menu', __NAMESPACE__ . '\\remove_menu', 11 );

	// Add defer attribute to script tag
	// @todo #21 Use core script registration using 'defer' attribute
	// @see  https://make.wordpress.org/core/2023/07/14/registering-scripts-with-async-and-defer-attributes-in-wordpress-6-3/
	// Was before: add_filter( 'script_loader_tag', __NAMESPACE__ . '\\defer_frontend_js', 0, 3 ); // !
	add_filter( 'Figuren_Theater\Theming\Defer_Async_Loader\scripts_to_defer', __NAMESPACE__ . '\\defer_frontend_js', 0 );

	// If we set the shortcode attr 'headline' to empty values,
	// empty html-tags are still rendered.
	add_filter( 'embed_privacy_opt_out_headline', '__return_false' );
	// If we set the shortcode attr 'subline' to empty values,
	// empty html-tags are still rendered.
	add_filter( 'embed_privacy_opt_out_subline', '__return_false' );

	// The plugin registers its PT on 'init:5'.
	add_filter( 'register_epi_embed_post_type_args', __NAMESPACE__ . '\\disable_export' );

	// Load some additional styles.
	add_action( 'wp_enqueue_scripts', __NAMESPACE__ . '\\enqueue_css_fix' );

}

/**
 * Handle options
 *
 * @return void
 */
function filter_options() :void {

	$_options = [

		// BEWARE, this is not the plugin-version,
		// but a $version var in class-migration.php,
		// that could be slightly different from the main plugin version.
		//
		// set to sth. higher than 1.2.0, where 30+ 'epi_embed' posts are created
		// 'embed_privacy_migrate_version' => '1.4.0', // plugin version 1.4.4
		//
		// only used for custom triggered migration-routine,
		// normal calls onto this option
		// are prevented by the falsish state
		// of  'is_migrating
		// DISABLED 4 being a bad idea! 'embed_privacy_migrate_version' => 'initial',
		//
		// prevents this from beeing added on every admin_init-call
		// THIS PREVENTS 5 DB queries
		// and reduces admin load-time from 0.20 s to 0.02s
		// because of forbidden "read AND write"
		//
		// but this needs to have the initatial setup done
		// on site setup
		// DISABLED 4 being a bad idea! 'embed_privacy_is_migrating' => 1, // !!!!

		'embed_privacy_javascript_detection'       => 'yes',
		'embed_privacy_local_tweets'               => 'yes',
		'embed_privacy_preserve_data_on_uninstall' => '', // An empty string by default.
		'embed_privacy_download_thumbnails'        => 'yes',
		'embed_privacy_disable_link'               => 0,
	];

	/*
	 * Gets added to the 'OptionsCollection'
	 * from within itself on creation.
	 */
	new Options\Factory(
		$_options,
		'Figuren_Theater\Options\Option',
		BASENAME
	);

	// BEWARE, this is not the plugin-version,
	// but a $version var in class-migration.php,
	// that could be slightly different from the main plugin version.
	//
	// set to sth. higher than 1.2.0, where 30+ 'epi_embed' posts are created
	// 'embed_privacy_migrate_version' => '1.4.0', // plugin version 1.4.4
	//
	// only used for custom triggered migration-routine,
	// normal calls onto this option
	// are prevented by the falsish state
	// of  'is_migrating
	//
	// new Options\Option(
	// 'embed_privacy_migrate_version',
	// 'initial',
	// BASENAME,
	// 'site_option'
	// ); // .
}

/**
 * Hide the plugins admin-menu
 *
 * @return void
 */
function remove_menu() :void {
	remove_submenu_page( 'options-general.php', 'embed_privacy' );
}

/**
 * Load 'embed-privacy' JS defered.
 *
 * @todo #21 Use core script registration using 'defer' attribute
 * @see  https://make.wordpress.org/core/2023/07/14/registering-scripts-with-async-and-defer-attributes-in-wordpress-6-3/
 *
 * @param string[] $scripts_to_defer Handles of JS files to enqueue 'defer'ed.
 *
 * @return string[]
 */
function defer_frontend_js( array $scripts_to_defer ) :array {

	$scripts_to_defer[] = 'embed-privacy';

	return $scripts_to_defer;
}

/**
 * Creates ra. 37 'epi_embed' posts
 *
 * Normally done on every !!! admin page-request.
 *
 * add_action( 'Figuren_Theater\Network\Setup\insert_first_content', __NAMESPACE__ . '\\activation' );
 * add_action( 'Figuren_Theater\Onboarding\Sites\Installation\insert_first_content', __NAMESPACE__ . '\\activation' );

 * @deprecated 2023.08.18
 *
 * @version 2022.06.10
 * @author  Carsten Bach

function activation() {

	if ( ! class_exists( 'Embed_Privacy_Plugin\\Migration' ) ) {
		return;
	}

	$_option_name   = 'embed_privacy_is_migrating';
	$_option_filter = 'pre_option_' . $_option_name;

	add_filter( $_option_filter, '__return_zero', 1037 );

	// Deactivate the option to allow
	// the migration to start
	//
	// $epi_option = \Figuren_Theater\API::get('Options')->get( "option_{$_option_name}" );
	// $epi_option->set_value( '0' ); // !

	// Start the regular migration,
	// usually a post creation routine for 'epi_embed' posts
	//
	// this is only possible, as long as
	// 'migrate_version' is sth. different from the real version-number.
	$ep_migrate = new Embed_Privacy_Plugin\Migration;
	$ep_migrate->migrate();

	// Reset back to the before-state and run
	// $epi_option->set_value( '1' ); //.

	remove_filter( $_option_filter, '__return_zero', 1037 );
}
 */

/**
 * Prevent export of 'epi_embed' post_type
 *
 * @param array<string, mixed> $args The register_post_type arguments for the 'epi_embed' post_type.
 *
 * @return array<string, mixed>
 */
function disable_export( array $args ) :array {
	$args['can_export'] = false;
	return $args;
}

/**
 * Enqueue minimal CSS fix
 *
 * @return void
 */
function enqueue_css_fix() :void {
	// Same args used for wp_enqueue_style().
	$args = [
		'handle' => 'embed-privacy-fix',
		'src'    => WPMU_PLUGIN_URL . Privacy\ASSETS_URL . 'embed-privacy/fix.css',
		'deps'   => [ 'embed-privacy' ],
	];

	// Add "path" to allow inlining asset if the theme opts-in.
	$args['path'] = Privacy\DIRECTORY . 'assets/embed-privacy/fix.css';

	// Enqueue asset.
	wp_enqueue_style(
		$args['handle'],
		$args['src'],
		$args['deps'],
		null,
		'screen'
	);
}
