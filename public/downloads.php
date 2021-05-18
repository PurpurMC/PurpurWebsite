<?php
require_once("opengraph.php");
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
    <script src="https://kit.fontawesome.com/b282bc267d.js" crossorigin="anonymous"></script>
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
        <ul class="tabs"></ul>
        <select id="dropdown"></select>
        <table class="downloads">
          <thead><tr><td class="left">Build</td><td class="middle">Changes</td><td class="right">Date</td></tr></thead>
          <tbody></tbody>
        </table>
      </div>
    </div>
    <footer>
      &copy; 2019-2021 <a href="https://pl3x.net">pl3x.net</a> <a href="https://github.com/pl3xgaming/Purpur/blob/ver/1.16.5/LICENSE">MIT</a>
      <p><a href="https://gitlab.gnome.org/Teams/Releng/gnome-os-site" target="_blank">Site design CC-BY-SA</a></p>
    </footer>
  </body>
</html>

