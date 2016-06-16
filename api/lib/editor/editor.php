<?php

  class RoomstylerEditor extends RoomstylerBase {

    private $_settings = [];
    private $_whitelabeled = false;
    private $html_opts = ['frameborder' => 0, 'width' => 1024, 'height' => 768];

    public function __construct(array $settings, $whitelabeled = false) {
      $this->_settings = $settings;
      $this->_whitelabeled = $whitelabeled;
    }

    public function embed(array $opts = [], array $html_opts = []) {
      $html_attrs = [];
      $src_options = [];
      $html_opts = array_merge($this->html_opts, $html_opts);
      $login_allowed = (isset($opts['login']) ? ($opts['login'] == true) : true);

      foreach ($html_opts as $attr => $val) $html_attrs[] = "$attr=\"$val\"";

      $attrs = join(' ', $html_attrs);

      #logs in with token if supplied, otherwise it tries to login with api user token if it's set
      if ($login_allowed)
        if (isset($opts['token'])) $src_options['token'] = $opts['token'];
        else if (isset($this->_settings['token'])) $src_options['token'] = $this->_settings['token'];

      # sets language if set, defaults to api default language
      if (isset($opts['language'])) $src_options['language'] = $opts['language'];
      else $src_options['lang'] = $this->_settings['language'];

      # opens specific room if set
      if (isset($opts['room_url'])) {
        $src_options['room_url'] = $this->room_url($opts['room_url']);
      }

      return "<iframe src=\"{$this->embed_url($src_options)}\" {$attrs}></iframe>";
    }

    private function embed_url($attrs) {
      $src = $this->_settings['protocol'] . '://';

      if ($this->_whitelabeled && $this->_settings['whitelabel']) $src .= $this->_settings['whitelabel'] . '.';

      foreach ($attrs as $attr => $val) $attrs[$attr] = "$attr=$val";

      return $src .= $this->_settings['host'] . '/embed' . '?' . join('&', $attrs);
    }

    private function room_url($url) {
      $url = preg_replace('/^(www\.|https?:\/\/)/', '', $url);

      if ($this->_whitelabeled) $url = $this->_settings['whitelabel']  . '.' . $url;

      return urlencode($this->_settings['protocol'] . '://' . $url);
    }

  }

?>
