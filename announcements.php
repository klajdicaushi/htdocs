<?php
    
    /* Sherben per te shfaqur ose krijuar njoftimet e administratorit */

    // konfigurimi
    require("/../site_folders/includes/config.php");

    // nese eshte kerkuar te shfaqet nje njoftim
    if ($_SERVER["REQUEST_METHOD"] == "GET") 
    {
    	// nese nuk ka nje vlere per id_njoftim
    	if (!isset($_GET["id_njoftim"]))
    		redirect("/");

    	// merr te dhenat e njoftimit
    	if (($njoftim = query("SELECT * FROM njoftime_admin WHERE id_njoftim = ?", $_GET["id_njoftim"])) === false)
    		apologize("Nuk mund të shfaqet njoftimi për momentin.");

    	// shfaq njoftimin
    	render("announcement_show.php", ["title" => $njoftim[0]["titulli"], "njoftim" => $njoftim[0]]);
    }

    // nese faqja eshte arritur nepermjet POST (duke plotesuar nje formular)
    else if ($_SERVER["REQUEST_METHOD"] == "POST")
    {
        // nese admini kerkon te krijoje nje njoftim
    	if ($_POST["action"] == "create")
    		render("announcement_create.php", ["title" => "Krijo një njoftim"]);

    	// nese admini deshiron te hedhe njoftimin ne databaze
    	elseif ($_POST["action"] == "publish") 
    	{
    		if (query("INSERT INTO njoftime_admin (titulli, permbajtja, data_publikimit) VALUES (?, ?, CURDATE())", $_POST["titulli"], $_POST["permbajtja"]) === false)
    			apologize("Nuk mund të publikohet njoftimi për momentin.");

    		// gjej id-ne e njoftimit te shtuar
    		$id = query("SELECT LAST_INSERT_ID() AS id_njoftim");

    		// shko tek njoftimi
    		redirect("/announcements.php?id_njoftim=" . $id[0]["id_njoftim"]);
    	}

    	// nese admini deshiron te fshije nje njoftim
    	elseif ($_POST["action"] == "delete")
    	{
    		if (query("DELETE FROM njoftime_admin WHERE id_njoftim = ?", $_POST["id_njoftim"]) === false)
				apologize("Nuk mund të kryhet veprimi për momentin. Provoni sërish më vonë.");

			// shko tek faqja kryesore
			redirect("/");
    	}
    }

?>