<?php

  class RoomstylerMaterialMethods extends RoomstylerMethodBase {

    public function find($id, $params = []) {
      return RoomstylerRequest::send('RoomstylerMaterial', "materials/$id", $params);
    }

  }

?>
