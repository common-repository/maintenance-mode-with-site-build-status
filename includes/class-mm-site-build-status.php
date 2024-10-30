<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    MM_Site_Build_Status
 * @subpackage MM_Site_Build_Status/includes
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
 * @package    MM_Site_Build_Status
 * @subpackage MM_Site_Build_Status/includes
 * @author     Your Name <email@example.com>
 */
class MM_Site_Build_Status {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      MM_Site_Build_Status_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $mm_site_build_status    The string used to uniquely identify this plugin.
	 */
	protected $mm_site_build_status;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
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
		if ( defined( 'MM_SITE_BUILD_STATUS_VERSION' ) ) {
			$this->version = MM_SITE_BUILD_STATUS_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->mm_site_build_status = 'mm-site-build-status';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - MM_Site_Build_Status_Loader. Orchestrates the hooks of the plugin.
	 * - MM_Site_Build_Status_i18n. Defines internationalization functionality.
	 * - MM_Site_Build_Status_Admin. Defines all hooks for the admin area.
	 * - MM_Site_Build_Status_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * Static Values
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/static-values.php';

		/**
		 * Helper functions
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/helper-functions.php';

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-mm-site-build-status-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-mm-site-build-status-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-mm-site-build-status-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-mm-site-build-status-public.php';

		/**
		 * The class responsible for determining the maintenance mode logic
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-mm-site-build-status-maintenance-mode.php';

		/**
		 * The class responsible for the maintenance mode general options
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-mm-site-build-status-general-settings.php';

		/**
		 * The class responsible for clearing caches
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-mm-site-build-status-clear-cache.php';

		$this->loader = new MM_Site_Build_Status_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the MM_Site_Build_Status_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new MM_Site_Build_Status_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new MM_Site_Build_Status_Admin( $this->get_mm_site_build_status(), $this->get_version() );
		$general_settings = new MM_Site_Build_Status_General_Settings( $this->get_mm_site_build_status(), $this->get_version() );
		$clear_cache = new MM_Site_Build_Status_Clear_Cache( $this->get_mm_site_build_status(), $this->get_version() );

		// Plugin Admin
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'load_admin_menu' );

		$plugin_basename = plugin_basename( plugin_dir_path( __DIR__ ) . $this->mm_site_build_status . '.php' );
		$this->loader->add_filter( 'plugin_action_links_' . $plugin_basename, $plugin_admin, 'add_settings_link_to_plugin_menu' );

		// General Settings
		$this->loader->add_action( 'admin_init', $general_settings, 'mm_general_settings' );
		$this->loader->add_action( 'wp_ajax_mm_get_image', $general_settings, 'mm_get_image' );

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new MM_Site_Build_Status_Public( $this->get_mm_site_build_status(), $this->get_version() );
		$maintenance_mode = new MM_Site_Build_Status_Maintenance_Mode( $this->get_mm_site_build_status(), $this->get_version() );

		// Plugin Public
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles', 9999999999999 );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts', 9999999999999 );

		// Maintenance Mode
		$this->loader->add_action( 'wp_enqueue_scripts', $maintenance_mode, 'deregister_scripts_and_styles', 99999999999999 );
		$this->loader->add_filter( 'template_include', $maintenance_mode, 'redirect_to_maintenance_mode' );

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
	public function get_mm_site_build_status() {
		return $this->mm_site_build_status;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    MM_Site_Build_Status_Loader    Orchestrates the hooks of the plugin.
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
