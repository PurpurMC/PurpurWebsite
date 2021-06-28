<?php

  // return an array from json data
  function processData($json) {
    // globals we need
    global $timezone, $today;

    // create the array
    $arr = (array) null;

    // iterate json data
    foreach ($json as $entry) {
      // organize our data
      $seconds = ($entry[0] / 1000); // millis to seconds
      $count = $entry[1]; // data count

      // get date in simplified format
      $date = (new DateTime("@$seconds"))->setTimezone($timezone)->format('m/d/y');

      // only store highest count for that day
      if (strcmp($date, $today) !== 0 && $count > (@$arr[$date] == null ? -1 : $arr[$date])) {
        // populate the array
        $arr[$date] = $count;
      }
    }

    // return our data array
    return $arr;
  }

?>
