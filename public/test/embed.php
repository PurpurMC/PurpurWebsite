<?php
  $author_name = @$_GET['author_name'];
  $author_url = @$_GET['author_url'];
  $provider_name = @$_GET['provider_name'];
  $provider_url = @$_GET['provider_url'];
?>{
    "author_name": "<?=$author_name?>",
    "author_url": "<?=$author_url?>",
    "provider_name": "<?=$provider_name?>",
    "provider_url": "<?=$provider_url?>"
 }
