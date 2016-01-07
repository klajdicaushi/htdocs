<?php

	// konfigurimi
    require("/../site_folders/includes/config.php"); 

    // faqja nuk hapet nga studentet
    if ($_SESSION["type"] == "student")
    	redirect("/");

   	// nese nuk eshte dhene nje vlere per 'show' ne URL
    if (!isset($_GET["show"]))
    	$_GET["show"] = "all";

    // shfaq te gjithe studentet
    if ($_GET["show"] == "all") 
    {
    	// nese nuk eshte zgjedhur nje opsion per renditjen, rendit sipas emrit
    	if (!isset($_GET["sort"]))
    		$_GET["sort"] = "emri";

    	if (!isset($_GET["order"]))
    		$_GET["order"] = "ASC";

	    // merr te dhenat e renditura nga sistemi
	    if (($rows = query("SELECT * FROM student ORDER BY " . $_GET["sort"] . " " . $_GET["order"])) === false)
	    	apologize("Nuk mund të merren të dhënat nga sistemi. Provoni sërish më vonë.");

	    // shfaq studentet
	    render("students_show.php", ["title" => "Lista e studentëve", "students" => $rows]);
	}

	// shfaq nje student te perzgjedhur
	if ($_GET["show"] == "selected")
	{
		
		if (!isset($_GET["id"]))
			redirect("/students.php?show=all");

		// merr te dhenat e studentit nga databaza
		if (($result = query("SELECT * FROM student WHERE id = ?", $_GET["id"])) === false)
			apologize("Nuk mund të hapet profili për momentin. Provoni sërish më vonë.");
	
		$student = $result[0];
		render("students_select.php", ["title" => $student["emri"], "student" => $student]);
	}
?>