<?php
header ('Content-Type: image/png');

require_once ('../../jpgraph/jpgraph.php');
require_once ('../../jpgraph/jpgraph_line.php');

$filename = 'data.json';

$contents = file_get_contents($filename);
$json = json_decode($contents === false ? '' : $contents, true);

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
foreach($json['dates'] as $date) {
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

$l1=new LinePlot($json['servers']['purpur']['data']);
$l1->SetColor($json['servers']['purpur']['color']);
$l1->SetLegend('Purpur');
$l2=new LinePlot($json['servers']['tuinity']['data']);
$l2->SetColor($json['servers']['tuinity']['color']);
$l2->SetLegend('Tuinity');
$l3=new LinePlot($json['servers']['yatopia']['data']);
$l3->SetColor($json['servers']['yatopia']['color']);
$l3->SetLegend('Yatopia');

$graph->Add($l1);
$graph->Add($l2);
$graph->Add($l3);

$graph->Stroke();
?>
