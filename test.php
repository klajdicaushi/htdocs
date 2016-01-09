<?php
	$dataSot = getdate();	// data e sotme
	$dataSot = date_create_from_format("Y-m-d", $dataSot["year"] . "-" . $dataSot["mon"] . "-" . $dataSot["mday"]);	// krijo DateTime object
    $dataAfati = date_create($_POST["afati"]);
?>