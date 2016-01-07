<?php

    // konfigurimi
    require("/../site_folders/includes/config.php"); 

    if ($_SESSION["type"] == "student") 
    {

    }

    elseif($_SESSION["type"] == "kompani")
    {
    	// nese kompania do te shohe njoftimet e nje kompanie tjeter
    	if (isset($_GET["id_kompani"]))
            // riktheje tek njoftimet e veta
            redirect("/jobs.php");

        // merr njoftimet nga databaza
    	if (($rows = query("SELECT * FROM njoftime WHERE id_kompani = ?", $_SESSION["id"])) === false)
    		apologize("Nuk mund të shfaqen njoftimet për momentin. Provoni sërish më vonë.");

    	// shfaq njoftimet
    	render("jobs_show.php", ["title" => "Njoftimet e mia", "njoftime" => $rows]);
    }

?>