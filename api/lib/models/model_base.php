<?php

  class RoomstylerModelBase extends RoomstylerBase {

    private $_fields_set = false;
    private $_errors = [];
    private $_http_status = 0;
    private $id = NULL;

    public function __construct($row, $errors = [], $status = 0) {
      parent::__construct();
      $this->_errors = $errors;
      $this->_http_status = $status;
      if (is_object($row)) $row = get_object_vars($row);
      if (is_array($row)) {
        foreach ($row as $field => $value) $this->$field = $value;
        $this->_fields_set = true;
      }
    }

    public function __call($method, $arguments) {
      return $this->call_with_context($method, $arguments, $this);
    }

    public function __get($prop) {
      if (property_exists($this, $prop)) return $this->$prop;
      else trigger_error('Call to undefined property '.__CLASS__.'::'.$prop, E_USER_ERROR);
    }

    public function successful() {
      return empty($this->_errors) && $this->_http_status < 400;
    }

    public function errors() {
      return $this->_errors;
    }

    public function exists() {
      return $this->_fields_set;
    }

  }

?>
