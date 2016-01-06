<?php
	
	// konfigurimi
    require("/../site_folders/includes/config.php"); 

    // kontrollo nese perdoruesi eshte student
    if ( ($rows = query("SELECT type FROM users WHERE id = ?", $_SESSION["id"])) === false)
    	apologize("Nuk mund të verifikohet identiteti. Provoni sërish më vonë.");

    // nese nuk eshte student, shko tek faqja kryesore
    if ($rows[0]["type"] != "student")
    	redirect("/");

    // merr notat nga sistemi
    if ( ($rows = query("SELECT lenda, nota FROM nota WHERE id_student = ?", $_SESSION["id"])) === false)
    	apologize("Nuk mund të merren notat për momentin. Provoni sërish më vonë.");

    // shfaq notat
    render("grades_show.php", ["title" => "Lista e notave", "fields" => $rows]);

?>