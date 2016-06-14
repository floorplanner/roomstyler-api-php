<?php

  class RoomstylerContestMethods extends RoomstylerMethodBase {

    public function index($params = []) {
      return RoomstylerRequest::send('RoomstylerContest', "contests", $params);
    }

    public function find($id, $params = []) {
      return RoomstylerRequest::send('RoomstylerContest', "contests/$id", $params);
    }

  }

?>
