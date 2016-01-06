<?php

    // konfigurimi
    require("/../site_folders/includes/config.php");
    
    // nese faqja eshte arritur nepermjet GET (link ose redirect)
    if ($_SERVER["REQUEST_METHOD"] == "GET")
        render("change_password.php", ["title" => "Ndërro fjalëkalimin"]);
    
    // nese faqja eshte arritur nepermjet POST (duke plotesuar nje formular)
    elseif($_SERVER["REQUEST_METHOD"] == "POST")
    {
        
        // kontrollo nese te gjitha fushat jane te plotesuara
        foreach ($_POST as $key => $value)
            if (empty($value)) // nese ka fusha te paplotesuara, shfaq alert dhe rishfaq formen
            { 
                showAlert("Ju lutemi, plotësoni të gjitha fushat!");
                render("change_password.php", ["title" => "Ndërro fjalëkalimin"]);
                // shko tek elementi i pare i paplotesuar
                echo "<script>";
                echo "document.getElementById('myForm').$key.focus()";
                echo "</script>";
                return;
            }

        // kontrollo nese passwordet perputhen
        if ($_POST["newPassword"] != $_POST["confirmPassword"])
        { 
            showAlert("Fjalëkalimet nuk përputhen!");
            render("change_password.php", ["title" => "Ndërro fjalëkalimin"]);
            // shko tek fusha e passwordit te ri
            echo "<script>";
            echo "document.getElementById('myForm').newPassword.focus()";
            echo "</script>";
            return;
        }

        // kontrollo nese passwordi i ri eshte i ndryshem nga i pari
        if ($_POST["newPassword"] == $_POST["currPassword"])
        { 
            showAlert("Fjalëkalimi i ri duhet të jetë i ndryshëm nga i vjetri!");
            render("change_password.php", ["title" => "Ndërro fjalëkalimin"]);
            // shko tek fusha e passwordit te ri
            echo "<script>";
            echo "document.getElementById('myForm').newPassword.focus()";
            echo "</script>";
            return;
        }
           
        // nese perdoruesi eshte student
        if ($_SESSION["type"] == "student") 
        {
            // merr kodin hash te passwordit aktual
            if (($result = query("SELECT password FROM student WHERE id = ?", $_SESSION["id"])) === false)
                apologize("Nuk mund të verifikohet fjalëkalimi për momentin. Provoni përsëri.");

            // verifiko passwordin
            if ( !password_verify($_POST["currPassword"], $result[0]["password"]))
            {
                showAlert("Fjalëkalimi është i gabuar!");
                render("change_password.php", ["title" => "Ndërro fjalëkalimin"]);
                // shko tek fusha e passwordit aktual
                echo "<script>";
                echo "document.getElementById('myForm').currPassword.focus()";
                echo "</script>";
                return;
            }

            // nderro passwordin
            if (query("UPDATE student SET password = ? WHERE id = ?", password_hash($_POST["newPassword"], PASSWORD_DEFAULT), $_SESSION["id"]) === false) 
                apologize("Nuk mund të ndryshohet fjalëkalimi për momentin. Provoni përsëri.");

        }

        // nese perdoruesi eshte kompani
        elseif ($_SESSION["type"] == "kompani") 
        {
            // merr kodin hash te passwordit aktual
            if (($result = query("SELECT password FROM kompani WHERE id = ?", $_SESSION["id"])) === false)
                apologize("Nuk mund të verifikohet fjalëkalimi për momentin. Provoni përsëri.");

            // verifiko passwordin
            if ( !password_verify($_POST["currPassword"], $result[0]["password"]))
            {
                showAlert("Fjalëkalimi është i gabuar!");
                render("change_password.php", ["title" => "Ndërro fjalëkalimin"]);
                // shko tek fusha e passwordit aktual
                echo "<script>";
                echo "document.getElementById('myForm').currPassword.focus()";
                echo "</script>";
                return;
            }

            // nderro passwordin
            if (query("UPDATE kompani SET password = ? WHERE id = ?", password_hash($_POST["newPassword"], PASSWORD_DEFAULT), $_SESSION["id"]) === false) 
                apologize("Nuk mund të ndryshohet fjalëkalimi për momentin. Provoni përsëri.");
        }
                
        // nese cdo gje shkon mire, shko tek faqja kryesore
        redirect("/");    
    }

?>
