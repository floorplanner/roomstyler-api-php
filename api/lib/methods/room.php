<?php

  class RoomstylerRoomMethods extends RoomstylerMethodBase {

    public function index($params = []) {
      $params = array_merge(['limit' => 50, 'skip_total' => true, 'skip_last_updated' => true], $params);
      return RoomstylerRequest::send('RoomstylerRoom', "rooms", $params);
    }

    public function find($id, $params = []) {
      $params = array_merge(['skip_model' => true], $params);
      return RoomstylerRequest::send('RoomstylerRoom', "rooms/$id", $params);
    }

    public function search($params = []) {
      $params = array_merge(['limit' => 50], $params);
      return RoomstylerRequest::send('RoomstylerRoom', "rooms/search", $params);
    }

    public function search_meta() {
      return RoomstylerRequest::send('RoomstylerSearchMeta', "rooms/search/meta");
    }

  }

?>
