<?php
$table_of_contents = [];
class TOC {
  public function __construct($count, $name, $url) {
    global $table_of_contents;
    $indent = "";
    for ($i = 0; $i < $count; $i++) {
      $indent .= "&nbsp;&nbsp;";
    }
    $this->count = $count;
    $this->indent = $indent;
    $this->name = $name;
    $this->url = $url;
    array_push($table_of_contents, $this);
  }
}
?>
