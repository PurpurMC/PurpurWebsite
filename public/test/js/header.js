window.onload = (event) => {
  const params = new URLSearchParams(window.location.search);
  const element = document.getElementById(params.get("id"));
  if (element != null) {
    element.scrollIntoView();
  }
};
