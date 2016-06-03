<?php
  class RoomstylerResponse {
    private $path;
    private $arguments;
    private $method;
    private $status;
    private $body;

    public function __construct(array $data = []) {
      foreach ($data as $prop => $val) {
        if (property_exists('RoomstylerResponse', $prop)) {
          $this->$prop = $val;
        }
      }
    }

    public function path() {
      return $this->path;
    }

    public function arguments() {
      return $this->arguments;
    }

    public function method() {
      return $this->method;
    }

    public function status() {
      return $this->status;
    }

    public function body() {
      return $this->body;
    }


  }
?>
