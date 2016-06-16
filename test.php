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

  # remove this line if you don't need to do any writing to the API
  require 'config.example.php';
  require 'index.php';

  $api = new RoomstylerApi(['whitelabel' => $CONFIG['whitelabel_credentials'], 'user' => $CONFIG['user_credentials']]);

  ################################################################################
  #                                                                              #
  #                                   ROOM                                       #
  #                                                                              #
  ################################################################################

  # fetch rooms from the server, ROOM SEARCH can take parameters for each type in SEARCH_META
  # e.g. category, style or timeframes

  # search_meta does not return an object or array of objects that you can work with directly.
  # it returns an object with properties that each contain an object or array that you can work with.
  # search meta fetches categories, styles and timeframes so if you had a variable $search_meta = $api->rooms->search_meta();
  # you could then do $search_meta->categories to get the categories

  # the categories returned by this call will be the same as in the $api->categories->index(); call
  # pp('ROOM SEARCH META', $api->rooms->search_meta());

  # pp('ROOM INDEX', $api->rooms->index(['limit' => 5]));
  # pp('ROOM INDEX WL', $api->wl->rooms->index(['limit' => 5]));
  # pp('ROOM FIND', $api->rooms->find(715833));
  # pp('ROOM SEARCH', $api->rooms->search(['q' => 'test', 'limit' => 5]));
  # pp('ROOM PANORAMAS', $api->rooms->panoramas(['limit' => 5]));

  # actions on room
  # $room = $api->rooms->find(14075620);

  # room [comment, toggle_love, chown] cannot be executed without regular authentication of a user
  # pp('ROOM PLACE COMMENT', $room->comment('with user credentials and without wl credentials'));
  # pp('ROOM TOGGLE LOVE', $room->toggle_love());
  # pp('ROOM CHOWN', $room->chown(1350462));
  # pp('ROOM DELETE', $room->delete());
  # pp('ROOM PRODUCTS', $room->products());
  # pp('ROOM RELATED ROOMS', $room->related_rooms());
  # pp('ROOM LOVED BY', $room->loved_by());
  # pp('ROOM COMMENTS', $room->comments());
  # pp('ROOM ADD TAGS', $room->add_tags('some,comma-seperated,tags'));
  # pp('ROOM RENDER 3D', $room->render());

  # render_2d doesn't work without a callback url
  # pp('ROOM RENDER 2D', $room->render('2d', ['callback' => 'https://fail.nonexistent.com']));

  # note that remove_tags removes tags from our system, not from our cached index
  # the results for this will not be instant (or will if you're lucky)
  # pp('ROOM REMOVE TAGS', $room->remove_tags('some,comma-seperated,tags'));

  ################################################################################
  #                                                                              #
  #                                   USER                                       #
  #                                                                              #
  ################################################################################

  # fetch user(s) by supplied id(s)
  # using a numeric ID
  # pp('USER FIND', $api->users->find(972691));

  # using a comma seperated string of id's
  # pp('USER FIND', $api->users->find('972691,972693,100034'));

  # create a user
  # pp('USER CREATE', $api->users->create(['username' => 'some_username', 'password' => 'some_password', 'email' => 'some_email@example.tld']));

  # actions on a user
  # $user = $api->users->find(972691);

  # pp('USER DELETE', $user->delete());
  # pp('USER LOVED_ROOMS', $user->loved_rooms());
  # pp('USER COLLECTIONS', $user->collections());
  # pp('USER COLLECTION', $user->collection(42));

  ################################################################################
  #                                                                              #
  #                                 CONTEST                                      #
  #                                                                              #
  ################################################################################

  # fetch contests
  # pp('CONTEST INDEX', $api->contests->index(['limit' => 5]));
  # pp('CONTEST FIND', $api->contests->find(1317));

  # actions on contest
  # $contest = $api->contests->find(1317);

  # pp('CONTEST ENTRIES', $contest->entries());

  ################################################################################
  #                                                                              #
  #                               COLLECTION                                     #
  #                                                                              #
  ################################################################################

  # fetch collection(s)
  # directly calling collection methods instead of through a RoomstylerUser object
  # returns the global list of collections instead of that users collections
  # pp('COLLECTION INDEX', $api->collections->index());
  # pp('COLLECTION FIND', $api->collections->find(44));

  # actions on collection
  # $collection = $api->collections->find(44)['result'];

  # pp($collection->items());

  ################################################################################
  #                                                                              #
  #                                MATERIAL                                      #
  #                                                                              #
  ################################################################################

  # fetch material
  # pp('MATERIAL FIND', $api->materials->find(3360));

  ################################################################################
  #                                                                              #
  #                                COMPONENT                                     #
  #                                                                              #
  ################################################################################

  # fetch component
  # pp('COMPONENT FIND', $api->components->find('7b7e830978663ca44cafe62f095ee5f05af7670b'));

  ################################################################################
  #                                                                              #
  #                                CATEGORIES                                    #
  #                                                                              #
  ################################################################################

  # fetch categories
  # pp('CATEGORY INDEX', $api->categories->index());

  ################################################################################
  #                                                                              #
  #                                   EMBED                                      #
  #                                                                              #
  ################################################################################

  echo $api->editor->embed();

?>
