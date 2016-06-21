<?php

  class RoomstylerError {

    private static $http_errors = [
      'default' => [
        200 => 'OK',
        201 => 'Created',
        302 => 'Found',
        403 => 'Forbidden',
        404 => 'Not found',
        422 => 'Unprocessable entity',
        500 => 'Internal server error'],
      'user' => [
        404 => 'specific error'
      ]
    ];

    private $_errors = [];

    public function __construct(array $errors, array $opts = []) {
      $this->_errors = $errors;
      if (isset($opts['http_status']) && isset($opts['custom_http_errors_for'])) {
        $type = strtolower(str_replace('Roomstyler', '', $opts['custom_http_errors_for']));
        if (!array_key_exists($opts['http_status'], self::$http_errors[$type]))
          $type = 'default';
        array_push($this->_errors, self::$http_errors[$type][$opts['http_status']]);
      }
    }

    public function any() {
      return !empty($this->_errors);
    }

    public function get() {
      return $this->_errors;
    }

    public function each($callback) {
      return $this->_each($callback, $this->_errors);
    }

    private function _each($callback, $error_list, array $parent_labels = []) {
      foreach ($error_list as $label => $error) {
        if (!is_numeric($label)) array_push($parent_labels, $label);

        if (is_array($error)) $this->_each($callback, $error, $parent_labels);
        else call_user_func($callback, $error, $parent_labels);

        if (!is_numeric($label)) array_splice($parent_labels, -1, 1);
      }
    }

  }

?>
