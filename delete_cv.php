<?php
	
	/* Sherben per te mundesuar fshirjen e CV-se nga studentet ose administratori */

	// konfigurimi
	require("/../site_folders/includes/config.php"); 

	// nese faqja eshte arritur jo nepermjet nje forme, shko tek faqja kryesore
	if ($_SERVER["REQUEST_METHOD"] == "GET")
		redirect("/");

	elseif ($_SERVER["REQUEST_METHOD"] == "POST") 
	{
		if (($result = query("DELETE FROM cv WHERE id_student = ?", ($_SESSION["type"] == "admin") ? $_POST["id"] : $_SESSION["id"])) === false)
			apologize("Nuk mund të plotësohet kërkesa për momentin. Provoni sërish më vonë.");

		// nese gjithcka shkon mire, shko tek te dhenat e studentit
		if ($_SESSION["type"] == "admin")
			redirect("students.php?show=selected&id=" . $_POST["id"]);
		else
			redirect("/profile.php");
	}

?>