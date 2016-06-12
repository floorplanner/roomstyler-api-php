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
      else $src_options['language'] = $this->_settings['language'];

      # opens specific room if set
      if (isset($opts['room_url'])) $src_options['room_url'] = $opts['room_url'];

      foreach ($src_options as $attr => $val) $src_options[$attr] = "$attr=$val";

      $src = $this->_settings['protocol'] . '://';
      if (parent::is_scoped_for_wl()) {
        $src .= $this->_settings['whitelabel'] . '.';
        parent::scope_wl(false);
      }
      $src = $src .= $this->_settings['host'] . '/embed' . '?' . join('&', $src_options);

      return "<iframe src=\"$src\" {$attrs}></iframe>";
    }

  }

?>
