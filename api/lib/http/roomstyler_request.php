<?php
  require_once 'roomstyler_response.php';

  class RoomstylerRequest {

    private static $_settings = [];
    private static $_curl = NULL;

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

      return [
        'result' => new $type($res['body']),
        'request_params' => new RoomstylerResponse([
          'type' => $type,
          'path' => $path,
          'full_path' => $url,
          'arguments' => $compiled_args,
          'method' => $mtd[0],
          'status' => NULL,
          'body' => $res['body'],
          'error' => $res['error']])];
    }

    private static function build_arg_array($args, $mtd) {
      if (array_keys($args) == range(0, count($args) - 1))
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
      $url = join('/', [self::$_settings['host'],
                        self::$_settings['prefix'], $path]);

      $url = self::$_settings['protocol'] . '://' . $url;

      if ($args) {
        $query = [];
        foreach ($args as $key => $value)
          $query[] = $key . '=' . $value;

        return ($url . '?' . join('&', $query));
      }

      return $url;
    }

    private static function curl_fetch($url, array $params, $mtd) {
      $curl = curl_init();

      curl_setopt_array($curl, [
        CURLOPT_FAILONERROR => true,
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_URL => $url,
        CURLOPT_POST => ($mtd == self::POST),
        CURLOPT_CONNECTTIMEOUT => self::$_settings['connect_timeout'],
        CURLOPT_TIMEOUT => self::$_settings['timeout'],
        CURLOPT_USERAGENT => self::$_settings['user_agent']]);

      if (!empty($params) && $mtd == self::POST)
        curl_setopt($curl, CURLOPT_POSTFIELDS, $params);

      $body = curl_exec($curl);
      if ($body) $body = json_decode($body);
      if (count($body) == 1) $body = array_shift($body);

      $curl_info = curl_getinfo($curl);

      $res = ['body' => $body,
              'error' => ['status' => $curl_info['http_code'],
                          'message' => curl_error($curl)]];

      curl_close($curl);

      return $res;
    }
  }
?>
