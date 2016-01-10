<?php

    // konfigurimi
    require("/../site_folders/includes/config.php"); 

    // nese faqja eshte arritur nepermjet GET (link ose redirect)
    if ($_SERVER["REQUEST_METHOD"] == "GET")
    {
        // nese ka nje perdorues te loguar
        if (!empty($_SESSION["id"]))
            // shko tek faqja kryesore
            redirect("/");
            
        // ne te kundert, shfaq formen
        renderNoMenu("login_form.php", ["title" => "Hyr"]);
    }

    // nese faqja eshte arritur nepermjet POST (duke plotesuar nje formular)
    else if ($_SERVER["REQUEST_METHOD"] == "POST")
    {
        // nese nuk eshte vendosur perdoruesi
        if (empty($_POST["username"])) {
            // shfaq nje alert dhe rishfaq formularin
            showAlert("Duhet të jepni përdoruesin.");
            renderNoMenu("login_form.php", ["title" => "Hyr"]);
        }
    
        // nese nuk eshte vendosur passwordi
        else if (empty($_POST["password"])) {
            // shfaq nje alert dhe rishfaq formularin
            showAlert("Duhet të jepni fjalëkalimin.");
            renderNoMenu("login_form.php", ["title" => "Hyr"]);
        }
    
        // nese te dyja fushat jane plotesuar
        else 
        {
            // ekzekuto query 
            if ( ($rows = query("SELECT * FROM users WHERE username = ?", $_POST["username"])) === false)
                apologize("Nuk mund të kryhet verifikimi. Provoni sërish.");
    
            // nese u gjet perdoruesi, kontrollo fjalekalimin
            if (count($rows) == 1)
            {
                // rreshti i pare (dhe i vetem)
                $row = $rows[0];
    
                // krahaso hash-in e passwordit te dhene nga perdoruesi me ate te databazes
                if (password_verify($_POST["password"], $row["password"]))
                {
                    // kujto qe perdoruesi eshte loguar duke ruajtur ID-ne e tij
                    $_SESSION["id"] = $row["id"];
                    // kujto llojin e perdoruesit
                    $_SESSION["type"] = $row["type"];
                    // kujto username
                    $_SESSION["username"] = $row["username"];
    
                    // shko tek faqja kryesore
                    redirect("/");
                }
            }
    
            // nese te dhenat nuk jane te sakta, shfaq nje alert dhe rishfaq formularin
            showAlert("Përdorues ose fjalëkalim i gabuar.");
            renderNoMenu("login_form.php", ["title" => "Hyr", "username" => $_POST["username"]]);
        }
    }

?>