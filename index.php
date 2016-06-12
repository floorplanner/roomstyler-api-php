<?php

  ini_set('display_errors', 1);
  error_reporting(E_ALL);

  # this file contains the $CONFIG definition and is excluded in .gitignore
  require 'config.php';

  require 'helpers.php';
  require 'api/rs_api.php';

  $api = new RoomstylerApi(['whitelabel' => $CONFIG['RS_WHITELABEL'], 'password' => $CONFIG['RS_WHITELABEL_PASSWORD'], 'debug' => true]);

  # use custom url in editor
  $url = $api->rooms->find(14013530)['result']->url;

  # use this token in editor
  $token = $api->users->login($CONFIG['RS_USER_USERNAME'], $CONFIG['RS_USER_PASSWORD'])['result']->token;

  # can't seem to use 'room_url' while using whitelabel editor ($api->wl->editor...)

  # embed returns a HTML string which needs to be echoe'd out
  echo $api->editor->embed(['token' => $token, 'room_url' => urlencode($url)]);

?>
