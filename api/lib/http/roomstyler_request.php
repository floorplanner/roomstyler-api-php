<?php

  class RoomstylerRequest extends RoomstylerBase {

    private static $_settings = [];
    private static $scope_wl = false;

    const DELETE = 'delete';
    const POST = 'post';
    const GET = 'get';
    const PUT = 'put';
    const PATCH = 'patch';

    public function __construct(array $arr = []) {
      if (!empty($arr)) self::OPTIONS($arr);
    }

    public static function OPTIONS(array $arr) {
      self::$_settings = $arr;
    }

    public static function scope_wl($b = NULL) {
      if (is_bool($b)) self::$scope_wl = $b;
      else self::$scope_wl = false;
      return self::$scope_wl;
    }

    public static function send($type, $path, array $args = [], $method = self::GET) {
      $mtd = self::fallback_method($method);
      if ($method == self::GET) $args = self::build_arg_array($args, $mtd);
      $url = self::build_url($path, ($method == self::GET ? $args : []));
      $res = self::curl_fetch($url, $args, $method);

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
          'status' => $res['status'],
          'headers' => $res['headers'],
          'body' => $res['body'],
          'errors' => $res['errors']])];
    }

    private static function collection_from_response($type, $res, $store = false) {
      # check if JSON response root node contains a property (either plural or singular) for what we're trying to fetch
      # if found, reassign $res to this property and continue creating collection
      $plural_type = parent::to_plural(strtolower(str_replace('Roomstyler', '', $type)));
      $singular_type = strtolower(str_replace('Roomstyler', '', $type));
      $out = [];
      $errors = $res['errors'];
      $res = $res['body'];

      if (is_object($res))
        if (property_exists($res, $plural_type)) $res = $res->$plural_type;
        else if (property_exists($res, $singular_type)) $res = $res->$singular_type;

      # if result is an array then we want to return an array of wrapped objects
      if (is_array($res))
        # if the count is only one, there's probably a root node wrapping the data
        if (count($res) == 1) $out = new $type(array_shift($res), $errors);
        else foreach($res as $_ => $obj) $out[] = new $type($obj, $errors);
      else $out = new $type($res, $errors);
      return $out;
    }

    private static function build_arg_array($args, $mtd) {
      if (count($args) <= 0) return [];
      if (count(array_keys($args)) !== count(range(0, count($args) - 1)))
        throw new Exception("Please pass an associative array!");

      $out = [];

      if ($mtd[1] !== NULL) $out[self::$_settings['method_param']] = $mtd[1];
      foreach ($args as $key => $value) $out[urlencode($key)] = urlencode($value);
      return $out;
    }

    private static function fallback_method($method) {
      if ($method == self::POST || $method == self::GET) return [$method, NULL];
      return [self::POST, $method];
    }

    private static function build_url($path, $args = NULL) {
      $base_path = self::$_settings['protocol'] . '://';
      if (self::$_settings['whitelabel'] && self::$scope_wl) $base_path .= self::$_settings['whitelabel'] . '.';
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
        CURLOPT_HEADER => true,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_URL => $url,
        CURLOPT_HTTPHEADER => self::$_settings['request_headers'],
        CURLOPT_CONNECTTIMEOUT => self::$_settings['connect_timeout'],
        CURLOPT_TIMEOUT => self::$_settings['timeout'],
        CURLOPT_USERAGENT => self::$_settings['user_agent']]);

      # request authentication through http basic
      if (self::$scope_wl) {
        list($uname, $upass) = [self::$_settings['whitelabel'], self::$_settings['password']];
        curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($curl, CURLOPT_USERPWD, $uname . ':' . $upass);
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
