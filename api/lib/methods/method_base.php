<?php

  class RoomstylerMethodBase extends RoomstylerBase {

    protected $_settings = [];
    protected $_whitelabeled = false;

    public function __construct(array $settings, $whitelabeled) {
      $this->_settings = $settings;
      $this->_whitelabeled = $whitelabeled;
    }

  }

?>
