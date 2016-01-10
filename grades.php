<?php
	
	// konfigurimi
    require("/../site_folders/includes/config.php"); 

    // nese perdoruesi eshte student
    if ($_SESSION["type"] == "student") 
    {
        // nese studenti do te shohe notat e dikujt tjeter
        if (isset($_GET["id_student"]))
            // riktheje tek notat e vet
            redirect("/grades.php");

        // merr notat nga sistemi duke perdorur $_SESSION["id"]
        if ( ($rows = query("SELECT lenda, nota FROM nota WHERE id_student = ?", $_SESSION["id"])) === false)
            apologize("Nuk mund të shfaqen notat për momentin. Provoni sërish më vonë.");
    }

    // nese perdoruesi eshte kompani ose admin
    else 
    {
        // merr notat nga sistemi duke perdorur $_GET["id"]
        if ( ($rows = query("SELECT lenda, nota FROM nota WHERE id_student = ?", $_GET["id_student"])) === false)
            apologize("Nuk mund të shfaqen notat për momentin. Provoni sërish më vonë.");
    }

    // shfaq notat
    render("grades_show.php", ["title" => "Lista e notave", "fields" => $rows]);

?>