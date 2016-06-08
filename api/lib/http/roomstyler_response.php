<?php
  class RoomstylerResponse {
    private $type;
    private $path;
    private $full_path;
    private $arguments;
    private $method;
    private $status;
    private $headers;
    private $body;
    private $error;

    public function __construct(array $data = []) {
      foreach ($data as $prop => $val)
        if (property_exists('RoomstylerResponse', $prop)) $this->$prop = $val;

      return $this;
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

    public function headers($type = 'request') {
      if ($type != 'request' || $type != 'response') $type = 'request';
      return $this->headers[$type];
    }


  }
?>
