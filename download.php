<?php

	/* Sherben per te shkarkuar CV-te e hedhura ne sistem */
	
	// konfigurimi
	require("/../site_folders/includes/config.php");

	if (isset($_GET["id_student"])) {
		$result = query("SELECT name, type, size, content FROM cv WHERE id_student = ?", $_GET["id_student"]);
			//apologize("Nuk mund të shkarkohet CV për momentin. Provoni sërish më vonë.");

		extract($result[0]);

		header("Content-length: $size");
		header("Content-type: $type");
		header("Content-Disposition: attachment; filename=$name");
		echo $content;

	}
?>