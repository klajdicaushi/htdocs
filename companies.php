<?php

	// konfigurimi
    require("/../site_folders/includes/config.php"); 

    // faqja nuk hapet nga kompanite
    if ($_SESSION["type"] == "kompani")
    	redirect("/");

    // nese nuk eshte dhene nje vlere per 'show' ne URL
    if (!isset($_GET["show"]))
        $_GET["show"] = "all";

    // shfaq te gjitha kompanite
    if ($_GET["show"] == "all")
    {
    	// merr te dhenat nga sistemi
    	if (($rows = query("SELECT * FROM kompani")) === false)
    		apologize("Nuk mund të merren të dhënat nga sistemi. Provoni sërish më vonë.");

        // shfaq kompanite
        render("companies_show.php", ["title" => "Lista e kompanive", "companies" => $rows]);
    }

    // shfaq nje kompani te perzgjedhur
    if ($_GET["show"] == "selected")
    {   
        // nese nuk eshte perzgjedhur nje id
        if (!isset($_GET["id_kompani"]))
            redirect("/companies.php");

        // merr te dhenat e kompanise nga databaza
        if (($result = query("SELECT * FROM kompani WHERE id = ?", $_GET["id_kompani"])) === false)
            apologize("Nuk mund të hapet profili për momentin. Provoni sërish më vonë.");

        $kompani = $result[0];
        render("companies_select.php", ["title" => $kompani["emri_kompani"], "kompani" => $kompani]);
    }

?>