<?php

  class RoomstylerCategoryMethods extends RoomstylerMethodBase {

    public function index($params = []) {
      return RoomstylerRequest::send($this, 'RoomstylerCategory', 'categories', $params);
    }

  }

?>
