<?php
/**
 * Plugin Name: Easy Cookie Consent & Compliance
 * Plugin URI: https://easywptools.com/easy-cookie-manager
 * Description: Manage cookie consent, ensure compliance, and enhance user experience with GDPR, CCPA, and global laws.
 * Version: 1.0.0
 * Author: SH Sajal Chowdhury
 * Author URI: https://easywptools.com
 * Text Domain: easy-cookie-manager
 * Domain Path: /languages
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

// Define constants.
define( 'ECM_VERSION', '1.0.0' );
define( 'ECM_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'ECM_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

// Include autoloader.
require_once ECM_PLUGIN_DIR . 'includes/class-ecm-loader.php';

// Initialize the plugin.
function ecm_init_plugin() {
    $loader = new ECM_Loader();
    $loader->run();
}
add_action( 'plugins_loaded', 'ecm_init_plugin' );

// Include Freemius integration (optional).
// require_once ECM_PLUGIN_DIR . 'includes/class-ecm-freemius.php';
