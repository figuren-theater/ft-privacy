<?php
/**
 * Figuren_Theater Admin_UI Embed_Privacy.
 *
 * @package figuren-theater/admin_ui/embed_privacy
 */

namespace Figuren_Theater\Admin_UI\Embed_Privacy;

use Figuren_Theater\Options;

use epiphyt\Embed_Privacy as Embed_Privacy_Plugin;

use function add_action;
use function add_filter;
use function remove_filter;
use function remove_submenu_page;

/**
 * Register module.
function register() {
	Altis\register_module(
		'admin_ui',
		DIRECTORY,
		'Admin_UI',
		[
			'defaults' => [
				'enabled' => true,
			],
		],
		__NAMESPACE__ . '\\bootstrap'
	);
}
 */
const BASENAME = 'embed-privacy/embed-privacy.php';

/**
 * Bootstrap module, when enabled.
 */
function bootstrap() {

	add_action( 'Figuren_Theater\loaded', __NAMESPACE__ . '\\filter_options', 11 );
	
	add_action( 'plugins_loaded', __NAMESPACE__ . '\\load_plugin' );
	
}

function load_plugin() {

	require_once FT_VENDOR_DIR . '/' . BASENAME;
	
	// Remove plugins menu
	add_action( 'admin_menu', __NAMESPACE__ . '\\remove_menu', 11 );

	// Add defer attribute to script tag
	add_filter( 'script_loader_tag', __NAMESPACE__ . '\\defer_frontend_js', 0, 3 );

	//
	add_action( 'Figuren_Theater\Network\Setup\insert_first_content', __NAMESPACE__ . '\\activation' );

	// if we set the shortcode attr 'headline' to empty values, 
	// empty html-tags are still rendered
	add_filter( 'embed_privacy_opt_out_headline', '__return_false' );
	// if we set the shortcode attr 'subline' to empty values, 
	// empty html-tags are still rendered
	add_filter( 'embed_privacy_opt_out_subline', '__return_false' );

}


function filter_options() {
		
		$_options = [

			// BEWARE, this is not the plugin-version,
			// but a $version var in class-migration.php,
			// that could be slightly different from the main plugin version.
			// 
			// set to sth. higher than 1.2.0, where 30+ 'epi_embed' posts are created
			// 'embed_privacy_migrate_version' => '1.4.0', // plugin version 1.4.4 
			
			// only used for custom triggered migration-routine, 
			// normal calls onto this option 
			// are prevented by the falsish state 
			// of  'is_migrating 
			'embed_privacy_migrate_version' => 'initial', 

			// prevents this from beeing added on every admin_init-call
			// THIS PREVENTS 5 DB queries
			// and reduces admin load-time from 0.20 s to 0.02s
			// because of forbidden "read AND write"
			// 
			// but this needs to have the initatial setup done 
			// on site setup
			'embed_privacy_is_migrating' => 1, // !!!!

			//
			'embed_privacy_javascript_detection' => 'yes',
			'embed_privacy_local_tweets' => 'yes',
			'embed_privacy_preserve_data_on_uninstall' => '', // empty string by default
		];

		new Options\Factory( 
			$_options, 
			'Figuren_Theater\Options\Option', 
			BASENAME, 
		);
	

		// BEWARE, this is not the plugin-version,
		// but a $version var in class-migration.php,
		// that could be slightly different from the main plugin version.
		// 
		// set to sth. higher than 1.2.0, where 30+ 'epi_embed' posts are created
		// 'embed_privacy_migrate_version' => '1.4.0', // plugin version 1.4.4 
		
		// only used for custom triggered migration-routine, 
		// normal calls onto this option 
		// are prevented by the falsish state 
		// of  'is_migrating 
		new Options\Option(
			'embed_privacy_migrate_version',
			'initial',
			BASENAME,
			'site_option'
		);


	}

function remove_menu() : void {
	remove_submenu_page( 'options-general.php', 'embed_privacy' );
}


/**
 * Add defer attribute to script tag
 *
 * Renders the plugin frontend script with added defer attribute,
 * to prevent render blocking.
 *
 * @version    2022-10-24
 * @author     Carsten Bach
 *
 * @uses       script_loader_tag  Filters the HTML script tag of an enqueued script.
 *
 * @param      string $tag        The `<script>` tag for the enqueued script.
 * @param      string $handle     The script's registered handle.
 * @param      string $src        The script's source URL.
 * 
 * @return     string $tag        The `<script defer>` tag for the enqueued script.
 */
function defer_frontend_js( string $tag, string $handle, string $src ) : string {

	// if not our script, do nothing and return original $tag
	if ( 'embed-privacy' !== $handle )
		return $tag;

	// if this is alrady done, do nothing and return original $tag
	if ( strpos( $tag, 'defer' ) || strpos( $tag, 'async' ) )
		return $tag;
	
	$tag = str_replace( '></script>', ' defer></script>', $tag );
	return $tag;
}


/**
 * Creates ra. 37 'epi_embed' posts
 *
 * Normally done on every !!! admin page-request.
 *
 * @version 2022.06.10
 * @author  Carsten Bach
 *
 */
function activation() {

	if ( ! class_exists('Embed_Privacy_Plugin\\Migration'))
		return; // early

	$_option_name   = 'embed_privacy_is_migrating';
	$_option_filter = 'pre_option_' . $_option_name;

	add_filter( $_option_filter, '__return_zero', 1037 );

	// $epi_option = \Figuren_Theater\API::get('Options')->get( "option_{$_option_name}" );
	// deactivate option to allow 
	// the migration to start
	// $epi_option->set_value( '0' );
// \do_action( 'qm/debug', \get_option( $_option_name ) );
	
	// Start the regular migration, 
	// usually a post creation routine for 'epi_embed' posts
	// 
	// this is only possible, as long as 
	// 'migrate_version' is sth. different from the real version-number
	$ep_migrate = new Embed_Privacy_Plugin\Migration;
	$ep_migrate->migrate();

	// reset back to before-state
	// $epi_option->set_value( '1' );
// \do_action( 'qm/debug', \get_option( $_option_name ) );

	remove_filter( $_option_filter, '__return_zero', 1037 );
}

