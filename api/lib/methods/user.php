<?php

  class RoomstylerUserMethods extends RoomstylerMethodBase {

    public function find($ids, $params = []) {
      if (is_array($ids)) $ids = implode($ids, ',');
      return (new RoomstylerRequest($this->_settings, $this->_whitelabeled))->send('RoomstylerUser', "users/$ids", $params);
    }

    public function create($params) {
      return (new RoomstylerRequest($this->_settings, $this->_whitelabeled))->send('RoomstylerUser', "users", ['user' => $params], RoomstylerRequest::POST, RoomstylerRequest::AUTH_API);
    }

    public function login($username, $password) {
      return (new RoomstylerRequest($this->_settings, $this->_whitelabeled))->send('RoomstylerUser', "users/login", ['email' => $username, 'password' => $password], RoomstylerRequest::POST);
    }

  }

?>
