<?php
  require 'api/rs_api.php';

  # anonymous access
  $anonymous_api = new RoomstylerApi();

  # anonymous and user access
  $anonymous_and_user_api = new RoomstylerApi(['user' => ['name' => 'myusername', 'password' => 'mypassword']]);

  # anonymous and whitelabel access
  $anonymous_and_whitelabel_api = new RoomstylerApi(['whitelabel' => ['name' => 'wlname', 'password' => 'wlpass']]);

  # full access
  $godmode_api = new RoomstylerApi(['user' => ['name' => 'myusername', 'password' => 'mypassword'],
                                    'whitelabel' => ['name' => 'wlname', 'password' => 'wlpass']]);
?>
