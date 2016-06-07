<?php

  class RoomstylerCategoryMethods extends RoomstylerMethodBase {

    protected static function index($_ = NULL, $params = []) {
      if (is_array($_)) $params = $_;
      return RoomstylerRequest::send('RoomstylerCategory', 'categories', $params);
    }

  }

?>
