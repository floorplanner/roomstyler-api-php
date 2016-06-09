<?php

  class RoomstylerMethodBase extends RoomstylerBase {

    private $_debug;
    private $_cls;

    public function __construct() {
      $this->_cls = get_class($this);
    }

    public function __call($method, $args) {
      if (method_exists($this, $method)) return call_user_func_array("$this->_cls::$method", $args);
      else trigger_error('Call to undefined method '.__CLASS__.'::'.$method.'()', E_USER_ERROR);
    }

  }

?>
