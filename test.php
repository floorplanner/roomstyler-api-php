<?php

  ini_set('display_errors', 1);
  error_reporting(E_ALL);
  date_default_timezone_set('CET');

  # development logging
  function pp() {
    foreach (func_get_args() as $arg) {
      echo '<pre>';
      print_r($arg);
      echo '</pre>';
    }
  }

  require 'index.php';

  # fetch rooms from the server, ROOM SEARCH can take parameters for each type in SEARCH_META
  # e.g. category, style or timeframes
  # pp('ROOM SEARCH META', $api->rooms->search_meta());
  # pp('ROOM INDEX', $api->rooms->index(['limit' => 5]));
  # pp('ROOM INDEX WL', $api->wl->rooms->index(['limit' => 5]));
  # pp('ROOM FIND', $api->rooms->find(715833));
  # pp('ROOM SEARCH', $api->rooms->search(['q' => 'test', 'limit' => 5]));

  # actions on room
  # $room = $api->rooms->find(14075616);

  # pp('ROOM PLACE COMMENT', $room->comment('with user credentials and without wl credentials'));
  # pp('ROOM TOGGLE LOVE', $room->toggle_love());
  # pp('ROOM ADD TAGS', $room->add_tags('some,comma-seperated,tags'));
  # note that remove_tags removes tags from our system, not from our cached index
  # the results for this will not be instant (or will if you're lucky)
  # pp('ROOM REMOVE TAGS', $room->remove_tags('some,comma-seperated,tags'));

  # pp('ROOM RENDER 3D', $room->render());
  # pp('ROOM RENDER 2D', $room->render('2d'));

  # pp('ROOM CHOWN', $room->chown([OTHER_USER_ID]));
  # pp('ROOM DELETE', $room->delete());

  # properties on room

  # pp('ROOM PRODUCTS', $room->products());
  # pp('ROOM RELATED ROOMS', $room->related_rooms());
  # pp('ROOM LOVED BY', $room->loved_by());
  # pp('ROOM COMMENTS', $room->comments());
?>
