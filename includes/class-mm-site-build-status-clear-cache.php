<?php

// $pantheon_cache = new Pantheon_Cache();

class MM_Site_Build_Status_Clear_Cache {

  public function __construct() {
    // Class Variables
  }

  public function clear_cache() {
    $this->clear_pantheon_cache();
    wp_cache_flush();
    echo 'Cache cleared';
  }

  public function clear_pantheon_cache() {
    if ( function_exists( 'pantheon_clear_edge_all' ) ) {
      pantheon_clear_edge_all();
    }
  }

}
