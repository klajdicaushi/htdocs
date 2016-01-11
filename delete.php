<?php

	/* Sherben per ti mundesuar administratorit fshirjen e perdoruesve */
	
	// konfigurimi
	require("/../site_folders/includes/config.php"); 

	// nese faqja eshte arritur jo nepermjet nje formulari, shko tek faqja kryesore
	if ($_SERVER["REQUEST_METHOD"] == "GET")
		redirect("/");

	elseif ($_SERVER["REQUEST_METHOD"] == "POST") 
	{
		// fshi perdoruesin nga databaza
		if (($result = query("DELETE FROM users WHERE id = ?", $_POST["id"])) === false)
			apologize("Nuk mund të plotësohet kërkesa për momentin. Provoni sërish më vonë.");

		// rikthehu tek faqja ku ishe
		if ($_POST["type"] == "student")
			redirect("students.php");
		else if ($_POST["type"] == "kompani")
			redirect("companies.php");
	}

?>