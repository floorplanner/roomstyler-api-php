<?php

  class RoomstylerBase {

    protected $_cls;
    protected $_type;
    protected $_debug;
    protected static $_scope_wl = false;

    public function __construct($debug = false) {
      $this->_cls = get_class($this);
      $this->_type = strtolower(preg_replace('/Roomstyler|Methods|Model/', '', $this->_cls));
      $this->_debug = $debug;
    }

    public static function scope_wl($b = NULL) {
      if (is_bool($b)) self::$_scope_wl = $b;
      else self::$_scope_wl = false;
      return self::$_scope_wl;
    }

    public static function is_scoped_for_wl() {
      return self::$_scope_wl;
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
      return "Roomstyler{$prop}";
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
