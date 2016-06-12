<?php

  class RoomstylerRequest extends RoomstylerBase {

    private static $_settings = [];

    const DELETE = 'delete';
    const POST = 'post';
    const GET = 'get';
    const PUT = 'put';
    const PATCH = 'patch';

    public static function OPTIONS(array $arr) {
      self::$_settings = $arr;
    }

    public static function send($type, $path, array $args = [], $method = self::GET) {
      $mtd = self::fallback_method($method);
      if ($method == self::GET) $args = self::build_arg_array($args, $mtd);
      if ($mtd[1] !== NULL) $args[self::$_settings['method_param']] = $mtd[1];
      $url = self::build_url($path, ($method == self::GET ? $args : []));
      $res = self::curl_fetch($url, $args, $mtd[0]);

      if ($type == NULL) return $res;

      $final_res = self::collection_from_response($type, $res);

      if (!self::$_settings['debug']) return $final_res;
      return [
        'result' => $final_res,
        'request_info' => new RoomstylerResponse([
          'type' => $type,
          'path' => $path,
          'full_path' => $url,
          'arguments' => $args,
          'method' => $mtd[0],
          'original_method' => $mtd[1],
          'status' => $res['status'],
          'headers' => $res['headers'],
          'body' => $res['body'],
          'errors' => $res['errors']])];
    }

    private static function collection_from_response($type, $res, $store = false) {
      # check if JSON response root node contains a property (either plural or singular) for what we're trying to fetch
      # if found, reassign $res to this property and continue creating collection
      $singular_type = strtolower(str_replace('Roomstyler', '', $type));
      $plural_type = parent::to_plural($singular_type);
      $out = [];
      $errors = [];
      $status = $res['status'];
      if (isset($res['errors']) && !is_array($res['errors'])) $errors = $res['errors'];
      $errors = $res['errors'];
      if (isset($res['error'])) array_merge($errors, ['missing_parameter' => $res['error']]);
      $res = $res['body'];

      if (is_object($res))
        if (property_exists($res, $plural_type)) $res = $res->$plural_type;
        else if (property_exists($res, $singular_type)) $res = $res->$singular_type;

      #SearchMeta request is different since it fetches multiple nested resources
      if ($singular_type == 'searchmeta') return new RoomstylerSearchMeta($res, $errors, $status);

      # if result is an array then we want to return an array of wrapped objects
      if (is_array($res))
        # if the count is only one, there's probably a root node wrapping the data
        if (count($res) == 1) $out = new $type(array_shift($res), $errors, $status);
        else foreach($res as $_ => $obj) $out[] = new $type($obj, $errors, $status);
      else $out = new $type($res, $errors, $status);
      return $out;
    }

    private static function build_arg_array($args, $mtd) {
      if (count($args) <= 0) return [];

      $out = [];

      foreach ($args as $key => $value) $out[urlencode($key)] = urlencode($value);
      return $out;
    }

    private static function fallback_method($method) {
      if ($method == self::POST || $method == self::GET) return [$method, NULL];
      return [self::POST, $method];
    }

    private static function build_url($path, $args = NULL) {
      $base_path = self::$_settings['protocol'] . '://';
      if (self::$_settings['whitelabel'] && parent::is_scoped_for_wl()) $base_path .= self::$_settings['whitelabel'] . '.';
      if (self::$_settings['host']) $base_path .= self::$_settings['host'] . '/';
      if (self::$_settings['prefix']) $base_path .= self::$_settings['prefix'];
      $url = $base_path . '/' . $path;

      if ($args) {
        $query = [];
        foreach ($args as $key => $value) $query[] = $key . '=' . $value;
        return ($url . '?' . join('&', $query));
      }

      return $url;
    }

    private static function curl_fetch($url, array $params, $mtd) {
      $curl = curl_init();

      curl_setopt_array($curl, [
        CURLINFO_HEADER_OUT => true,
        CURLOPT_COOKIE => true,
        CURLOPT_COOKIEFILE => true,
        CURLOPT_HEADER => true,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_URL => $url,
        CURLOPT_HTTPHEADER => self::$_settings['request_headers'],
        CURLOPT_CONNECTTIMEOUT => self::$_settings['connect_timeout'],
        CURLOPT_TIMEOUT => self::$_settings['timeout'],
        CURLOPT_USERAGENT => self::$_settings['user_agent']]);

      # request authentication through http basic
      if (parent::is_scoped_for_wl()) {
        list($uname, $upass) = [self::$_settings['whitelabel'], self::$_settings['password']];
        curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($curl, CURLOPT_USERPWD, $uname . ':' . $upass);
        parent::scope_wl(false);
      }

      # request authentication through user login
      if (self::$_settings['token']) {
        if (!is_array($params)) $params = [];
        $params = array_merge(['token' => self::$_settings['token']], $params);
      }

      # request method handling is done prior to this step.
      # if a request needs to be post (e.g. on DELETE, PATCH etc...) it will be
      if (!empty($params) && $mtd == self::POST) {
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($params));
      }

      $raw = curl_exec($curl);
      $curl_info = curl_getinfo($curl);
      $http_status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
      curl_close($curl);

      $response_headers = substr($raw, 0, $curl_info['header_size']);
      $body = substr($raw, $curl_info['header_size']);
      $errors = [];

      if ($body && $http_status < 400) $body = json_decode($body);
      else $errors = json_decode($body, true);

      $res = ['body' => $body,
              'headers' => [
                'request' => self::parse_header_str($curl_info['request_header']),
                'response' => self::parse_header_str($response_headers)],
              'status' => $http_status,
              'errors' => $errors];

      return $res;
    }

    private static function parse_header_str($header_str) {
      $out = [];
      $header_lines = preg_split('/\r\n|\n|\r/', $header_str);
      $header_lines = array_filter($header_lines, function($value) { return strlen($value) > 0; });

      foreach ($header_lines as $line) {
        $split_header = explode(': ', $line);
        if (count($split_header) == 1) $out['HTTP'][] = $split_header[0];
        else $out[$split_header[0]] = $split_header[1];
      }

      return $out;
    }

  }
?>
