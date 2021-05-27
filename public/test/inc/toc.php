<?php
$table_of_contents = [];
class TOC {
  public function __construct($path) {
    $arr = explode('.', $path);

    $this->path = $path;
    $this->count = count($arr);
    $this->name = end($arr);
    $this->indent = "";

    for ($i = 0; $i < $this->count; $i++) {
      $this->indent .= "&nbsp;&nbsp;";
    }

    global $table_of_contents;
    array_push($table_of_contents, $this);
  }
}
?>
