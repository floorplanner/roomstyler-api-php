<?php

  class RoomstylerSearchMeta extends RoomstylerModelBase {

    public $categories = [];
    public $styles = [];
    public $timeframes = [];

    public function __construct($data, $errors, $status) {
      parent::__construct(NULL, $errors, $status);
      foreach($data as $class_cat => $results) {
        $model_cls_name = parent::model_class_name($class_cat);
        foreach ($results as $obj) array_push($this->$class_cat, new $model_cls_name($obj, $errors, $status));
      }
    }

  }

?>
