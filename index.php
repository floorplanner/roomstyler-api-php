<?php

  ini_set('display_errors', 1);
  error_reporting(E_ALL);
  date_default_timezone_set('CET');

  # development logging
  function pp() {
    foreach (func_get_args() as $arg) {
      echo '<pre>';
      print_r($arg);
      echo '</pre>';
    }
  }

  # this file contains the $CONFIG definition and is excluded in .gitignore
  require 'config.php';
  require 'api/rs_api.php';

  $api = new RoomstylerApi(['whitelabel' => $CONFIG['whitelabel_credentials'], 'user' => $CONFIG['user_credentials'], 'debug' => true]);

  # rooms are not scoped on WL based on token, this still has to be manually added
  $room = $api->wl->rooms->find(14075591)['result'];

  # one should be able to call functions that manipulate or get more data / relational data
  pp($room->comment('test'));

  # but one shouldn't be able to call a global index function from a specific object
  # even tho this isn't too bad, it should probably be fixed.

  # suggestions for this are moving the methods that you are allowed to call onto the model
  # then removing the magic call_with_obj_params() method and editing call to return NULL for any unset property

  # this way, if a property doesn't exist no error is thrown but NULL is returned (falsy)
  # this will allow us to never explicitly have to update the back-end with new properties etc
  
  # downside is that there will also be no exceptions or 'undefined property' errors at all which might be dramatic
  # depending on how one checks for these properties

  # following that, all callable methods from the ...Methods object that should be callable on the model
  # will be moved to the model.
  pp($room->index());

?>
