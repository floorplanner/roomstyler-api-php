<?php
  require_once 'lib/roomstyler_base.php';

  require_once 'lib/http/roomstyler_response.php';
  require_once 'lib/http/roomstyler_request.php';

  require_once 'lib/editor/editor.php';

  require_once 'lib/models/model_base.php';
  require_once 'lib/models/user.php';
  require_once 'lib/models/comment.php';
  require_once 'lib/models/collection.php';
  require_once 'lib/models/collection_item.php';
  require_once 'lib/models/product.php';
  require_once 'lib/models/contest.php';
  require_once 'lib/models/contest_vote.php';
  require_once 'lib/models/contest_entry.php';
  require_once 'lib/models/room.php';
  require_once 'lib/models/room_render.php';
  require_once 'lib/models/search_meta.php';
  require_once 'lib/models/category.php';
  require_once 'lib/models/style.php';
  require_once 'lib/models/timeframe.php';
  require_once 'lib/models/component.php';
  require_once 'lib/models/material.php';

  require_once 'lib/methods/method_base.php';
  require_once 'lib/methods/user.php';
  require_once 'lib/methods/collection.php';
  require_once 'lib/methods/contest.php';
  require_once 'lib/methods/room.php';
  require_once 'lib/methods/room_render.php';
  require_once 'lib/methods/category.php';
  require_once 'lib/methods/component.php';
  require_once 'lib/methods/material.php';

  class RoomstylerApi extends RoomstylerBase {

    const VERSION = "1.0";

    private $_current_user = NULL;
    private $_whitelabeled = false;
    private $_settings = [
      'protocol' => 'https',
      'whitelabel' => [],
      'user' => [],
      'host' => 'roomstyler.com',
      'prefix' => 'api',
      'key' => NULL,
      'token' => NULL,
      'timeout' => 5,
      'language' => 'en',
      'connect_timeout' => 30,
      'request_headers' => ['Content-Type: application/json; charset=utf-8'],
      'debug' => false];

    public function __construct(array $settings) {
      foreach ($this->_settings as $setting => $value)
        if (array_key_exists($setting, $settings)) $this->_settings[$setting] = $settings[$setting];

      $this->_settings['user_agent'] = $this->generate_user_agent();

      if (!empty($this->_settings['user'])) $this->login($this->_settings['user']['name'], $this->_settings['user']['password']);
    }

    public function login($name, $password) {
      $response = $this->users->login($name, $password);

      if ($this->_settings['debug']) $response = $response['result'];
      if ($response->successful() && property_exists($response, 'token')) {
        $this->_current_user = $response;
        $this->_settings['token'] = $response->token;
        return true;
      }

      return false;
    }

    public function logout() {
      $this->_current_user = NULL;
      $this->_settings['token'] = NULL;
      return true;
    }

    public function logged_in() {
      return $this->_current_user != NULL;
    }

    public function user() {
      return $this->_current_user;
    }

    # objects are returned based on properties being called.
    # these properties do not exist but if they have a Roomstyler{type} and
    # optionally a Roomstyler{type}Methods object they will be called here.

    # the 'wl' property is used to toggle api scoping
    # the 'editor' property is used to access an editor class to allow for embedding
    # with given settings.
    public function __get($prop) {
      switch ($prop) {
        case 'wl':
          $this->_whitelabeled = true;
          $out = $this;
        break;
        case 'editor':
          $out = new RoomstylerEditor($this->_settings, $this->_whitelabeled);
        break;
        default:
          # no scope, no authentication
          $class_name = parent::method_class_name($prop);
          $out = new $class_name($this->_settings, $this->_whitelabeled);
        break;
      }

      if ($prop != 'wl') $this->_whitelabeled = false;
      return $out;
    }

    protected function generate_user_agent() {
      return join(' ', [
        'RoomstylerApi/' . self::VERSION,
        "({$this->_settings['protocol']}://{$this->_settings['host']})"]);
    }

  }
?>
