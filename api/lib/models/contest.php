<?php

  class RoomstylerContest extends RoomstylerModelBase {

  public function votes($params = []) {
    return (new RoomstylerRequest($this->_settings, $this->_whitelabeled))->send('RoomstylerContestVote', "contests/{$this->id}/votes", $params);
  }

  public function entries($params = []) {
    $params = array_merge($params, ['additional_attrs' => ['contest_id' => $this->id], 'auth_type' => RoomstylerRequest::AUTH_USER]);
    return (new RoomstylerRequest($this->_settings, $this->_whitelabeled))->send('RoomstylerContestEntry', "contests/{$this->id}/contest_entries", $params);
  }

  }

?>
