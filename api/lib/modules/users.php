<?php

  class RoomstylerUser {

    private static $resp;

    public function __construct(RoomstylerResponse $resp) {
      self::$resp = $resp;
    }
    
  }

?>
