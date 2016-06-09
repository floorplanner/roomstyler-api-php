<?php
  require_once 'lib/roomstyler_base.php';
  require_once 'lib/http/roomstyler_response.php';
  require_once 'lib/http/roomstyler_request.php';

  require_once 'lib/models/model_base.php';
  require_once 'lib/models/user.php';
  require_once 'lib/models/product.php';
  require_once 'lib/models/contest.php';
  require_once 'lib/models/room.php';
  require_once 'lib/models/room_render.php';
  require_once 'lib/models/category.php';
  require_once 'lib/models/component.php';
  require_once 'lib/models/material.php';

  require_once 'lib/methods/method_base.php';
  require_once 'lib/methods/user.php';
  require_once 'lib/methods/contest.php';
  require_once 'lib/methods/room.php';
  require_once 'lib/methods/room_render.php';
  require_once 'lib/methods/category.php';
  require_once 'lib/methods/component.php';
  require_once 'lib/methods/material.php';

  class RoomstylerApi extends RoomstylerBase {

    const VERSION = "1.0";

    private $_settings = [
      'protocol' => 'https',
      'whitelabel' => NULL,
      'password' => NULL,
      'host' => 'roomstyler.com',
      'prefix' => 'api',
      'method_param' => '_method',
      'key' => NULL,
      'token' => NULL,
      'timeout' => 30,
      'connect_timeout' => 30,
      'request_headers' => ['Content-Type: application/json'],
      'debug' => false];

    public function __construct($settings) {
      foreach ($this->_settings as $setting => $value)
        if (array_key_exists($setting, $settings)) $this->_settings[$setting] = $settings[$setting];

      $this->_settings['user_agent'] = $this->generate_user_agent();

      RoomstylerRequest::OPTIONS($this->_settings);
      return $this;
    }

    public function __get($prop) {
      switch ($prop) {
        case 'wl':
          # scope to owned whitelabel (identified by 'whitelabel' and 'password' through basic auth)
          RoomstylerRequest::scope_wl(true);
          return $this;
        break;
        default:
          # no scope, no authentication
          $class_name = self::method_class_name($prop);
          return new $class_name($this->_settings['debug']);
      }
    }

    protected static function method_class_name($prop) {
      $prop = ucfirst(parent::to_singular($prop));
      return "Roomstyler{$prop}Methods";
    }

    protected static function model_class_name($prop) {
      $prop = ucfirst(parent::to_singular($prop));
      return "Roomstyler{$prop}Model";
    }

    protected function generate_user_agent() {
      return join(' ', [
        'RoomstylerApi/' . self::VERSION,
        "({$this->_settings['protocol']}://{$this->_settings['host']})"]);
    }

  }
?>
