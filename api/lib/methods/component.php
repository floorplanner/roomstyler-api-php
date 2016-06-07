<?php

  class RoomstylerComponentMethods extends RoomstylerMethodBase {

    protected static function find($id, $params = []) {
      return RoomstylerRequest::send('RoomstylerComponent', "components/$id", $params);
    }

  }

?>
