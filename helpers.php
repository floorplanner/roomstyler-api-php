<?php

  function pp() {
    foreach (func_get_args() as $arg) {
      echo '<pre>';
      print_r($arg);
      echo '</pre>';
    }
  }

?>
