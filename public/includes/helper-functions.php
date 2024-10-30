<?php

function format_titles( $value ) {
  return ucwords( preg_replace('/\-+/', ' ', $value) );
}

function convert_to_class_name( $value ) {
  return strtolower( preg_replace('/\_+|\s/', '-', $value) );
}
