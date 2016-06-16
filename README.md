# Roomstyler PHP API

---
This is a PHP library that makes it easier to use the Roomstyler RESTful API.
It is intended to be a wrapper for the API so that you, as user of the API
will never have to write your own HTTP requests but instead can simply call a method to do it for you.

---
This document is not yet finished, a lot of the documentation is still being worked on!
---

## Installation

I am going to assume here that you already have your PHP development stack installed or can atleast run PHP.

```
~$ cd project-root
project-root $ git clone git@github.com:SidOfc/roomstyler-api-php.git
```

If all went well you should now have all the files to run the API.

## Getting started

After cloning the project into your project's root directory you'll have to hook it into your application.
This is done by requiring the `lib/rs_api.php` file which includes the `RoomstylerApi` class and all the other requires to different files.

_index.php_

```php
<?php

  require 'lib/rs_api.php';

?>
```

After requiring this file you can get started by creating an instance of the API.

#### Anonymous API access

When you want to read public data

```php
<?php

  require 'lib/rs_api.php';

  # can read all public data
  $rsapi = new RoomstylerApi();

?>
```

#### User API access

For personal use, read global data or perform actions on rooms as the signed in user (whitelabel users can also login)

```php
<?php

  require 'lib/rs_api.php';

  # can read all public data
  # can perform actions on rooms such as placing a comment or toggling a like
  $rsapi = new RoomstylerApi(['user' => ['name' => 'myusername', 'password' => 'mypassword']]);

?>
```

#### Whitelabel API access

For when you want to read global data and read, write or modify your own whitelabel data

```php
<?php

  require 'lib/rs_api.php';

  # can read all public data
  # can read all whitelabel data
  $rsapi = new RoomstylerApi(['whitelabel' => ['name' => 'mywhitelabel', 'password' => 'mywhitelabelpassword']]);

?>
```

#### Godmode API access

For the those who want to maximize their integration potential, this allows you to read and write and modify data of your whitelabel and your own rooms and perform actions on rooms as the signed in user

```php
<?php

  require 'lib/rs_api.php';

  # can read all public data
  # can read all whitelabel data
  # can perform actions on rooms such as placing a comment or toggling a like
  $rsapi = new RoomstylerApi(['user' => ['name' => 'myusername', 'password' => 'mypassword'],
                              'whitelabel' => ['name' => 'mywhitelabel', 'password' => 'mywhitelabelpassword']]);

?>
```

After doing this setup you should probably run a simple test to check if you can actually get a response back from a call.

```php
<?php
  # print the 5 latest rooms
  echo '<pre>';
  print_r($rsapi->rooms->index(['limit' => 5]));
  echo '</pre>';
?>
```

#### More configuration options

We just talked about the `user` and `whitelabel` options that can be passed to the constructor of the `RoomstylerApi` class but there are more options:

* protocol
  * _default: `https`_  
  Specify the default protocol
* whitelabel
  * _default: `[]`_  
  Logs in a whitelabel (discussed above)
* user
  * _default: `[]`_  
  Logs in a user (discussed above)
* host
  * _default: `roomstyler.com`_  
  The default hostname for the API
* prefix
  * _default: `api`_  
  The default namespace that prepends every request route e.g. `rooms/10` => `api/rooms/10`
* token
  * _default: `NULL`_  
  When you log in through the `user` option this property will be set to the server generated token
* timeout
  * _default: `5`_  
  Maximum number of seconds to allow cURL to execute a function
* language
  * _default: `en`_  
  Specify the editor language, supports: `en`, `fr`, `de`, `es`, `nl`
* connect_timeout
  * _default: `30`_  
  Maximum number of seconds to wait before connection times out (use 0 to wait indefinitely)
* request_headers
  * _default: `['Content-Type: application/json; charset=utf-8']`_  
  The default content type used to communicate with our API using `POST` requests
* debug
  * _default: `false`_  
  Set to true to wrap results in an array containing `result` and `request_info` which can be used to view the request

Everything is already setup to work with the API so you barely have to change these settings.
The option you'll most likely be using is `debug` which allows you to take a peek into the request.

## Rooms

### Aggregation

#### <a name="search_meta"></a> Getting search meta data

**PHP snippet**

```php
<?php
  print_r($rsapi->rooms->search_meta());
  # => RoomstylerSearchMeta{}
?>
```

**Method signature**

```
RoomstylerRoomMethods->search_meta();
```

**Parameters**

* None

#### <a name="fetching_rooms"></a> Fetching rooms

**PHP snippet**

```php
<?php
  print_r($rsapi->rooms->index());
  # => [RoomstylerRoom{}, RoomstylerRoom{}, ...]
?>
```

**Method signature**

```
RoomstylerRoomMethods->index($params = []);
```

**Parameters**

