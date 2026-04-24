<?php
/**
 * Plugin Name: CHF PoMaster
 * Description: Header & Footer Builder for Elementor Free with JSON presets.
 * Plugin URI:  https://github.com/Pomaster-cmd/CHF-PoMaster
 * Version:     0.0.0
 * Author:      PoMaster
 * Text Domain: chf-pomaster
 * Requires at least: 6.5
 * Requires PHP: 7.4
 * Requires Plugins: elementor
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'CHF_POMASTER_VERSION', '0.0.0' );
define( 'CHF_POMASTER_FILE', __FILE__ );
define( 'CHF_POMASTER_PATH', plugin_dir_path( __FILE__ ) );
define( 'CHF_POMASTER_URL', plugin_dir_url( __FILE__ ) );
define( 'CHF_POMASTER_POST_TYPE', 'chfp_template' );

require_once CHF_POMASTER_PATH . 'includes/class-plugin.php';

/**
 * Boot the plugin singleton.
 *
 * @return \CHFPoMaster\Plugin
 */
function chf_pomaster() {
	return \CHFPoMaster\Plugin::instance();
}

add_action( 'plugins_loaded', 'chf_pomaster' );
