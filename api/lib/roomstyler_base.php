<?php

  class RoomstylerBase {

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
