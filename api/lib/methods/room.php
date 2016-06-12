<?php

  class RoomstylerRoomMethods extends RoomstylerMethodBase {

    protected static function index($params = []) {
      $params = array_merge(['limit' => 50, 'skip_total' => true, 'skip_last_updated' => true], $params);
      return RoomstylerRequest::send('RoomstylerRoom', "rooms", $params);
    }

    protected static function find($id, $params = []) {
      $params = array_merge(['skip_model' => true], $params);
      return RoomstylerRequest::send('RoomstylerRoom', "rooms/$id", $params);
    }

    protected static function products($id, $params = []) {
      $params = array_merge(['skip_model' => true], $params);
      return RoomstylerRequest::send('RoomstylerRoom', "rooms/$id/products", $params);
    }

    protected static function related_rooms($id, $params = []) {
      return RoomstylerRequest::send('RoomstylerRoom', "rooms/$id/related", $params);
    }

    protected static function loved_by($id, $params = []) {
      return RoomstylerRequest::send('RoomstylerUser', "rooms/$id/loved_by", $params);
    }

    protected static function comments($id, $params = []) {
      return RoomstylerRequest::send('RoomstylerComment', "rooms/$id/comments", $params);
    }

    public static function comment($id, $content) {
      return RoomstylerRequest::send('RoomstylerComment', "rooms/$id/comments", ['comment' => ['comment' => $content]], RoomstylerRequest::POST);
    }

    public static function toggle_love($id, $params = []) {
      return RoomstylerRequest::send('RoomstylerRoom', "rooms/$id/toggle_like", $params, RoomstylerRequest::POST);
    }

  }

?>
