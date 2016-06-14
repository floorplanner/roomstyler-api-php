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
  require 'config.php';
  require 'api/rs_api.php';

  $api = new RoomstylerApi(['whitelabel' => $CONFIG['whitelabel_credentials'], 'user' => $CONFIG['user_credentials'], 'debug' => true]);

  # rooms are not scoped on WL based on token, this still has to be manually added
  $var = $api->wl->rooms->index();

  # one should be able to call functions that manipulate or get more data / relational data
  pp($var);

  pp($api->rooms->index());
?>
