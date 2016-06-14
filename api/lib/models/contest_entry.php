<?php

  class RoomstylerContestEntry extends RoomstylerModelBase {

    public function vote() {
      return (new RoomstylerRequest($this->_settings, $this->_whitelabeled))->send('RoomstylerVote', "contests/{$this->contest_id}/entries/{$this->id}/vote");
    }

  }

?>
