<?php

  class RoomstylerRoomMethods extends RoomstylerMethodBase {

    public function index($params = []) {
      $params = array_merge(['limit' => 50, 'skip_total' => true, 'skip_last_updated' => true], $params);
      if (!isset($params['whitelabel']) && $this->_whitelabeled) $params['whitelabel'] = $this->_settings['whitelabel']['name'];
      return (new RoomstylerRequest($this->_settings, $this->_whitelabeled))->send('RoomstylerRoom', "rooms", $params);
    }

    public function find($id, $params = []) {
      $params = array_merge(['skip_model' => true], $params);
      return (new RoomstylerRequest($this->_settings, $this->_whitelabeled))->send('RoomstylerRoom', "rooms/$id", $params);
    }

    public function search($params = []) {
      $params = array_merge(['limit' => 50], $params);
      return (new RoomstylerRequest($this->_settings, $this->_whitelabeled))->send('RoomstylerRoom', "rooms/search", $params);
    }

    public function panoramas($params = []) {
      $params = array_merge(['limit' => 50, 'page' => 1], $params);
      return (new RoomstylerRequest($this->_settings, $this->_whitelabeled))->send('RoomstylerRoom', "rooms/panoramas", $params);
    }

    public function search_meta() {
      return (new RoomstylerRequest($this->_settings, $this->_whitelabeled))->send('RoomstylerSearchMeta', "rooms/search/meta");
    }

  }

?>
