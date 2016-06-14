<?php

  ini_set('display_errors', 1);
  error_reporting(E_ALL);
  date_default_timezone_set('CET');

  # development logging
  function pp() {
    foreach (func_get_args() as $arg) {
      echo '<pre>';
      print_r($arg);
      echo '</pre>';
    }
  }

  # this file contains the $CONFIG definition and is excluded in .gitignore
  # an example can be found in config.example.php
  require 'config.php';
  require 'api/rs_api.php';

  $api = new RoomstylerApi(['whitelabel' => $CONFIG['whitelabel_credentials'], 'user' => $CONFIG['user_credentials'], 'debug' => true]);
?>
