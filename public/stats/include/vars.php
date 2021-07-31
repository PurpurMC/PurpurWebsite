<?php

  // our json files
  $dataFile = 'data/data.json';
  $serversFile = 'data/servers.json';

  // file contents
  $dataContents = file_get_contents($dataFile);
  $serversContents = file_get_contents($serversFile);

  // get json from files
  $jsonData = json_decode($dataContents === false ? '' : $dataContents, true);
  $jsonServers = json_decode($serversContents === false ? '' : $serversContents, true);

  // set up time stuffs
  $now = time();
  $timezone = new DateTimeZone("America/Mexico_City");
  $timenow = new DateTime("now", $timezone);
  $threePM = (new DateTime("now", $timezone))->setTime(15,0,0);
  $today = (new DateTime("now", $timezone))->setTime(0,0,0)->format('m/d/y');

  // last checked date
  $last = isset($jsonData['last']) ? $jsonData['last'] : 0;

?>
