<?php

  class RoomstylerModelBase extends RoomstylerBase {

    private $_cls;
    private $_fields_set = false;
    private $_errors = [];
    private $id = NULL;

    public function __construct($row, $errors = []) {
      $this->_errors = $errors;
      $this->_cls = get_class($this);
      if (is_object($row)) $row = get_object_vars($row);
      if (is_array($row)) {
        foreach ($row as $field => $value) $this->$field = $value;
        $this->_fields_set = true;
      }
    }

    public function __get($prop) {
      if (property_exists($this, $prop)) return $this->$prop;
      else trigger_error('Call to undefined property '.__CLASS__.'::'.$prop.'()', E_USER_ERROR);
    }

    public function successful() {
      return empty($this->_errors);
    }

    public function errors() {
      return $this->_errors;
    }

    public function exists() {
      return $this->_fields_set;
    }

  }

?>
