<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    MM_Site_Build_Status
 * @subpackage MM_Site_Build_Status/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    MM_Site_Build_Status
 * @subpackage MM_Site_Build_Status/public
 * @author     Your Name <email@example.com>
 */
class MM_Site_Build_Status_Public {

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
	 * @param      string    $mm_site_build_status       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */

	public function __construct( $mm_site_build_status, $version ) {

		$this->mm_site_build_status = $mm_site_build_status;
		$this->version = $version;
		$this->load_custom_includes();

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
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

		 if ( MM_Site_Build_Status_Maintenance_Mode::is_maintenance_mode() ) {

			 // Styles array located in /includes/static-values.php
			 $styles = get_maintenance_mode_styles();

			 foreach ( $styles as $style ) {
				 if ( !array_key_exists( 'deps', $style ) ) {
					 $style['deps'] = array();
				 }
				 if ( !array_key_exists( 'in_footer', $style ) ) {
					 $style['in_footer'] = 'all';
				 }

				 wp_enqueue_style( $style['handle'], $style['src'], $style['deps'], $this->version, $style['in_footer'] );
			 }
	 	}

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
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

		 if ( MM_Site_Build_Status_Maintenance_Mode::is_maintenance_mode() ) {
			wp_register_script( $this->mm_site_build_status, plugin_dir_url( __FILE__ ) . 'js/mm-site-build-status-public.js', array( 'jquery' ), $this->version, false );

				// Create an instance of MM_Site_Build_Status_General_Settings
			$mm_site_build_status_general_settings = new MM_Site_Build_Status_General_Settings;

			// Localize setting names for frontend
			$general_settings_class_vars = get_class_vars( get_class( $mm_site_build_status_general_settings ) );
			wp_localize_script( $this->mm_site_build_status, 'site_build_stage_names', $general_settings_class_vars );

			wp_enqueue_script( $this->mm_site_build_status );
		}

	}

	public function load_custom_includes() {

		/**
		 * Loads custom PHP functionality
		 */

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/includes/helper-functions.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/includes/determine-column-width.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/includes/determine-status-icon.php';
	}

}
