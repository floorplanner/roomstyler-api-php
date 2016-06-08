<?php

  ini_set('display_errors', 1);
  error_reporting(E_ALL);

  require 'user.php';
  require 'helpers.php';
  require 'api/rs_api.php';

  $api = new RoomstylerApi(['whitelabel' => $CONFIG['RS_USER_WL'], 'password' => $CONFIG['RS_USER_PASS'], 'debug' => true]);

  $rooms = $api->rooms->index(['limit' => 10]);

  pp($rooms);

?>
