<?php

	/* Menaxhon hedhjen e CV-ve te studenteve ne sistem */
	
	// konfigurimi
	require("/../site_folders/includes/config.php"); 
	
	// nese faqja eshte arritur jo nepermjet nje forme, shko tek faqja kryesore
	if ($_SERVER["REQUEST_METHOD"] == "GET")
		redirect("/");

	// kontrollo nese file eshte ngarkuar me sukses
	if(isset($_POST['upload']) && $_FILES['userfile']['size'] > 0)
	{
		$fileName = $_FILES['userfile']['name'];
		$tmpName  = $_FILES['userfile']['tmp_name'];
		$fileSize = $_FILES['userfile']['size'];
		$fileType = $_FILES['userfile']['type'];

		$fp = fopen($tmpName, 'r');
		$content = fread($fp, filesize($tmpName));
		fclose($fp);

		$fileName = addslashes($fileName);

		if (($result = query("INSERT INTO cv (id_student, name, size, type, content) VALUES (?, ?, ?, ?, ?)", 
			($_SESSION["type"] == "admin") ? $_POST["id"] : $_SESSION["id"], $fileName, $fileSize, $fileType, $content)) === false)
				apologize("Nuk mund të ngarkohet dokumenti në këtë moment. Provoni sërish më vonë.");
		
		// nese gjithcka shkon mire
		if ($_SESSION["type"] == "admin")
			redirect("students.php?show=selected&id=" . $_POST["id"]);
		else
			redirect("/profile.php");
	} 

?>