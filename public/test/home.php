<?php
  require_once("inc/common.php");

  $page = "home";

  require_once("inc/header.php");
?>
          <link type="text/css" rel="stylesheet" href="css/home.css" />
          <div class="middle">
            <h1>Home</h1>
            <p class="logo">
              <a href="https://purpur.pl3x.net">
                <img src="https://repository-images.githubusercontent.com/184300222/14b11480-3303-11eb-8ca4-ea5711d942fb" alt="Purpur">
              </a>
            </p>
          </div>
<?php
  new TOC("Contact");
  new TOC("Downloads");
  new TOC("License");
  new TOC("bStats");
  new TOC("API");
  new TOC("Javadoc");
  new TOC("Dependency Information");
  new TOC("Building and setting up");
  new TOC("Initial setup");
  new TOC("Creating a patch");
  new TOC("Compiling");

  $prev = null;
  $next = "Commands";
  require_once("inc/footer.php");
?>
