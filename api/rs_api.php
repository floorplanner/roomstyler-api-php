<?php
  require_once 'lib/http/roomstyler_request.php';

  class RoomstylerApi {
    private $_settings = [
      'protocol' => 'https',
      'host' => 'roomstyler.com',
      'prefix' => 'api',
      'method_param' => '_method',
      'key' => NULL,
      'token' => NULL,
      'timeout' => 2,
      'connect_timeout' => 30,
      'user_agent' => 'RoomstylerApi/1.0 (https://roomstyler.com)'];

    public function __construct($settings) {
      foreach ($this->_settings as $setting => $value)
        if (array_key_exists($setting, $settings))
          $this->_settings[$setting] = $settings[$setting];

      RoomstylerRequest::OPTIONS($this->_settings);
      return $this;
    }

    public function users(array $params = []) {
      return $req = RoomstylerRequest::send('users/972691', $params);
    }
  }
?>
