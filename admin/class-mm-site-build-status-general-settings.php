<?php

class MM_Site_Build_Status_General_Settings {

  /**
   * Class Variables
   * NOTE: Variables must be declared here to be accessible in the public view
   * and in JavaScript
   */
  var $define_site_build_stages = 'define_site_build_stages';
  var $on_off = 'on_off';
  var $current_live_site = 'current_live_site';
  var $client_name = 'client_name';
  var $client_logo = 'client_logo';
  var $background_image = 'background_image';
  var $admin_nonce_key = '_ajax_nonce';
  var $mm_preview_image_nonce = 'mm_preview_image_nonce';
  var $default_background_image = 'stock-image-1.jpg';

  // Coded so that the $progress_states array can be dynamically generated and users
  // can define their own progress states
  var $progress_states = array(
    array(
      'value' => 'not-started',
      'name' => 'Not Started',
      'tooltip' => 'This stage has not been started.'
    ),
    array(
      'value' => 'in-progress',
      'name' => 'In Progress',
      'tooltip' => 'This stage is in progress.'
    ),
    array(
      'value' => 'completed',
      'name' => 'Completed',
      'tooltip' => 'This stage has been completed.'
    ),
    array(
      'value' => 'waiting-on-client',
      'name' => 'Waiting On Client',
      'tooltip' => 'We are waiting on something from the client before we start this stage.'
    )
  );

  public function __construct() {

  }

  /*--------------------------------------------------------------
  ## Getter Functions
  --------------------------------------------------------------*/

  public function get_mm_preview_image_nonce() {
    return $this->mm_preview_image_nonce;
  }

  public function get_admin_nonce_key() {
    return $this->admin_nonce_key;
  }

  /*--------------------------------------------------------------
  ## Helper Functions
  --------------------------------------------------------------*/

  public function create_mm_preview_image_nonce() {
    return wp_create_nonce( $this->mm_preview_image_nonce );
  }

  public function check_nonce( $nonce, $nonce_key = null ) {
    if ( $nonce_key === null ) {
      $nonce_key = $this->get_admin_nonce_key();
    }
    if ( !check_ajax_referer( $nonce, $nonce_key ) ) {
      wp_send_json_error( 'Invalid security token sent.' );
      wp_die( 'Invalid nonce token used.' );
    }
  }

  /*--------------------------------------------------------------
  ## Register Settings
  --------------------------------------------------------------*/

  public function mm_general_settings() {
    $menu_slug  = 'maintenance-mode-site-build-status';
    $section = 'mm-general-options';
    $page = 'mm-general-options';
    $title = __( 'General Options', MM_SITE_BUILD_STATUS_TEXT );
    add_settings_section( $section, $title, array( $this, 'mm_general_options' ), $menu_slug );

    //register_setting($option_group, $option_name, $args = array());
    $option_group = 'mm-settings-general';
    register_setting( $option_group, $this->on_off );
    register_setting( $option_group, $this->client_name, array( $this, 'mm_client_name_validation' ) );
    register_setting( $option_group, $this->client_logo );
    register_setting( $option_group, $this->define_site_build_stages, array( $this, 'mm_define_site_build_stages_validation' ) );
    register_setting( $option_group, $this->current_live_site, array( $this, 'mm_current_live_site_validation' ) );
    register_setting( $option_group, $this->background_image );

    //add_settings_field( $id, $title, $callback, $page, $section, $args );
    add_settings_field( $this->on_off, __( 'On/Off', MM_SITE_BUILD_STATUS_TEXT ), array( $this, 'mm_on_off' ), $menu_slug, $page );
    add_settings_field( $this->client_name, __( 'Client Name', MM_SITE_BUILD_STATUS_TEXT ), array( $this, 'mm_client_name' ), $menu_slug, $page );
    add_settings_field( $this->client_logo, __( 'Logo', MM_SITE_BUILD_STATUS_TEXT ), array( $this, 'mm_client_logo' ), $menu_slug, $page );
    add_settings_field( $this->define_site_build_stages, __( 'Define Site Build Stages', MM_SITE_BUILD_STATUS_TEXT ), array( $this, 'mm_define_site_build_stages' ), $menu_slug, $page );
    add_settings_field( $this->current_live_site, __( 'Current Live Site', MM_SITE_BUILD_STATUS_TEXT ), array( $this, 'mm_current_live_site' ), $menu_slug, $page );
    add_settings_field( $this->background_image, __( 'Background Image', MM_SITE_BUILD_STATUS_TEXT ), array( $this, 'mm_background_image' ), $menu_slug, $page );
  }

  public function mm_general_options() {
    echo __( 'Customize your general settings.', MM_SITE_BUILD_STATUS_TEXT );
  }

  /*--------------------------------------------------------------
  ## On/Off
  --------------------------------------------------------------*/

  public function mm_on_off() {
    $options = get_option( $this->on_off );
    $checked = ( @$options == 1 ? 'checked' : '' );
    echo '<label><input type="checkbox" name="' . $this->on_off . '" value="1" '.$checked.' /> </label>';
  }

