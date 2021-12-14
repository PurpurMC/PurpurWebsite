let timestamps;
let check24h;

const localeOptions = Intl.DateTimeFormat().resolvedOptions();

const locale = localeOptions.locale;
const timezone = localeOptions.timeZone;

window.addEventListener("load", function() {
  document.getElementById("dropdown").onchange = changeDropdown;
  timestamps = document.getElementsByClassName("timestamp");
  check24h = document.getElementById("check-24h");
  check24h.checked = is24h();

  check24h.addEventListener("change", function() {
    window.localStorage.is24h = check24h.checked;
    updateTimestamps();
  });

  updateTimestamps();
});

function changeDropdown(option) {
  window.location.href = "/downloads?v=" + option.target.value;
}

function updateTimestamps() {
  for (const timestamp of timestamps) {
    const date = new Date(Number.parseFloat(timestamp.dataset.timestamp));
    timestamp.innerHTML = date.toLocaleString(locale, {
      month: "2-digit",
      day: "2-digit",
      year: "2-digit",
      hour: "2-digit",
      minute: "2-digit",
      second: "2-digit",
      timeZone: timezone,
      hour12: !check24h.checked
    });

    timestamp.classList.add("visible");
  }
}

function is24h() {
  return window.localStorage.is24h === "true" || localeUses24HourTime(locale);
}

// https://stackoverflow.com/a/60437579
function localeUses24HourTime(langCode) {
  return new Intl.DateTimeFormat(langCode, {
    hour: "numeric"
  }).formatToParts(new Date(2020, 0, 1, 13)).find(part => part.type === "hour").value.length === 2;
}