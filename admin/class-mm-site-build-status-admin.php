<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    MM_Site_Build_Status
 * @subpackage MM_Site_Build_Status/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    MM_Site_Build_Status
 * @subpackage MM_Site_Build_Status/admin
 * @author     Your Name <email@example.com>
 */
class MM_Site_Build_Status_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $mm_site_build_status    The ID of this plugin.
	 */
	private $mm_site_build_status;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $mm_site_build_status       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $mm_site_build_status, $version ) {

		$this->mm_site_build_status = $mm_site_build_status;
		$this->mm_admin_url = 'maintenance-mode-site-build-status';
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in MM_Site_Build_Status_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The MM_Site_Build_Status_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		// Font Awesome
		wp_enqueue_style( 'font-awesome', 'https://use.fontawesome.com/releases/v5.6.3/css/all.css', array(), $this->version, 'all' );

		wp_enqueue_style( $this->mm_site_build_status, plugin_dir_url( __FILE__ ) . 'css/mm-site-build-status-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in MM_Site_Build_Status_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The MM_Site_Build_Status_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_media(); // Loads media selector scripts

		wp_enqueue_script( $this->mm_site_build_status, plugin_dir_url( __FILE__ ) . 'js/mm-site-build-status-admin.js', array( 'jquery' ), $this->version, false );
		wp_enqueue_script( $this->mm_site_build_status . '-media-selector', plugin_dir_url( __FILE__ ) . 'js/mm-site-build-status-media-selector.js', array( 'jquery' ), $this->version, false );

    // Create an instance of MM_Site_Build_Status_General_Settings
		$mm_site_build_status_general_settings = new MM_Site_Build_Status_General_Settings;

 		// localize the empty input HTML for JavaScript
    $general_settings_html = array(
      'empty_input' => $mm_site_build_status_general_settings->mm_define_site_build_stages_empty_input(),
      'remove_button' => $mm_site_build_status_general_settings->mm_define_site_build_stages_remove_button(),
      'progress_select' => $mm_site_build_status_general_settings->mm_define_site_build_progress(),
			'link_select' => $mm_site_build_status_general_settings->mm_define_site_build_stage_link()
    );
    wp_localize_script( $this->mm_site_build_status, 'site_build_stage', $general_settings_html );

		// Localize setting names for admin interface
		$general_settings_class_vars = get_class_vars( get_class( $mm_site_build_status_general_settings ) );
    wp_localize_script( $this->mm_site_build_status, 'site_build_stage_names', $general_settings_class_vars );

		// Ajax Nonce: https://developer.wordpress.org/plugins/javascript/ajax/#nonce
		$admin_nonces = array(
			'admin_nonce_key' => $mm_site_build_status_general_settings->get_admin_nonce_key(),
			'mm_preview_image_nonce' => $mm_site_build_status_general_settings->create_mm_preview_image_nonce(),
		);
		wp_localize_script( $this->mm_site_build_status, 'admin_nonces', $admin_nonces );
	}

	// Source: https://codex.wordpress.org/Plugin_API/Filter_Reference/plugin_action_links_(plugin_file_name)
	public function add_settings_link_to_plugin_menu( $links ) {
		$mylinks = array(
			'<a href="' . admin_url( 'admin.php?page=' . $this->mm_admin_url ) . '">' . __( 'Settings', MM_SITE_BUILD_STATUS_TEXT) . '</a>',
		);
		return array_merge( $links, $mylinks );
	}

	// Source: https://blog.idrsolutions.com/2014/06/wordpress-plugin-part-1/
	public function load_admin_menu() {

		/**
		 * Adds the admin menu into WordPress Backend
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in MM_Site_Build_Status_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The MM_Site_Build_Status_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

    // Main Admin Page
    $page_title = __( 'Maintenance Mode with Site Build Status', MM_SITE_BUILD_STATUS_TEXT);
    $menu_title = __( 'Maintenance', MM_SITE_BUILD_STATUS_TEXT);
    $capability = 'manage_options';
    $menu_slug  = 'maintenance-mode-site-build-status';
    $function   = array( $this, 'admin_menu_view' );
    $icon_url   = '';
    $position   = 79;

    add_menu_page( $page_title, $menu_title, $capability, $menu_slug, $function, $icon_url, $position );

    add_submenu_page( $menu_slug, 'General '.$page_title, 'General', $capability, $menu_slug, $function );

	}

	public function admin_menu_view() {

		/**
		 * Provides the admin menu view
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in MM_Site_Build_Status_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The MM_Site_Build_Status_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

	   require_once plugin_dir_path( dirname( __FILE__ ) ) . '/admin/partials/mm-site-build-status-admin-display.php';
	}

}