  /*--------------------------------------------------------------
  ## Client Name
  --------------------------------------------------------------*/

  public function mm_client_name() {
    $client_name = get_option( $this->client_name );
    echo '<label>
    <input type="text" value="' . $client_name . '" name="' . $this->client_name . '" />
    </label>';
  }

  public function mm_client_name_validation( $client_name ) {
    return wp_strip_all_tags( $client_name );
  }

  /*--------------------------------------------------------------
  ## Client Logo
  --------------------------------------------------------------*/

  // Source: https://wordpress.stackexchange.com/questions/235406/how-do-i-select-an-image-from-media-library-in-my-plugin
  public function mm_client_logo() {
    $image_id = get_option( $this->client_logo );
    $default_image_slug = 'default-client-logo.png';
    $preview_image_dimensions = 'medium';

    if ( intval( $image_id ) > 0 ) { // Loads image if option is selected
      $image = $this->mm_get_preview_image( $image_id, $preview_image_dimensions );
    } else { // Default image
      // $image = $this->mm_default_preview_image( $default_image_slug, $preview_image_dimensions );
      $image = '<img width="200" height="122" class="mm-preview-image" src="" alt="Logo" />';
    }

    if ( $image ) {
      echo $image;
    } else {
      echo '<img width="200" height="122" class="mm-preview-image" src="" alt="Logo" />';
      // echo $this->mm_default_preview_image( $default_image_slug, $preview_image_dimensions );
    }

     echo $this->mm_hidden_input_image_value( $this->client_logo, $image_id );
     echo $this->mm_select_an_image_button( 'thumbnail' );
  }

  /*--------------------------------------------------------------
  ## Site Build Stages
  --------------------------------------------------------------*/

  public function mm_define_site_build_stages() {
    $options = get_option( $this->define_site_build_stages );

    if ( !is_array( $options ) ) {
      $options = array( $options );
    }

    echo '<div class="define-site-build-stages-container">';

    foreach ( $options as $key => $option ) {
      echo '<div class="site-build-stage">';
      echo $this->mm_define_site_build_stages_filled_input( $option['name'], $key );
      echo $this->mm_define_site_build_progress( $option['progress'], $key );
      echo $this->mm_define_site_build_stage_link( $option['link'], $key );
      echo $this->mm_define_site_build_stages_remove_button();
      echo '</div>';
    }

    echo $this->mm_define_site_build_stages_empty_input();
    echo '<button class="add-site-build-stage button button-primary">' . __( 'Add Stage', MM_SITE_BUILD_STATUS_TEXT ) . '</button>';
    echo '</div>';
  }

  public function mm_define_site_build_stages_empty_input() {
    // This HTML is sent to JavaScript and used to create new inputs when "Add Stage" is clicked
    return '<div class="site-build-stage">
        <label>
          <input class="site-build-stage__name" type="text" value="" placeholder="Enter text then click the button to create a stage." />
        </label>
        </div>';
  }

  public function mm_define_site_build_stages_remove_button() {
    // This HTML is sent to JavaScript and used to create a new remove button when "Add Stage" is clicked
    return '<span class="remove-site-build-stage">
      <i class="fas fa-times"></i>
      </span>';
  }

  public function mm_define_site_build_stages_filled_input( $option, $key ) {
      return '
        <label>
          <input class="site-build-stage__name" type="text" name="' . $this->define_site_build_stages . '[' . $key . '][name]" value="' . $option . '"/>
        </label>';
  }

  public function mm_define_site_build_stages_validation( $site_build_stages ) {
    // Create output array
    $output = array();

    // If a site build stage input is empty do not save to output array
    foreach ( $site_build_stages as $site_build_stage ) {
      // Strip tags from name field
      $site_build_stage['name'] = wp_strip_all_tags( $site_build_stage['name'] );

      // Validate Site Build Stage Link
      if ( !empty( $site_build_stage['link'] ) ) {

        $regex = '/^(http:\/\/www\.|https:\/\/www\.|http:\/\/|https:\/\/)?[a-z0-9]+([\-\.]{1}[a-z0-9]+)*\.[a-z]{2,5}(:[0-9]{1,5})?(\/.*)?$/';

        // Return an error message if not a valid URL and input was not empty
        if ( !preg_match( $regex, $site_build_stage['link'] ) ) {
          add_settings_error( 'Site Build Stage Link', $this->define_site_build_stages, '"' . $site_build_stage['link'] . '" is not a valid url. Please check for any typos.' );
          $site_build_stage['link'] = '';
        } else {
          $site_build_stage['link'] = wp_strip_all_tags( add_scheme( $site_build_stage['link'] ) );
        }

      }

      // If input wasn't empty and the name field has a value after sanitization
      if ( !empty( $site_build_stage ) && !empty( $site_build_stage['name'] ) ) {
        $output[] = $site_build_stage;
      }
    }

    // Return output array
    return $output;
  }

  /*--------------------------------------------------------------
  ## Site Build Link
  --------------------------------------------------------------*/

