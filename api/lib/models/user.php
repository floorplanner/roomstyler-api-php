<?php

  class RoomstylerUser extends RoomstylerModelBase {

    public function delete($params = []) {
      return (new RoomstylerRequest($this->_settings, $this->_whitelabeled))->send('RoomstylerUser', "users/{$this->id}", [], RoomstylerRequest::DELETE);
    }

    public function loved_rooms($params = []) {
      return (new RoomstylerRequest($this->_settings, $this->_whitelabeled))->send('RoomstylerRoom', "users/{$this->id}/rooms_loved", $params);
    }

    public function collections($params = []) {
      return (new RoomstylerRequest($this->_settings, $this->_whitelabeled))->send('RoomstylerCollection', "users/{$this->id}/collections", $params);
    }

    public function collection($collection_id, $params = []) {
      return (new RoomstylerRequest($this->_settings, $this->_whitelabeled))->send('RoomstylerCollection', "users/{$this->id}/collections/$collection_id", $params);
    }

  }

?>
