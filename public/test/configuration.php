<?php
  require_once("inc/common.php");

  // get the yaml data from file
  $filename = 'data/data.yml';
  $contents = file_get_contents($filename);
  $json = yaml_parse($contents === false ? '' : $contents);

  // get current option for open graph
  $urlid = @$_GET['id'];
  $option = getValue($json, $urlid);
  if ($option !== null) {
    $ogdesc = htmlspecialchars($option['description']) . "\n\ndefault: " . htmlspecialchars($option['default']);
    $ogimg = "https://i.pinimg.com/originals/0c/d8/55/0cd85593806593360a4a114550449670.gif";
    $ogurl = "$ogurl?id=" . $urlid;
    $path = explode('.', $urlid);
    $ogtitle = array_pop($path);
    $path = implode('.', $path);
    $oembed = "author_name=".$path."&author_url=".$ogurl."&provider_name=Purpur%20Documentation&provider_url=".$ogurl;
  }

  $page = "configuration";

  require_once("inc/header.php");
?>
          <link type="text/css" rel="stylesheet" href="css/configuration.css" />
          <div class="middle noindent">
            <div class="section noindent">
              <h1>Configuration</h1>
              <p>This page details the various configuration settings exposed by Purpur in the purpur.yml file.</p>
              <p>If you want information on settings in airplane.yml, paper.yml, spigot.yml, bukkit.yml and server.properties you should see their respective documentation pages.</p>
              <div class="section links">
                <p class='link'><a href="https://minecraft.gamepedia.com/Server.properties" target="_blank">Server Configuration</a> (server.properties)</p>
                <p class='link'><a href="https://bukkit.gamepedia.com/Bukkit.yml" target="_blank">Bukkit Configuration</a> (bukkit.yml)</p>
                <p class='link'><a href="https://www.spigotmc.org/wiki/spigot-configuration/" target="_blank">Spigot Configuration</a> (spigot.yml)</p>
                <p class='link'><a href="https://paper.readthedocs.io/en/latest/server/configuration.html" target="_blank">Paper Configuration</a> (paper.yml)</p>
                <p class='link'><a href="https://github.com/TECHNOVE/Airplane/wiki" target="_blank">Airplane Configuration</a> (airplane.yml)</p>
              </div>
              <div class='subsection warning'>
                <span class='optionvalue'>Configuration values change frequently at times. It is possible for the information here to be incomplete. If you cannot find what you’re looking for or think something may be wrong, Contact us through our <a href="https://purpur.pl3x.net/discord">Discord</a> server.</span>
              </div>
            </div>
            <div class="section noindent">
              <br/>
              <h1>Global Settings</h1>
            </div>
<?php
  buildConfig($json["settings"], null, 0);
?>
            <div class="section noindent">
              <br/>
              <h1>World Specific Settings</h1>
            </div>
<?php
  buildConfig($json["world-settings"]["default"], null);
?>
          </div>
<?php
  $prev = "Commands";
  $next = "Permissions";
  require_once("inc/footer.php");

  function buildConfig($arr, $key) {
    global $table_of_contents;
    foreach ($arr as $k => $v) {
      $path = ($key === null ? "" : $key . ".") . $k;

      if (is_array($v) && !is_array($v[array_key_first($v)])) {
          new TOC($path);
          showOption($path, $k, $v);
          continue;
      }

      new TOC($path);
      echo "<div>\n";
      echo "<div class='anchor' id='$path'></div>\n";
      echo "<p class='headerlink' title='$path'>$k <span>🔗</span></p>\n";
      buildConfig($v, $path);
      echo "</div>\n";
    }
  }

  function showOption($path, $key, $option) {
    echo "<div class='section'>\n";
    echo "<div class='anchor' id='$path'></div>\n";
    echo "<p class='headerlink' title='$path'>$key <span>🔗</span></p>\n";
    showLine($option, 'requirement');
    showLine($option, 'default');
    showLine($option, 'description');
    showLine($option, 'note');
    showLine($option, 'warning');
    echo "</div>\n";
  }

  function showLine($option, $name) {
    if (isset($option[$name])) {
      echo "<div class='subsection $name'>\n";
      echo "<span class='optionvalue'>" . parseMarkdown($option[$name]) . "</span>\n";
      echo "</div>\n";
    }
  }
?>
