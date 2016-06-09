<?php

  ini_set('display_errors', 1);
  error_reporting(E_ALL);

  # this file contains the $CONFIG definition and is excluded in .gitignore
  require 'config.php';

  require 'helpers.php';
  require 'api/rs_api.php';

  $api = new RoomstylerApi(['whitelabel' => $CONFIG['RS_USER_WL'], 'password' => $CONFIG['RS_USER_PASS'], 'debug' => true]);

  $rooms = $api->rooms->index(['limit' => 10]);

  pp($rooms);

?>
