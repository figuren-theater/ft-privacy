<?php
/**
 * @package koko-analytics
 * @license GPL-3.0+
 * @author Danny van Kooten
 *
 * This file acts as an optimized endpoint file for the Koko Analytics plugin.
 */

// WordPress site_ID of the current blog
// phpcs:ignore WordPress.Security.NonceVerification.Recommended, WordPress.Security.ValidatedSanitizedInput.InputNotValidated
$site_id = (int) $_GET['c'];

// path to pageviews.php file in uploads directory
define( 'KOKO_ANALYTICS_BUFFER_FILE', __DIR__ . "/uploads/sites/$site_id/pageviews.php" );

// path to src/functions.php in Koko Analytics plugin directory
require dirname( __FILE__, 2 ) . '/vendor/wpackagist-plugin/koko-analytics/src/functions.php';

// function call to collect request data
KokoAnalytics\collect_request();
