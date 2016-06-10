<?php

  class RoomstylerBase {

    protected $_cls;
    protected $_type;

    public function __construct() {
      $this->_cls = get_class($this);
      $this->_type = strtolower(preg_replace('/Roomstyler|Methods|Model/', '', $this->_cls));
    }

    public function __call($method, $args) {
      $method_class = self::method_class_name($this->type());
      if (method_exists($method_class, $method)) {
        $method_params = self::method_params($method_class, $method);
        return call_user_func_array("$method_class::$method", $args);
      } else trigger_error('Call to undefined method '.$method_class.'::'.$method.'()', E_USER_ERROR);
    }

    public function call_with_context($method, $args, $klass) {
      $method_class = self::method_class_name($this->type());
      if (method_exists($method_class, $method)) {
        $method_params = self::method_params($method_class, $method);
        if ($method_params[0] == 'id') array_unshift($args, $klass->id);
        return call_user_func_array("$method_class::$method", $args);
      } else trigger_error('Call to undefined method '.$method_class.'::'.$method.'()', E_USER_ERROR);
    }

    protected function type() {
      return $this->_type;
    }

    protected static function method_params($klass, $method) {
      $reflection = new ReflectionMethod($klass, $method);
      return array_map(function($param) {
        return $param->getName();
      }, $reflection->getParameters());
    }

    protected static function model_class_name($prop) {
      $prop = ucfirst(self::to_singular($prop));
      return "Roomstyler{$prop}Model";
    }

    protected static function method_class_name($prop) {
      $prop = ucfirst(self::to_singular($prop));
      return "Roomstyler{$prop}Methods";
    }

    protected static function to_singular($prop) {
      $prop = preg_replace(['/ies$/', '/s$/'], ['y', ''], $prop);
      return strtolower($prop);
    }

    protected static function to_plural($prop) {
      $prop = preg_replace(['/y$/', '/([^s])$/'], ['ies', '$1s'], $prop);
      return strtolower($prop);
    }
  }

?>
