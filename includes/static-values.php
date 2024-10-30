<?php
function get_maintenance_mode_styles() {
  return array(
    array(
      'handle' => 'mm-font-awesome',
      'src' => 'https://use.fontawesome.com/releases/v5.6.3/css/all.css'
    ),
     array(
       'handle' => 'mm-google-fonts',
       'src' => 'https://fonts.googleapis.com/css?family=Montserrat'
     ),
     array(
       'handle' => MM_SITE_BUILD_STATUS_TEXT,
       'src' => plugin_dir_url( __FILE__ ) . '../public/css/mm-site-build-status-public.css'
     )
   );
}
