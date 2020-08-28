<?php
  $indo = file_get_contents("https://api.kawalcorona.com/indonesia");
	$prov = file_get_contents("https://api.kawalcorona.com/indonesia/provinsi/");
	$dataindo = json_decode($indo, true);
	$dataprov = json_decode($prov, true);
	//echo count($dataprov);
	$datain = $dataindo["0"]["name"];
	$datapro = $dataprov["attributes"];
	//print_r($dataprov);
	$positif = $dataindo["0"]["positif"];
	$sembuh = $dataindo["0"]["sembuh"];
	$meninggal = $dataindo["0"]["meninggal"];
	$dirawat = $dataindo["0"]["dirawat"];
  ?>
