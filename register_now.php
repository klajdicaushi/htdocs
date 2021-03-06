<?php 

	/* Sherben per te menaxhuar regjistrimin e perdoruesve te rinj */

	// konfigurimi
	require("/../site_folders/includes/config.php"); 

	// kontrollo nese fushat jane te plotesuara
	foreach ($_POST as $key => $value) {
		// anashkalo vetem per fushen e pershkrimit ose celularit
		if ( $key != "pershkrimi" && $key != "cel")
			if (empty($value)) { // nese ka fusha te paplotesuara, shfaq alert dhe rishfaq formen
				showAlert("Ju lutemi, plotësoni të gjitha fushat e kërkuara!");
				renderNoMenu("register_" . $_POST["type"] . ".php", ["title" => "Regjistrohu", "fields" => $_POST]);
				// shko tek elementi i pare i paplotesuar
				echo "<script>";
				echo "document.getElementById('myForm').$key.focus()";
				echo "</script>";
				return;
			}
	}

	if (!preg_match("/^[1-9]{2}$/", $_POST["mosha"]))
	{
		showAlert("Moshë e pavlefshme!");
		renderNoMenu("register_" . $_POST["type"] . ".php", ["title" => "Regjistrohu", "fields" => $_POST]);
		// shko tek mosha
		echo "<script>";
		echo "document.getElementById('myForm').mosha.focus()";
		echo "</script>";
		return;
	}

	// kontrollo nese eshte vendosur gjinia
	if (!isset($_POST["gjinia"]))
	{
		showAlert("Ju lutemi, vendosni gjininë!");
		renderNoMenu("register_" . $_POST["type"] . ".php", ["title" => "Regjistrohu", "fields" => $_POST]);
		return;
	}

	// nese passwordet nuk jane te njejte
	if ($_POST["password"] != $_POST["confirmation"]) 
	{
		showAlert("Fjalëkalimet nuk përputhen.");
		renderNoMenu("register_" . $_POST["type"] . ".php", ["title" => "Regjistrohu", "fields" => $_POST]);
		// shko tek fusha e passwordit
		echo "<script>";
		echo "document.getElementById('myForm').password.focus()";
		echo "</script>";
		return;
	}

	// kontrollo numrin e celularit
	if ($_POST["cel"] != "" && !preg_match("/^(\+)?[0-9]+$/", $_POST["cel"]))
	{
		showAlert("Numër celulari i pavlefshëm.");
		renderNoMenu("register_" . $_POST["type"] . ".php", ["title" => "Regjistrohu", "fields" => $_POST]);
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
		renderNoMenu("register_" . $_POST["type"] . ".php", ["title" => "Regjistrohu", "fields" => $_POST]);
		// shko tek fusha e emailit
		echo "<script>";
		echo "document.getElementById('myForm').email.focus()";
		echo "</script>";
		return;
	}

	/* // kontrollo fortesine e passwordit
	if(!preg_match('/^(?=.*\d)(?=.*[A-Za-z])[0-9A-Za-z!@#$%]{8,30}$/', $_POST["password"])) 
	{
    	showAlert("Fjalëkalimi nuk plotëson kushtet.");
		renderNoMenu("register_" . $_POST["type"] . ".php", ["title" => "Regjistrohu", "fields" => $_POST]);
		// shko tek fusha e emailit
		echo "<script>";
		echo "document.getElementById('myForm').password.focus()";
		echo "</script>";
		return;
	} */

	// nese te gjitha fushat jane plotesuar

	// kontrollo nese ekziston nje perdorues me ate username
	$rows = query("SELECT * FROM users WHERE username = ?", $_POST["username"]);
	if (count($rows) != 0) {
		showAlert("Ky përdorues ekziston. Ju lutemi zgjidhni një emër tjetër përdoruesi.");
		renderNoMenu("register_" . $_POST["type"] . ".php", ["title" => "Regjistrohu", "fields" => $_POST]);
		// shko tek fusha e perdoruesit
		echo "<script>";
		echo "document.getElementById('myForm').username.focus()";
		echo "</script>";
		return;
	}

	// shto perdoruesin e ri ne tabelen users
	if ( query("INSERT INTO users (username, password, type) VALUES (?, ?, ?)", 
		$_POST["username"], password_hash($_POST["password"], PASSWORD_DEFAULT), $_POST["type"]) === false)
		apologize("Nuk mund të regjistroheni për momentin. Provoni sërish më vonë.");

	// gjej id-ne e fundit te shtuar ne databaze
	$rows = query("SELECT LAST_INSERT_ID() AS id");

	// nese perdoruesi eshte student
	if ($_POST["type"] == "student") {
		// kontrollo moshen
		if (!preg_match("/^[1-9][0-9]$/", $_POST["mosha"])) {
			showAlert("Moshë e pavlefshme.");
			renderNoMenu("register_" . $_POST["type"] . ".php", ["title" => "Regjistrohu", "fields" => $_POST]);
			// shko tek fusha e moshes
			echo "<script>";
			echo "document.getElementById('myForm').mosha.focus()";
			echo "</script>";
			return;
		}

		// shto perdoruesin e ri ne tabelen student
		if ( query("INSERT INTO student (id, emri, gjinia, mosha, email, cel) VALUES (?, ?, ?, ?, ?, ?)", 
			$rows[0]["id"], $_POST["emri"], $_POST["gjinia"], intval($_POST["mosha"]), $_POST["email"], $_POST["cel"]) === false)
				apologize("Nuk mund të regjistroheni për momentin. Provoni sërish më vonë.");
	}

	// nese perdoruesi eshte kompani
	else if ($_POST["type"] == "kompani") {

		// shto perdoruesin e ri ne tabelen kompani
		if ( query("INSERT INTO kompani (id, emri_kompani, qyteti, adresa, email, cel, pershkrimi) VALUES (?, ?, ?, ?, ?, ?, ?)", 
			$rows[0]["id"], $_POST["emri_kompani"], $_POST["qyteti"], $_POST["adresa"], 
				$_POST["email"], $_POST["cel"], $_POST["pershkrimi"]) === false)
				apologize("Nuk mund të regjistroheni për momentin. Provoni sërish më vonë.");

	} 

	// nese cdo gje ka shkuar mire

	// regjistro perdoruesin per session-in aktual
	$_SESSION["id"] = $rows[0]["id"];

	// kujto username
	$_SESSION["username"] = $_POST["username"];

	// regjistro llojin e perdoruesit per kete session
	$_SESSION["type"] = $_POST["type"];

	// shko tek faqja kryesore
	redirect("/");
	
?>