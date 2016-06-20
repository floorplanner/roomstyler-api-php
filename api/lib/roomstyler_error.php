<?php

  class RoomstylerError {

    private $_errors = [];

    public function __construct(array $errors, array $opts = []) {
      $this->_errors = $errors;
    }

    public function any() {
      return !empty($this->_errors);
    }

    public function each($callback) {
      return $this->_each($callback, $this->_errors);
    }

    private function _each($callback, $error_list, array $parent_labels = []) {
      foreach ($error_list as $label => $error)
        if (is_array($error)) {
          if (!is_numeric($label)) array_push($parent_labels, $label);
          $this->_each($callback, $error, $parent_labels);
          if (!is_numeric($label)) array_splice($parent_labels, -1, 1);
        } else {
          if (!is_numeric($label)) array_push($parent_labels, $label);
          call_user_func($callback, $error, $parent_labels);
        }
    }

  }

?>
