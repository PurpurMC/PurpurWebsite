<?php
$pattern = ['/(href=")#(.+"\s+class="md-nav__link">)/', '/(class="headerlink"\s+href=")#(.+?" title="Permanent link">)/'];
$replacement = ['$1./$2', '$1./$2'];

$index = preg_replace($pattern, $replacement, file_get_contents("docs/Configuration/index.html"));
echo $index;

//header('Location: /docs/configuration#'.$_GET['option']);
//die();
