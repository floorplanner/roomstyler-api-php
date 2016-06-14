<?php

  class RoomstylerCollection extends RoomstylerModelBase {

    public function items($params = []) {
      return (new RoomstylerRequest($this->_settings, $this->_whitelabeled))->send('RoomstylerCollectionItem', "collections/{$this->id}/items", $params);
    }

  }

?>
