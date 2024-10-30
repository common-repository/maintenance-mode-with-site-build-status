<?php

// Source: https://stackoverflow.com/questions/2762061/how-to-add-http-if-it-doesnt-exists-in-the-url#answer-14701491
function add_scheme( $url, $scheme = 'https://' ) {
  return parse_url( $url, PHP_URL_SCHEME ) === null ? $scheme . $url : $url;
}

function is_valid_url( $url ) {
  $regex = '/^(http:\/\/www\.|https:\/\/www\.|http:\/\/|https:\/\/)?[a-z0-9]+([\-\.]{1}[a-z0-9]+)*\.[a-z]{2,5}(:[0-9]{1,5})?(\/.*)?$/';
  return 1 === preg_match( $regex, $url );
}
