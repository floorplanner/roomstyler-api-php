<?php

  class RoomstylerContest extends RoomstylerModelBase {

  public function votes($params = []) {
    return (new RoomstylerRequest($this->_settings, $this->_whitelabeled))->send('RoomstylerContestVote', "contests/{$this->id}/votes", $params);
  }

  public function entries($params = []) {
    $settings = array_merge($this->_settings, ['parent_attrs' => ['contest_id' => $this->id]]);
    return (new RoomstylerRequest($settings, $this->_whitelabeled))->send('RoomstylerContestEntry', "contests/{$this->id}/contest_entries", $params, RoomstylerRequest::GET, RoomstylerRequest::AUTH_USER);
  }

  }

?>
