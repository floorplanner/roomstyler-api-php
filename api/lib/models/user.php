<?php

  class RoomstylerUser extends RoomstylerModelBase {

    public function delete($params = []) {
      return RoomstylerRequest::send('RoomstylerUser', "users/{$this->id}", [], RoomstylerRequest::DELETE);
    }

    public function loved_rooms($params = []) {
      return RoomstylerRequest::send('RoomstylerRoom', "users/{$this->id}/rooms_loved", $params);
    }

    public function collections($params = []) {
      return RoomstylerRequest::send('RoomstylerCollection', "users/{$this->id}/collections", $params);
    }

    public function collection($collection_id, $params = []) {
      return RoomstylerRequest::send('RoomstylerCollection', "users/{$this->id}/collections/$collection_id", $params);
    }

  }

?>
