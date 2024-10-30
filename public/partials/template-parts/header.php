<html class="mm-site-build-status-html">
<head>
  <?php do_action( 'wp_head' ); ?>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <?php

    // Create an instance of MM_Site_Build_Status_General_Settings
    $mm_site_build_status_general_settings = new MM_Site_Build_Status_General_Settings();
    $general_settings_class_vars = get_class_vars( get_class( $mm_site_build_status_general_settings ) );

  ?>

</head>

<?php
  $background_image_id = get_option( $general_settings_class_vars['background_image'] );
  $background_image = wp_get_attachment_url( $background_image_id ) ? wp_get_attachment_url( $background_image_id ) : plugins_url( MM_SITE_BUILD_STATUS_SLUG ) . '/admin/images/' . $mm_site_build_status_general_settings->default_background_image;
?>

<body class="mm-site-build-status main-background-image" style="background-image: url(<?php echo $background_image; ?>)">
