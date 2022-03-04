<?php
require_once("inc/versions.php");
require_once("inc/header.php");
?>
  <body>
    <header>
      <div class="container">
        <h1>Purpur</h1>
        <h2>Your Minecraft, your way</h2>
        <img class="logo" src="images/purpur.svg" alt="Purpur Logo" />
        <p>Purpur is a drop-in replacement for Paper servers designed for configurability and new, fun, exciting gameplay features.</p>
        <a class="button download-button blue-button" href="https://api.purpurmc.org/v2/purpur/<?=$currentVersion?>/latest/download">Download</a>
        <a class="tiny" href="https://purpurmc.org/downloads">See all builds</a>
      </div>
    </header>
    <div class="row-one">
      <div class="container group">
        <div class="block">
          <img class="icon" src="images/documentation.svg" alt="Fully Documented" />
          <h3>Fully Documented</h3>
          <p>Read over our comprehensive feature documentation and make it your own.</p>
          <a class="button white-button" href="/docs">Documentation</a>
        </div>
        <div class="block">
          <img class="icon" src="images/opensource.svg" alt="Free and Open Source" />
          <h3>Free and Open Source</h3>
          <p>Built with love by people of all walks of life all over the globe.</p>
          <a class="button white-button" href="/github" target="_blank">GitHub</a>
        </div>
      </div>
    </div>
    <div class="row-two">
      <div class="container group">
        <div class="block">
          <img class="icon" src="images/warning.svg" alt="Found a bug? File it!" />
          <h3>Found a Bug? File it!</h3>
          <p>If you run into issues or have any suggestions, let us know.</p>
          <a class="button red-button" href="/issues" target="_blank">Issue Tracker</a>
        </div>
        <div class="block">
          <img class="icon" src="images/discord.svg" alt="Get Involved" />
          <h3>Get Involved</h3>
          <p>Get in touch with the developers, ask questions, and join the fun.</p>
          <a class="button blue-button" href="/discord" target="_blank">Discord</a>
        </div>
      </div>
    </div>
    <footer>
      &copy; 2019-2022 <a href="https://purpurmc.org">purpurmc.org</a> <a href="https://github.com/PurpurMC/Purpur/blob/HEAD/LICENSE">MIT</a>
      <p><a href="https://gitlab.gnome.org/Teams/Releng/gnome-os-site" rel="noreferrer" target="_blank">Site design CC-BY-SA</a></p>
    </footer>
  </body>
</html>
