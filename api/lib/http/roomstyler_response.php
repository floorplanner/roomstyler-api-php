<?php
  class RoomstylerResponse extends RoomstylerBase {

    private $type;
    private $path;
    private $full_path;
    private $arguments;
    private $method;
    private $status;
    private $headers;
    private $body;
    private $errors;

    private $successful = false;

    public function __construct(array $data = []) {
      foreach ($data as $prop => $val) if (property_exists($this, $prop)) $this->$prop = $val;
      if (empty($this->errors)) $this->successful = true;
      return $this;
    }

    public function successful() {
      return $this->successful;
    }

    public function errors() {
      return $this->errors;
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
      return $this->headers[$type];
    }

  }
?>
