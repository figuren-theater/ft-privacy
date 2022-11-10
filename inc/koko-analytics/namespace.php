<?php
/**
 * Figuren_Theater Privacy Koko_Analytics.
 *
 * @package figuren-theater/privacy/koko_analytics
 */

namespace Figuren_Theater\Privacy\Koko_Analytics;

use FT_VENDOR_DIR;

use Figuren_Theater\Options;

use function Figuren_Theater\get_config;

use function add_action;
use function add_filter;
use function get_role;
use function remove_all_actions;
use function remove_submenu_page;

const BASENAME   = 'koko-analytics/koko-analytics.php';
const PLUGINPATH = FT_VENDOR_DIR . '/wpackagist-plugin/' . BASENAME;

/**
 * Bootstrap module, when enabled.
 */
function bootstrap() {

	add_action( 'Figuren_Theater\loaded', __NAMESPACE__ . '\\filter_options', 11 );
	
	add_action( 'plugins_loaded', __NAMESPACE__ . '\\load_plugin' );
}

function load_plugin() {

	$config = Figuren_Theater\get_config()['modules']['privacy'];
	if ( ! $config['koko-analytics'] )
		return; // early

	require_once PLUGINPATH;

	add_action( 'admin_menu', __NAMESPACE__ . '\\change_menu_title', 20 );

	add_action( 'admin_head-dashboard_page_koko-analytics', __NAMESPACE__ . '\\cleanup_admin_ui' );
	
	add_action( 'wp_dashboard_setup', __NAMESPACE__ . '\\change_meta_box_title', 11 );
}


function filter_options() {
	
	$_options = [
		'use_cookie'              => 0,
		'exclude_user_roles'      => 
		[
			'administrator',
			'editor',
			'author',
			'contributor',
		],
		'prune_data_after_months' => 36,
		'default_view'            => 'last_28_days',
	];

	new Options\Option(
		'koko_analytics_settings',
		$_options,
		BASENAME,
	);
}

function change_menu_title(){
	global $submenu;

	// 
	if (isset($submenu['index.php'][6][0]) && 'Analytics' === $submenu['index.php'][6][0])
		$submenu['index.php'][6][0] = __('Zugriffe','figurentheater');
}

function change_meta_box_title() {

	global $wp_meta_boxes;

	$post_type  = 'dashboard'; // our screen->ID
	$context    = 'side';
	$priority   = 'high';
	$id         = 'koko-analytics-dashboard-widget';

	if (isset($wp_meta_boxes[$post_type][$context][$priority][$id]['title']))
		$wp_meta_boxes[$post_type][$context][$priority][$id]['title'] = __('Usage statistics - GDPR compliant','figurentheater');

}


function cleanup_admin_ui() : void {

	update_needed_roles();

	add_action( 'admin_footer_text', function(){
		// doesnt work
		// $_koko = new KokoAnalytics\Admin;
		// \remove_action( 'admin_footer_text', array( $_koko, 'footer_text' ) );
		// works !
		remove_all_actions( 'admin_footer_text', 10 );
	}, 0 );


	#if ( \current_user_can( 'manage_sites' ) )
	#	return;

	echo '<style>
		#koko-analytics-admin .two.nav .subsubsub {
			display: none!important;
		}
	</style>';
}

function update_needed_roles() {

	$editor = get_role( 'editor' );
	$administrator = get_role( 'administrator' );
	$editor->add_cap( 'view_koko_analytics' );
	$administrator->add_cap( 'view_koko_analytics' );
}
