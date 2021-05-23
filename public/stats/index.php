<?php

// get the json data from file
$filename = 'data.json';
$contents = file_get_contents($filename);
$json = json_decode($contents === false ? '' : $contents, true);

// time stuffs
$now = time();
$last = isset($json['last']) ? $json['last'] : 0;

// only update from bstats once a day
if ($now - $last > 86400) {
    foreach ($json['servers'] as $server => $data) {
         // get 6 months worth of data
        $servers = json_decode(file_get_contents('https://bstats.org/api/v1/plugins/' . $data['id'] . '/charts/servers/data/?maxElements=8640'));
        $players = json_decode(file_get_contents('https://bstats.org/api/v1/plugins/' . $data['id'] . '/charts/players/data/?maxElements=8640'));

        // data maps
        $servermap = (array) null;
        $playermap = (array) null;

        // iterate server data
        foreach ($servers as $entry) {
            $seconds = $entry[0] / 1000; // millis to seconds
            $count = $entry[1]; // server count

            // get gmt date in simplified format
            $date = (new DateTime("@$seconds", new DateTimeZone("UTC")))->format('m/d/y');

            // only store highest count for that day
            if ($count > ($servermap[$date] == null ? -1 : $servermap[$date])) {
              $servermap[$date] = $count;
            }
        }

        // iterate player data
        foreach ($players as $entry) {
            $seconds = $entry[0] / 1000; // millis to seconds
            $count = $entry[1]; // player count

            // get gmt date in simplified format
            $date = (new DateTime("@$seconds", new DateTimeZone("UTC")))->format('m/d/y');

            // only store highest count for that day
            if ($count > ($playermap[$date] == null ? -1 : $playermap[$date])) {
              $playermap[$date] = $count;
            }
        }

        // remove last day (it's incomplete)
        array_pop($servermap);
        array_pop($playermap);

         // store data in json
        $json['servers'][$server]['data'] = array_values($servermap);
        $json['players'][$server]['data'] = array_values($playermap);
        $json['dates'] = array_keys($servermap); // either map's dates are fine
    }

    // store last day as beginning of today (gmt)
    $json['last'] = (new DateTime("@$now", new DateTimeZone("UTC")))->setTime(0,0)->getTimestamp();

    // write to file
    file_put_contents($filename, json_encode($json));
}

?><!doctype html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <title>Server Usage</title>
    <meta name="title" content="Server Usage" />
    <meta name="description" content="" />
    <meta property="twitter:title" content="Server Usage" />
    <meta property="twitter:image" content="https://purpur.pl3x.net/stats/jpgraph.php" />
    <meta property="twitter:card" content="summary_large_image" />
    <meta property="twitter:description" content="" />
    <meta property="og:title" content="Server Usage" />
    <meta property="og:url" content="https://purpur.pl3x.net/stats" />
    <meta property="og:description" content="" />
    <meta property="og:image" content="https://purpur.pl3x.net/stats/jpgraph.php" />
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
      body {
        color: #ffffff;
        background-color: #212121;
      }
      div#stats_graph {
        max-width: 1200px;
        margin: 0 auto;
      }
    </style>
  </head>
  <body>
    <div id="stats_graph">
      <canvas id="servers"></canvas>
    </div>
    <br/><br/>
    <div id="stats_graph">
      <canvas id="players"></canvas>
    </div>
    <script>
      let serverChart;
      let serverConfig = {
          type: 'line',
          options: {
              responsive: true,
              interaction: {
                  mode: 'nearest',
                  intersect: false
              },
              plugins: {
                  title: {
                      display: true,
                      color: '#fff'
                  },
                  tooltip: {
                      mode: 'index',
                      intersect: false
                  },
                  legend: {
                      display: true,
                      labels: {
                          color: '#fff'
                      }
                  }
              },
              scales: {
                  x: {
                      display: true,
                      title: {
                          display: true,
                          text: 'Date',
                          color: '#fff'
                      },
                      ticks: {
                          color: '#fff' 
                      },
                      grid: {
                          color: '#444'
                      }
                  },
                  y: {
                      display: true,
                      beginAtZero: true,
                      title: {
                          display: true,
                          text: 'Servers',
                          color: '#fff'
                      },
                      ticks: {
                          color: '#fff'
                      },
                      grid: {
                          color: '#444'
                      }
                  }
              }
          }
      };

      function loadData(name, json) {
          json.dates.forEach(function(date) {
              serverConfig.data.labels.push(date);
          });

          Object.keys(json.servers).forEach(function(server) {
              let obj = json.servers[server];
              serverConfig.data.datasets.push({
                  label: server,
                  backgroundColor: obj['color'],
                  borderColor: obj['color'],
                  data: obj['data']
              });
          });

          serverConfig.options.plugins.title.text = "Server Usage";

          serverChart.update();

          serverConfig.data.datasets = [];

          Object.keys(json.players).forEach(function(server) {
              let obj = json.players[server];
              serverConfig.data.datasets.push({
                  label: server,
                  backgroundColor: json.servers[server]['color'],
                  borderColor: json.servers[server]['color'],
                  data: obj['data']
              });
          });

          serverConfig.options.plugins.title.text = "Player Counts";

          playerChart.update();
      }

      window.onload = function () {
        serverChart = new Chart(document.getElementById('servers'), serverConfig);
        playerChart = new Chart(document.getElementById('players'), serverConfig);
          fetch('data.json')
              .then(async res => {
                  if (res.ok) {
                      loadData(name, await res.json());
                  }
              });
      };
    </script>
  </body>
</html>

