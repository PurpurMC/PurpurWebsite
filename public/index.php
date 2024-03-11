<?php
require_once("inc/versions.php");
require_once("inc/header.php");
?>
<body class="flex-col">
<header class="flex-col">
    <h1>Purpur</h1>
    <h2>Your Minecraft, your way</h2>
    <div class="logo">
        <!-- <img src="images/purpur.svg" alt="Purpur logo"> -->
    </div>
    <p class="text-center">
        Purpur is a drop-in replacement for Paper servers designed for configurability and new, fun, exciting gameplay
        features.
    </p>
    <div class="downloads">
        <a href="https://api.purpurmc.org/v2/purpur/<?=$currentVersion?>/latest/download" class="button text-center">Download latest</a>
        <a href="https://purpurmc.org/downloads" class="button text-center">See all builds</a>
    </div>
</header>
<section class="basic-links">
    <a href="/docs" class="card" id="documentation">
        <div class="icon">
            <img src="images/documentation.svg" alt="documentation" loading="lazy">
        </div>
        <h2>Fully documented</h2>
        <p class="text-center">
            Read over our comprehensive feature documentation and make it your own.
        </p>
    </a>
    <a href="/github" class="card" id="open-source">
        <div class="icon">
            <img src="images/opensource.svg" alt="open source" loading="lazy">
        </div>
        <h2>Free and open source</h2>
        <p class="text-center">
            Built with love by people of all walks of life all over the globe.
        </p>
    </a>
    <a href="/issues" class="card" id="found-a-bug">
        <div class="icon">
            <img src="images/warning.svg" alt="issues" loading="lazy">
        </div>
        <h2>Found a bug? File it!</h2>
        <p class="text-center">
            If you run into issues or have any suggestions, let us know.
        </p>
    </a>
    <a href="/discord" class="card" id="discord">
        <div class="icon">
            <img src="images/discord.svg" alt="discord" loading="lazy">
        </div>
        <h2>Get involved</h2>
        <p class="text-center">
            Get in touch with the developers, ask questions, and join the fun.
        </p>
    </a>
</section>
<section class="support">
    <h1 class="text-center">Support the project</h1>
    <div class="wrapper">
        <a href="https://opencollective.com/purpurmc" class="card" id="open-collective">
            <div class="icon">
                <img src="images/opencollective.svg" alt="open collective" loading="lazy">
            </div>
            <h2>Open Collective</h2>
            <p class="text-center">Support Purpur on Open Collective</p>
        </a>
        <a href="https://github.com/sponsors/PurpurMC" class="card" id="github-sponsors">
            <div class="icon">
                <img src="images/github.svg" alt="github sponsors" loading="lazy">
            </div>
            <h2>GitHub Sponsors</h2>
            <p class="text-center">Support Purpur via GitHub Sponsors</p>
        </a>
    </div>
</section>
<footer>
    <p class="text-center">&copy; 2019-2024 <a href="https://purpurmc.org/">purpurmc.org</a> <a href="https://github.com/PurpurMC/Purpur/blob/HEAD/LICENSE">MIT</a></p>
</footer>
</body>
</html>
