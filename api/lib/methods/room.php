<?php

  class RoomstylerRoomMethods extends RoomstylerMethodBase {

    public function index($params = []) {
      $params = array_merge(['limit' => 50, 'skip_total' => true, 'skip_last_updated' => true], $params);
      return RoomstylerRequest::send($this, 'RoomstylerRoom', "rooms", $params);
    }

    public function find($id, $params = []) {
      $params = array_merge(['skip_model' => true], $params);
      return RoomstylerRequest::send($this, 'RoomstylerRoom', "rooms/$id", $params);
    }

    public function search($params = []) {
      $params = array_merge(['limit' => 50], $params);
      return RoomstylerRequest::send($this, 'RoomstylerRoom', "rooms/search", $params);
    }

    public function search_meta() {
      return RoomstylerRequest::send($this, 'RoomstylerSearchMeta', "rooms/search/meta");
    }

    public function products($id, $params = []) {
      $params = array_merge(['skip_model' => true], $params);
      return RoomstylerRequest::send($this, 'RoomstylerRoom', "rooms/$id/products", $params);
    }

    public function related_rooms($id, $params = []) {
      return RoomstylerRequest::send($this, 'RoomstylerRoom', "rooms/$id/related", $params);
    }

    public function loved_by($id, $params = []) {
      return RoomstylerRequest::send($this, 'RoomstylerUser', "rooms/$id/loved_by", $params);
    }

    public function comments($id, $params = []) {
      return RoomstylerRequest::send($this, 'RoomstylerComment', "rooms/$id/comments", $params);
    }

    public function delete($id) {
      return RoomstylerRequest::send($this, 'RoomstylerRoom', "rooms/$id", [], RoomstylerRequest::DELETE);
    }

    public function add_tags($id, $tags = []) {
      $tags = join(',', $tags);
      return RoomstylerRequest::send($this, 'RoomstylerRoom', "rooms/$id/tags", ['tags' => $tags], RoomstylerRequest::POST);
    }

    public function remove_tags($id, $tags = []) {
      $tags = join(',', $tags);
      return RoomstylerRequest::send($this, 'RoomstylerRoom', "rooms/$id/tags", ['tags' => $tags], RoomstylerRequest::DELETE);
    }

    public function comment($id, $content) {
      return RoomstylerRequest::send($this, 'RoomstylerComment', "rooms/$id/comments", ['comment' => ['comment' => $content]], RoomstylerRequest::POST);
    }

    public function toggle_love($id, $params = []) {
      return RoomstylerRequest::send($this, 'RoomstylerRoom', "rooms/$id/toggle_like", $params, RoomstylerRequest::POST);
    }

    public static function render($id, $params = [], $mode = '') {
      $params = array_merge(['width' => 1920, 'height' => 1080], $params);
      if ($mode == '2d') $mode = "_$mode";
      else $mode = '';
      return RoomstylerRequest::send('RoomstylerRoom', "rooms/$id/render$mode", $params, RoomstylerRequest::POST);
    }

    public static function chown($id, $user_id) {
      return RoomstylerRequest::send('RoomstylerRoom', "rooms/$id/chown", ['user_id' => $user_id], RoomstylerRequest::POST);
    }

  }

?>
