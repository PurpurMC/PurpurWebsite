<?php

$filename = 'data.json';

$contents = file_get_contents($filename);
$json = json_decode($contents === false ? '' : $contents, true);

$now = time();
$last = isset($json['last']) ? $json['last'] : 0;

// only update from bstats once a day
if ($now - $last > 86400) {
    foreach ($json['servers'] as $server => $data) {
        $data = json_decode(file_get_contents('https://bstats.org/api/v1/plugins/' . $data['id'] . '/charts/servers/data/?maxElements=8640')); // 6 months worth

        $map = (array) null;

        foreach ($data as $entry) {
            $seconds = $entry[0] / 1000; // millis to seconds
            $count = $entry[1];

            $date = (new DateTime("@$seconds", new DateTimeZone("UTC")))->format('m/d/y');

            if ($count > ($map[$date] == null ? -1 : $map[$date])) {
              $map[$date] = $count;
            }
        }

        array_pop($map); // remove last day (it's incomplete)

        $json['servers'][$server]['data'] = array_values($map);
        $json['dates'] = array_keys($map);
    }

    $json['last'] = (new DateTime("@$now", new DateTimeZone("UTC")))->setTime(0,0)->getTimestamp();

    file_put_contents($filename, json_encode($json));
}

?><!doctype html>
<html lang="en">
  <head>
    <title>Server Usage</title>
    <meta charset="UTF-8" />
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
      body {
        color: #ffffff;
        background-color: #212121;
      }
      div {
        width: 1200px;
        margin: 0 auto;
      }
    </style>
  </head>
  <body>
    <div>
      <canvas id="canvas"></canvas>
    </div>
    <script>
      let chart;
      let config = {
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
                      text: 'Server Usage',
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
              config.data.labels.push(date);
          });

          Object.keys(json.servers).forEach(function(server) {
              let obj = json.servers[server];
              config.data.datasets.push({
                  label: server,
                  backgroundColor: obj['color'],
                  borderColor: obj['color'],
                  data: obj['data']
              });
          });

          chart.update();
      }

      window.onload = function () {
          chart = new Chart(document.getElementById('canvas'), config);
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

