<?php
    require_once("opengraph.php");

    $currentVersion = "1.17.1";

    $contents = file_get_contents("/srv/purpur/papyrus/data.json");
    $json = json_decode($contents, true);

    $project = null;
    foreach ($json["projects"] as $possibleProject) {
        if ($possibleProject["name"] == "purpur") {
            $project = $possibleProject;
        }
    }

    $versionNames = [];
    foreach ($project["versions"] as $version) {
        $versionNames[] = $version["name"];
    }
    rsort($versionNames);

    $versionName = $_GET["v"];
    if ($versionName == null || !in_array($versionName, $versionNames)) {
        $versionName = $currentVersion;
    }

    $version = [];
    foreach ($project["versions"] as $possibleVersion) {
        if ($possibleVersion["name"] == $versionName) {
            $version = $possibleVersion;
        }
    }

    $builds = [];
    foreach ($version["builds"] as $build) {
        array_push($builds, $build);
    }
    rsort($builds);

    function getDownloadButton($version, $build, $result) {
        if ($result == "SUCCESS") {
            return "<a href='https://api.purpurmc.org/v2/purpur/$version/$build/download' class='button white-button' download='purpur-$version-$build.jar' title='Download build #$build'><span><i class='fas fa-cloud-download-alt'></i> $build</span></a>";
        } else {
            return "<a href='#' class='button disabled-button' title='Failed build #$build'><span><i class='fas fa-times-circle'></i> $build</span></a>";
        }
    }

    function getCommits($commits) {
        if ($commits == null) {
            return "No changes";
        }

        $result = "";
        foreach ($commits as $commit) {
            $committer = "\n\n- " . scrub($commit["author"]) . " <" . str_replace(".", "&period;", str_replace("@", "&commat;", scrub($commit["email"]))) . ">";
            $hash = "<a href='https://github.com/PurpurMC/Purpur/commit/" . $commit["hash"] . "' class='hash' rel='noreferrer' target='_blank'>" . substr($commit["hash"], 0, 7) . "</a>";
            $result .= "<p title='" . shortenGitHubUrls(scrub($commit["title"])) . $committer . "'><span>[$hash]</span> " . parseIssues(scrub(explode("\n", $commit["title"])[0])) . "</p>\n";
        }
        return $result;
    }

    function parseIssues($description) {
        $result = "";

        foreach (explode(" ", $description) as $str) {
            if (preg_match('/^\W?(#[0-9]+)\W?$/', $str, $match)) {
                $str = "<a href='https://github.com/PurpurMC/Purpur/issues/" . substr($match[1], 1) . "' class='issue' rel='noreferrer' target='_blank'>" . $str . "</a>";
            }
            else if (preg_match('/^\W?(MC\-[0-9]+)\W?$/', $str, $match)) {
                $str = "<a href='https://bugs.mojang.com/browse/" . $match[1] . "' class='issue' rel='noreferrer' target='_blank'>" . $str . "</a>";
            }

            $result .= (strlen($result) > 0 ? " " : "") . $str;
        }

        return $result;
    }

    function shortenGitHubUrls($description) {
        return preg_replace('/(https:\/\/github\.com\/.*\/.*\/commit\/([a-z0-9]{7})[a-z0-9]{33})/', "[$2] ", $description);
    }

    function scrub($str) {
        return str_replace("'", "&apos;", str_replace('"', "&quot;", htmlspecialchars($str)));
    }

?><!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title><?=$title?></title>
        <meta name="title" content="<?=$title?>" />
        <meta name="description" content="<?=$description?>" />
        <meta property="twitter:title" content="<?=$title?>" />
        <meta property="twitter:image" content="<?=$ogimg?>" />
        <meta property="twitter:card" content="summary_large_image" />
        <meta property="twitter:description" content="<?=$description?>" />
        <meta property="og:title" content="<?=$title?>" />
        <meta property="og:url" content="https://purpurmc.org" />
        <meta property="og:description" content="<?=$description?>" />
        <meta property="og:image" content="<?=$ogimg?>" />
        <link rel="icon" type="image/x-icon" href="images/purpur.svg" />
        <link type="text/css" rel="stylesheet" href="css/raleway.css" />
        <link type="text/css" rel="stylesheet" href="css/index.css" />
        <link href="css/downloads.css" type="text/css" rel="stylesheet" />
        <script src="js/downloads.js"></script>
        <script src="js/fontawesome.js" crossorigin="anonymous"></script>
    </head>
    <body>
    <div class="top-row">
        <div class="container">
            <nav>
                <a href="/docs"><i class="fas fa-book-open"></i> Docs</a>
                <a href="/github" target="_blank"><i class="fab fa-github"></i> GitHub</a>
                <a href="/discord" target="_blank"><i class="fab fa-discord"></i> Discord</a>
                <a href="/downloads"><i class="fas fa-cloud-download-alt"></i> Downloads</a>
            </nav>
            <a href="/"><img src="images/purpur.svg" alt="Purpur logo" /></a>
            <div>
                <a href="/"><h2>Purpur</h2></a>
                <p>Your Minecraft, your way</p>
            </div>
        </div>
    </div>
    <div class="row-one">
        <div class="container">
            <ul class="tabs">
                <?php foreach ($versionNames as $name): ?>
                    <li class="<?=$name == $versionName ? "selected" : ""?>"><a href="?v=<?=$name?>"><?=$name?></a></li>
                <?php endforeach; ?>
            </ul>
            <select id="dropdown">
                <?php foreach ($versionNames as $name): ?>
                    <option value="<?=$name?>" <?=$name == $versionName ? "selected" : ""?>><?=$name?></option>
                <?php endforeach; ?>
            </select>
            <div id="oldVersionWarning" class="oldVersionWarning <?=($versionName != $currentVersion ? "visible" : "")?>">
                You are trying to download builds for an unsupported version of Minecraft!<br />
                Keep in mind that if you download these builds, you won't get any support from Purpur!
            </div>
            <table class="downloads">
                <thead><tr><td class="left">Build</td><td class="middle">Changes</td><td class="right">Date</td></tr></thead>
                <tbody>
                    <?php foreach ($builds as $build): ?>
                        <tr>
                            <td class="left"><?=getDownloadButton($build["version"], $build["build"], $build["result"])?></td>
                            <td class="mid"><?=getCommits($build["commits"])?></td>
                            <td class="right"><?=date("Y-m-d H:i:s", $build["timestamp"] / 1000)?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <footer>
        &copy; 2019-2021 <a href="https://purpurmc.org">purpurmc.org</a> <a href="https://github.com/PurpurMC/Purpur/blob/ver/1.18/LICENSE">MIT</a>
        <p><a href="https://gitlab.gnome.org/Teams/Releng/gnome-os-site" rel="noreferrer" target="_blank">Site design CC-BY-SA</a></p>
    </footer>
    </body>
</html>


