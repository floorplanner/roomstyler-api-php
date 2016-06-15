<?php

  class RoomstylerUserMethods extends RoomstylerMethodBase {

    public function find($ids, $params = []) {
      if (is_array($ids)) $ids = implode($ids, ',');
      return (new RoomstylerRequest($this->_settings, $this->_whitelabeled))->send('RoomstylerUser', "users/$ids", $params);
    }

    public function create($params) {
      $all_params = ['user' => $params, 'auth_type' => RoomstylerRequest::AUTH_API];
      return (new RoomstylerRequest($this->_settings, $this->_whitelabeled))->send('RoomstylerUser', "users", $all_params, RoomstylerRequest::POST);
    }

    public function login($username, $password) {
      return (new RoomstylerRequest($this->_settings, $this->_whitelabeled))->send('RoomstylerUser', "users/login", ['email' => $username, 'password' => $password], RoomstylerRequest::POST);
    }

  }

?>
