<?php

  class RoomstylerComponentMethods extends RoomstylerMethodBase {

    public function find($id, $params = []) {
      return RoomstylerRequest::send($this, 'RoomstylerComponent', "components/$id", $params);
    }

  }

?>
