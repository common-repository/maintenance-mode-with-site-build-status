<?php

/**
 * Provide a public-facing view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    MM_Site_Build_Status
 * @subpackage MM_Site_Build_Status/public/partials
 */

?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->

<?php include_once 'template-parts/header.php'; ?>

  <div class="fullscreen flex-center">

  <?php
    //  locate_template( './templates/mm-basic', true );
    include_once 'templates/mm-basic.php';
  ?>

  </div>

<?php include_once 'template-parts/footer.php'; ?>
