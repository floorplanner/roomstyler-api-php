<?php
  class RoomstylerApi {
    private $_settings = [
      'host' => 'roomstyler.com',
      'prefix' => 'api',
      'key' => NULL,
      'token' => NULL
    ];

    public function __construct($key, $token) {
      $this->_settings['key'] = $key;
      $this->_settings['token'] = $token;

      pp($this->_settings);
    }
  }
?>
