<?php

  class RoomstylerMaterialMethods extends RoomstylerMethodBase {

    public function find($id, $params = []) {
      return RoomstylerRequest::send($this, 'RoomstylerMaterial', "materials/$id", $params);
    }

  }

?>
