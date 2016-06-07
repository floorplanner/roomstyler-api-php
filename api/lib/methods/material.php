<?php

  class RoomstylerMaterialMethods extends RoomstylerMethodBase {

    protected static function find($id, $params = []) {
      return RoomstylerRequest::send('RoomstylerMaterial', "materials/$id", $params);
    }

  }

?>
