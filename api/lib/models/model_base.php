<?php

  class RoomstylerModelBase extends RoomstylerBase {

    private $http_status_descriptions = [
      '200' => 'OK',
      '201' => 'Created',
      '302' => 'Found',
      '403' => 'Forbidden',
      '404' => 'Not found',
      '422' => 'Unprocessable entity',
      '500' => 'Internal server error'
    ];

    private $_fields_set = false;
    private $_errors = [];
    private $_http_status = 0;
    private $_accessible_props = [];
    protected $_settings = [];
    protected $_whitelabeled = false;

    public $id = NULL;

    public function __construct($row, $settings, $whitelabeled, $errors = [], $status = 0, $parent_attrs = false) {
      $this->_errors = new RoomstylerError(is_array($errors) ? $errors : []);
      $this->_http_status = $status;
      $this->_settings = $settings;
      $this->_whitelabeled = $whitelabeled;

      # if an object is returned, convert it to a key-value array containing properties and values
      if (is_object($row)) $row = get_object_vars($row);
      if (is_array($row)) {
        # setting parent properties on child if needed
        if (is_array($parent_attrs)) $row = array_merge($row, $parent_attrs);
        foreach ($row as $field => $value) {
          $this->$field = $value;
          $this->_accessible_props[] = $field;
        }
        if (count($this->_accessible_props) > 0) $this->_fields_set = true;
      }
    }

    public function __get($prop) {
      # If a property is set dynamically, allow access - otherwise return NULL
      # this is done in case the API changes in such a way that if a property is no longer available, NULL will be returned as a fallback instead of a Notice: error
      if (in_array($prop, $this->_accessible_props)) return $this->$prop;
      return NULL;
    }

    public function successful() {
      return $this->_http_status < 300 && empty($this->_errors);
    }

    public function errors() {
      return $this->_errors;
    }

    public function exists() {
      return $this->_http_status < 400 && $this->_fields_set;
    }

  }

?>
