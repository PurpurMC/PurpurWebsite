<?php
require_once('header.php');

$filename = 'json/bstats.json';

$contents = file_get_contents($filename);
$json = json_decode($contents === false ? '' : $contents, true);

$now = time();
$lastcheck = isset($json['lastcheck']) ? $json['lastcheck'] : 0;

if ($now - $lastcheck > 60 * 30 ) { // only hit bStats servers every 30 minutes
    $json['lastcheck'] = $lastcheck = $now;
    $json['servers'] = json_decode(file_get_contents('https://bstats.org/api/v1/plugins/5103/charts/servers/data/?maxElements=1'))[0][1];
    $json['players'] = json_decode(file_get_contents('https://bstats.org/api/v1/plugins/5103/charts/players/data/?maxElements=1'))[0][1];
    $json['countries'] = count(json_decode(file_get_contents('https://bstats.org/api/v1/plugins/5103/charts/location/data')));
    file_put_contents($filename, json_encode($json));
}

$total_servers = $json['servers'];
$total_players = $json['players'];
$total_countries = $json['countries'];
?>
    <div id="begin"></div>
    <div>
      <div class="wrapper">
        <div class="main">
          <link href="/css/index.css" type="text/css" rel="stylesheet" />
          <script src="/js/index.js"></script>
          <div id="top">
            <h1>Welcome to Purpur</h1>
            <p>Feel free to look around</p>
            <a href="#begin">CLICK TO BEGIN</a>
          </div>
        </div>
      </div>
    </div>
    <a id="aboutus"></a>
    <div id="about">
      <div class="wrapper">
        <div class="main">
          <div class="section">
            <ul>
              <li>
                <p>The most feature rich software to set your server apart from the others.</p>
                <p>Our goal is to bring you the most configuration options and gameplay on top of the best performaning software in the industry.</p>
              </li>
              <li><img src="/images/purpur_config.png" alt="Purpur configuration file" /></li>
            </ul>
          </div>
        </div>
      </div>
    </div>
    <a id="ourgoal"></a>
    <div id="starlight">
      <div class="wrapper">
        <div class="main">
          <div class="section">
            <ul>
              <li><img src="/images/starlight_th.png" alt="Starlight performance comparison graph" /></li>
              <li>
                <p>Purpur is built on top of the leading performance mods in the industry.</p>
                <p>Things like the blazing fast chunk performance thanks to Tuinity's complete rewrite of the light engine, Starlight.</p>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>
    <div id="ridables">
      <div class="wrapper">
        <div class="main">
          <div class="section">
            <ul>
              <li>
                <p>We started out with just making all the mobs ridable, but have now grown into much more.</p>
                <p>Now with more configuration options than can list on this site, check out our docs to see what all is available.</p>
              </li>
              <li><img src="/images/ridables.png" alt="Ridables logo" width="600" /></li>
            </ul>
          </div>
        </div>
      </div>
    </div>
    <div id="bstats">
      <div class="wrapper">
        <div class="main">
          <ul>
            <li>
              <div>
                <div><i class="fas fa-server"></i></div>
                <div>
                  <p><?=$total_servers?></p>
                  <span>SERVERS</span>
                </div>
              </div>
            </li>
            <li>
              <div>
                <div><i class="fas fa-users"></i></div>
                <div>
                  <p><?=$total_players?></p>
                  <span>PLAYERS</span>
                </div>
              </div>
            </li>
            <li>
              <div>
                <div><i class="fas fa-globe-americas"></i></div>
                <div>
                  <p><?=$total_countries?></p>
                  <span>COUNTRIES</span>
                </div>
              </div>
            </li>
          </ul>
        </div>
      </div>
    </div>
    <a id="theteam"></a>
    <div>
      <div class="wrapper">
        <div class="main">
          <div id="team">
            <h1>Meet The Team</h1>
            <h3>Our dedicated volunteers that bring you the best experience possible</h3>
            <ul>
              <li>
                <a href="https://github.com/BillyGalbreath" target="_blank">
                  <img src="https://avatars1.githubusercontent.com/u/332527?s=460&u=9a4ea3a56d212d307ed052382f9dba29e7464ea5&v=4" alt="BillyGalbreath" title="BillyGalbreath" />
                  <p>BillyGalbreath</p>
                  <span>Developer</span>
                </a>
              </li>
              <li>
                <a href="https://github.com/jpenilla" target="_blank">
                  <img src="https://avatars1.githubusercontent.com/u/11360596?s=460&u=4aeed942f7fa91934b31a5616d4158d5e401b62b&v=4" alt="jpenilla" title="jpenilla" />
                  <p>jpenilla</p>
                  <span>Developer</span>
                </a>
              </li>
              <li>
                <a href="https://github.com/granny" target="_blank">
                  <img src="https://avatars2.githubusercontent.com/u/43185817?s=460&u=f13419c8287d69aa0392b7c118fc5121b2ccac95&v=4" alt="granny" title="granny" />
                  <p>granny</p>
                  <span>Documenter</span>
                </a>
              </li>
              <li>
                <a href="https://github.com/ChrystiGalbreath" target="_blank">
                  <img src="https://avatars2.githubusercontent.com/u/753637?s=460&u=793718eaa9356db941e9421d7a126e3ef0fc4a34&v=4" alt="Chrysti" title="Chrysti" />
                  <p>Chrysti</p>
                  <span>Documenter</span>
                </a>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>          
    <div id="thanks">
      <div class="wrapper">
        <ul>
          <li><img src="/images/thx_paper.png" alt="Paper" title="Paper" /></li>
          <li><img src="/images/thx_tuinity.png" alt="Tuinity" title="Tuinity" /></li>
          <li><img src="/images/thx_github.png" alt="GitHub" title="GitHub" /></li>
          <li><img src="/images/thx_intellij.png" alt="IntelliJ IDEA" title="IntelliJ IDEA" /></li>
          <li><img src="/images/thx_yourkit.png" alt="YourKit" title="YourKit" /></li>
        </ul>
      </div>
    </div>
<?php
require_once("footer.php")
?>