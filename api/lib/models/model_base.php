<?php

  class RoomstylerModelBase extends RoomstylerBase {

    private $_http_status = 0;
    private $_accessible_props = ['errors'];

    private $errors;

    protected $_settings = [];
    protected $_whitelabeled = false;

    public function __construct($row, $settings, $whitelabeled, RoomstylerError $errors, $status = 0, $parent_attrs = false) {
      $this->errors = $errors;
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
      }
    }

    public function __get($prop) {
      # If a property is set dynamically, allow access - otherwise return NULL
      # this is done in case the API changes in such a way that if a property is no longer available, NULL will be returned as a fallback instead of a Notice: error
      if (in_array($prop, $this->_accessible_props)) return $this->$prop;
      return NULL;
    }

    public function exists() {
      $props_with_values = [];
      
      foreach ($this->_accessible_props as $prop)
        if (!is_null($this->$prop)) $props_with_values[] = $prop;

      return $this->_http_status < 400 && count($props_with_values) >= 2;
    }

  }

?>
