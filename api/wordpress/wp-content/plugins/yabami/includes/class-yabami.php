<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://yabaiwebyasan.com/
 * @since      1.0.0
 *
 * @package    Yabami
 * @subpackage Yabami/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Yabami
 * @subpackage Yabami/includes
 * @author     Yabaiwebyasan <yabaiwebyasan@gmail.com>
 */
class Yabami {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Yabami_Loader $loader Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string $plugin_name The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string $version The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'PLUGIN_NAME_VERSION' ) ) {
			$this->version = PLUGIN_NAME_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'yabami';

		$this->load_dependencies();
		$this->set_locale();
		add_action( 'rest_api_init', function () {

			// For CORS
			remove_filter( 'rest_pre_serve_request', 'rest_send_cors_headers' );
			add_filter( 'rest_pre_serve_request', function ( $value ) {
				$origin        = get_http_origin();
				$arrow_origins = ENV === 'dev' ? array(
					'http://localhost:3000',
					'http://localhost:5000',
					site_url()
				) : array(
					site_url(),
					'https://snsanalytics-ef74a.firebaseapp.com'
				);
				if ( $origin && in_array( $origin, $arrow_origins ) ) {
					header( 'Access-Control-Allow-Origin: ' . esc_url_raw( $origin ) );
					header( 'Access-Control-Allow-Methods: POST, GET, OPTIONS, PUT, DELETE' );
					header( 'Access-Control-Allow-Credentials: true' );
				}

				return $value;
			} );

			$user = new Yabami_Rest_User_Controller();
			$user->register_endpoints();
		} );
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Yabami_Loader. Orchestrates the hooks of the plugin.
	 * - Yabami_i18n. Defines internationalization functionality.
	 * - Yabami_Admin. Defines all hooks for the admin area.
	 * - Yabami_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/lib/vendor/autoload.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/models/class-yabami-model.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/models/class-yabami-model-user.php';

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/rest-api/class-yabami-rest-controller.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/rest-api/endpoints/class-yabami-rest-user-controller.php';

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-yabami-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-yabami-i18n.php';

		$this->loader = new Yabami_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Yabami_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Yabami_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Yabami_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}
