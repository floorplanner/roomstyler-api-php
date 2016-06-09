<?php

  ini_set('display_errors', 1);
  error_reporting(E_ALL);

  # this file contains the $CONFIG definition and is excluded in .gitignore
  require 'config.php';

  require 'helpers.php';
  require 'api/rs_api.php';

  $api = new RoomstylerApi(['whitelabel' => $CONFIG['RS_USER_WL'], 'password' => $CONFIG['RS_USER_PASS'], 'debug' => true]);

  $response = $api->wl->users->create(['username' => 'sidofc_test_0798576', 'email' => 'sidofc_test_0798576@testing.com', 'password' => 'my awesome password']);

  pp($response);

  // if ($response->successful()) pp('Your account has been created! Check your email to activate');
  // else pp($response->errors());

  # test for domain appending and scoping through http basic auth
  // $rooms = $api->rooms->index(['limit' => 10, 'skip_total' => true, 'skip_last_updated' => true]);
  // $rooms2 = $api->wl->rooms->index(['limit' => 10, 'skip_total' => true, 'skip_last_updated' => true]);
  // pp($rooms['request_info']->headers('request'), $rooms2['request_info']->headers('request'));

?>
