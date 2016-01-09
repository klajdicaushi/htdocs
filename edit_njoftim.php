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

    		// nese kerkohet te ndryshohen te dhenat e njoftimit
    		if ($_POST["submit"] == "edit")
    			render("edit_njoftim.php", ["title" => "Modifiko njoftimin", "fields" => $result[0]]);

    		// nese kerkohet te fshihet njoftimi
    		elseif ($_POST["submit"] == "delete") 
    		{
    			// fshi njoftimin nga databaza
    			if (query("DELETE FROM njoftime WHERE id_njoftim = ?", $_POST["id_njoftim"]) === false)
    				apologize("Nuk mund të fshihet njoftimi për momentin. Provoni sërish më vonë.");

    			// kthehu tek faqja e njoftimeve
    			redirect("/jobs.php");
    		}
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

	    	// modifiko te dhenat ne tabelen njoftime
	    	if (query("UPDATE njoftime SET pozicioni = ?, qyteti = ?, adresa = ?, pershkrimi = ?, kualifikimet = ?, afati = ? WHERE id_njoftim = ?",
	    			$_POST["pozicioni"], $_POST["qyteti"], $_POST["adresa"], $_POST["pershkrimi"], 
	    				$_POST["kualifikimet"], $_POST["afati"], $_POST["id_njoftim"]) === false)
	    		apologize("Nuk mund të modifikohet njoftimi për momentin. Provoni sërish më vonë.");

	    	// nese cdo gje shkon mire, shko tek njoftimi
	    	redirect("/jobs.php?show=selected&id_njoftim=" . $_POST["id_njoftim"]);
    	}
    }
?>