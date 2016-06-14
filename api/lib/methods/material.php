<?php

  class RoomstylerMaterialMethods extends RoomstylerMethodBase {

    public function find($id, $params = []) {
      return (new RoomstylerRequest($this->_settings, $this->_whitelabeled))->send('RoomstylerMaterial', "materials/$id", $params);
    }

  }

?>
