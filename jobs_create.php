<?php

	/* Sherben per te krijuar njoftime te reja */

    // konfigurimi
    require("/../site_folders/includes/config.php"); 

    // nese faqja eshte arritur nepermjet GET (link ose redirect)
    if ($_SERVER["REQUEST_METHOD"] == "GET")
    	render("jobs_create.php", ["title" => "Krijo njoftim"]);

    // nese faqja eshte arritur nepermjet POST (duke plotesuar nje formular)
    elseif ($_SERVER["REQUEST_METHOD"] == "POST") 
    {
    	// kontrollo nese fushat jane te plotesuara
    	foreach ($_POST as $key => $value) 
    	{
	       	// anashkalo vetem per fushen e pershkrimit ose kualifikimeve
	       	if ( $key != "pershkrimi" && $key != "kualifikimet")
	       	    if (empty($value)) { // nese ka fusha te paplotesuara, shfaq alert dhe rishfaq formen
	       	        showAlert("Ju lutemi, plotësoni të gjitha fushat e kërkuara!");
	       	        render("edit_njoftim.php", ["title" => "Modifiko njoftimin", "fields" => $_POST]);
	       	        // shko tek elementi i pare i paplotesuar
	       	        echo "<script>";
	       	        echo "document.getElementById('myForm').$key.focus()";
	       	        echo "</script>";
	       	        return;
	       	    }
    	}

    	// kontrollo nese data eshte ne format te rregullt
    	if (!preg_match("/^[0-9]{4}-[0-9]{1,2}-[0-9]{1,2}$/", $_POST["afati"])) 
    	{	
    		showAlert("Datë e pavlefshme!");
	       	        render("edit_njoftim.php", ["title" => "Modifiko njoftimin", "fields" => $_POST]);
	       	        // shko tek fusha e dates
	       	        echo "<script>";
	       	        echo "document.getElementById('myForm').afati.focus()";
	       	        echo "</script>";
	       	        return;
    	}

    	// hidh njoftimin e ri ne databaze
    	if (query("INSERT INTO njoftime (id_kompani, pozicioni, qyteti, adresa, pershkrimi, kualifikimet, data_publikimit, afati)" . 
    			"VALUES (?, ?, ?, ?, ?, ?, CURDATE(), ?)",
	    			$_SESSION["id"], $_POST["pozicioni"], $_POST["qyteti"], $_POST["adresa"], $_POST["pershkrimi"], 
	    				$_POST["kualifikimet"], $_POST["afati"]) === false)
	    		apologize("Nuk mund të krijohet njoftimi për momentin. Provoni sërish më vonë.");

	    // gjej id-ne e fundit te shtuar tek tabela njoftime
	    $rows = query("SELECT LAST_INSERT_ID() AS id_njoftim");

    	// nese cdo gje shkon mire, shko tek njoftimi
	    redirect("/jobs.php?show=selected&id_njoftim=" . $rows[0]["id_njoftim"]);
    }

?>