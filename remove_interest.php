<?php

	/* Sherben per t'i mundesuar adminit heqjen e interesimit te studenteve per nje vend pune */

    // konfigurimi
    require("/../site_folders/includes/config.php"); 

    // nese faqja eshte arritur jo nepermjet nje forme, shko tek faqja kryesore 
    if ($_SERVER["REQUEST_METHOD"] == "GET")
    	redirect("/");

    // nese admini do te heqe interesimin e nje studenti per nje pune
	if (query("DELETE FROM kandidate WHERE id_njoftim = ? AND id_student = ?", $_POST["id_njoftim"], $_POST["id_student"]) === false)
    		apologize("Nuk mund të kryhet veprimi për momentin. Provoni sërish më vonë.");
    
    // nese gjithcka shkon mire, rikthehu tek faqja e njoftimit
    redirect("/jobs.php?show=selected&id_njoftim=" . $_POST["id_njoftim"]);

?>