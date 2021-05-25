<?php
  // get the yaml data from file
  $filename = 'data/github.yml';
  $contents = file_get_contents($filename);
  $github = yaml_parse($contents === false ? '' : $contents);

  $now = time();
  $last = isset($github['last']) ? $github['last'] : 0;

  // only update from github once every two minutes
  if ($now - $last > 120) {
    $contents = getContents('https://api.github.com/repos/pl3xgaming/purpurdocs');

    $data = json_decode($contents, true);

    $github['stars'] = $data['stargazers_count'];
    $github['forks'] = $data['forks_count'];

    $github['last'] = $now;

    file_put_contents($filename, yaml_emit($github));
  }
?>
