<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * Dashboard. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://wensolutions.com
 * @since             1.0.0
 * @package           WEN_Logo_Slider
 *
 * @wordpress-plugin
 * Plugin Name:       WEN Logo Slider
 * Plugin URI:        https://wordpress.org/plugins/wen-logo-slider/
 * Description:       Responsive logo slider for WordPress sites.
 * Version:           3.4.0
 * Author:            WEN Solutions
 * Author URI:        http://wensolutions.com/
 * Requires at least: 4.4
 * Requires PHP:      5.6.2
 * Tested up to:      6.6
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wen-logo-slider
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}


// Define
define( 'WEN_LOGO_SLIDER_NAME', 'WEN Logo Slider' );
define( 'WEN_LOGO_SLIDER_VERSION', '3.3.0' );
define( 'WEN_LOGO_SLIDER_SLUG', 'wen-logo-slider' );
define( 'WEN_LOGO_SLIDER_FILE_BASE', __FILE__ );
define( 'WEN_LOGO_SLIDER_BASENAME', basename( dirname( __FILE__ ) ) );
define( 'WEN_LOGO_SLIDER_DIR', rtrim( plugin_dir_path( __FILE__ ), '/' ) );
define( 'WEN_LOGO_SLIDER_URL', rtrim( plugin_dir_url( __FILE__ ), '/' ) );
define( 'WEN_LOGO_SLIDER_POST_TYPE_LOGO_SLIDER', 'logo_slider' );


/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-wen-logo-slider-activator.php
 */
function activate_wen_logo_slider() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wen-logo-slider-activator.php';
	WEN_Logo_Slider_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-wen-logo-slider-deactivator.php
 */
function deactivate_wen_logo_slider() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wen-logo-slider-deactivator.php';
	WEN_Logo_Slider_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_wen_logo_slider' );
register_deactivation_hook( __FILE__, 'deactivate_wen_logo_slider' );

/**
 * The core plugin class that is used to define internationalization,
 * dashboard-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-wen-logo-slider.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_wen_logo_slider() {

	$plugin = new WEN_Logo_Slider();
	$plugin->run();

}
run_wen_logo_slider();
