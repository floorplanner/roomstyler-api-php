<?php

  class RoomstylerContest extends RoomstylerModelBase {

  public function votes($params = []) {
    return RoomstylerRequest::send('RoomstylerContestVote', "contests/{$this->id}/votes", $params);
  }

  public function entries($params = []) {
    return RoomstylerRequest::send('RoomstylerContestEntry', "contests/{$this->id}/contest_entries", $params);
  }

  }

?>
