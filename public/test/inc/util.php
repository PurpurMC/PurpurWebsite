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

  // for configuration.yml

  function buildConfig($arr, $key) {
    foreach ($arr as $k => $v) {
      $path = ($key === null ? "" : $key . ".") . $k;

      if (is_array($v) && !is_array($v[array_key_first($v)])) {
          showOption($path, $k, $v);
          continue;
      }

      echo "<div>\n";
      echo "<div class='anchor' id='$path'></div>\n";
      echo "<p class='headerlink'><a href='?id=$path'>$k <span>🔗</span></a></p>\n";
      buildConfig($v, $path);
      echo "</div>\n";
    }
  }

  function showOption($path, $key, $option) {
    echo "<div class='section'>\n";
    echo "<div class='anchor' id='$path'></div>\n";
    echo "<p class='headerlink' title='$path'><a href='?id=$path'>$key <span>🔗</span></a></p>\n";
    showLine($option, 'requirement');
    showLine($option, 'default');
    showLine($option, 'description');
    showLine($option, 'note');
    showLine($option, 'warning');
    echo "</div>\n";
  }

  function showLine($option, $name) {
    if (isset($option[$name])) {
      echo "<div class='subsection $name'>\n";
      echo "<span class='optionvalue'>" . parseMarkdown($option[$name]) . "</span>\n";
      echo "</div>\n";
    }
  }
?>
