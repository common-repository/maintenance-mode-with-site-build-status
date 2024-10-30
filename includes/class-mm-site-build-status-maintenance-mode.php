<?php

class MM_Site_Build_Status_Maintenance_Mode {

  static function is_maintenance_mode() {
    // Source: https://www.isitwp.com/maintenance-mode-without-plug-in/
    // Source: https://markjaquith.wordpress.com/2014/02/19/template_redirect-is-not-for-loading-templates/

    // If maintenance mode is set to on, the user isn't logged in, and the user can't edit themes
    if ( get_option( 'on_off' ) && !is_user_logged_in() && ( !current_user_can( 'edit_themes' ) ) ) {
      return true;
    } else {
      return false;
    }
  }

  public function redirect_to_maintenance_mode( $template ) {
    if ( $this->is_maintenance_mode() ) {
      // replace $template with maintenance mode template
      $template = plugin_dir_path( dirname( __FILE__ ) ) . '/public/partials/mm-site-build-status-public-display.php';
    }
    return $template;
  }

  // https://gelwp.com/articles/removing-all-enqueued-and-default-css-scripts-in-wordpress/
  public function remove_non_maintenance_mode_styles() {

    // get all styles data
  	global $wp_styles;
    $mm_style_handles = array_column( get_maintenance_mode_styles(), 'handle' );

  	// loop over all of the registered scripts
  	foreach ( $wp_styles->registered as $handle => $data ) {
  		// if we want to keep it, skip it
  		if ( in_array( $handle, $mm_style_handles ) ) continue;

  		// otherwise remove it
  		wp_deregister_style( $handle );
  		wp_dequeue_style( $handle );
  	}
  }

  public function deregister_scripts_and_styles() {
    if ( $this->is_maintenance_mode() ) {
      $this->remove_non_maintenance_mode_styles();
      // $this->remove_non_maintenance_mode_scripts();
    }
  }

}
