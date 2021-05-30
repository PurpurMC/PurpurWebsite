<?php
  // get the json data from file
  $filename = 'stats/data.json';
  $contents = file_get_contents($filename);
  $json = json_decode($contents === false ? '' : $contents, true);

  // time stuffs
  $now = time();
  $last = isset($json['last']) ? $json['last'] : 0;

  // check if data needs updating (only once a day)
  if ($now - $last > 86400) {
    // hit stats page so it can update data
    file_get_contents('https://purpur.pl3x.net/stats/');

    // get data again  
    $contents = file_get_contents($filename);
    $json = json_decode($contents === false ? '' : $contents, true);
  }

  // get data
  $servers = end($json['servers']['purpur']['data']);
  $players = end($json['players']['purpur']['data']);

  // bottom row
  $bstats = "bStats - Servers: " . $servers . " Players: " . $players;

  // create image
  $image = imagecreatetruecolor(500,100);

  // load background from png
  $sig = imagecreatefrompng('images/sig.png');
  imagecopy($image, $sig, 0, 0, 0, 0, imagesx($sig), imagesy($sig));
  imagestring($image, 3, 180, 85, $bstats, imagecolorallocate($image, 200, 200, 200));

  // show image to browser
  header("Content-Type: image/png");
  imagepng($image);
?>

