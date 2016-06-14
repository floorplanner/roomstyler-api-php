<?php

  class RoomstylerComponentMethods extends RoomstylerMethodBase {

    public function find($id, $params = []) {
      return RoomstylerRequest::send('RoomstylerComponent', "components/$id", $params);
    }

  }

?>
