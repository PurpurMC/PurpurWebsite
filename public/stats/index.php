<?php

  require_once ("include/vars.php");
  require_once ("include/util.php");
  
?><!doctype html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <title>Server Usage</title>
    <meta name="title" content="Server Usage" />
    <meta name="description" content="" />
    <meta property="twitter:title" content="Server Usage" />
    <meta property="twitter:image" content="https://purpurmc.org/stats/jpgraph.php?t=<?=$now?>" />
    <meta property="twitter:card" content="summary_large_image" />
    <meta property="twitter:description" content="" />
    <meta property="og:title" content="Server Usage" />
    <meta property="og:url" content="https://purpurmc.org/stats" />
    <meta property="og:description" content="" />
    <meta property="og:image" content="https://purpurmc.org/stats/jpgraph.php?t=<?=$now?>" />
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
    <meta http-equiv="Pragma" content="no-cache" />
    <meta http-equiv="Expires" content="0" />
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="js/config.js"></script>
    <script src="js/stats.js"></script>
    <link type="text/css" rel="stylesheet" href="css/styles.css" />
  </head>
  <body>
    <div id="loading">
      <span>
        Loading data. Please wait...<br/><br/>
        <div class="lds-default"><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div>
      </span>
    </div>
    <div id="graphs">
      <div id="stats_graph"><canvas id="servers"></canvas></div>
      <br/><br/>  
      <div id="stats_graph"><canvas id="players"></canvas></div>
      <p>
        These graphs update once a day at <?=$todayThreePM->format("H:i O")?><br>
        Time now: <?=$timenow->format("m/d/y H:i O")?><br>
        Next update: <?=$tomorrowThreePM->format("m/d/y H:i O")?>
      </p>
    </div>
  </body>
</html>
