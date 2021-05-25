<?php
  require_once("github.php");
?><!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <title>Purpur - Your Minecraft, your way</title>

    <meta name="title" content="Purpur - Your Minecraft, your way" />
    <meta name="description" content="<?=$ogdesc?>" />
    <meta name="theme-color" content="<?=$ogcolor?>" />

    <meta property="twitter:card" content="summary_large_image" />
    <meta property="twitter:domain" content="purpur.pl3x.net">
    <meta property="twitter:url" content="<?=$ogurl?>" />
    <meta property="twitter:title" content="<?=$ogtitle?>" />
    <meta property="twitter:description" content="<?=$ogdesc?>" />
    <meta property="twitter:image" content="<?=$ogimg?>" />

    <meta property="og:url" content="<?=$ogurl?>" />
    <meta property="og:type" content="website" />
    <meta property="og:title" content="<?=$ogtitle?>" />
    <meta property="og:description" content="<?=$ogdesc?>" />
    <meta property="og:image" content="<?=$ogimg?>" />

    <link type="application/json+oembed" href="<?=$url?>data/embed.php?<?=$oembed?>" />

    <link rel="icon" type="image/x-icon" href="/images/purpur.svg" />
    <link type="text/css" rel="stylesheet" href="css/header.css" />

    <script src="js/header.js"></script>
  </head>
  <body>
    <header>
      <div class="container">
        <a class="logo" href="<?=$url?>"><img src="/images/purpur.svg" alt="Purpur Documentation" /></a>
        <a class="navbtn"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24"><path d="M3 6h18v2H3V6m0 5h18v2H3v-2m0 5h18v2H3v-2z"></path></svg></a>
        <p>Purpur Documentation</p>
        <a class="purpurdocs" href="">
          <div class="icon"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" width="24"><path d="M439.55 236.05L244 40.45a28.87 28.87 0 00-40.81 0l-40.66 40.63 51.52 51.52c27.06-9.14 52.68 16.77 43.39 43.68l49.66 49.66c34.23-11.8 61.18 31 35.47 56.69-26.49 26.49-70.21-2.87-56-37.34L240.22 199v121.85c25.3 12.54 22.26 41.85 9.08 55a34.34 34.34 0 01-48.55 0c-17.57-17.6-11.07-46.91 11.25-56v-123c-20.8-8.51-24.6-30.74-18.64-45L142.57 101 8.45 235.14a28.86 28.86 0 000 40.81l195.61 195.6a28.86 28.86 0 0040.8 0l194.69-194.69a28.86 28.86 0 000-40.81z"/></svg></div>
          <div class="github">
            PurpurDocs
            <span id="gh_stats"><?=$github['stars']?> Stars · <?=$github['forks']?> Forks</span>
          </div>
        </a>
      </div>
    </header>
    <div class="container main">
      <div class="left">
        <p class="head">Purpur Documentation</p>
        <p><a <?=$page=="home"?"class='selected' ":""?>href="home">Home</a></p>
        <p><a <?=$page=="commands"?"class='selected' ":""?>href="commands">Commands</a></p>
        <p><a <?=$page=="configuration"?"class='selected' ":""?>href="configuration">Configuration</a></p>
        <p><a <?=$page=="permissions"?"class='selected' ":""?>href="permissions">Permissions</a></p>
      </div>
