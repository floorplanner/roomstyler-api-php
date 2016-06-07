<?php
  require_once 'lib/http/roomstyler_request.php';

  require_once 'lib/models/model_base.php';
  require_once 'lib/models/user.php';
  require_once 'lib/models/product.php';
  require_once 'lib/models/room.php';
  require_once 'lib/models/room_render.php';
  require_once 'lib/models/category.php';

  require_once 'lib/methods/method_base.php';
  require_once 'lib/methods/user.php';
  require_once 'lib/methods/product.php';
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
      switch ($prop) {
        case 'users':
        case 'user':
          return new RoomstylerUserMethods($this->_settings['debug']);
        break;
        case 'rooms':
        case 'room':
          return new RoomstylerRoomMethods($this->_settings['debug']);
        break;
        case 'products':
        case 'product':
          return new RoomstylerProductMethods($this->_settings['debug']);
        break;
        case 'renders':
        case 'render':
          return new RoomstylerRoomRenderMethods($this->_settings['debug']);
        break;
        case 'categories':
        case 'category':
          return new RoomstylerCategoryMethods($this->_settings['debug']);
        break;
      }
    }
  }
?>
