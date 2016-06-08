<?php
  require_once 'roomstyler_response.php';

  class RoomstylerRequest {

    private static $_settings = [];

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

    public static function send($type, $path, array $args = [], $method = self::GET) {
      $mtd = self::fallback_method($method);
      $compiled_args = self::build_arg_array($args, $mtd);
      $url = self::build_url($path, $method == self::GET ? $compiled_args : []);
      $res = self::curl_fetch($url, $compiled_args, $method);

      if ($type == NULL) return $res;

      if (is_array($res['body'])) {
        if (count($res['body']) == 1)
          $final_res = new $type(array_shift($res['body']));
        else {
          $final_res = [];
          foreach($res['body'] as $_ => $obj) $final_res[] = new $type($obj);
        }
      } else {
        $final_res = new $type($res['body']);
      }

      return [
        'result' => $final_res,
        'request_info' => new RoomstylerResponse([
          'type' => $type,
          'path' => $path,
          'full_path' => $url,
          'arguments' => $compiled_args,
          'method' => $mtd[0],
          'status' => $res['status'],
          'headers' => $res['headers'],
          'body' => $res['body'],
          'error' => $res['error']])];
    }

    private static function build_arg_array($args, $mtd) {
      if (count($args) <= 0) return [];
      if (count(array_keys($args)) !== count(range(0, count($args) - 1)))
        throw new Exception("Please pass an associative array!");

      $compiled_args = [];

      if ($mtd[1] !== NULL)
        $compiled_args[self::$_settings['method_param']] = $mtd[1];

      foreach ($args as $key => $value)
        $compiled_args[urlencode($key)] = urlencode($value);

      return $compiled_args;
    }

    private static function fallback_method($method) {
      if ($method == self::POST || $method == self::GET)
        return [$method, NULL];

      return [self::POST, $method];
    }

    private static function build_url($path, $args = NULL) {
      $base_path = self::$_settings['protocol'] . '://';
      if (self::$_settings['whitelabel']) $base_path .= self::$_settings['whitelabel'] . '.';
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
        CURLOPT_HEADER => 1,
        CURLOPT_FAILONERROR => true,
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_URL => $url,
        CURLOPT_POST => ($mtd == self::POST),
        CURLOPT_HTTPHEADER => self::$_settings['request_headers'],
        CURLOPT_CONNECTTIMEOUT => self::$_settings['connect_timeout'],
        CURLOPT_TIMEOUT => self::$_settings['timeout'],
        CURLOPT_USERAGENT => self::$_settings['user_agent']]);

      # request authentication through http basic
      list($uname, $upass) = [self::$_settings['whitelabel'],
                              self::$_settings['password']];

      if (!empty($uname) && !empty($upass)) {
        curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($curl, CURLOPT_USERPWD, $uname . ':' . $upass);
      }

      # request method handling is done prior to this step.
      # if a request needs to be post (e.g. on DELETE, PATCH etc...) it will be
      if (!empty($params) && $mtd == self::POST)
        curl_setopt($curl, CURLOPT_POSTFIELDS, $params);

      $raw = curl_exec($curl);
      $curl_info = curl_getinfo($curl);
      $response_headers = substr($raw, 0, $curl_info['header_size']);
      $body = substr($raw, $curl_info['header_size']);
      if ($body) $body = json_decode($body);

      $res = ['body' => $body,
              'headers' => [
                'request' => self::parse_header_str($curl_info['request_header']),
                'response' => self::parse_header_str($response_headers)],
              'status' => $curl_info['http_code'],
              'error' => ['curl_error' => curl_errno($curl),
                          'message' => curl_error($curl)]];

      curl_close($curl);

      return $res;
    }

    private static function parse_header_str($header_str) {
      $out = [];
      $header_lines = preg_split('/\r\n|\n|\r/', $header_str);
      $header_lines = array_filter($header_lines, function($value) { return strlen($value) > 0; });
      $out['Meta-Information'] = array_shift($header_lines);

      foreach ($header_lines as $line) {
        list($key, $val) = explode(': ', $line);
        $out[$key] = $val;
      }

      return $out;
    }
  }
?>
