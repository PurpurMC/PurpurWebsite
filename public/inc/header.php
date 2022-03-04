<?php
require_once("opengraph.php");
?>

<!DOCTYPE html>
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
    <meta property="og:url" content="https://purpurmc.org" />
    <meta property="og:description" content="<?=$description?>" />
    <meta property="og:image" content="<?=$ogimg?>" />
    <link rel="icon" type="image/x-icon" href="/images/purpur.svg" />
    <link type="text/css" rel="stylesheet" href="/css/raleway.css" />
    <link type="text/css" rel="stylesheet" href="/css/index.css" />
</head>
