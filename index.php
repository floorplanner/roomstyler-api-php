<?php
  ini_set('display_errors', 1);
  error_reporting(E_ALL);
  date_default_timezone_set('CET');
  
  require 'config.php';
  require 'api/rs_api.php';

  $api = new RoomstylerApi(['user' => $CONFIG['user_credentials']]);
  $user = $api->user->find(972691);
?>
