<?php

  class RoomstylerComponentMethods extends RoomstylerMethodBase {

    public function find($id, $params = []) {
      return (new RoomstylerRequest($this->_settings, $this->_whitelabeled))->send('RoomstylerComponent', "components/$id", $params);
    }

  }

?>
