<?php

  class RoomstylerUserMethods extends RoomstylerMethodBase {

    public function find($ids, $params = []) {
      if (is_array($ids)) $ids = implode($ids, ',');
      return RoomstylerRequest::send('RoomstylerUser', "users/$ids", $params);
    }

    public function create($params) {
      return RoomstylerRequest::send('RoomstylerUser', "users", ['user' => $params], RoomstylerRequest::POST);
    }

    public function login($username, $password) {
      return RoomstylerRequest::send('RoomstylerUser', "users/login", ['email' => $username, 'password' => $password], RoomstylerRequest::POST);
    }

  }

?>
