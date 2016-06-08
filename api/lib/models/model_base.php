<?php

  class RoomstylerModelBase {

    private $_cls;
    private $_fields_set = false;
    public $id = NULL;

    public function __construct($row) {
      $this->_cls = get_class($this);
      if ($row && is_array($row)) {
        foreach ($row as $field => $value) $this->$field = $value;
        $this->_fields_set = true;
      }
    }

    public function __get($prop) {
      if (property_exists($this, $prop))
        return $this->$prop;
      else
        trigger_error('Call to undefined property '.__CLASS__.'::'.$prop.'()',
                      E_USER_ERROR);
    }

    public function exists() {
      return $this->_fields_set;
    }
  }

?>
