<?php // phpcs:ignore PSR1.Files.SideEffects.FoundWithSymbols
/**
 * This file acts as an optimized endpoint file for the Koko Analytics plugin.
 *
 * @package figuren-theater/ft-privacy
 *
 * @source  koko-analytics
 * @license GPL-3.0+
 * @author Danny van Kooten
 */

// phpcs:ignore WordPress.Security.NonceVerification.Recommended
if ( ! isset( $_GET['c'] ) ) {
	return;
}

// Get the WordPress site_ID of the current blog
// and do a secure 'cast to string'.
//
// phpcs:ignore WordPress.Security.NonceVerification.Recommended
$ft_site_id = filter_var( wp_unslash( $_GET['c'] ), FILTER_SANITIZE_NUMBER_INT );

// Path to pageviews.php file in uploads directory.
define( 'KOKO_ANALYTICS_BUFFER_FILE', __DIR__ . "/uploads/sites/$ft_site_id/pageviews.php" ); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedConstantFound

// Don't make this a global.
unset( $ft_site_id );

// Path to src/functions.php in Koko Analytics plugin directory.
require dirname( __DIR__, 1 ) . '/vendor/wpackagist-plugin/koko-analytics/src/functions.php';

// Function call to collect request data.
KokoAnalytics\collect_request();
