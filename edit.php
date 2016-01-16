<?php

	/* Sherben per te modifikuar te dhenat e perdoruesve */

    // konfigurimi
    require("/../site_folders/includes/config.php");

    // nese faqja eshte arritur nepermjet GET (link ose redirect)
    if ($_SERVER["REQUEST_METHOD"] == "GET") 
    {
    	// nese perdoruesi eshte student
    	if ($_SESSION["type"] == "student") 
    	{
    		// merr te dhenat e studentit
    		if (($rows = query("SELECT * FROM student WHERE id = ?", $_SESSION["id"])) === false)
    			apologize("Nuk mund të merren të dhënat për momentin.");
    		// shfaq formularin per modifikimin e te dhenave te studentit
    		render("edit_student.php", ["title" => "Modifiko", "fields" => $rows[0]]);
    	}

    	// nese perdoruesi eshte kompani
    	elseif ($_SESSION["type"] == "kompani") 
    	{
    		// merr te dhenat e kompanise
    		if (($rows = query("SELECT * FROM kompani WHERE id = ?", $_SESSION["id"])) === false)
    			apologize("Nuk mund të merren të dhënat për momentin.");
    		// shfaq formularin per modifikimin e te dhenave te kompanise
    		render("edit_kompani.php", ["title" => "Modifiko", "fields" => $rows[0]]);
    	}

    	// nese perdoruesi eshte admin
    	elseif($_SESSION["type"] == "admin") 
    	{
    		// merr te dhenat e perdoruesit qe do te modifikoje
    		if (($rows = query("SELECT * FROM " . $_GET["type"] . " WHERE id = ?", $_GET["id"])) === false)
    			apologize("Nuk mund të merren të dhënat për momentin.");
    		// shfaq formularin per modifikimin e te dhenave te perdoruesit
    		render("edit_" . $_GET["type"] . ".php", ["title" => "Modifiko", "fields" => $rows[0]]);
    	}
    }

    // nese faqja eshte arritur nepermjet POST (duke plotesuar nje formular)
    elseif ($_SERVER["REQUEST_METHOD"] == "POST")
    {
    	// kontrollo nese fushat jane te plotesuara
    	foreach ($_POST as $key => $value) {
	        // anashkalo vetem per fushen e pershkrimit ose celularit
	        if ( $key != "pershkrimi" && $key != "cel")
	            if (empty($value)) { // nese ka fusha te paplotesuara, shfaq alert dhe rishfaq formularin
	                showAlert("Ju lutemi, plotësoni të gjitha fushat e kërkuara!");
	                // nese faqja po vizitohet nga admini
	                if ($_SESSION["type"] == "admin")
	                	render("edit_" . $_POST["type"] . ".php", ["title" => "Modifiko", "fields" => $_POST]);
	                // nese faqja po vizitohet nga student ose kompani
	                else 
	                	render("edit_" . $_SESSION["type"] . ".php", ["title" => "Modifiko", "fields" => $_POST]);
	                // shko tek elementi i pare i paplotesuar
	                echo "<script>";
	                echo "document.getElementById('myForm').$key.focus()";
	                echo "</script>";
	                return;
	            }
    	}

    	// kontrollo numrin e celularit
    	if ($_POST["cel"] != "" && !preg_match("/^(\+)?[0-9]+$/", $_POST["cel"]))
    	{
	        showAlert("Numër celulari i pavlefshëm.");
	        // nese faqja po vizitohet nga admini
			if ($_SESSION["type"] == "admin")
	        	render("edit_" . $_POST["type"] . ".php", ["title" => "Modifiko", "fields" => $_POST]);
	        // nese faqja po vizitohet nga student ose kompani
	        else 
	        	render("edit_" . $_SESSION["type"] . ".php", ["title" => "Modifiko", "fields" => $_POST]);
	        // shko tek fusha e numrit
	        echo "<script>";
	        echo "document.getElementById('myForm').cel.focus()";
	        echo "</script>";
	        return;
	    }

    	// kontrollo formatin e e-mailit
	    if (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) 
	    {
	        showAlert("E-mail i pavlefshëm.");
	        // nese faqja po vizitohet nga admini
	        if ($_SESSION["type"] == "admin")
	            render("edit_" . $_POST["type"] . ".php", ["title" => "Modifiko", "fields" => $_POST]);
		    // nese faqja po vizitohet nga student ose kompani
	    	else 
	        	render("edit_" . $_SESSION["type"] . ".php", ["title" => "Modifiko", "fields" => $_POST]);
	        // shko tek fusha e emailit
	        echo "<script>";
	        echo "document.getElementById('myForm').email.focus()";
	        echo "</script>";
	        return;
	    }

	    // nese perdoruesi eshte student ose admin qe po modifikon nje student
	    if (($_SESSION["type"] == "student") || ($_SESSION["type"] == "admin" && $_POST["type"] == "student"))
	    {
	        // kontrollo moshen
	        if (!preg_match("/^[1-9][0-9]$/", $_POST["mosha"])) {
	            showAlert("Moshë e pavlefshme.");
	            // nese faqja po vizitohet nga admini
	        	if ($_SESSION["type"] == "admin")
	            	render("edit_" . $_POST["type"] . ".php", ["title" => "Modifiko", "fields" => $_POST]);
		    	// nese faqja po vizitohet nga student
	    		else 
	        		render("edit_" . $_SESSION["type"] . ".php", ["title" => "Modifiko", "fields" => $_POST]);
	            // shko tek fusha e moshes
	            echo "<script>";
	            echo "document.getElementById('myForm').mosha.focus()";
	            echo "</script>";
	            return;
	        }

	        // modifiko te dhenat ne tabelen student
	        if ( query("UPDATE student SET emri = ?, gjinia = ?, mosha = ?, email = ?, cel = ? WHERE id = ?", $_POST["emri"], 
	            $_POST["gjinia"], intval($_POST["mosha"]), $_POST["email"], $_POST["cel"], ($_SESSION["type"] == "admin") ? $_POST["id"] : $_SESSION["id"]) === false)
	                apologize("Nuk mund të modifikohen të dhënat për momentin. Provoni sërish më vonë.");	
    	}

    	// nese perdoruesi eshte kompani ose admin qe po modifikon nje kompani
    	if (($_SESSION["type"] == "kompani") || ($_SESSION["type"] == "admin" && $_POST["type"] == "kompani"))
    	{
    		if ( query("UPDATE kompani SET emri_kompani = ?, qyteti = ?, adresa = ?, email = ?, cel = ?, pershkrimi = ? WHERE id = ?", 
            	$_POST["emri_kompani"], $_POST["qyteti"], $_POST["adresa"], 
                	$_POST["email"], $_POST["cel"], $_POST["pershkrimi"], ($_SESSION["type"] == "admin") ? $_POST["id"] : $_SESSION["id"]) === false)
                		apologize("Nuk mund të modifikohen të dhënat për momentin. Provoni sërish më vonë.");
    	}

    	// nese cdo gje shkon mire

    	// nese perdoruesi eshte admin
    	if ($_SESSION["type"] == "admin") 
    	{
    		// nese po modifikonte nje student
    		if ($_POST["type"] == "student")
    			redirect("students.php?show=selected&id=" . $_POST["id"]);
    		// nese po modifikonte nje kompani
    		elseif ($_POST["type"] == "kompani")
    			redirect("companies.php?show=selected&id_kompani=" . $_POST["id"]);
    	}

    	//nese perdoruesi eshte student ose kompani
    	else
    		redirect("/profile.php");
    }

?>