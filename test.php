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

  require 'index.php';

  pp('ROOM SEARCH META', $api->rooms->search_meta());
  pp('ROOM INDEX', $api->rooms->index(['limit' => 5]));
  pp('ROOM INDEX WL', $api->wl->rooms->index(['limit' => 5]));
  pp('ROOM FIND', $api->rooms->find(715833));
  pp('ROOM SEARCH', $api->rooms->search(['q' => 'test', 'limit' => 5]));

?>
