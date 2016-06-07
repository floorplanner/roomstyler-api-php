<?php

  class RoomstylerModelBase {

    private $debug;
    private $_cls;
    public $id = NULL;

    public function __construct($row, $debug = false) {
      $this->debug = $debug;
      $this->_cls = get_class($this);
      foreach ($row as $field => $value) $this->$field = $value;
    }

    public function __get($prop) {
      if (property_exists($this, $prop))
        return $this->$prop;
      else
        trigger_error('Call to undefined property '.__CLASS__.'::'.$prop.'()',
                      E_USER_ERROR);
    }
  }

?>
