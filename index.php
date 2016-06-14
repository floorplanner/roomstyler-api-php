<?php
  # for examples, see the test.php file in the root directory

  # this file contains the $CONFIG definition and is excluded in .gitignore
  # an example can be found in config.example.php
  require 'config.php';
  require 'api/rs_api.php';

  $api = new RoomstylerApi(['whitelabel' => $CONFIG['whitelabel_credentials'], 'user' => $CONFIG['user_credentials'], 'debug' => false]);
?>
