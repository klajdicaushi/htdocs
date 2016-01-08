<?php

    // konfigurimi
    require("/../site_folders/includes/config.php"); 

    // nese faqja eshte arritur nepermjet GET
    if ($_SERVER["REQUEST_METHOD"] == "GET")
    	redirect("/profile.php");

    // nese faqja eshte arritur nepermjet POST
    elseif($_SERVER["REQUEST_METHOD"] == "POST")
    {	
    	
    	// nese kerkohet te modifikohet njoftimi
    	if ($_POST["action"] == "edit")
    	{
    		// merr te dhenat e njoftimit
    		if (($result = query("SELECT * FROM njoftime WHERE id_njoftim = ?", $_POST["id_njoftim"])) === false)
    			apologize("Nuk mund të merren të dhënat për momentin. Provoni sërish më vonë.");
    		render("edit_njoftim.php", ["title" => "Modifiko njoftimin", "fields" => $result[0]]);
    	}

    	// nese kerkohet te hidhen te dhenat ne sistem
    	if ($_POST["action"] == "publish")
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

	    	// modifiko te dhenat ne tabelen njoftime
	    	$date = date_create($_POST["afati"]);
	    	if (query("UPDATE njoftime SET pozicioni = ?, qyteti = ?, adresa = ?, pershkrimi = ?, kualifikimet = ?, afati = ? WHERE id_njoftim = ?",
	    			$_POST["pozicioni"], $_POST["qyteti"], $_POST["adresa"], $_POST["pershkrimi"], 
	    				$_POST["kualifikimet"], $_POST["afati"], $_POST["id_njoftim"]) === false)
	    		apologize("Nuk mund të modifikohet njoftimi për momentin. Provoni sërish më vonë.");

	    	// nese cdo gje shkon mire, shko tek njoftimi
	    	redirect("/jobs.php?show=selected&id_njoftim=" . $_POST["id_njoftim"]);
    	}
 
    }
?>