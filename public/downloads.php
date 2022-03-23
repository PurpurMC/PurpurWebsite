<?php
    require_once("inc/versions.php");

    $allHotfixes = [];

    $allKnownVulnerabilities = [
        [
                "affectedVersions"=>["1.18", "1.17.1", "1.17", "1.16.5", "1.16.4", "1.16.3", "1.16.2", "1.16.1", "1.16", "1.15.2", "1.15.1", "1.15", "1.14.4", "1.14.3", "1.14.2", "1.14.1"],
                "message"=>"This version is known to contain an RCE vulnerability!<br /><a href='https://purpurmc.org/docs/Log4j/'>Read about the exploit and potential mitigations here</a>"
        ]
    ];

    $contents = file_get_contents("/srv/papyrus/data.json");
    $json = json_decode($contents, true);

    $project = null;
    foreach ($json["projects"] as $possibleProject) {
        if ($possibleProject["name"] == "purpur") {
            $project = $possibleProject;
        }
    }

    $rootVersionNames = [];
    $versionNames = [];
    foreach ($project["versions"] as $version) {
        $versionNames[] = $version["name"];

        preg_match("/\d\.\d*/", $version["name"], $matches);
        $rootVersionNames[] = $matches[0];
    }
    rsort($versionNames);

    $rootVersionNames = array_unique($rootVersionNames);
    rsort($rootVersionNames);

    $finalVersionNames = [];
    $visibleTabs = [];
    foreach ($rootVersionNames as $rootVersion) {
        $subversions = [];

        foreach($versionNames as $version) {
            if (str_contains($version, $rootVersion)) {
                $subversions[] = $version;
            }
        }

        usort($subversions, "version_compare");

        $finalVersion = $subversions[count($subversions) - 1];
        $finalVersionNames[] = $finalVersion;

        if (!in_array($finalVersion, $forceInvisible)) {
            $visibleTabs[] = $finalVersion;
        }
        if (in_array($finalVersion, $betaVersions)) {
            $finalVersion = $subversions[count($subversions) - 2];
            $visibleTabs[] = $subversions[count($subversions) - 2];
        }
    }

    $versionName = $currentVersion;
    if (isset($_GET["v"])) {
        $versionName = filter_var($_GET["v"], FILTER_SANITIZE_STRING);
    }

    if (!in_array($versionName, $versionNames)) {
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

    if (array_key_exists($versionName, $allHotfixes)) {
        $hotfixesForVersion = $allHotfixes[$versionName];
        $hotfixBuilds = $hotfixesForVersion["builds"];
        foreach ($hotfixBuilds as $build) {
            $build["isHotfix"] = true;
            array_unshift($builds, $build);
        }
    }

    $disclaimers = [];
    $currentVersionIndex = array_search($currentVersion, $versionNames);
    $selectedVersionIndex = array_search($versionName, $versionNames);
    $isExperimental = in_array($versionName, $betaVersions);
    if ($isExperimental) {
        $disclaimers[] = "You are trying to download experimental builds!<br /><u>DO NOT</u> use these builds in production, as there may be many bugs and corruption issues.<br />Please report any and all issues you encounter!";
    }

    if ($versionName != $currentVersion && ($selectedVersionIndex > $currentVersionIndex || !in_array($versionName, $finalVersionNames))) {
        $disclaimers[] = "You are trying to download builds for an old version of Minecraft!<br />Keep in mind that if you download these builds, you will not receive support.";
    }

    foreach ($allKnownVulnerabilities as $vulnerability) {
        if (in_array($versionName, $vulnerability["affectedVersions"])) {
            $disclaimers[] = $vulnerability["message"];
        }
    }

    function getHotfix($version, $build) {
        return "<a href='https://api.purpurmc.org/hotfixes/$version/purpur-$version-$build.jar' class='button white-button' download='purpur-$version-$build.jar' title='Download build #$build'><span><i class='fas fa-cloud-download-alt'></i> $build</span></a>";
    }

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
            <a href="/"><img src="/images/purpur.svg" alt="Purpur logo" /></a>
            <div>
                <a href="/"><h2>Purpur</h2></a>
                <p>Your Minecraft, your way</p>
            </div>
        </div>
    </div>
    <div class="row-one">
        <div class="container">
            <ul class="tabs">
                <?php foreach ($visibleTabs as $name): ?>
                    <li class="<?=$name == $versionName ? "selected" : ""?>"><a class="tabLink"><?=$name?></a></li>
                <?php endforeach; ?>
            </ul>
            <select id="dropdown">
                <?php foreach ($visibleTabs as $name): ?>
                    <option value="<?=$name?>" <?=$name == $versionName ? "selected" : ""?>><?=$name?></option>
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
                            <td class="left"><?=array_key_exists("isHotfix", $build) ? getHotfix($versionName, $build["build"]) : getDownloadButton($build["version"], $build["build"], $build["result"])?></td>
                            <td class="mid"><?=getCommits($build["commits"])?></td>
                            <td class="right timestamp" data-timestamp="<?=$build["timestamp"]?>"></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <footer>
        &copy; 2019-2022 <a href="https://purpurmc.org">purpurmc.org</a> <a href="https://github.com/PurpurMC/Purpur/blob/HEAD/LICENSE">MIT</a>
        <p><a href="https://gitlab.gnome.org/Teams/Releng/gnome-os-site" rel="noreferrer" target="_blank">Site design CC-BY-SA</a></p>
    </footer>
    </body>
</html>
