<?php

  require_once("opengraph.php");

  $contents = file_get_contents("/home/billy/pillar/data.json");

  $json = json_decode($contents === false ? '' : $contents, true);

  $versions = [];
  foreach($json["purpur"] as $key => $value) {
    array_push($versions, $key);
  }
  rsort($versions);

  $url = @$_SERVER[REQUEST_URI];
  if ($url != null) {
    $url = str_replace("/downloads/", "", $url);
    $url = explode("/", $url)[0];
  }

  $currentVersion = $url;
  if ($currentVersion == null) {
    $currentVersion = $versions[0];
  }

?><!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?=$title?></title>
    <meta name="title" content="<?=$title?>" />
    <meta name="description" content="<?=$description?>" />
    <meta property="twitter:title" content="<?=$title?>" />
    <meta property="twitter:image" content="<?=$ogimg?>" />
    <meta property="twitter:card" content="summary_large_image" />
    <meta property="twitter:description" content="<?=$description?>" />
    <meta property="og:title" content="<?=$title?>" />
    <meta property="og:url" content="https://purpur.pl3x.net" />
    <meta property="og:description" content="<?=$description?>" />
    <meta property="og:image" content="<?=$ogimg?>" />
    <link rel="icon" type="image/x-icon" href="/images/purpur.svg" />
    <link type="text/css" rel="stylesheet" href="/css/raleway.css" />
    <link type="text/css" rel="stylesheet" href="/css/index.css" />
    <link href="/css/downloads.css" type="text/css" rel="stylesheet" />
    <script src="/js/downloads.js"></script>
    <script src="/js/fontawesome.js" crossorigin="anonymous"></script>
  </head>
  <body>
    <div class="top-row">
      <div class="container">
        <nav>
          <a href="/docs"><i class="fas fa-book-open"></i> Docs</a>
          <a href="/github" target="_blank"><i class="fab fa-github"></i> GitHub</a>
          <a href="/discord" target="_blank"><i class="fab fa-discord"></i> Discord</a>
          <a href="/downloads"><i class="fas fa-cloud-download-alt"></i> Downloads</a>
        </nav>
        <a href="/"><img src="/images/purpur.svg" alt="Purpur logo" /></a>
        <div>
          <a href="/"><h2>Purpur</h2></a>
          <p>Your Minecraft, your way</p>
        </div>
      </div>
    </div>
    <div class="row-one">
      <div class="container">
        <ul class="tabs">
<?php
  foreach($versions as $version) {
    echo "          <li" . ($currentVersion == $version ? " class='selected'" : "") . "><a href='$version'>$version</a></li>\n";
  }
?>
        </ul>
        <select id="dropdown">
<?php
  foreach($versions as $version) {
    echo "          <option value='$version'" . ($currentVersion == $version ? " selected" : "") . ">$version</option>\n";
  }
?>
        </select>
         <div id="oldVersionWarning" class="oldVersionWarning <?=($currentVersion != $versions[0] ? "visible" : "")?>">
          You are trying to download builds for old/unsupported version of Minecraft!<br />
          Keep in mind that if you download these builds, you won't get any support from Purpur!
        </div>
        <table class="downloads">
          <thead><tr><td class="left">Build</td><td class="middle">Changes</td><td class="right">Date</td></tr></thead>
          <tbody>
<?php
  $json = $json["purpur"][$currentVersion];
  $builds = [];
  foreach($json as $key => $value) {
    array_push($builds, $key);
  }
  rsort($builds);
  foreach($builds as $build) {
    $link = download($currentVersion, $build, $json[$build]);
    $commits = commits($json[$build]["commits"]);
    $date = date('Y-m-d H:i:s', $json[$build]["timestamp"] / 1000);
    echo "            <tr><td class='left'>$link</td><td class='mid'>$commits</td><td class='right'>$date</td></tr>\n";
  }
?>
          </tbody>
        </table>
      </div>
    </div>
    <footer>
      &copy; 2019-2021 <a href="https://pl3x.net">pl3x.net</a> <a href="https://github.com/pl3xgaming/Purpur/blob/ver/1.16.5/LICENSE">MIT</a>
      <p><a href="https://gitlab.gnome.org/Teams/Releng/gnome-os-site" rel="noreferrer" target="_blank">Site design CC-BY-SA</a></p>
    </footer>
  </body>
</html>
<?php

  function download($version, $build, $json) {
    if ($json["result"] == "SUCCESS") {
      return "<a href='https://api.pl3x.net/v2/purpur/$version/$build/download' class='button white-button' download='purpur-$version-$build.jar' title='Download build #$build'><span><i class='fas fa-cloud-download-alt'></i> $build</span></a>";
    } else {
      return "<a href='#' class='button disabled-button' title='Failed build #$build'><span><i class='fas fa-times-circle'></i> $build</span></a>";
    }
  }

  function commits($json) {
    if (count($json) == 0) {
      return "No changes";
    }
    $results = "";
    foreach($json as $key) {
      $committer = "\n\n- " . scrub($key["author"]) . " <" . str_replace(".", "&period;", str_replace("@", "&commat;", scrub($key["email"]))) . ">";
      $hash = "<a href='https://github.com/pl3xgaming/Purpur/commit/" . $key["hash"] . "' class='hash' rel='noreferrer' target='_blank'>" . substr($key["hash"], 0, 7) . "</a>";
      $results .= "<p title='" . shortenGitHubUrls(scrub($key["description"])) . $committer . "'><span>[$hash]</span> " . parseIssues(scrub(explode("\n", $key["description"])[0])) . "</p>\n";
    }
    return $results;
  }

  function parseIssues($description) {
    $result = "";

    foreach (explode(" ", $description) as $str) {
      if (preg_match('/^\W?(#[0-9]+)\W?$/', $str, $match)) {
        $str = "<a href='https://github.com/pl3xgaming/Purpur/issues/" . substr($match[1], 1) . "' class='issue' rel='noreferrer' target='_blank'>" . $str . "</a>";
      }
      else if (preg_match('/^\W?(MC\-[0-9]+)\W?$/', $str, $match)) {
        $str = "<a href='https://bugs.mojang.com/browse/" . $match[1] . "' class='issue' rel='noreferrer' target='_blank'>" . $str . "</a>";
      }

      $result .= (strlen($result) > 0 ? " " : "") . $str;
    }

    return $result;
  }

  function shortenGitHubUrls($description) {
    return preg_replace('/(https:\/\/github\.com\/.*\/.*\/commit\/([a-z0-9]{7})[a-z0-9]{33})/', "[$2] ", $description);
  }

  function scrub($str) {
    return str_replace("'", "&apos;", str_replace('"', "&quot;", htmlspecialchars($str)));
  }

  echo $currentVersion;

?>
