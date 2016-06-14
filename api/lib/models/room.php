<?php

  class RoomstylerRoom extends RoomstylerModelBase {

    public function products($params = []) {
      $params = array_merge(['skip_model' => true], $params);
      return RoomstylerRequest::send('RoomstylerProduct', "rooms/{$this->id}/products", $params);
    }

    public function related_rooms($params = []) {
      return RoomstylerRequest::send('RoomstylerRoom', "rooms/{$this->id}/related", $params);
    }

    public function loved_by($params = []) {
      return RoomstylerRequest::send('RoomstylerUser', "rooms/{$this->id}/loved_by", $params);
    }

    public function comments($params = []) {
      return RoomstylerRequest::send('RoomstylerComment', "rooms/{$this->id}/comments", $params);
    }

    public function delete() {
      return RoomstylerRequest::send('RoomstylerRoom', "rooms/{$this->id}", [], RoomstylerRequest::DELETE);
    }

    public function add_tags($tags) {
      $tags = join(',', $tags);
      return RoomstylerRequest::send('RoomstylerRoom', "rooms/{$this->id}/tags", ['tags' => $tags], RoomstylerRequest::POST);
    }

    public function remove_tags($tags) {
      $tags = join(',', $tags);
      return RoomstylerRequest::send('RoomstylerRoom', "rooms/{$this->id}/tags", ['tags' => $tags], RoomstylerRequest::DELETE);
    }

    public function comment($content) {
      return RoomstylerRequest::send('RoomstylerComment', "rooms/{$this->id}/comments", ['comment' => ['comment' => $content]], RoomstylerRequest::POST);
    }

    public function toggle_love($params = []) {
      return RoomstylerRequest::send('RoomstylerRoom', "rooms/{$this->id}/toggle_like", $params, RoomstylerRequest::POST);
    }

    public static function render($mode = '', $params = []) {
      $params = array_merge(['width' => 1920, 'height' => 1080], $params);
      if ($mode == '2d') $mode = "_$mode";
      else $mode = '';
      return RoomstylerRequest::send('RoomstylerRoom', "rooms/{$this->id}/render$mode", $params, RoomstylerRequest::POST);
    }

    public static function chown($user_id) {
      return RoomstylerRequest::send('RoomstylerRoom', "rooms/{$this->id}/chown", ['user_id' => $user_id], RoomstylerRequest::POST);
    }

  }

?>
