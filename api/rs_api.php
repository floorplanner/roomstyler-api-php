<?php
  require_once 'lib/http/roomstyler_request.php';

  require_once 'lib/models/model_base.php';
  require_once 'lib/models/user.php';
  require_once 'lib/models/product.php';
  require_once 'lib/models/contest.php';
  require_once 'lib/models/room.php';
  require_once 'lib/models/room_render.php';
  require_once 'lib/models/category.php';

  require_once 'lib/methods/method_base.php';
  require_once 'lib/methods/user.php';
  require_once 'lib/methods/contest.php';
  require_once 'lib/methods/room.php';
  require_once 'lib/methods/room_render.php';
  require_once 'lib/methods/category.php';

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
      'user_agent' => 'RoomstylerApi/1.0 (https://roomstyler.com)',
      'debug' => false];

    public function __construct($settings) {
      foreach ($this->_settings as $setting => $value)
        if (array_key_exists($setting, $settings))
          $this->_settings[$setting] = $settings[$setting];

      RoomstylerRequest::OPTIONS($this->_settings);
      return $this;
    }

    public function __get($prop) {
      $class_name = 'Roomstyler' . ucfirst(strtolower(preg_replace(['/ies$/', '/s$/'], ['y', ''], $prop))) . 'Methods';
      return new $class_name($this->_settings['debug']);
    }
  }
?>
