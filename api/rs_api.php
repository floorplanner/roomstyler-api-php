<?php
  require_once 'lib/roomstyler_request.php';

  class RoomstylerApi {
    private $_settings = [
      'host' => 'roomstyler.com',
      'prefix' => 'api',
      'protocol_param' => '_method',
      'key' => NULL,
      'token' => NULL
    ];

    public function __construct($settings) {
      foreach ($this->_settings as $setting => $value) {
        if (array_key_exists($setting, $settings)) {
          $this->_settings[$setting] = $settings[$setting];
        }
      }
      return $this;
    }

    public function get_settings() {
      return $this->_settings;
    }
  }
?>
