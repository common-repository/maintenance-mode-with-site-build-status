<?php

/**
 * Template Name: mm-basic
 *
 * @package red_underscores
 */

$client_name = get_option( $general_settings_class_vars['client_name'] );
// var_dump($general_settings_class_vars);
$client_name_output = $client_name ? __( 'Site Build Status for ', MM_SITE_BUILD_STATUS_TEXT ) . $client_name : __( 'Site Build Status', MM_SITE_BUILD_STATUS_TEXT );
$client_logo_id = get_option( $general_settings_class_vars['client_logo'] );
$client_logo = wp_get_attachment_url( $client_logo_id );
?>

<div class="fullscreen-modal fullscreen-modal-skin">

  <div class="fullscreen-modal-content-container">

    <?php if ( !empty( $client_logo ) ) { ?>

      <img class="client-logo" alt="Logo" src="<?php echo $client_logo ?>" />

    <?php } ?>

    <h1 class="page-title-heading"><?php echo $client_name_output; ?></h1>

    <div class="sbs-table">

      <?php
        $site_build_stages = get_option( $general_settings_class_vars['define_site_build_stages'] );
        $current_live_site = get_option( $general_settings_class_vars['current_live_site'] );

        // Add new titles to this array (can link to WP Database later too if needed)
        $column_title_arr = array(
          array(
          'title_display' => __( 'Status', MM_SITE_BUILD_STATUS_TEXT ),
          'title_slug' => 'status',
          'custom_classes' => 'status',
          'column_width' => '20%'
        ),
          array(
            'title_display' => __( 'Stage', MM_SITE_BUILD_STATUS_TEXT ),
            'title_slug' => 'stage',
            'custom_classes' => 'stage',
            'column_width' => '80%'
          )
        );

        $default_column_width = 100 / count( $column_title_arr );

        // If there are site build stages specified display table
        if ( !empty( $site_build_stages ) ) {

          // Table title row
          echo '<div class="sbs-table__column-title-container sbs-table__row">';

          // Table title columns
          foreach( $column_title_arr as $title ) {
            echo '<div class="sbs-table__column-title sbs-table__column-title-skin ' . trim( $title['custom_classes'] ) . '" style="flex: 0 1 ' . $title['column_width'] . '"><h4>' . $title['title_display'] . '</h4></div>';
          }

          echo '</div>';

          // NEED TO FIND WAY OF GETTING TITLE TO CELLS (for determining column width and to dynamically generate class names)
          // Possible Solution: Maybe rename values in database
          // Possible Solution: Create a function that maps title values to data values

          // Table content row generator. Generates with information from site_build_stages
          foreach( $site_build_stages as $site_build_stage ) {
            $status_icon = determine_status_icon( $site_build_stage['progress'], $general_settings_class_vars['progress_states'] );

            echo '<div class="sbs-table__column-cell-container sbs-table__row">';
              echo '<div class="sbs-table__column-cell status" style="flex: 0 1 ' . $column_title_arr[0]['column_width'] . '">' . assign_status_icon( $status_icon ) . '</div>';

              if ( !empty( $site_build_stage['link'] ) ) {
                echo '<a target="_blank" href="' . $site_build_stage['link'] . '">';
              }

                echo '<div class="sbs-table__column-cell stage" style="flex: 0 1 ' . $column_title_arr[1]['column_width'] . '">' . $site_build_stage['name'] . '</div>';

              if ( !empty( $site_build_stage['link'] ) ) {
                echo '</a>';
              }

            echo '</div>';
          }

        }

      ?>

    </div>

    <?php if ( is_valid_url( $current_live_site ) ) { ?>

      <!-- Link to previous or temporary website -->
      <a class="current-live-site-url" target="_blank" href="<?php echo $current_live_site; ?>"><?php _e( 'Click here to visit the current website', MM_SITE_BUILD_STATUS_TEXT ) ?></a>

    <?php } ?>

  </div>

</div>
