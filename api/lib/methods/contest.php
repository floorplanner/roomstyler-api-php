<?php

  class RoomstylerContestMethods extends RoomstylerMethodBase {

    public function index($params = []) {
      return (new RoomstylerRequest($this->_settings, $this->_whitelabeled))->send('RoomstylerContest', "contests", $params);
    }

    public function find($id, $params = []) {
      return (new RoomstylerRequest($this->_settings, $this->_whitelabeled))->send('RoomstylerContest', "contests/$id", $params);
    }

  }

?>
