<?php

  ini_set('display_errors', 1);
  error_reporting(E_ALL);
  date_default_timezone_set('CET');

  # this file contains the $CONFIG definition and is excluded in .gitignore
  require 'config.php';

  require 'helpers.php';
  require 'api/rs_api.php';

  $api = new RoomstylerApi(['whitelabel' => $CONFIG['whitelabel_credentials'], 'user' => $CONFIG['user_credentials'], 'debug' => true]);

  pp($api->current_user());

  $room = $api->rooms->find(14012955);

  pp($room);

  // pp($room['result']->comments());

  $time = strftime('%Y-%m-%d %H:%m', time());
  pp($room['result']->comment($time . ' -> I am awesome!'));

  exit();

  # use this token in editor
  # $token = $api->users->login($CONFIG['RS_USER_USERNAME'], $CONFIG['RS_USER_PASSWORD'])['result']->token();

  # use custom url in editor
  # $room = $api->rooms->find(14012955)['result']->url();

  # embed returns a HTML string which needs to be echoe'd out
  # echo $api->wl->editor->embed(['token' => $token, 'room_url' => $url]);

?>
