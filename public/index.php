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
  </head>
  <body>
    <header>
      <div class="container">
        <h1>Purpur</h1>
        <h2>Your Minecraft, your way</h2>
        <img class="logo" src="/images/purpur.svg" alt="Purpur Logo" />
        <p>Purpur is a drop-in replacement for Paper servers designed for configurability, new fun
           &amp; exciting gameplay features, and performance built on top of Airplane</p>
        <a class="button download-button blue-button" href="https://api.pl3x.net/v2/purpur/1.17.1/latest/download">Download</a>
        <a class="tiny" href="/downloads">See all builds</a>
      </div>
    </header>
    <div class="row-one">
      <div class="container group">
        <div class="block">
          <img class="icon" src="/images/documentation.svg" alt="Fully Documented" />
          <h3>Fully Documented</h3>
          <p>Read over our comprehensive feature documentation and make it your own.</p>
          <a class="button white-button" href="/docs">Documentation</a>
        </div>
        <div class="block">
          <img class="icon" src="/images/opensource.svg" alt="Free and Open Source" />
          <h3>Free and Open Source</h3>
          <p>Built with love by people of all walks of life all over the globe.</p>
          <a class="button white-button" href="/github" target="_blank">GitHub</a>
        </div>
      </div>
    </div>
    <div class="row-two">
      <div class="container group">
        <div class="block">
          <img class="icon" src="/images/warning.svg" alt="Found a bug? File it!" />
          <h3>Found a Bug? File it!</h3>
          <p>If you run into issues or have any suggestions, let us know.</p>
          <a class="button red-button" href="/issues" target="_blank">Issue Tracker</a>
        </div>
        <div class="block">
          <img class="icon" src="/images/discord.svg" alt="Get Involved" />
          <h3>Get Involved</h3>
          <p>Get in touch with the developers, ask questions, and join the fun.</p>
          <a class="button blue-button" href="/discord" target="_blank">Discord</a>
        </div>
      </div>
    </div>
    <footer>
      &copy; 2019-2021 <a href="https://pl3x.net">pl3x.net</a> <a href="https://github.com/pl3xgaming/Purpur/blob/ver/1.16.5/LICENSE">MIT</a>
      <p><a href="https://gitlab.gnome.org/Teams/Releng/gnome-os-site" rel="noreferrer" target="_blank">Site design CC-BY-SA</a></p>
    </footer>
  </body>
</html>

