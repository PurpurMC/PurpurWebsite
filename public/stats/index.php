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

        $counts = (array) null;
        $dates = (array) null;

        foreach ($data as $entry) {
            $time = $entry[0] / 1000; // millis to seconds
            $count = $entry[1];

            $dt = new DateTime("@$time");
            if ($dt->format('H')  == 19 && $dt->format('i') == 0) {
                array_push($counts, $count);
                array_push($dates, $time);
                $last = $time;
            }
        }

        $json['servers'][$server]['data'] = $counts;
        $json['dates'] = $dates;
    }

    $json['last'] = $last;

    file_put_contents($filename, json_encode($json));
}

?><!doctype html>
<html lang="en">
  <head>
    <title>Server Usage</title>
    <meta charset="UTF-8" />
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  </head>
  <body>
    <div style="width:1200px;margin:0 auto">
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
                      text: 'Server Usage'
                  },
                  tooltip: {
                      mode: 'index',
                      intersect: false
                  },
              },
              scales: {
                  x: {
                      display: true,
                      title: {
                          display: true,
                          text: 'Date'
                      }
                  },
                  y: {
                      display: true,
                      beginAtZero: true,
                      title: {
                          display: true,
                          text: 'Servers'
                      }
                  }
              }
          }
      };

      function convert(seconds) {
          let date = new Date(seconds * 1000);
          return (date.getMonth() + 1) + "/" +  date.getDate() + "/" +  ("" + date.getFullYear()).slice(2);
      }

      function loadData(name, json) {
          json.dates.forEach(function(date) {
              config.data.labels.push(convert(date));
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

