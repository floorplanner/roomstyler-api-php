<?php

  class RoomstylerCategoryMethods extends RoomstylerMethodBase {

    protected static function index($params = []) {
      return RoomstylerRequest::send('RoomstylerCategory', 'categories', $params);
    }

  }

?>
