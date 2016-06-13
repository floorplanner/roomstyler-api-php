<?php

  class RoomstylerUserMethods extends RoomstylerMethodBase {

    public function create($params) {
      return RoomstylerRequest::send($this, 'RoomstylerUser', "users", ['user' => $params], RoomstylerRequest::POST);
    }

    public function login($username, $password) {
      return RoomstylerRequest::send($this, 'RoomstylerUser', "users/login", ['email' => $username, 'password' => $password], RoomstylerRequest::POST);
    }

    public function delete($id, $params = []) {
      return RoomstylerRequest::send($this, 'RoomstylerUser', "users/$id", [], RoomstylerRequest::DELETE);
    }

    public function find($ids, $params = []) {
      if (is_array($ids)) $ids = implode($ids, ',');
      return RoomstylerRequest::send($this, 'RoomstylerUser', "users/$ids", $params);
    }

    public function token($id, $params = []) {
      return RoomstylerRequest::send($this, 'RoomstylerUser', "users/$id/token", $params);
    }

    public function loved_rooms($id, $params = []) {
      return RoomstylerRequest::send($this, 'RoomstylerRoom', "users/$id/rooms_loved", $params);
    }

    public function collections($id, $params = []) {
      return RoomstylerRequest::send($this, 'RoomstylerCollection', "users/$id/collections", $params);
    }

    public function collection($id, $collection_id, $params = []) {
      return RoomstylerRequest::send($this, 'RoomstylerCollection', "users/$id/collections/$collection_id", $params);
    }

    public function collection_items($id, $collection_id, $params = []) {
      return RoomstylerRequest::send($this, 'RoomstylerCollectionItem', "users/$id/collections/$collection_id/items", $params);
    }

  }

?>
