# Roomstyler PHP API

---
This is a PHP library that makes it easier to use the Roomstyler RESTful API.
It is intended to be a wrapper for the API so that you, as user of the API
will never have to write your own HTTP requests but instead can simply call a method to do it for you.

## <a name="heading_toc"></a> Table of contents

* [Table of contents](#heading_toc)
* [Installation](#heading_install)
* [Getting started](#heading_getting_started)
* [Structure](#heading_structure)
  * [RoomstylerApi](#structure_object_roomstyler_api)
  * [RoomstylerMethodBase](#structure_object_roomstyler_method_base)
  * [RoomstylerModelBase](#structure_object_roomstyler_model_base)
* [Errors](#errors)
  * [RoomstylerError](#roomstyler_error)
    * [`any()`](#roomstyler_error_any)
    * [`get()`](#roomstyler_error_get)
    * [`each()`](#roomstyler_error_each)
* [API endpoints](#api_endpoints)
  * [Rooms](#heading_rooms)
    * [List](#fetch_rooms)
    * [List whitelabel](#fetch_whitelabel_rooms)
    * [Find](#find_specific_room)
    * [Search](#searching_rooms)
    * [Panorama's](#fetch_panoramas)
    * [Comment](#comment_on_room)
    * [Toggle love](#love_a_room)
    * [Change owner](#change_room_owner)
    * [Delete](#delete_room)
    * [Products](#fetch_room_products)
    * [Loves](#fetch_room_loves)
    * [Related rooms](#fetch_related_rooms)
    * [Comments](#fetch_room_comments)
    * [Add tags](#add_room_tags)
    * [Remove tags](#remove_room_tags)
    * [Render](#render_room)
  * [Users](#heading_users)
    * [Find](#finding_users)
    * [Create](#create_a_user)
    * [Login](#user_login)
    * [Delete](#delete_a_user)
    * [Loved rooms](#user_loved_rooms)
    * [Collections](#user_collections)
    * [Specific user collections](#user_specific_collection)
  * [Contests](#heading_contests)
    * [List](#fetch_contests)
    * [Find](#fetch_specific_contest)
    * [Entries](#fetch_contest_entries)
  * [Contest Entries](#heading_contest_entries)
    * [Vote](#vote_for_contest_entry)
  * [Materials](#heading_materials)
    * [Find](#find_material)
  * [Components](#heading_components)
    * [Find](#find_component)
  * [Categories](#heading_categories)
    * [List](#fetch_categories)
* [3dplanner](#3dplanner)
  * [Embed](#embed_3dplanner)

## <a name="heading_install"></a> Installation

I am going to assume here that you already have your PHP development stack installed or can atleast run PHP.

```
~$ cd project-root
project-root $ git clone git@github.com:SidOfc/roomstyler-api-php.git
```

If all went well you should now have all the files to run the API.

## <a name="heading_getting_started"></a> Getting started

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

#### <a name="heading_config_options"></a> More configuration options

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

## <a name="heading_structure"></a> Structure

This is a general overview of the structure of the core objects behind the API, I will try to explain what you can do and when you can do it as best as I can.
The API is OOP only, which means you're going to have to apply a little bit of PHP's OOP but don't worry, it'll be easy!

### <a name="structure_object_roomstyler_api"> RoomstylerApi

Starting with the `RoomstylerApi` class, this is the base for the entire API.
It contains the settings and defaults that we've already discussed [here](#heading_config_options)

It also handles calls like this that you will be using:

```php
#call to a new instance of "RoomstylerRoomMethods"
$api->rooms;

#call to a new instance of "RoomstylerComponentMethods"
$api->components;
```

If you're someone who has done OOP for some time and are familiar with [PHP's magic methods](http://php.net/manual/en/language.oop5.magic.php)
and more specifically [the `__get` magic method](http://php.net/manual/en/language.oop5.overloading.php#object.get) you'll know exactly what I'm talking about.

When you call something like `->components` on the `$api` (Could be any variable name, must be an instance of the `RoomstylerApi` class)
the `$api` will look up a class with the name of `RoomstylerComponentMethods`.

It does this by first converting whatever property you're trying to call to its singular form so `components` becomes `component`, then converting the first character to uppercase so `component` becomes `Component`
and last but not least it *prepends* `Roomstyler` and *appends* `Methods` so that the final result becomes `RoomstylerComponentMethods`.

If you're already using the singular form of a word, e.g. `component` then the step to convert will do nothing and it will still uppercase the first character and *prepend* `Roomstyler` and *append* `Methods`

This `RoomstylerComponentMethods` class extends `RoomstylerMethodBase` and allows you to call the [documented component aggregation methods](#heading_components).

Essentially this means that you can call any property and get either an instance of a class back if it exists and is included, or a `Fatal Error: Class 'Roomstyler[NonExistentClass]Methods' not found in...`.

### <a name="structure_object_roomstyler_method_base"> RoomstylerMethodBase

This is the base class behind the scenes that allows you to use any and all of the `RoomstylerApi->_settings` within an instance of `Roomstyler[...]Methods`
It's purpose is to provide a standard interface for actions you execute to **get** a dataset.

### <a name="structure_object_roomstyler_model_base"> RoomstylerModelBase

This is the base class behind the returned results from the requests. You use the methods in `Roomstyler[...]Methods` to get a set of results (through some `find()`, `index()` or `search()` action)
after which you get a single object or an array of objects back which you can then manipulate.

#### <a name="structure_object_roomstyler_model_base_properties"> Properties

This base class is actually more useful than the `RoomstylerMethodBase` since this one does the same and more, it also dynamically populates itself with properties returned from the API.

To get an idea of what I'm talking about, consider this `json` response from `api/users/972691` it should look something like this:

```json  
{
  "id": 972691,
  "username": "Sidney Liebrand",
  "role": "admin",
  "bio": "I'm a 21 year old web developer from the Netherlands, born, raised and still living in the always magnificent Dinteloord.",
  "avatar": "https://d2sdvaauesfb7j.cloudfront.net/avatars/972691-1434979777.jpg",
  "background": "https://d2sdvaauesfb7j.cloudfront.net/img/empty.jpeg"
}
```

And compare it to the return object that would look like this after a successful request and being `__construct`ed

```php
<?php
$api = new RoomstylerApi(['user' => $CONFIG['user_credentials']]);
$user = $api->user->find(972691);

echo '<pre>';
print_r($user);
# =>

RoomstylerUser Object
(
    [_fields_set:RoomstylerModelBase:private] => 1
    [_http_status:RoomstylerModelBase:private] => 200
    [_accessible_props:RoomstylerModelBase:private] => ['errors']

    [errors:RoomstylerModelBase:private] => RoomstylerError Object
        (
        )

    [_settings:protected] => Array
        (
            [protocol] => https
            [whitelabel] => Array
                (
                )

            [user] => Array
                (
                    [name] => mysignin@email.com
                    [password] => mysignin_password
                )

            [host] => roomstyler.com
            [prefix] => api
            [token] => 64f97c9ee52df2735fsample-tokene6e252d58837d41b05cd
            [timeout] => 5
            [language] => en
            [connect_timeout] => 30
            [request_headers] => Array
                (
                    [0] => Content-Type: application/json; charset=utf-8
                )

            [debug] =>
            [user_agent] => RoomstylerApi/1.0 Type/normal (https://roomstyler.com)
        )

    [_whitelabeled:protected] =>
    [id] => 972691
    [username] => Sidney Liebrand
    [role] => admin
    [bio] => I'm a 21 year old web developer from the Netherlands, born, raised and still living in the always magnificent Dinteloord.
    [avatar] => https://d2sdvaauesfb7j.cloudfront.net/avatars/972691-1434979777.jpg
    [background] => https://d2sdvaauesfb7j.cloudfront.net/img/empty.jpeg
)
?>
```

Now all the properties that start with an underscore (`_`) are also `:protected` which means we can't even access them.
If you try accessing this freshly fetched users `_whitelabel` property `$user->_whitelabel` it would simply return `Notice:  Undefined property: RoomstylerUser::$_whitelabel`

If you tried to access the public (and dynamically populated) `id` on the other hand, you would get either `NULL` or it's value if it's set.
The same goes for all other properties. Normally you would get a notice if you call a property that does not exist on an object (`$user->non_existent_prop`): `Notice:  Undefined property: RoomstylerUser::$non_existent_prop` but since the fields are *subject to change* this would mean that you could get random `Notice` errors for no reason.

Because of this, all properties that do not exist or aren't public (except `errors` which is made public through `__get`) will return `NULL`.

#### <a name="structure_object_roomstyler_model_base_methods"> Methods

The `RoomstylerModelBase` class also provides us with some other methods we can use to see wether a request was `successful()`, the object actually `exists()` (not just an empty object - but actually having properties), or if the object in question has any `errors()`

This is done (using our `$user` initiated on top) by calling `$user->errors()` for any json rendered errors, `$user->successful()` to check wether the request returned with a http status of less than `400` and no json errors or `$user->exists()` to check if any property is set at all.

## <a name="errors"></a> Errors

Every object returned from the API will have an accessible `errors` property that has a few methods.
To check wether any of the objects contains any error (including http errors)

the maximum limit of the index call is 50.
if this is exceeded the server will return a JSON error and an Unprocessable Entity (422) http error

```php
$response = $rsapi->rooms->index(['limit' => 100]);

# full response
# since the API call errored, a single object is returned instead of an array of objects.
print_r($response);

# if this didn't happen, you'd have to call
print_r($response[0]);

# to see the errors (which are always bound to every returned object)
```

To access these errors, call the `errors` property on a single entity.
It will return an instance of the [`RoomstylerError`](#roomstyler_error) class.

```php
$response->errors
# => RoomstylerError{}
```

### <a name="roomstyler_error"></a> RoomstylerError

The `RoomstylerError` class has three methods, one to [`get()`](#roomstyler_error_get) all the errors.
One to see if there are [`any()`](#roomstyler_error_any) errors and a function that loops through [`each()`](#roomstyler_error_each) error.

They will be explained using this example request.

(This request will error out since the max. limit for the `index()` function is `50`)

```php
$response = $rsapi->rooms->index(['limit' => 100]);
```

#### <a name="roomstyler_error_any"></a> Check if there are errors

The [`any()`](#roomstyler_error_any) function will return `true` if any errors including any http errors occured.

```php
$response->errors->any();
# => true
```

#### <a name="roomstyler_error_get"></a> Get all errors

The [`get()`](#roomstyler_error_get) function will return an array of errors including http errors.
If there are no errors, an empty array is returned.

The array returned is associative and will contain numeric and string keys.
As you might be able to see below, the errors have different depths making it hard to property loop the errors.
The solution for this is the builtin [`each()`](#roomstyler_error_each) method which is explained later.

```php
$response->errors->get();
# => [0 => 'Some error', 'assoc' => 'too', 'user' => ['nested' => ['errors', 'for a single entity']]]
```

#### <a name="roomstyler_error_each"></a> Loop all the errors

If there are errors, you can loop these through the [`each()`](#roomstyler_error_each) function.
It takes one parameter, which is a closure or callable function that itself takes one (optionally two) parameter(s)
The first parameter contains the *error message* of an error and the second parameter will contain it's parent keys if it was a nested hash.

As we know the above `$response` example gives us two errors, one through a nested *json* object with an `error` key which contains the error. And a http error since we're requesting too much data.

If we'd print the [`get()`](#roomstyler_error_get) function with this example, the errors array would look like this:

```php
# => [
  'error' => 'Limit should be between 1 and 50',
  0 => 'Unprocessable entity'
]
```

Knowing the structure of the errors, if we build something like this:

```php
$response->errors->each(function($error, $labels) {
  if ($labels) $label = join('.', $labels);
  else $label = 'default_label';

  print_r($label": $error");
});
```

The output will look like this:

```
error: Limit should be between 1 and 50
default_label: Limit should be between 1 and 50
```

If the `error` key wasn't an error but instead another hash with say a `quantity` key containing the same error, the output for the same request would look like:

```
error.quantity: Limit should be between 1 and 50
default_label: Limit should be between 1 and 50
```

## <a name="api_endpoints"></a> API endpoints

### <a name="heading_rooms"></a> Rooms

#### Aggregation

##### <a name="search_meta"></a> Getting search meta data

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

##### <a name="fetch_rooms"></a> Fetching rooms

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

* `$params` - Optional (Defaults do get set) - An array containing any the following keys:
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

##### <a name="fetch_whitelabel_rooms"></a> Fetching whitelabel rooms

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

##### <a name="find_specific_room"></a> Finding a specific room

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

##### <a name="searching_rooms"></a> Searching rooms

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

* `$params` - Optional (Defaults do get set) - An array containing any the following keys:
  * `q` - Required - A search string
  * `limit` - Optional (Default `50`) - A number between (and including) 1 and 50
  * `page` - Optional (Default `1`) - A number that defines the page you're on (useful for pagination)
  * `since` - Optional ([see `RoomstylerSearchMeta`](#search_meta)) - Filters results within specified timeframe
  * `category` - Optional ([see `RoomstylerSearchMeta`](#search_meta)) - Filters results within specified category
  * `style` - Optional ([see `RoomstylerSearchMeta`](#search_meta)) - Filters results within specified style
  * `kind` - Optional - If it has the value of `own` it will search through the logged in users rooms (requires user access)

##### <a name="fetch_panoramas"></a> Fetching panoramas

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

* `$params` - Optional (Defaults do get set) - An array containing any the following keys:
  * `limit` - Optional (Default `50`) - A number between (and including) 1 and 50
  * `page` - Optional (Default `1`) - A number that defines the page you're on (useful for pagination)
  * `since` - Optional ([see `RoomstylerSearchMeta`](#search_meta)) - Filters results within specified timeframe
  * `skip_total` - Optional - skips counting of panorama's, speeds up request slightly if `true`

### Actions

Lets say Let's initialize a `$room` variable and use that in the following requests like so:

```php
<?php $room = $rsapi->rooms->find(123456); ?>
```

##### <a name="comment_on_room"></a> Comment on a room

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

##### <a name="love_a_room"></a> Toggle love on a room

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

##### <a name="change_room_owner"></a> Change owner of a room

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

##### <a name="delete_room"></a> Delete a room

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

##### <a name="fetch_room_products"></a> Fetch products used in the room

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

##### <a name="fetch_room_loves"></a> Fetch users loves of the room

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

##### <a name="fetch_related_rooms"></a> Fetch related rooms of a room

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

##### <a name="fetch_room_comments"></a> Fetch comments on a room

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

##### <a name="add_room_tags"></a> Add tags to a room

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

##### <a name="remove_room_tags"></a> Remove tags from a room

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


##### <a name="render_room"></a> Render room in 2D/3D

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
* `$params` - An array containing any the following keys:
  * `width` - Optional (Default value of `1920`) - Width at which to render room
  * `height` - Optional (Default value of `1080`) - Height at which to render room
  * `callback` - Optional (Required if `$mode` is `2d`) - A callback url that will receive a `POST` request when rendering is done

### <a name="heading_users"></a> Users

#### Aggregation, Creation and Login

##### <a name="finding_users"></a> Finding users

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


##### <a name="create_a_user"></a> Create a user

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


##### <a name="user_login"></a> Login

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

Let's initialize a `$user` variable and use that in the following requests like so:

```php
<?php $user = $rsapi->users->find(972691); ?>
```

##### <a name="delete_a_user"></a> Delete a user

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

##### <a name="user_loved_rooms"></a> Get user loved rooms


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

* `$params` - Optional (Defaults do get set) - An array containing any the following keys:
  * `page` - Optional - The page on which you're on (sets query offset to `(page - 1) * per_page`)
  * `per_page` - Optional - The amount of results to display on a page
  * `skip_total` - Optional (Default `true`) - skips counting results, speeds up query slightly

##### <a name="user_collections"></a> Get user collections

**PHP snippet**

```php
<?php
  print_r($user->collections());
  # => [RoomstylerCollection{}, RoomstylerCollection{}, ...]
?>
```

**Method signature**

```
RoomstylerUser->collections()
```

**Parameters**

* None

##### <a name="user_specific_collection"></a> Get specific user collection

**PHP snippet**

```php
<?php
print_r($user->collection(44));
# => RoomstylerCollection{}
?>
```

**Method signature**

```
RoomstylerUser->collection($id)
```

**Parameters**

* `$id` - Required - which of the users' collections to fetch

### <a name="heading_contests"></a> Contests

#### Aggregation

##### <a name="fetch_contests"></a> Fetch contests

**PHP snippet**

```php
<?php
print_r($api->contests->index());
# => [RoomstylerContest{}, RoomstylerContest{}, ...]
?>
```

**Method signature**

```
RoomstylerContestMethods->index($params = [])
```

**Parameters**

* `$params` - Optional (Defaults do get set) - An array containing any the following keys:
  * `per_page` - Optional (Default `25`) - A number between (and including) 1 and 50
  * `page` - Optional (Default `1`) - A number that defines the page you're on (useful for pagination)
  * `status` - Optional - Either `"finished"`, `"open"` or `"vote"`
  * `title` - Optional - Return only contests where given string is contained within their `title`

##### <a name="fetch_specific_contest"></a> Fetch a contest

**PHP snippet**

```php
<?php
print_r($api->contests->find(1317));
# => RoomstylerContest{}
?>
```

**Method signature**

```
RoomstylerContestMethods->find($id)
```

**Parameters**

* `$id` - Required - the `id` of the contest to fetch

### Actions

Let's initialize a `$contest` variable and use that in the following requests like so:

```php
<?php $contest = $rsapi->contests->find(1317); ?>
```

##### <a name="fetch_contest_entries"></a> Fetch contest entries

**PHP snippet**

```php
<?php
print_r($contest->entries());
# => [RoomstylerContestEntry{}, RoomstylerContestEntry{}, ...]
?>
```

**Method signature**

```
RoomstylerContest->entries($params = [])
```

**Parameters**

* `$params` - Optional (Defaults do get set) - An array containing any the following keys:
  * `per_page` - Optional (Default `25`) - A number between (and including) 1 and 50
  * `page` - Optional (Default `1`) - A number that defines the page you're on (useful for pagination)
  * `order` - Optional - Attribute to order by and the direction to order by
  * `rand_seed` - Optional - If supplied, entries will be returned psuedo-random based on the seed (must be an integer)

### <a name="heading_contest_entries"></a> Contest entries

### Actions

Let's initialize a `$contest_entry` variable and use that in the following requests like so:

```php
<?php $contest_entry = $rsapi->contests->find(1317)->entries()[0]; ?>
```

##### <a name="vote_for_contest_entry"></a> Vote for a contest entry

**PHP snippet**

```php
<?php
# requires user access
print_r($contest_entry->vote());
# => RoomstylerVote{}
?>
```

**Method signature**

```
RoomstylerContestEntry->vote()
```

**Parameters**

* None

### <a name="heading_material"></a> Material

#### Aggregation

##### <a name="find_material"></a> Find a material item

**PHP snippet**

```php
<?php
print_r($api->materials->find(3360));
# => RoomstylerMaterial{}
?>
```

**Method signature**

```
RoomstylerMaterialMethods->find($id)
```

**Parameters**

* `$id` - Required - the `id` of the material item to fetch

### <a name="heading_components"></a> Component

#### Aggregation

##### <a name="find_component"></a> Find a component item

**PHP snippet**

```php
<?php
print_r($api->components->find('7b7e830978663ca44cafe62f095ee5f05af7670b'));
# => RoomstylerComponent{}
?>
```

**Method signature**

```
RoomstylerComponentMethods->find($id)
```

**Parameters**

* `$id` - Required - the `id` of the component item to fetch

### <a name="heading_categories"></a> Categories

#### Aggregation

##### <a name="fetch_categories"></a> Fetching categories

**PHP snippet**

```php
<?php
print_r($api->categories->index());
# => [RoomstylerCategory{}, RoomstylerCategory{}, ...]
?>
```

**Method signature**

```
RoomstylerCategoryMethods->index()
```

**Parameters**

* None

## <a name="3dplanner"></a> 3dplanner

### <a name="embed_3dplanner"></a> Embedding the 3dplanner

**PHP snippet**

```php
<?php
print_r($api->editor->embed());
# => <iframe...>
?>
```

**Method signature**

```
RoomstylerEditor->embed($opts = [], $html_opts = [])
```

**Parameters**

* `$opts` - Optional (Defaults do get set) - An array containing any the following keys:
  * `room_url` - Optional - Opens a `room_url` (returned from the `RoomstylerRoom->url` property)
  * `token` - Optional - Log in a user through a token
  * `language` - Optional - Set the language for the editor to use, `en`, `es`, `nl`, `fr` and `de`
  * `login` - Optional - if false, prevents all logins
* `$html_opts` - Optional (Defaults do get set) - An array consisting of valid html attribute => value pairs
  * `frameborder` - Optional (Default `0`) - HTML prop to hide the border around the editor
  * `width` - Optional (Default `1024`) - Width of the editor iframe
  * `height` - Optional (Default `768`) - Height of the editor iframe
