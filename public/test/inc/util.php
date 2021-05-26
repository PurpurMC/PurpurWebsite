<?php
  function getContents($url) {
    $opts = [
      'http' => [
        'method' => 'GET',
        'header' => [
          'User-Agent: PHP'
        ]
      ]
    ];
    $context = stream_context_create($opts);
    return file_get_contents($url, false, $context);
  }

  function getValue($json, $key) {
    if (strpos($key, '.') !== FALSE) {
      $v = explode(".", $key);
      $k = array_shift($v);
      return getValue($json[$k], implode('.', $v));
    }
    return @$json[$key];
  }

  function parseMarkdown($s) {
    // convert html
    $s = htmlspecialchars($s);

    // parse code block
    $s = preg_replace_callback('/`(.*?)`/', function ($matches) {
      return '<span class="codeblock">' . $matches[1] . '</span>';
    }, $s);

    // parse links https://stackoverflow.com/a/25104285
    $s = preg_replace_callback('/\[(.*?)\]\((.*?)\)/', function ($matches) {
      return '<a href="' . $matches[2] . '?id=' . $matches[1] . '">' . $matches[1] . '</a>';
    }, $s);

    return $s;
  }
?>
