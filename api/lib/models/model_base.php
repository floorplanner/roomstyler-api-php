<?php

  class RoomstylerModelBase extends RoomstylerBase {

    private $_fields_set = false;
    private $_errors = [];
    private $_http_status = 0;
    protected $_settings = [];
    protected $_whitelabeled = false;

    public $id = NULL;

    public function __construct($row, $settings, $whitelabeled, $errors = [], $status = 0, $parent_attrs = false) {
      $this->_errors = $errors;
      $this->_http_status = $status;
      $this->_settings = $settings;
      $this->_whitelabeled = $whitelabeled;

      # if an object is returned, convert it to a key-value array containing properties and values
      if (is_object($row)) $row = get_object_vars($row);
      if (is_array($row)) {
        # setting parent properties on child if needed
        if (is_array($parent_attrs)) $row = array_merge($row, $parent_attrs);
        foreach ($row as $field => $value) $this->$field = $value;
        $this->_fields_set = true;
      }
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
