<?php
/**
 * The file that defines the core plugin class
 *
 * @link       https://pluginette.com
 * @since      1.0.0
 *
 * @package    Bookslot
 * @subpackage Bookslot/Includes
 */

namespace Metabase;

use Metabase\Includes\i18n;
use Metabase\Includes\Loader;


/**
 * The core plugin class.
 *
 * @since      1.0.0
 * @package    Metabase
 * @subpackage Metabase/Includes
 * @author     David Towoju <hello@pluginette.com>
 */
class Core {

	protected $loader;
	protected $plugin_name;
	protected $version;
	protected $license_manager;


	/**
	 * Define the core functionality of the plugin.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		$this->plugin_name = 'metabase';
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function register() {
		$this->load_dependencies();
		$this->set_locale();
		$this->define_hooks();
		$this->loader->run();
	}

	/**
	 * The code that runs during plugin activation.
	 */
	public function activate_metabase() {
	}

	/**
	 * The code that runs during plugin deactivation.
	 */
	public function deactivate_metabase() {
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {
		$this->loader = new Loader();
	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {
		$plugin_i18n = new i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );
	}

	/**
	 * Register all of the hooks related to the admin & public area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_hooks() {
		$plugin_admin = new Admin( $this->plugin_name, $this->version );
		$this->loader->add_action( 'add_meta_boxes', $plugin_admin, 'register_post_metaboxes', 10 );
		$this->loader->add_action( 'edit_user_profile', $plugin_admin, 'register_user_metaboxes', 2000 );
		$this->loader->add_action( 'show_user_profile', $plugin_admin, 'register_user_metaboxes', 2000 );
		$this->loader->add_action( 'wp_ajax_ajax_edit_meta', $plugin_admin, 'handle_edit', 10 );
	}

}
