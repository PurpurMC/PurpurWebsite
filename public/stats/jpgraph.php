<?php
  header('Content-Type: image/png');
  header('Cache-Control: no-cache, no-store, must-revalidate');
  header('Pragma: no-cache');
  header('Expires: 0');

  require_once ('../../jpgraph/jpgraph.php');
  require_once ('../../jpgraph/jpgraph_line.php');
  require_once ("include/vars.php");

  $graph = new Graph(350,250);
  $graph->clearTheme();
  $graph->SetScale('textlin');
  $graph->SetMargin(45,10,45,50);
  $graph->SetBox(true, '#DADADA');

  $graph->title->Set("Server Usage");
  $graph->title->SetFont(FF_DV_SANSSERIF,FS_NORMAL,9);

  $graph->legend->SetFrameWeight(0);
  $graph->legend->SetPos(0.2, 0.08, 'top', 'top');
  $graph->legend->SetFillColor('#FAFAFA');
  $graph->legend->SetLayout(LEGEND_HOR);
  $graph->legend->SetColumns(3);
  $graph->legend->SetShadow(false);
  $graph->legend->SetMarkAbsSize(5);
  $graph->legend->SetFont(FF_DV_SANSSERIF,FS_NORMAL,7);

  $labels = array();
  $dates = array();
  $i = 0;
  foreach($jsonData['data']['dates'] as $date) {
    if ($i++ % 22 == 0) {
      array_push($labels, $i);
      array_push($dates, $date);
    }
  }
  $graph->xaxis->SetMajTickPositions($labels, $dates);
  $graph->xaxis->SetFont(FF_DV_SANSSERIF,FS_NORMAL,6);
  $graph->xaxis->SetLabelAngle(50);
  $graph->xaxis->HideTicks();

  $graph->yaxis->SetFont(FF_DV_SANSSERIF,FS_NORMAL,8);
  $graph->yaxis->HideTicks();

  $l1=new LinePlot($jsonData['data']['servers']['purpur']);
  $l1->SetColor($jsonServers['servers']['purpur']['color']);
  $l1->SetLegend('Purpur');

  $graph->Add($l1);

  $graph->Stroke();
?>
