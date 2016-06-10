<?php

  class RoomstylerUserMethods extends RoomstylerMethodBase {

    protected static function create($params) {
      return RoomstylerRequest::send('RoomstylerUser', "users", ['user' => $params], RoomstylerRequest::POST);
    }

    protected static function login($username, $password) {
      return RoomstylerRequest::send('RoomstylerUser', "users/login", ['email' => $username, 'password' => $password], RoomstylerRequest::POST);
    }

    protected static function find($ids, $params = []) {
      if (is_array($ids)) $ids = implode($ids, ',');
      return RoomstylerRequest::send('RoomstylerUser', "users/$ids", $params);
    }

    protected static function token($id, $params = []) {
      return RoomstylerRequest::send('RoomstylerUser', "users/$id/token", $params);
    }

    protected static function loved_rooms($id, $params = []) {
      return RoomstylerRequest::send('RoomstylerRoom', "users/$id/rooms_loved", $params);
    }

    protected static function collections($id, $params = []) {
      return RoomstylerRequest::send('RoomstylerCollection', "users/$id/collections", $params);
    }

    protected static function collection($id, $collection_id, $params = []) {
      return RoomstylerRequest::send('RoomstylerCollection',
                                     "users/$id/collections/$collection_id", $params);
    }

    protected static function collection_items($id, $collection_id, $params = []) {
      return RoomstylerRequest::send('RoomstylerCollectionItem',
                                     "users/$id/collections/$collection_id/items", $params);
    }

  }

?>
