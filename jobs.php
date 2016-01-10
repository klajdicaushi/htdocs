<?php

    // konfigurimi
    require("/../site_folders/includes/config.php"); 

    // nese nuk eshte zgjedhur nje opsion per show
    if (!isset($_GET["show"]))
    	$_GET["show"] = "all";

    // nese faqja vizitohet nga nje student
    if ($_SESSION["type"] == "student" || $_SESSION["type"] == "admin") 
    {
    	// nese duhet te shfaqet te gjitha njoftimet te gjitha njoftimet
        if ($_GET["show"] == "all") 
        {
        	// nese nuk eshte perzgjedhur nje kompani
        	if (!isset($_GET["id_kompani"])) 
        	{
	        	// merr gjithe njoftimet nga databaza
	    		if (($rows = query("SELECT * FROM njoftime")) === false)
	    			apologize("Nuk mund të shfaqen njoftimet për momentin. Provoni sërish më vonë.");

	    		// shfaq njoftimet
    			render("jobs_show.php", ["title" => "Lista e njoftimeve", "njoftime" => $rows]);
	    	}
			
			// nese eshte perzgjedhur nje kompani
	    	else 
	    	{
	    		// merr njoftimet e kompanise nga databaza
	    		if (($rows = query("SELECT * FROM njoftime WHERE id_kompani = ?", $_GET["id_kompani"])) === false)
	    			apologize("Nuk mund të shfaqen njoftimet për momentin. Provoni sërish më vonë.");

	    		// merr emrin e kompanise nga databaza per ta vendosur si titull te faqes
	    		if (($kompani = query("SELECT emri_kompani FROM kompani WHERE id = ?", $_GET["id_kompani"])) === false)
    				apologize("Nuk mund të shfaqen njoftimet për momentin. Provoni sërish më vonë.");

	    		// shfaq njoftimet
    			render("jobs_show.php", ["title" => $kompani[0]["emri_kompani"], "njoftime" => $rows]);
	    	}
    	}

    	// nese eshte shtypur nje nga butonat e interesimit
    	if ($_SERVER["REQUEST_METHOD"] == "POST")
    	{
    		// nese studenti do te interesohet
    		if ($_POST["action"] == "subscribe") {
    			if (query("INSERT INTO kandidate (id_njoftim, id_student) VALUES (?, ?)", $_POST["id_njoftim"], $_SESSION["id"]) === false)
    				apologize("Nuk mund të kryhet veprimi për momentin. Provoni sërish më vonë.");
    		}

			// nese studenti ose admini do te heqe interesimin
    		elseif($_POST["action"] == "unsubscribe") {
    			if (query("DELETE FROM kandidate WHERE id_njoftim = ? AND id_student = ?", $_POST["id_njoftim"], 
                    ($_SESSION["type"] == "admin") ? $_POST["id_student"] : $_SESSION["id"]) === false)
    				    apologize("Nuk mund të kryhet veprimi për momentin. Provoni sërish më vonë.");
    		}

    		// nese gjithcka shkon mire, rikthehu tek faqja e njoftimit
    		redirect("/jobs.php?show=selected&id_njoftim=" . $_POST["id_njoftim"]);

    	}
    }

    // nese faqja vizitohet nga nje kompani
    elseif($_SESSION["type"] == "kompani")
    {
        // nese kompania do te shohe te gjitha njoftimet e veta
        if ($_GET["show"] == "all") 
        {
        	// merr njoftimet nga databaza
    		if (($rows = query("SELECT * FROM njoftime WHERE id_kompani = ?", $_SESSION["id"])) === false)
    			apologize("Nuk mund të shfaqen njoftimet për momentin. Provoni sërish më vonë.");
	
    		// shfaq njoftimet
    		render("jobs_show.php", ["title" => "Njoftimet e mia", "njoftime" => $rows]);
    	}    	
    }

    // nese eshte perzgjedhur nje njoftim
    if ($_GET["show"] == "selected")
    {
    	// nese nuk eshte vendosur 'id_njoftim' ne URL
    	if (!isset($_GET["id_njoftim"]))
    		redirect("/jobs.php");

    	// merr te dhenat e njoftimit
    	if (($njoftim = query("SELECT * FROM njoftime WHERE id_njoftim = ?", $_GET["id_njoftim"])) === false)
    		apologize("Nuk mund të shfaqet njoftimi për momentin. Provoni sërish më vonë.");

    	// merr emrin e kompanise
    	if (($kompani = query("SELECT * FROM kompani WHERE id = ?", $njoftim[0]["id_kompani"])) === false)
    		apologize("Nuk mund të shfaqet njoftimi për momentin. Provoni sërish më vonë.");

    	// shfaq njoftimin
    	render("jobs_select.php", ["title" => $njoftim[0]["pozicioni"], "njoftim" => $njoftim[0], "kompani" => $kompani[0]]);
    }


?>