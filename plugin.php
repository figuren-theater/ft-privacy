<?php
/**
 * Plugin Name:     figuren.theater | Privacy
 * Plugin URI:      https://github.com/figuren-theater/ft-privacy
 * Description:     Privacy first! And this is the code that does it. Curated for the WordPress Multisite figuren.theater
 * Author:          figuren.theater
 * Author URI:      https://figuren.theater
 * Text Domain:     figurentheater
 * Domain Path:     /languages
 * Version:         1.2.2
 *
 * @package         Figuren_Theater\ft-privacy
 */

namespace Figuren_Theater\Privacy;

const DIRECTORY = __DIR__;

add_action( 'altis.modules.init', __NAMESPACE__ . '\\register' );
