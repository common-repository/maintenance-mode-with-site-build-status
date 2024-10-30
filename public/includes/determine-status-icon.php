<?php

function determine_status_icon( $selected_progress_state, $progress_states ) {
  $key = array_search( $selected_progress_state, array_column( $progress_states, 'value' ) );
  return $progress_states[$key];
}

function assign_status_icon( $status_icon ) {
  switch( $status_icon['value'] ) {
    case 'in-progress':
      $icon = 'fas fa-spinner';
      break;
    case 'waiting-on-client':
      $icon = 'fas fa-user-clock';
      break;
    case 'completed':
      $icon = 'fas fa-check';
      break;
    case 'not-started':
      $icon = 'fab fa-expeditedssl';
      break;
  }

  return '<div class="tooltip">
            <i class="' . $icon . '"></i>
            <span class="tooltip__text">' . $status_icon['tooltip'] . '</span>
          </div>';
}
