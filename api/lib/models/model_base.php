<?php

  class RoomstylerModelBase {
    private $debug;

    public function __construct($row, $debug = false) {
      $this->debug = $debug;
      foreach ($row as $field => $value) $this->$field = $value;
    }

    public function __call($method, $args) {
      if (property_exists($this, $method))
        return $this->$method;
      else
        trigger_error('Call to undefined method '.__CLASS__.'::'.$method.'()',
                      E_USER_ERROR);
    }
  }

?>
