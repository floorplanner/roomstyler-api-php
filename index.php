<?php

  ini_set('display_errors', 1);
  error_reporting(E_ALL);

  # this file contains the $CONFIG definition and is excluded in .gitignore
  require 'config.php';

  require 'helpers.php';
  require 'api/rs_api.php';

  $api = new RoomstylerApi(['username' => $CONFIG['RS_USER_USERNAME'], 'password' => $CONFIG['RS_USER_PASSWORD'], 'debug' => false]);

  pp($api->current_user());

  $time = time();
  // $response = $api->wl->users->create(['username' => "rs_test_$time", 'email' => "rs_test_$time@testing.com", 'password' => 'my awesome password']);
  // $response = $api->wl->rooms->find(14013530)['result']->comment("$time :: This is an comment created through the API");
  // $response = $api->wl->rooms->comment(14013530, "$time :: This is an comment created through the API");
  // $room = $api->wl->rooms->find(14013530)['result'];
  // pp($room->comments());
  // pp($room->comment("testing"));
  // pp($room->comments());
  // pp($response);

  // if ($response->successful()) pp('Your account has been created! Check your email to activate');
  // else pp($response->errors());

  # test for domain appending and scoping through http basic auth
  // $rooms = $api->rooms->index(['limit' => 10]);
  // $rooms2 = $api->wl->rooms->index(['limit' => 10]);
  // pp($rooms['request_info']->headers('request'), $rooms2['request_info']->headers('request'));
  // pp($rooms, $rooms2);

?>
