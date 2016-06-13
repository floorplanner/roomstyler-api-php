<?php

  ini_set('display_errors', 1);
  error_reporting(E_ALL);
  date_default_timezone_set('CET');

  # this file contains the $CONFIG definition and is excluded in .gitignore
  require 'config.php';

  require 'helpers.php';
  require 'api/rs_api.php';

  $api = new RoomstylerApi(['whitelabel' => $CONFIG['whitelabel_credentials'], 'user' => $CONFIG['user_credentials'], 'debug' => true]);

  # rooms are not scoped on WL based on token, this still has to be manually added
  $room = $api->wl->rooms->find(14075591)['result'];

  # chown will only work if no `user` is supplied (e.g. no token is sent in the request)
  # pp($room->chown(1350462));

?>
