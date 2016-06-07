<?php

  class RoomstylerUserMethods extends RoomstylerMethodBase {

    protected static function find($ids) {
      if (is_array($ids)) {
        $url = 'users/' . implode($ids, ',');
      } else {
        $url = "users/$ids";
      }

      return RoomstylerRequest::send('RoomstylerUser', $url);
    }

  }

?>
