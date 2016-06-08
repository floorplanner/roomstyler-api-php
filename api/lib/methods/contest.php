<?php

  class RoomstylerContestMethods extends RoomstylerMethodBase {

    protected static function index($params = []) {
      return RoomstylerRequest::send('RoomstylerContest', "contests", $params);
    }

    protected static function find($id, $params = []) {
      return RoomstylerRequest::send('RoomstylerContest', "contests/$id", $params);
    }

    protected static function votes($id, $params = []) {
      return RoomstylerRequest::send('RoomstylerContest', "contests/$id/votes", $params);
    }

    protected static function entries($id, $params = []) {
      return RoomstylerRequest::send('RoomstylerContest', "contests/$id/contest_entries", $params);
    }

  }

?>