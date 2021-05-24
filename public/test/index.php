<?php

  // get the json data from file
  $filename = 'data.json';
  $contents = file_get_contents($filename);
  $json = json_decode($contents === false ? '' : $contents, true);

  $ogtitle = "Purpur Documentation";
  $ogdesc = "Read over our comprehensive feature documentation and make your server your own";
  $ogimg = "https://i.pinimg.com/originals/0c/d8/55/0cd85593806593360a4a114550449670.gif";
  $ogurl = "https://purpur.pl3x.net/test/";

  // get current option
  $urlid = @$_GET['id'];
  $option = getValue($json, $urlid);
  if ($option !== null) {
    $ogdesc = "$urlid\n\n" . htmlspecialchars($option['description']) . "\n\ndefault: " . htmlspecialchars($option['default']);
    $ogurl = "$ogurl?id=" . $urlid;
  }

?><!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />

    <title>Purpur - Your Minecraft, your way</title>

    <meta name="title" content="Purpur - Your Minecraft, your way" />
    <meta name="description" content="<?=$ogdesc?>" />

    <meta property="twitter:card" content="summary_large_image" />
    <meta property="twitter:domain" content="purpur.pl3x.net">
    <meta property="twitter:url" content="<?=$ogurl?>">
    <meta property="twitter:title" content="<?=$ogtitle?>" />
    <meta property="twitter:description" content="<?=$ogdesc?>" />
    <meta property="twitter:image" content="<?=$ogimg?>" />

    <meta property="og:url" content="<?=$ogurl?>" />
    <meta property="og:type" content="website">
    <meta property="og:title" content="<?=$ogtitle?>" />
    <meta property="og:description" content="<?=$ogdesc?>" />
    <meta property="og:image" content="<?=$ogimg?>" />

    <link rel="icon" type="image/x-icon" href="/images/purpur.svg" />
    <link type="text/css" rel="stylesheet" href="styles.css" />

    <script>
      window.onload = (event) => {
        const params = new URLSearchParams(window.location.search);
        document.getElementById(params.get("id")).scrollIntoView();
      };
    </script>
  </head>
  <body>
<?php
  buildConfig($json, null);
?>
  </body>
</html>
<?php
  function buildConfig($arr, $key) {
    foreach ($arr as $k => $v) {
      $path = ($key === null ? "" : $key . ".") . $k;

      if (is_array($v) && !is_array($v[array_key_first($v)])) {
          showOption($path, $k, $v);
          continue;
      }

      echo "<div id='$path'>\n";
      echo "<p class='headerlink'>• $k <a href='?id=$path'>🔗</a></p>\n";
      buildConfig($v, $path);
      echo "</div>\n";
    }
  }

  function showOption($path, $key, $option) {
    echo "<div class='section' id='$path'>\n";
    echo "<p class='headerlink' title='$path'>• $key <a href='?id=$path' class='anchor'>🔗</a></p>\n";
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
      echo "<span class='optionvalue'>" . htmlspecialchars($option[$name]) . "</span>\n";
      echo "</div>\n";
    }
  }

  function getValue($json, $key) {
    if (strpos($key, '.') !== FALSE) {
      $v = explode(".", $key);
      $k = array_shift($v);
      return getValue($json[$k], implode('.', $v));
    }
    return $json[$key];
  }
?>