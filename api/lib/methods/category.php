<?php

  class RoomstylerCategoryMethods extends RoomstylerMethodBase {

    protected static function index($_ = NULL, $params = []) {
      if (is_array($_)) $params = $_;
      return ['result' => RoomstylerRequest::send(NULL, 'categories', $params)['body']];
    }

  }

?>
