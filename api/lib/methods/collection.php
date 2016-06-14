<?php

  class RoomstylerCollectionMethods extends RoomstylerMethodBase {

    public function index($params = []) {
      return (new RoomstylerRequest($this->_settings, $this->_whitelabeled))->send('RoomstylerCollection', "collections", $params);
    }

    public function find($id, $params = []) {
      return (new RoomstylerRequest($this->_settings, $this->_whitelabeled))->send('RoomstylerCollection', "collections/{$id}", $params);
    }

  }

?>
