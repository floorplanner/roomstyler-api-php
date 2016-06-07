<?php

  class RoomstylerRoomMethods extends RoomstylerMethodBase {

    protected static function index($_ = NULL, $params = []) {
      if (is_array($_)) $params = $_;
      return RoomstylerRequest::send('RoomstylerRoom', "rooms", $params);
    }

    protected static function find($id, $params = []) {
      return RoomstylerRequest::send('RoomstylerRoom', "rooms/$id", $params);
    }

    protected static function products($id, $params = []) {
      return RoomstylerRequest::send('RoomstylerRoom', "rooms/$id", $params);
    }

    protected static function related_rooms($id, $params = []) {
      return RoomstylerRequest::send('RoomstylerRoom', "rooms/$id/related", $params);
    }

    protected static function loved_by($id, $params = []) {
      return RoomstylerRequest::send('RoomstylerRoom', "rooms/$id/loved_by", $params);
    }

    protected static function comments($id, $params = []) {
      return RoomstylerRequest::send('RoomstylerRoom', "rooms/$id/comments", $params);
    }

  }

?>
