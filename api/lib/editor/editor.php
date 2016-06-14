<?php

  class RoomstylerEditor extends RoomstylerBase {

    private static $scope_wl = false;

    private $_settings = [];
    private $html_opts = ['frameborder' => 0, 'width' => 1024, 'height' => 768];

    public function __construct(array $settings, array $html_opts = []) {
      $this->_settings = $settings;
      $this->html_opts = array_merge($this->html_opts, $html_opts);
    }

    public function embed(array $opts = [], array $html_opts = []) {
      $html_attrs = [];
      $src_options = [];
      $merged_attrs = array_merge($this->html_opts, $html_opts);

      foreach ($merged_attrs as $attr => $val) $html_attrs[] = "$attr=\"$val\"";

      $attrs = join(' ', $html_attrs);

      #logs in with token if supplied, otherwise it tries to login with api user token if it's set
      if (isset($opts['token'])) $src_options['token'] = $opts['token'];
      else if (isset($opts['login']) && $opts['login'] == true && $this->_settings['token'] != NULL) $src_options['token'] = $this->_settings['token'];

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

      if (parent::is_scoped_for_wl()) {
        if ($this->_settings['whitelabel']) $src .= $this->_settings['whitelabel'] . '.';
        parent::scope_wl(false);
      }
      
      foreach ($attrs as $attr => $val) $attrs[$attr] = "$attr=$val";

      return $src .= $this->_settings['host'] . '/embed' . '?' . join('&', $attrs);
    }

    private function room_url($url) {
      $url = preg_replace('/^(www\.|https?:\/\/)/', '', $url);
      if (parent::is_scoped_for_wl()) $url = $this->_settings['whitelabel']  . '.' . $url;

      return urlencode($this->_settings['protocol'] . '://' . $url);
    }

  }

?>
