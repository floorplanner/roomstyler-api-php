<?php

  class RoomstylerCategoryMethods extends RoomstylerMethodBase {

    public function index($params = []) {
      return (new RoomstylerRequest($this->_settings, $this->_whitelabeled))->send('RoomstylerCategory', 'categories', $params);
    }

  }

?>
