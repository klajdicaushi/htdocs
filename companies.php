<?php

	// konfigurimi
    require("/../site_folders/includes/config.php"); 

    // faqja nuk hapet nga kompanite
    if ($_SESSION["type"] == "kompani")
    	redirect("/");

    if (($rows = query("SELECT * FROM kompani")) === false)
    	apologize("Nuk mund të merren të dhënat nga sistemi");

?>