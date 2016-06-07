<?php

  class RoomstylerCategoryMethods extends RoomstylerMethodBase {

    protected static function index($params = []) {
      return ['result' => RoomstylerRequest::send(NULL, 'categories', $params)['body']];
    }

  }

?>
