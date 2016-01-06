<?php 

	// konfigurimi
    require("/../site_folders/includes/config.php"); 

    // nese perdoruesi eshte student
    if ($_SESSION["type"] == "student") {
    	if (($rows = query("SELECT * FROM student WHERE id = ?", $_SESSION["id"])) === false)
    		apologize("Nuk mund të merren të dhënat për momentin.");
    }

    // nese perdoruesi eshte kompani
    elseif ($_SESSION["type"] == "kompani") {
    	if (($rows = query("SELECT * FROM kompani WHERE id = ?", $_SESSION["id"])) === false)
    		apologize("Nuk mund të merren të dhënat për momentin.");
    }

    render("profile_" . $_SESSION["type"] . ".php", ["title" => "Profili im", "fields" => $rows[0]]);
    
?>