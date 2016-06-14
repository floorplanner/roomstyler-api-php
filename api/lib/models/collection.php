<?php

  class RoomstylerCollection extends RoomstylerModelBase {

    public function items($params = []) {
      return RoomstylerRequest::send('RoomstylerCollectionItem', "collections/{$this->id}/items", $params);
    }

  }

?>
