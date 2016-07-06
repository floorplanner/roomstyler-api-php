<?php

  class RoomstylerError {

    private static $http_errors = [
      'default' => [
        302 => 'HTTP 302 Found',
        304 => 'HTTP 304 Not Modified',
        400 => 'HTTP 400 Bad Request',
        401 => 'HTTP 401 Not Authorized',
        403 => 'HTTP 403 Forbidden',
        404 => 'HTTP 404 Not Found',
        422 => 'HTTP 422 Unprocessable Entity',
        500 => 'HTTP 500 Internal Server Error',
        502 => 'HTTP 502 Bad Gateway',
        503 => 'HTTP 503 Service Unavailable']
    ];

    private $_errors = [];

    public function __construct(array $errors, array $opts = []) {
      $this->_errors = $errors;
      if (isset($opts['http_status']) && $opts['http_status'] >= 300 && isset($opts['custom_http_errors_for'])) {
        $type = strtolower(str_replace('Roomstyler', '', $opts['custom_http_errors_for']));
        if (!isset(self::$http_errors[$type]) ||
            !isset(self::$http_errors[$type][$opts['http_status']]))
          $type = 'default';
        if (isset(self::$http_errors[$type][$opts['http_status']]))
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
