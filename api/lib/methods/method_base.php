<?php

  class RoomstylerMethodBase {

    private $_debug;

    public function __construct($debug = false) {
      $this->_debug = $debug;
      $this->_cls = get_class($this);
    }

    public function __call($method, $args) {
      if (method_exists($this, $method)) {
        $req = call_user_func("$this->_cls::$method", $args);
        if ($this->_debug == true) return $req;
        return $req['result'];
      } else
        trigger_error('Call to undefined method '.__CLASS__.'::'.$method.'()',
                      E_USER_ERROR);
    }

  }

?>