  public function mm_define_site_build_stage_link( $option = '', $key = '' ) {
    return '
      <label>
        <input placeholder="Optional URL (https://drive.google.com/drive/)" type="text" name="' . $this->define_site_build_stages . '[' . $key . '][link]" value="' . $option . '"/>
      </label>';
  }

  /*--------------------------------------------------------------
  ## Site Build Progress
  --------------------------------------------------------------*/

  public function mm_define_site_build_progress( $option = 'pending', $key = '' ) {

    // Coded so that the $progress_states array can be dynamically generated and users can define their own progress states
    $progress_states = $this->progress_states;

      $output = '<select name="' . $this->define_site_build_stages . '[' . $key . '][progress]" value="' . $option . '" selected="in-progress">';

      foreach( $progress_states as $progress_state ) {
        $selected = $progress_state['value'] == $option ? ' selected' : '';

        $output .= '<option value="' . $progress_state['value'] . '"' . $selected . '>' . $progress_state['name'] . '</option>';
      }

      $output .= '</select>';
      return $output;
  }

  /*--------------------------------------------------------------
  ## Current Live Site
  --------------------------------------------------------------*/

  public function mm_current_live_site() {
    $current_live_site = get_option( $this->current_live_site );
    echo '<label>
      <input type="text" value="' . $current_live_site . '" name="' . $this->current_live_site . '" placeholder="' . __( 'https://www.nameofcurrentlivesite.com', MM_SITE_BUILD_STATUS_TEXT ) . '" />
      </label>';
  }

  public function mm_current_live_site_validation( $current_live_site ) {
    if ( $current_live_site == '' ) {
      return $current_live_site;
    }

    // Return an error message if not a valid URL and input was not empty
    if ( !is_valid_url( $current_live_site ) && !empty( $current_live_site ) ) {
      add_settings_error( 'Current Live Site', $this->current_live_site, 'Please enter a valid URL');
      return false;
    } else {
      return wp_strip_all_tags( add_scheme( $current_live_site ) );
    }
  }

  /*--------------------------------------------------------------
  ## Background Image
  --------------------------------------------------------------*/

  // Source: https://wordpress.stackexchange.com/questions/235406/how-do-i-select-an-image-from-media-library-in-my-plugin
  public function mm_background_image() {
    $image_id = get_option( $this->background_image );
    $default_image_slug = $this->default_background_image;
    $preview_image_dimensions = 'medium';

    if ( intval( $image_id ) > 0 ) { // Loads image if option is selected
      $image = $this->mm_get_preview_image( $image_id, $preview_image_dimensions );
    } else { // Default image
      $image = $this->mm_default_preview_image( $default_image_slug, $preview_image_dimensions );
      // echo '<img width="200" height="122" class="mm-preview-image" src="" alt="Logo" />';
    }

    if ( $image ) {
      echo $image;
    } else {
      // echo '<img width="200" height="122" class="mm-preview-image" src="" alt="Logo" />';
      echo $this->mm_default_preview_image( $default_image_slug, $preview_image_dimensions );
    }
     echo $this->mm_hidden_input_image_value( $this->background_image, $image_id );
     echo $this->mm_select_an_image_button();
  }

  /*--------------------------------------------------------------
  ## Misc Image
  --------------------------------------------------------------*/

  // Ajax action to refresh the user image
  public function mm_get_image() {
    // Checks nonce, issues error if fails
    $this->check_nonce( $this->get_mm_preview_image_nonce() );

    if( isset( $_GET['id'] ) && current_user_can( 'activate_plugins' ) ) {
      $size = sanitize_text_field( $_GET['size'] );

      $image = wp_get_attachment_image( filter_input( INPUT_GET, 'id', FILTER_VALIDATE_INT ), $size, false, array( 'class' => 'mm-preview-image' ) );
      $data = array(
        'image'    => $image
      );
      wp_send_json_success( $data );
    } else {
      wp_send_json_error();
    }
  }

  // Select an Image button
  public function mm_select_an_image_button( $size = 'medium' ) {
    return '<input type="button" class="button-primary mm_media_manager" data-size="' . $size . '" value="' . __( 'Select an image', MM_SITE_BUILD_STATUS_TEXT ) . '"/>';
  }

  public function mm_hidden_input_image_value( $name, $image_id ) {
    return '<input type="hidden" name="' . $name . '" value="' . $image_id . '" class="regular-text" />';
  }

  public function mm_default_preview_image( $slug = 'stock-image-1.jpg', $dimension_arr = array( '200px', '300px' ) ) {
    return '<img class="mm-preview-image" width="' . $dimension_arr[0] . '" height="' . $dimension_arr[1] . '" src="' . plugin_dir_url( __FILE__ ) . 'images/' . $slug . '" />';
  }

  public function mm_get_preview_image( $image_id, $size_arr = 'medium' ) {
    return wp_get_attachment_image( $image_id, $size_arr, false, array( 'class' => 'mm-preview-image', 'alt' => 'Logo' ) );
  }

}
