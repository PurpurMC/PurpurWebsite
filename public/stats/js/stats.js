let colors = [];
let serverChart, playerChart;
let serverConfig = mkcfg("Server Counts", "Date", "Servers");
let playerConfig = mkcfg("Player Counts", "Date", "Players");

function mkcfg(title, x, y) {
  let cfg = JSON.parse(JSON.stringify(config));
  cfg.options.plugins.title.text = title;
  cfg.options.scales.x.title.text = x;
  cfg.options.scales.y.title.text = y;
  return cfg;
}

function updateConfig(config, data) {
  Object.keys(data).forEach(function(server) {
    config.data.datasets.push({
        label: server,
        backgroundColor: colors[server],
        borderColor: colors[server],
        data: data[server]
    });
  });
}

function loadData(json) {
  json.data.dates.forEach(function(date) {
    serverConfig.data.labels.push(date);
    playerConfig.data.labels.push(date);
  });

  updateConfig(serverConfig, json.data.servers);
  updateConfig(playerConfig, json.data.players);

  serverChart.update();
  playerChart.update();
}

function init(json) {
  document.getElementById("loading").style.opacity = 0;
  document.getElementById("graphs").style.opacity = 1;
  Object.keys(json.servers).forEach(function(server) {
    colors[server] = json.servers[server].color;
  });
  getJson('data/data.json', json => loadData(json));
}

function getJson(file, callback) {
  fetch(file).then(async res => {
    if (res.ok){
      callback(await res.json());
    }
  });
}

window.onload = function () {
  serverChart = new Chart(document.getElementById('servers'), serverConfig);
  playerChart = new Chart(document.getElementById('players'), playerConfig);
  getJson('update.php', res => getJson('data/servers.json', json => init(json)));
};
