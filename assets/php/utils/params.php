<?php
  $exec_get = isset($get_params);
  $exec_post = isset($post_params);

  if($exec_get) {
    foreach($get_params as $get_param) {
      if(!isset($_GET[$get_param])) {
        $exec_get = false;
      }
    }
  }
  if($exec_post) {
    foreach($post_params as $post_param) {
      if(!isset($_POST[$post_param])) {
        $exec_post = false;
      }
    }
  }
