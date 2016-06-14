<?php

  class RoomstylerCategoryMethods extends RoomstylerMethodBase {

    public function index($params = []) {
      return RoomstylerRequest::send('RoomstylerCategory', 'categories', $params);
    }

  }

?>
