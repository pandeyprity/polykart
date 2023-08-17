<?php
/**
 * The plugin bootstrap file
 *
 * @link              https://github.com/davidtowoju
 * @since             0.1.0
 * @package           Metabase
 *
 * @wordpress-plugin
 * Plugin Name:       Metabase - Post Meta and User Meta Editor
 * Plugin URI:        https://pluginette.com/product/metabase/
 * Description:       Manage post meta and user meta effortlessly. Works with custom post types too.
 * Version:           0.1.10
 * Author:            David Towoju
 * Author URI:        https://pluginette.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       metabase
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Current plugin version.
 */
define( 'METABASE_VERSION', '0.1.10' );
define( 'METABASE_DIR_PATH', plugin_dir_path( __FILE__ ) );
define( 'METABASE_DIR_URL', plugin_dir_url( __FILE__ ) );
define( 'METABASE_BASE', plugin_basename( __FILE__ ) );
define( 'METABASE_VIEWS', METABASE_DIR_PATH . 'resources/views/' );
define( 'METABASE_PARTIALS', METABASE_DIR_PATH . 'views/partials/' );
define( 'METABASE_ASSETS', METABASE_DIR_URL . 'assets/' );
define( 'METABASE_RESOURCES', METABASE_DIR_URL . 'resources/' );


if ( ! file_exists( METABASE_DIR_PATH . '/vendor/autoload.php' ) ) {
	return;
}

require METABASE_DIR_PATH . '/vendor/autoload.php';
require METABASE_DIR_PATH . '/app/Includes/helper.php';

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
$metabase = new Metabase\Core();


/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-metabase-activator.php
 */
register_activation_hook( __FILE__, array( $metabase, 'activate_metabase' ) );

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-metabase-deactivator.php
 */
register_deactivation_hook( __FILE__, array( $metabase, 'deactivate_metabase' ) );


/**
 * Begins execution of the plugin.
 *
 * @since    0.1.0
 */
$metabase->register();