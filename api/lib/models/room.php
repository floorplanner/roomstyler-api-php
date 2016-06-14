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
      $tags = join(',', $tags);
      return (new RoomstylerRequest($this->_settings, $this->_whitelabeled))->send('RoomstylerRoom', "rooms/{$this->id}/tags", ['tags' => $tags], RoomstylerRequest::POST);
    }

    public function remove_tags($tags) {
      $tags = join(',', $tags);
      return (new RoomstylerRequest($this->_settings, $this->_whitelabeled))->send('RoomstylerRoom', "rooms/{$this->id}/tags", ['tags' => $tags], RoomstylerRequest::DELETE);
    }

    public function comment($content) {
      $params = ['comment' => ['comment' => $content], 'auth_type' => RoomstylerRequest::AUTH_USER];
      return (new RoomstylerRequest($this->_settings, $this->_whitelabeled))->send('RoomstylerComment', "rooms/{$this->id}/comments", $params, RoomstylerRequest::POST);
    }

    public function toggle_love($params = []) {
      $params = array_merge($params, ['auth_type' => RoomstylerRequest::AUTH_USER]);
      return (new RoomstylerRequest($this->_settings, $this->_whitelabeled))->send('RoomstylerRoom', "rooms/{$this->id}/toggle_like", $params, RoomstylerRequest::POST);
    }

    public function render($mode = '', $params = []) {
      $params = array_merge(['width' => 1920, 'height' => 1080], $params);
      if ($mode == '2d') $mode = "_$mode";
      else $mode = '';
      return (new RoomstylerRequest($this->_settings, $this->_whitelabeled))->send('RoomstylerRoom', "rooms/{$this->id}/render$mode", $params, RoomstylerRequest::POST);
    }

    public function chown($user_id) {
      return (new RoomstylerRequest($this->_settings, $this->_whitelabeled))->send('RoomstylerRoom', "rooms/{$this->id}/chown", ['user_id' => $user_id], RoomstylerRequest::POST);
    }

  }

?>
