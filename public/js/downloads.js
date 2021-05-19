const downloads = 'https://purpur.pl3x.net/api/v1/purpur/';
const latestVersion = '1.16.5'; // change this value whenever newer version of Minecraft releases

var cache = new Map();
var current;

window.addEventListener('load', function() {
    getJSON(downloads, function(json) {
        var selected = window.location.hash ? window.location.hash.substring(1) : latestVersion;
        var ul = document.getElementsByClassName("tabs")[0];
        for (var i = 0; i < json.versions.length; i++) {
            createTab(ul, json.versions[i], selected);
        }
    });
});

function createTab(ul, version, selected) {
    var a = document.createElement("a");
    a.appendChild(document.createTextNode(version));
    a.href= "#" + version;
    a.onclick = clickTab;
    var li = document.createElement("li");
    li.appendChild(a);
    ul.appendChild(li);
    var option = document.createElement("option");
    option.value = version;
    option.appendChild(document.createTextNode(version));
    var dropdown = document.getElementById("dropdown");
    dropdown.appendChild(option);
    dropdown.onchange = changeDropdown;
    if (selected === version) {
        li.classList.add("selected");
        a.click();
    }
}

function changeDropdown(option) {
    selectTab(getTab(option.target.value));
    updateList(option.target.value);
}

function clickTab(li) {
    selectTab(this.parentElement);
    selectDropdown(this.innerHTML);
    updateList(this.innerHTML);
}

function selectTab(tab) {
    var tabs = tab.parentElement.children;
    for (var i = 0; i < tabs.length; i++) {
        tabs[i].classList.remove("selected");
    }
    tab.classList.add("selected");
}

function selectDropdown(version) {
    var dropdown = document.getElementById("dropdown");
    for (var i = 0; i < dropdown.options.length; i++) {
        var option = dropdown.options[i];
        option.selected = option.value == version;
    }
}

function getTab(version) {
    var tabs = document.getElementsByClassName("tabs")[0].children;
    for (var i = 0; i < tabs.length; i++) {
        if (tabs[i].children[0].innerHTML == version) {
            return tabs[i];
        }
    }
    return null;
}

function updateList(version) {
    var table = document.getElementsByClassName("downloads")[0];
    var tbody = table.getElementsByTagName("tbody")[0];
    tbody.innerHTML = "";

    current = version;

    if (cache.has(version)) {
        var map = cache.get(version);
        var builds = map.get('builds');
        for (var i = 0; i < builds.length; i++) {
            var tr = document.createElement('tr');
            tr.id = "row_" + builds[i];
            tbody.appendChild(tr);
        }
        map.forEach(function(data, build, map) {
            if (build !== "builds") {
                showData(version, build, data);
            }
        });
    } else {
        getJSON(downloads + version, function(json) {
            var builds = json.builds.all;
            builds.sort((a, b) => b - a);
            var map = new Map();
            map.set('builds', builds);
            cache.set(version, map);
            for (var i = 0; i < builds.length; i++) {
                var tr = document.createElement('tr');
                tr.id = "row_" + builds[i];
                tbody.appendChild(tr);
                populateData(version, builds[i]);
            }
        });
    }
    
    warnOldVersion(current); // show warning message
    
    return true;
}

function populateData(version, build) {
    var ci = 'https://ci.pl3x.net/job/';
    if (build > 908) {
        ci += 'Purpur/';
    } else if (build > 55) {
        ci += 'PurpurMaven/';
    } else {
        ci += 'PurpurOld/';
    }
    ci += build + '/api/json';
    getJSON(ci, function(json) {
        var map = cache.get(version);
        var data = map.get(build);
        if (data == null) {
            data = new Map();
        }
        json.changeSet.items.reverse().forEach(function(commit, index, array) {
            var entry = new Map();
            if (commit != null) {
                entry.set('hash', commit.commitId);
                entry.set('commit', commit.comment.split('\n')[0]);
            } else {
                entry.set('hash', null);
                entry.set('commit', "No changes");
            }
            entry.set('date', formateDate(json.timestamp));
            data.set(index, entry);
        });
        if (data.size == 0) {
            var entry = new Map();
            entry.set('commit', "No changes");
            entry.set('date', formateDate(json.timestamp));
            data.set(0, entry);
        }

        map.set(build, data);

        cache.set(version, map);
        showData(version, build, data);
    });
}

function showData(version, build, data) {
    if (current != version) {
        return;
    }

    var tr = document.getElementById('row_' + build);

    tr.appendChild(createBuildElement(version, build));
    tr.appendChild(createCommitElement(data));
    tr.appendChild(createDateElement(data));
}

function createBuildElement(version, build) {
    var td = document.createElement('td');
    td.className = "left";

    var i = document.createElement('i');
    i.className = "fas fa-cloud-download-alt";

    var span = document.createElement('span');
    span.appendChild(i);
    span.appendChild(document.createTextNode(" " + build));

    var a = document.createElement('a');
    a.href = downloads + version + "/" + build + "/download";
    a.className = "button white-button";
    a.download = "purpur-" + version + "-" + build + ".jar";
    a.title = "Download build #" + build;
    a.appendChild(span);

    td.appendChild(a);

    return td;
}

function createCommitElement(data) {
    var td = document.createElement('td');
    td.className = "mid";

    data.forEach(function(entry, index, data) {
        var p = document.createElement('p');

        if (entry.get('hash') != null) {
            var link = document.createElement('a');
            link.href = 'https://www.github.com/pl3xgaming/Purpur/commit/' + entry.get('hash');
            link.className = "hash";
            link.target = "_blank";
            link.appendChild(document.createTextNode(entry.get('hash').substr(0,7)));

            var span = document.createElement('span');
            span.appendChild(document.createTextNode('['));
            span.appendChild(link);
            span.appendChild(document.createTextNode('] '));

            p.appendChild(span);
        }

        p.appendChild(document.createTextNode(entry.get('commit')));

        td.appendChild(p);
    });

    return td;
}

function createDateElement(data) {
    var td = document.createElement('td');
    td.className = "right";

    if (data.has(0)) {
        var span = document.createElement('span');
        span.appendChild(document.createTextNode(data.get(0).get('date')));

        td.appendChild(span);
    }

    return td;
}

function getJSON(url, fn) {
    fetch(url)
        .then(async res => {
            if (res.ok) {
                fn(await res.json());
            }
        });
}

function formateDate(timestamp) {
    var date = new Date(timestamp);
    var year = date.getFullYear();
    var month = ('0' + (date.getMonth() + 1)).slice(-2)
    var day = ('0' + date.getDate()).slice(-2)
    var hours = ('0' + date.getHours()).slice(-2)
    var minutes = ('0' + date.getMinutes()).slice(-2)
    var seconds = ('0' + date.getSeconds()).slice(-2)
    return year + "-" + month + "-" + day + " " + hours + ":" + minutes + ":" + seconds;
}

function warnOldVersion(version){
    var warnText01 = 'You are trying to download builds for old/unsupported version of Minecraft!';
    var warnText02 = 'Keep in mind that if you download these builds, you won\'t get any support from Purpur!';

    var div = document.getElementById('oldVersionWarning');
    var selected = version;
    if (selected != latestVersion) {
        div.innerHTML = `${warnText01}<br>${warnText02}`;
        div.classList.add('oldVersionWarning');
    } else {
        div.innerHTML = '';
        div.classList.remove('oldVersionWarning');
    }
}
