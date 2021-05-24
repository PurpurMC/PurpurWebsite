<?php

  // get the json data from file
  $filename = 'data.json';
  $contents = file_get_contents($filename);
  $json = json_decode($contents === false ? '' : $contents, true);

  $ogtitle = "Purpur Documentation";
  $ogdesc = "";
  $ogimg = "https://purpur.pl3x.net/card.png";
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
      if (is_array($v) && !is_array($v[array_key_first($v)])) {
          showOption($key . "." . $k, $v);
          continue;
      }
      buildConfig($v, ($key === null ? "" : $key . ".") . $k);
    }
  }

  function showOption($key, $option) {
    echo "<div id='$key'>\n";
    echo "<b>full key</b>: <a href='?id=$key'>$key</a><br>\n";
    showLine($option, 'requirement');
    showLine($option, 'default');
    showLine($option, 'description');
    showLine($option, 'note');
    showLine($option, 'warning');
    echo "<hr>\n</div>\n";
  }

  function showLine($option, $name) {
    if (isset($option[$name])) echo "$name: " . htmlspecialchars($option[$name]) . "<br>\n";
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