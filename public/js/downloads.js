window.addEventListener('load', function() {
  document.getElementById("dropdown").onchange = changeDropdown;
});

function changeDropdown(option) {
  window.location.href = "/downloads/" + option.target.value;
}
