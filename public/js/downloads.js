window.addEventListener('load', function() {
  document.getElementById("dropdown").onchange = changeDropdown;

  const locale = navigator.languages?.[0] || navigator.language;
  const timezone = Intl.DateTimeFormat().resolvedOptions().timeZone

  const timestamps = document.getElementsByClassName("timestamp");
  for (const timestamp of timestamps) {
    const date = new Date(Number.parseFloat(timestamp.innerHTML));
    timestamp.innerHTML = date.toLocaleString(locale, {
      month: "2-digit",
      day: "2-digit",
      year: "2-digit",
      hour: "2-digit",
      minute: "2-digit",
      second: "2-digit",
      timeZone: timezone
    });

    timestamp.classList.add("visible");
  }
});

function changeDropdown(option) {
  window.location.href = "/downloads?v=" + option.target.value;
}