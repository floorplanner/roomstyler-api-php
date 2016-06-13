<?php

  class RoomstylerModelBase extends RoomstylerBase {

    private $_fields_set = false;
    private $_errors = [];
    private $_http_status = 0;
    private $_mtds = NULL;

    public $id = NULL;

    public function __construct($mtds, $row, $errors = [], $status = 0) {
      parent::__construct();
      $this->_mtds = $mtds;
      $this->_errors = $errors;
      $this->_http_status = $status;
      if (is_object($row)) $row = get_object_vars($row);
      if (is_array($row)) {
        foreach ($row as $field => $value) $this->$field = $value;
        $this->_fields_set = true;
      }
    }

    public function __call($method, $args = []) {
      if (property_exists($this, $method)) return $this->$method;
      # if a function is called whose property isn't defined, look in the
      # respective Roomstyler{obj}Methods object for another API call to execute.
      if (method_exists($this->_mtds, $method)) return $this->call_with_obj_params($this->_mtds, $method, $args);
    }

    public function call_with_obj_params($obj, $method, $args) {
      $method_params = parent::method_params($obj, $method);
      if ($method_params[0] == 'id') $args = array_unshift($args, $this->id());
      return call_user_func_array([$obj, $method], $args);
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
