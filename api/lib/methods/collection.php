<?php

  class RoomstylerCollectionMethods extends RoomstylerMethodBase {

    public function index($params = []) {
      return RoomstylerRequest::send('RoomstylerCollection', "collections", $params);
    }

    public function find($id, $params = []) {
      return RoomstylerRequest::send('RoomstylerCollection', "collections/{$id}", $params);
    }

  }

?>
