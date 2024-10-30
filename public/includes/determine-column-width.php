<?php

// Still a work in progress
function determine_column_width( $width_arr = 0 ) {
  return $width_arr || 100 / ( count( $column_title_arr ) - count( $width_arr ) );
}