* `$params` - Optional (Defaults do get set) - an array with the following keys:
  * `limit` - Optional (Default `50`) - A number between (and including) 1 and 50
  * `page` - Optional (Default `1`) - A number that defines the page you're on (useful for pagination)
  * `category` - Optional ([see `RoomstylerSearchMeta`](#search_meta)) - Filters results within specified category
  * `last_updated` - Optional - List rooms updated after a given timestamp
  * `created` - Optional - List rooms created after a given timestamp
  * `skip_last_updated` - Optional (Recommended, Default `true`) - skips fetching last updated room, significantly speeds up requests
  * `skip_total` - Optional (Recommended, Default `true`) - skips fetching a count of all rooms, significantly speeds up requests
  * `order` - Optional - Order results based on a room attribute (see a `RoomstylerRoom` object for a list of properties)
  * `direction` - Required if `order` specified - either `asc` or `desc`
  * `user_id` - Optional - fetch rooms owned by this user (requires user access)
  * `whitelabel` - Optional - fetch rooms owned by your whitelabel (requires whitelabel access)
  * `tag` - Optional - Filter rooms by given tag

#### Fetching whitelabel rooms

**This method accepts the same parameters as the non-scoped `index` method! The only difference is that the optional `whitelabel` parameter is set to the whitelabel user for you**

**PHP snippet**

```php
<?php
  # requires whitelabel access
  print_r($rsapi->wl->rooms->index());
  # => [RoomstylerRoom{}, RoomstylerRoom{}, ...]
?>
```

**Method signature and parameters: see [Fetching Rooms](#fetching_rooms)**

#### Finding a specific room

**PHP snippet**

```php
<?php
  print_r($rsapi->rooms->find(123456));
  # => RoomstylerRoom{}
?>
```

**Method signature**

```
RoomstylerRoomMethods->find($id);
```

**Parameters**

* `$id` - The id of the room to fetch

#### Searching rooms

**PHP snippet**

```php
<?php
  print_r($rsapi->rooms->search(['q' => 'test']));
  # => [RoomstylerRoom{}, RoomstylerRoom{}, ...]
?>
```

**Method signature**

```
RoomstylerRoomMethods->search($params = []);
```

**Parameters**

* `$params` - Optional (Defaults do get set) - an array with the following keys:
  * `q` - Required - A search string
  * `limit` - Optional (Default `50`) - A number between (and including) 1 and 50
  * `page` - Optional (Default `1`) - A number that defines the page you're on (useful for pagination)
  * `since` - Optional ([see `RoomstylerSearchMeta`](#search_meta)) - Filters results within specified timeframe
  * `category` - Optional ([see `RoomstylerSearchMeta`](#search_meta)) - Filters results within specified category
  * `style` - Optional ([see `RoomstylerSearchMeta`](#search_meta)) - Filters results within specified style
  * `kind` - Optional - If it has the value of `own` it will search through the logged in users rooms (requires user access)

#### Fetching panoramas

**PHP snippet**

```php
<?php
  print_r($rsapi->rooms->panoramas());
  # => [RoomstylerRoom{}, RoomstylerRoom{}, ...]
?>
```

**Method signature**

```
RoomstylerRoomMethods->panoramas($params = ['limit' => 50, 'page' => 1]);
```

**Parameters**

* `$params` - Optional (Defaults do get set) - an array with the following keys:
  * `limit` - Optional (Default `50`) - A number between (and including) 1 and 50
  * `page` - Optional (Default `1`) - A number that defines the page you're on (useful for pagination)
  * `since` - Optional ([see `RoomstylerSearchMeta`](#search_meta)) - Filters results within specified timeframe
  * `skip_total` - Optional - skips counting of panorama's, speeds up request slightly if `true`

### Actions

Lets say we initialize a `$room` variable and use that in the following requests like so:

```php
<?php $room = $rsapi->rooms->find(123456); ?>
```

#### Comment on a room

**PHP snippet**

```php
<?php
  # requires user access
  print_r($room->comment('My awesome comment!'));
  # => RoomstylerComment{}
?>
```

**Method signature**

```
RoomstylerRoom->comment($content);
```

**Parameters**

* `$content` - The comment text to be placed on the room

#### Toggle love on a room

**PHP snippet**

```php
<?php
  # requires user access
  print_r($room->toggle_love());
  # => RoomstylerRoom{}
?>
```

**Method signature**

```
RoomstylerRoom->toggle_love();
```

**Parameters**

* None

#### Change owner of a room

**PHP snippet**

```php
<?php
  # requires whitelabel access
  print_r($room->chown(972691));
  # => RoomstylerComment{}
?>
```

**Method signature**

```
RoomstylerRoom->chown($user_id);
```

**Parameters**

* `$user_id` - The target user that will be the new owner of the subject room (should be a user(id) of your whitelabel)

#### Delete a room

**PHP snippet**
```php
<?php

  # requires whitelabel access
  print_r($room->delete());
  # => RoomstylerRoom{}

?>
```

**Method signature**

```
RoomstylerRoom->delete();
```

**Parameters**

* None

#### Fetch products used in the room

**PHP snippet**

```php
<?php
  print_r($room->products());
  # => [RoomstylerProduct{}, RoomstylerProduct{}, ...]
?>
```

**Method signature**

```
RoomstylerRoom->products();
```

**Parameters**

* None

#### Fetch users loves of the room

**PHP snippet**

```php
<?php
  print_r($room->loved_by());
  # => [RoomstylerUser{}, RoomstylerUser{}, ...]
?>
```

**Method signature**

```
RoomstylerRoom->loved_by();
```

**Parameters**

* None

#### Fetch related rooms of a room

**PHP snippet**

```php
<?php
  print_r($room->related_rooms());
  # => [RoomstylerRoom{}, RoomstylerRoom{}, ...]
?>
```

**Method signature**

```
RoomstylerRoom->related_rooms();
```

**Parameters**

* None

#### Fetch comments on a room

**PHP snippet**

```php
<?php
  print_r($room->comments());
  # => [RoomstylerComment{}, RoomstylerComment{}, ...]
?>
```

**Method signature**

```
RoomstylerRoom->comment();
```

**Parameters**

* None

#### Add tags to a room

**PHP snippet**

```php
<?php
  print_r($room->add_tags(['first-tag', 'second-tag']));
  # => RoomstylerRoom{}
?>
```

**OR**

```php
<?php
  print_r($room->add_tags('first-tag,second-tag'));
  # => RoomstylerRoom{}
?>
```

**Method signature**

```
RoomstylerRoom->add_tags($tags)
```

**Parameters**

* `$tags` - Required - An array of individual tags or a string of comma-seperated tags

#### Remove tags from a room

**PHP snippet**

```php
<?php
  print_r($room->remove_tags(['first-tag', 'second-tag']));
  # => RoomstylerRoom{}
?>
```

**OR**

```php
<?php
  print_r($room->remove_tags('first-tag,second-tag'));
  # => RoomstylerRoom{}
?>
```

**Method signature**

```
RoomstylerRoom->remove_tags($tags)
```

**Parameters**

* `$tags` - Required - An array of individual tags or a string of comma-seperated tags


#### Render room in 2D/3D

**PHP snippet**

```php
<?php
  print_r($room->render());
  # => RoomstylerRoom{}
?>
```

**Method signature**

```
RoomstylerRoom->render($mode = '', $params = [])
```

**Parameters**

* `$mode` - Optional (should be nothing or `2d`) - Specify rendering method, if left empty it will render in 3D
* `$params` - An array containing the following keys
  * `width` - Optional (Default value of `1920`) - Width at which to render room
  * `height` - Optional (Default value of `1080`) - Height at which to render room
  * `callback` - Optional (Required if `$mode` is `2d`) - A callback url that will receive a `POST` request when rendering is done

## Users

### Aggregation, Creation and Login

#### Finding users

**PHP snippet**

```php
<?php
  print_r($api->users->find(972691));
  # => RoomstylerUser{}
?>
```

**OR**

```php
<?php
  print_r($api->users->find([972691, 972691]));
  # => [RoomstylerUser{}, RoomstylerUser{}, ...]
?>
```

**OR**

```php
<?php
  print_r($api->users->find('972691, 972691'));
  # => [RoomstylerUser{}, RoomstylerUser{}, ...]
?>
```

**Method signature**

```
RoomstylerUserMethods->find($ids)
```

**Parameters**

* `$ids` - Required - The `id` of a user, an array of `id`s or a string of comma seperated `id`s


#### Create a user

**PHP snippet**

```php
<?php
  print_r($api->users->create(['email' => 'my-email@provider.com', 'username' => 'myusername', 'password' => 'mypassword']));
  # => RoomstylerUser{}
?>
```

**Method signature**

```
RoomstylerUserMethods->create($params = [])
```

**Parameters**

* `$params` - Required
  * `email` - Required - Email we want to use for this account
  * `username` - Required
  * `password` - Required


#### Login

If you read over the user access setup section I showed an example of logging in as a user within the `constructor` of the object.
It is however, also possible to login seperately like this, if You didn't login before and call this function manually later, all requests from then on will have
user access.

This function also returns the token needed to use in other requests such as to comment or love a room.

Also, if you're already logged in you do not need to use this function this.

**PHP snippet**

```php
<?php
  print_r($api->users->login('my-email@provider.com', 'mypassword'));
  # => RoomstylerUser{}
?>
```

**Method signature**

```
RoomstylerUserMethods->login($email, $password)
```

**Parameters**

* `$email` - Required - Email to use
* `$password` - Required - Password for the account

### Actions

We initialize a `$user` variable and use that in the following requests like so:

```php
<?php $user = $rsapi->users->find(972691); ?>
```

#### Delete a user

Deletes a given user

**PHP snippet**

```php
<?php
  print_r($user->delete());
  # => RoomstylerUser{}
?>
```

**Method signature**

```
RoomstylerUser->delete()
```

**Parameters**

* None

#### Get user loved rooms


**PHP snippet**

```php
<?php
  print_r($user->loved_rooms());
  # => [RoomstylerRoom{}, RoomstylerRoom{}, ...]
?>
```

**Method signature**

```
RoomstylerUser->loved_rooms($params = [])
```

**Parameters**

* None
