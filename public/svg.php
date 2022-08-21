<?php

$type = isset($_GET["type"]) ? $_GET["type"] : "sponsors";

if ($type != "sponsors" && $type != "contributors") {
    die();
}

$now = time();

$avatarSize = isset($_GET["size"]) ? $_GET["size"] : 66;
$avatarGap = isset($_GET["gap"]) ? $_GET["gap"] : 4;
$avatarCols = isset($_GET["cols"]) ? $_GET["cols"] : 10;

$file = $type . ".json";
$contents = file_get_contents($file);
$json = json_decode($contents === false ? '' : $contents, true);

// update data only once every hour
if ($json == null || $json["timestamp"] < $now - 2400) {
    require_once("/var/www/.github_graphql_secret_token");
    $json = updateData($file, $json);
}

// draw the svg
if (@$json["data"]) {
    drawSvg($json["data"]);
}

// ###############

function updateData($file, $json) {
    global $now, $type;

    // save now to prevent concurrent updates
    $json["timestamp"] = $now;
    file_put_contents($file, json_encode($json));

    // get updated contributors list from github
    switch ($type) {
        case "contributors":
            $contents = getFromGitHubRestAPI("repos/PurpurMC/Purpur/contributors");
            $newJson = json_decode($contents === false ? '' : $contents, true);
            break;
        case "sponsors":
            $query = 'query Sponsors($org:String!){organization(login:$org){... on Sponsorable{sponsors(first:100){nodes{... on Actor{login,avatarUrl}}}}}}';
            $variables = ['org' => 'PurpurMC'];
            $contents = getFromGitHubGraphQL($query, $variables);
            $newJson = json_decode($contents === false ? '' : $contents, true);
            $newJson = $newJson["data"]["organization"]["sponsors"]["nodes"];
            break;
        default:
            die();
    }

    // grab only what we need
    $arr = (array)null;
    $i = 0;
    foreach ($newJson as $entry) {
        $arr["data"][$i]["name"] = $entry["login"];
        $arr["data"][$i]["avatar"] = getAvatar(@$entry["avatarUrl"] ?: $entry["avatar_url"]);
        $i++;
    }

    // store updated data
    $arr["timestamp"] = $now;
    $data = json_encode($arr);
    file_put_contents($file, $data);

    // convert back to json object
    return json_decode($data, true);
}

// resize avatars and convert to base64
function getAvatar($url) {
    global $avatarSize;

    // get image info
    $info = getimagesize($url);
    $type = $info[2];

    // create image resource
    if ($type == IMAGETYPE_JPEG) {
        $image = imagecreatefromjpeg($url);
    } else if ($type == IMAGETYPE_GIF) {
        $image = imagecreatefromgif($url);
    } else if ($type == IMAGETYPE_PNG) {
        $image = imagecreatefrompng($url);
    }

    // resize image resource
    $new_image = imagecreatetruecolor($avatarSize, $avatarSize);
    imagecopyresampled($new_image, $image, 0, 0, 0, 0, $avatarSize, $avatarSize, imagesx($image), imagesy($image));

    // draw and catch image resource
    ob_start();
    imagepng($new_image);
    $contents = ob_get_clean();

    // convert image to base64
    return 'data:' . image_type_to_mime_type($type) . ';base64,' . base64_encode($contents);
}

function drawSvg($data) {
    global $avatarSize, $avatarGap, $avatarCols;

    // image sizes
    $count = count($data);
    $width = ($avatarSize + $avatarGap) * ($count > $avatarCols ? $avatarCols : $count) - $avatarGap;
    $height = ($avatarSize + $avatarGap) * ceil($count / $avatarCols) - $avatarGap;

    header("Cache-Control: no-cache, no-store, must-revalidate");
    header("Pragma: no-cache");
    header("Expires: 0");
    header('Content-type: image/svg+xml');

    // write the beginning of the svg image
    echo '<?xml version="1.0" standalone="no"?>';
    echo '<!DOCTYPE svg PUBLIC "-//W3C//DTD SVG 1.1//EN" "http://www.w3.org/Graphics/SVG/1.1/DTD/svg11.dtd">';
    echo '<svg xmlns="http://www.w3.org/2000/svg" width="' . $width . '" height="' . $height . '" viewBox="0 0 ' . $width . ' ' . $height . '">';
    echo '<style>circle:hover {stroke-width: 2;}</style>' . "\n";

    // write the pattern definitions
    echo '<defs>' . "\n";
    foreach ($data as $entry) {
        $name = $entry["name"];
        $avatar = $entry["avatar"];
        echo '<pattern id="' . $name . '" patternUnits="userSpaceOnUse" width="' . $avatarSize . '" height="' . $avatarSize . '"><image href="' . $avatar . '" x="0" y="0" width="' . $avatarSize . '" height="' . $avatarSize . '" /></pattern>' . "\n";
    }
    echo '</defs>' . "\n";

    // write each contributor
    $col = 0;
    $row = 0;
    foreach ($data as $entry) {
        $name = $entry["name"];
        echo '<svg x="' . $col . '" y="' . $row . '"><title>' . $name . '</title><a href="https://github.com/' . $name . '" target="_blank"><circle cx="' . ($avatarSize / 2) . '" cy="' . ($avatarSize / 2) . '" r="' . ($avatarSize / 2 - 1) . '" stroke="#000" stroke-width="1" fill="url(#' . $name . ')"/></a></svg>' . "\n";
        $col += $avatarSize + $avatarGap;
        if ($col > $avatarSize * $avatarCols) {
            $col = 0;
            $row += $avatarSize + $avatarGap;
        }
    }

    // close off the svg image
    echo '</svg>';
}

function getFromGitHubRestAPI($endpoint) {
    return file_get_contents(
        "https://api.github.com/" . $endpoint,
        false, stream_context_create([
            'http' => [
                'method' => 'GET',
                'header' => [
                    'User-Agent: PHP'
                ]
            ]
        ])
    );
}

function getFromGitHubGraphQL($query, $variables) {
    global $token;
    return file_get_contents(
        "https://api.github.com/graphql",
        false, stream_context_create([
            'http' => [
                'method' => 'POST',
                'header' => [
                    'Authorization: bearer ' . $token,
                    'Content-Type: application/json',
                    'User-Agent: PHP'
                ],
                'content' => json_encode(['query' => $query, 'variables' => $variables]),
            ]
        ])
    );
}

?>
