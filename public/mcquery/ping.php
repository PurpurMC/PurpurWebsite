<?php

require 'xpaw.php';
require 'xpawexception.php';

use xPaw\xpaw;
use xPaw\xpawexception;

function ping($url, $port) {
    try {
        $query = new xpaw($url, $port);
        return $query->Query();
    } catch(xpawexception $e) {
        return null;
    } finally {
        if($query) {
            $query->Close();
        }
    }
}

$filename = 'cache.json';

$contents = file_get_contents($filename);
$json = json_decode($contents === false ? "" : $contents, true);

$now = time();
$last = isset($json['last']) ? $json['last'] : 0;

if ($now - $last > -1 ) {
    $json['last'] = $last = $now;
    
    for ($i = 0; $i < count($json['servers']); $i++) {
        $server = $json['servers'][$i];
        $data = ping($server['url'], $server['port']);
        $server['data'] = json_encode($data);
        $json['servers'][$i] = $server;
        var_dump($data);
        echo "<hr/>";
    }
    
    file_put_contents($filename, json_encode($json));
}

echo $last;

?>
