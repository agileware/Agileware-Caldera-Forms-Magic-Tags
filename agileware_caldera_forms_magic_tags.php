<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://agileware.com.au/
 * @since             1.0.0
 * @package           Agileware_caldera_forms_magic_tags
 *
 * @wordpress-plugin
 * Plugin Name:       Agileware Caldera Forms Magic Tags
 * Plugin URI:        https://agileware.com.au/
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.1
 * Author:            Agileware
 * Author URI:        https://agileware.com.au/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       agileware_caldera_forms_magic_tags
 * Domain Path:       /languages
 * GitHub Plugin URI: agileware/Aglieware-Caldera-Forms-Magic-Tags
 * GitHub Plugin URI: https://github.com/agileware/Aglieware-Caldera-Forms-Magic-Tags
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

// Check Caldera forms
function agileware_caldera_forms_magic_tags_child_plugin_has_parent_plugin() {
	if ( defined( 'CFCORE_VER' ) && version_compare( CFCORE_VER, '1.5.0.10', '>=' ) ) {
		return true;
	} else {
		deactivate_plugins( plugin_basename( __FILE__ ) );
		add_action( 'admin_head', 'agileware_caldera_forms_magic_tags_hide_update_msg' );
		add_action( 'admin_notices', 'agileware_caldera_forms_magic_tags_child_plugin_notice' );
	}
}

function agileware_caldera_forms_magic_tags_hide_update_msg() {
	if ( is_admin() ) {
		echo '<style>.update-nag, .updated { display: none; }</style>';
	}
}

function agileware_caldera_forms_magic_tags_child_plugin_notice() {
	?>
    <div class="error"><p>Sorry, this Plugin requires the Caldera Form plugin to be installed and active.</p></div><?php

}

add_action( 'admin_init', 'agileware_caldera_forms_magic_tags_child_plugin_has_parent_plugin' );

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'AGILEWARE_CALDERA_FORMS_MAGIC_TAGS_VERSION', '1.0.1' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-agileware_caldera_forms_magic_tags-activator.php
 */
function activate_agileware_caldera_forms_magic_tags() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-agileware_caldera_forms_magic_tags-activator.php';
	Agileware_caldera_forms_magic_tags_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-agileware_caldera_forms_magic_tags-deactivator.php
 */
function deactivate_agileware_caldera_forms_magic_tags() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-agileware_caldera_forms_magic_tags-deactivator.php';
	Agileware_caldera_forms_magic_tags_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_agileware_caldera_forms_magic_tags' );
register_deactivation_hook( __FILE__, 'deactivate_agileware_caldera_forms_magic_tags' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-agileware_caldera_forms_magic_tags.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_agileware_caldera_forms_magic_tags() {

	$plugin = new Agileware_caldera_forms_magic_tags();
	$plugin->run();

}

run_agileware_caldera_forms_magic_tags();
