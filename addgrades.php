<?php 
	// konfigurimi
    require("/../site_folders/includes/config.php"); 

    // nese faqja eshte arritur nepermjet GET (drejtperdrejt)
    if ($_SERVER["REQUEST_METHOD"] == "GET")
    	redirect("/");

    else if ($_SERVER["REQUEST_METHOD"] == "POST") 
    {
    	// kerko numrin e notave
    	if ($_POST["option"] == "choose")
    		render("addgrades_choose.php", ["title" => "Shto nota"]);

    	// shfaq formular per notat
    	elseif($_POST["option"] == "add") {
    		// kontrollo nese numri i lendeve eshte i vlefshem
    		if (!preg_match("/^[1-9][0-9]*$/", $_POST["nrLende"])) {
    			showAlert("Numri i lëndëve është i pavlefshëm.");
                if ($_SESSION["type"] == "student")
    			    render("addgrades_choose.php", ["title" => "Shto nota"]);
                elseif($_SESSION["type"] == "admin")
                    render("addgrades_choose.php", ["title" => "Shto nota", "id" => $_POST["id"]]);
    			return;
    		}
    		render("addgrades_add.php", ["title" => "Shto nota", "nrLende" => $_POST["nrLende"]]);
    	}

    	// hidh notat ne sistem
    	elseif($_POST["option"] == "publish")
    	{
    		for ($i = 0; $i < intval($_POST["nrLende"]); $i++) {

    			// kontrollo nese jane vendosur vlera per lenden dhe noten
    			if ($_POST["lenda" . $i] != "" && $_POST["nota" . $i] != "")
    				// ekzekuto query
    				if (query("INSERT INTO nota (id_student, lenda, nota) VALUES (?, ?, ?)", 
    					($_SESSION["type"] == "admin") ? $_POST["id"] : $_SESSION["id"], $_POST["lenda" . $i], intval($_POST["nota" . $i])) === false)
    						apologize("Nuk mund të hidhen notat në sistem. Provoni sërish.");
    		}

    		// merr te gjitha notat e studentit nga databaza
    		if ( ($rows = query("SELECT nota FROM nota WHERE id_student = ?", ($_SESSION["type"] == "admin") ? $_POST["id"] : $_SESSION["id"])) === false)
    			apologize("Nuk mund të llogaritet nota mesatare. Provoni sërish më vonë.");

    		// llogarit mesataren
    		$shuma = 0;
    		foreach ($rows as $row)
    			$shuma += intval($row["nota"]);

    		$mesatarja = number_format((float) $shuma / count($rows), 2);

    		if ( query("UPDATE student SET nota_mesatare = ? WHERE id = ?", $mesatarja, ($_SESSION["type"] == "admin") ? $_POST["id"] : $_SESSION["id"]) === false)
    			apologize("Nuk mund të llogaritet nota mesatare. Provoni sërish më vonë.");

    		// nese gjithcka shkon mire, shko tek notat e studentit
            if ($_SESSION["type"] == "admin")
                redirect("grades.php?id_student=" . $_POST["id"]);
            else
    		  redirect("/grades.php");
    	}
    }

?>