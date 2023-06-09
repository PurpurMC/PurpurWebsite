<?php
    require_once("inc/versions.php");

    $url = "https://api.purpurmc.org/v2/purpur";

    $project = file_get_contents($url);
    $project = json_decode($project, true);

    $versions = [];
    $rootVersions = [];
    foreach ($project["versions"] as $version) {
        $versions[] = $version;

        preg_match("/\d\.\d*/", $version, $matches);
        $rootVersions[] = $matches[0];
    }
    $rootVersions = array_unique($rootVersions);
    rsort($rootVersions);


    $listVersions = [];
    foreach ($rootVersions as $rootVersion) {
        $subversions = [];

        foreach($versions as $version) {
            if (str_contains($version, $rootVersion)) {
                $subversions[] = $version;
            }
        }
        usort($subversions, "version_compare");

        $finalVersion = $subversions[count($subversions) - 1];
        if (!in_array($finalVersion, $forceInvisible)) {
            $listVersions[] = $finalVersion;
        }

        if (in_array($finalVersion, $betaVersions)) {
            $finalVersion = $subversions[count($subversions) - 2];
            $listVersions[] = $subversions[count($subversions) - 2];
        }
    }

    $usingVersion = $currentVersion;
    if (isset($_GET["v"])) {
        $setVersion = filter_var($_GET["v"], FILTER_SANITIZE_STRING);
        if (in_array($setVersion, $versions)) {
            $usingVersion = $setVersion;
        }
    }

    $disclaimers = [];
    if (in_array($usingVersion, $betaVersions)) {
        $disclaimers[] = "You are trying to download experimental builds!<br /><u>DO NOT</u> use these builds in production, as there may be many bugs and corruption issues.<br />Please report any and all issues you encounter!";
    }

    if (($usingVersion != $currentVersion && $usingVersion != $listVersions[0]) && !in_array($usingVersion, $betaVersions)) {
        $disclaimers[] = "You are trying to download builds for an old version of Minecraft!<br />Keep in mind that if you download these builds, you will not receive support.";
    }

    foreach ($knownVulnerabilities as $vulnerability) {
        if (in_array($usingVersion, $vulnerability["affectedVersions"])) {
            $disclaimers[] = $vulnerability["message"];
        }
    }

    $version = json_decode(file_get_contents($url . "/" . $usingVersion . "?detailed"), true);
    $builds = (array) $version["builds"]["all"];
    rsort($builds);

    function getDownloadButton($url, $version, $build, $result) {
        if ($result == "SUCCESS") {
            return "<a href='$url/$version/$build/download' class='button white-button' download='purpur-$version-$build.jar' title='Download build #$build'><span><i class='fas fa-cloud-download-alt'></i> $build</span></a>";
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
            $result .= "<p title='" . shortenGitHubUrls(scrub($commit["description"])) . $committer . "'><span>[$hash]</span> " . parseIssues(scrub(explode("\n", $commit["description"])[0])) . "</p>\n";
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

    require_once('inc/header.php');
?>
    <body>
    <link href="/css/downloads.css" type="text/css" rel="stylesheet" />
    <script src="/js/downloads.js"></script>
    <script src="/js/fontawesome.js" crossorigin="anonymous"></script>

    <div class="top-row">
        <div class="container">
            <nav>
                <a href="/docs"><i class="fas fa-book-open"></i> Docs</a>
                <a href="/github" target="_blank"><i class="fab fa-github"></i> GitHub</a>
                <a href="/discord" target="_blank"><i class="fab fa-discord"></i> Discord</a>
                <a href="/downloads"><i class="fas fa-cloud-download-alt"></i> Downloads</a>
            </nav>
            <a href="/"><img src="/images/purpur-pride.svg" alt="Purpur logo" /></a>
            <div>
                <a href="/"><h2>Purpur</h2></a>
                <p>Your Minecraft, your way</p>
            </div>
        </div>
    </div>
    <div class="row-one">
        <div class="container">
            <ul class="tabs">
                <?php foreach ($listVersions as $version): ?>
                    <li class="<?=$version == $usingVersion ? "selected" : ""?>"><a class="tabLink"><?=$version?></a></li>
                <?php endforeach; ?>
            </ul>
            <select id="dropdown">
                <?php foreach ($listVersions as $version): ?>
                    <option value="<?=$version?>" <?=$version == $usingVersion ? "selected" : ""?>><?=$version?></option>
                <?php endforeach; ?>
            </select>
            <div class="versionWarning <?=(!empty($disclaimers) ? "visible" : "")?>">
                <?= join("<br /><br />", $disclaimers) ?>
            </div>
            <table class="downloads">
                <thead><tr><td class="left">Build</td><td class="middle">Changes</td><td class="right">Date <label>(24h <input type="checkbox" id="check-24h">)</label></td></tr></thead>
                <tbody>
                    <?php foreach ($builds as $build): ?>
                        <tr>
                            <td class="left"><?=getDownloadButton($url, $build["version"], $build["build"], $build["result"])?></td>
                            <td class="mid"><?=getCommits($build["commits"])?></td>
                            <td class="right timestamp" data-timestamp="<?=$build["timestamp"]?>"></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <footer>
        &copy; 2019-2023 <a href="https://purpurmc.org">purpurmc.org</a> <a href="https://github.com/PurpurMC/Purpur/blob/HEAD/LICENSE">MIT</a>
        <p><a href="https://gitlab.gnome.org/Teams/Releng/gnome-os-site" rel="noreferrer" target="_blank">Site design CC-BY-SA</a></p>
    </footer>
    </body>
</html>

