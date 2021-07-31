<?php

  require_once ("include/vars.php");
  require_once ("include/util.php");

  // only update from bstats once a day at 3PM central USA time
  if ($timenow->getTimestamp() > $threePM->getTimestamp()) {
    // write today to file quickly - this prevents subsequent page
    // loads from triggering bstats queries before first run finishes
    $jsonData['last'] = $today;
    file_put_contents($dataFile, json_encode($jsonData));

    foreach ($jsonServers['servers'] as $server => $data) {
      // get last 6 months worth of data from bstats
      $servers = processData(json_decode(file_get_contents('https://bstats.org/api/v1/plugins/' . $data['id'] . '/charts/servers/data/?maxElements=8640')));
      $players = processData(json_decode(file_get_contents('https://bstats.org/api/v1/plugins/' . $data['id'] . '/charts/players/data/?maxElements=8640')));

      // store data in json
      $jsonData['data']['servers'][$server] = array_values($servers);
      $jsonData['data']['players'][$server] = array_values($players);
      $jsonData['data']['dates'] = array_keys($servers); // either map's dates are fine
    }

    // write data to file
    file_put_contents($dataFile, json_encode($jsonData));

    echo '{"updated":"true"}';
  } else {
    echo '{"updated":"false"}';
  }

?>
