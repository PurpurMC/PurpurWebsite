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
  new TOC(0, "Contact", "");
  new TOC(0, "Downloads", "");
  new TOC(0, "License", "");
  new TOC(0, "bStats", "");
  new TOC(0, "API", "");
  new TOC(0, "Javadoc", "");
  new TOC(0, "Dependency Information", "");
  new TOC(0, "Building and setting up", "");
  new TOC(0, "Initial setup", "");
  new TOC(0, "Creating a patch", "");
  new TOC(0, "Compiling", "");


  $prev = null;
  $next = "Commands";
  require_once("inc/footer.php");
?>
