<?php

  class RoomstylerCategory extends RoomstylerModelBase {

    public function __construct($data, $errors = [], $status = 0) {
      if (is_string($data)) $this->name = $data;
      else parent::__construct($data, $errors, $status);
    }

  }

?>
