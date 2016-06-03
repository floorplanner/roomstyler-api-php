<?php
  require_once 'roomstyler_response.php';

  class RoomstylerRequest {

    const METHOD_DELETE = 'delete';
    const METHOD_POST = 'post';
    const METHOD_GET = 'get';
    const METHOD_PUT = 'put';
    const METHOD_PATCH = 'patch';

    public function send($url, array $args = [], $method = self::METHOD_GET) {
      $mtd = $this->fallback_method($method);

      return new RoomstylerResponse([
        'path' => $url,
        'arguments' => $args,
        'method' => $mtd,
        'status' => NULL,
        'body' => NULL
      ]);
    }

    private function fallback_method($method) {
      if ($method == self::METHOD_POST || $method == self::METHOD_GET)
        return [$method, NULL];
      return ['post', $method];
    }
  }
?>
