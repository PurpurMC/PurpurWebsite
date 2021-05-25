window.onload = (event) => {
  const params = new URLSearchParams(window.location.search);
  const element = document.getElementById(params.get("id"));
  if (element != null) {
    element.scrollIntoView();
  }
  /*fetch("https://api.github.com/repos/pl3xgaming/purpurdocs")
    .then(async res => {
      if (res.ok) {
        const json = await res.json();
        const span = document.getElementById("gh_stats");
        const forks = json['forks_count'];
        const stars = json['stargazers_count'];
        span.innerHTML = `${stars} Stars · ${forks} Forks`;
      }
    });*/
};
