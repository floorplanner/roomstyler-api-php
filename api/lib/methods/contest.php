<?php

  class RoomstylerContestMethods extends RoomstylerMethodBase {

    public function index($params = []) {
      return RoomstylerRequest::send($this, 'RoomstylerContest', "contests", $params);
    }

    public function find($id, $params = []) {
      return RoomstylerRequest::send($this, 'RoomstylerContest', "contests/$id", $params);
    }

    public function votes($id, $params = []) {
      return RoomstylerRequest::send($this, 'RoomstylerContestVote', "contests/$id/votes", $params);
    }

    public function entries($id, $params = []) {
      return RoomstylerRequest::send($this, 'RoomstylerContestEntry', "contests/$id/contest_entries", $params);
    }

  }

?>
