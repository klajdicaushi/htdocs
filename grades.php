<?php
	
	// konfigurimi
    require("/../site_folders/includes/config.php"); 

    // nese perdoruesi eshte student
    if ($_SESSION["type"] == "student") 
    {
        // nese studenti do te shohe notat e dikujt tjeter
        if (isset($_GET["id_student"]))
            // riktheje tek notat e vet
            redirect("/grades.php");

        // merr notat nga sistemi duke perdorur $_SESSION["id"]
        if ( ($rows = query("SELECT lenda, nota FROM nota WHERE id_student = ?", $_SESSION["id"])) === false)
            apologize("Nuk mund të shfaqen notat për momentin. Provoni sërish më vonë.");
    }

    // nese perdoruesi eshte kompani ose admin
    else 
    {
        // merr notat nga sistemi duke perdorur $_GET["id"]
        if ( ($rows = query("SELECT lenda, nota FROM nota WHERE id_student = ?", $_GET["id_student"])) === false)
            apologize("Nuk mund të shfaqen notat për momentin. Provoni sërish më vonë.");
    }

    // nese studenti ose admini ka kerkuar heqjen e nje note
    if ($_SERVER["REQUEST_METHOD"] == "POST")
    {
        if (query("DELETE FROM nota WHERE id_student = ? AND lenda = ?", 
            ($_SESSION["type"] == "admin") ? $_POST["id"] : $_SESSION["id"], $_POST["lenda"]) === false)
            apologize("Nuk mund të kryhet veprimi për momentin. Provoni sërish më vonë.");

        // nese gjithcka shkon mire, shko tek notat e studentit
        if ($_SESSION["type"] == "admin")
                redirect("/grades.php?id_student=" . $_POST["id"]);
        else
            redirect("/grades.php");
    }

    // shfaq notat
    render("grades_show.php", ["title" => "Lista e notave", "fields" => $rows]);

?>