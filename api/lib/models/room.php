<?php

  class RoomstylerRoom extends RoomstylerModelBase {

    public function products($params = []) {
      $params = array_merge(['skip_model' => true], $params);
      return (new RoomstylerRequest($this->_settings, $this->_whitelabeled))->send('RoomstylerProduct', "rooms/{$this->id}/products", $params);
    }

    public function related_rooms($params = []) {
      return (new RoomstylerRequest($this->_settings, $this->_whitelabeled))->send('RoomstylerRoom', "rooms/{$this->id}/related", $params);
    }

    public function loved_by($params = []) {
      return (new RoomstylerRequest($this->_settings, $this->_whitelabeled))->send('RoomstylerUser', "rooms/{$this->id}/loved_by", $params);
    }

    public function comments($params = []) {
      return (new RoomstylerRequest($this->_settings, $this->_whitelabeled))->send('RoomstylerComment', "rooms/{$this->id}/comments", $params);
    }

    public function delete() {
      return (new RoomstylerRequest($this->_settings, $this->_whitelabeled))->send('RoomstylerRoom', "rooms/{$this->id}", [], RoomstylerRequest::DELETE);
    }

    public function add_tags($tags) {
      if (is_array($tags)) $tags = join(',', $tags);
      return (new RoomstylerRequest($this->_settings, $this->_whitelabeled))->send('RoomstylerRoom', "rooms/{$this->id}/tags", ['tags' => $tags], RoomstylerRequest::POST);
    }

    public function remove_tags($tags) {
      if (is_array($tags)) $tags = join(',', $tags);
      return (new RoomstylerRequest($this->_settings, $this->_whitelabeled))->send('RoomstylerRoom', "rooms/{$this->id}/tags", ['tags' => $tags], RoomstylerRequest::DELETE);
    }

    public function comment($content) {
      return (new RoomstylerRequest($this->_settings, $this->_whitelabeled))->send('RoomstylerComment', "rooms/{$this->id}/comments", ['comment' => ['comment' => $content]], RoomstylerRequest::POST, RoomstylerRequest::AUTH_USER);
    }

    public function toggle_love($params = []) {
      return (new RoomstylerRequest($this->_settings, $this->_whitelabeled))->send('RoomstylerRoom', "rooms/{$this->id}/toggle_like", $params, RoomstylerRequest::POST, RoomstylerRequest::AUTH_USER);
    }

    public function render($mode = '', $params = []) {
      $params = array_merge(['width' => 1920, 'height' => 1080], $params);
      $mode = 'render';
      if ($mode == '2d' || $mode == 'panorama') $mode .= "_$mode";
      return (new RoomstylerRequest($this->_settings, $this->_whitelabeled))->send('RoomstylerRoom', "rooms/{$this->id}/{$mode}", $params, RoomstylerRequest::POST);
    }

    public function chown($user_id) {
      return (new RoomstylerRequest($this->_settings, $this->_whitelabeled))->send('RoomstylerRoom', "rooms/{$this->id}/chown", ['user_id' => $user_id], RoomstylerRequest::POST, RoomstylerRequest::AUTH_API);
    }

    public function panorama($params = []) {
      return (new RoomstylerRequest($this->_settings, $this->_whitelabeled))->send('RoomstylerRoomPanorama', "rooms/{$this->id}/panorama", $params);
    }

  }

?>
