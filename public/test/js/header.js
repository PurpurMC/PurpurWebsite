window.onload = (event) => {
  scrollTo(new URLSearchParams(window.location.search).get("id"));

  for (const link of document.getElementsByClassName('headerlink')) {
    link.addEventListener('click', onClick);
  }

  for (const link of document.getElementsByClassName('toclink')) {
    link.addEventListener('click', onClick);
  }
};

function onClick() {
  const path = this.getAttribute('title');
  scrollTo(path);
  updateUrl(`?id=${path}`);
}

function scrollTo(id) {
  const element = document.getElementById(id);
  if (element != null) {
    element.scrollIntoView();
  }
}

function updateUrl(url) {
  if (window.history.replaceState) {
    // prevents storing history with each change
    window.history.replaceState({}, null, url);
  } else {
    // always stores history with each change
    window.history.pushState({}, null, url);
  }
}
