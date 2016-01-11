<?php
    
    /* Sherben per ti mundesuar administratorit ndryshimin e passwordit te perdoruesve */

    // konfigurimi
    require("/../site_folders/includes/config.php");
    
    // nese faqja eshte arritur nepermjet GET (link ose redirect)
    if ($_SERVER["REQUEST_METHOD"] == "GET")
        render("change_password_admin.php", ["title" => "Ndrysho fjalëkalimin"]);
    
    // nese faqja eshte arritur nepermjet POST (duke plotesuar nje formular)
    elseif($_SERVER["REQUEST_METHOD"] == "POST")
    {
        // kontrollo nese te gjitha fushat jane te plotesuara
        foreach ($_POST as $value)
            if (empty($value)) // nese ka fusha te paplotesuara, shfaq alert dhe rishfaq formen
            { 
                showAlert("Ju lutemi, plotësoni të gjitha fushat!");
                render("change_password_admin.php", ["title" => "Ndërro fjalëkalimin", "id" => $_POST["id"]]);
                return;
            }

        // kontrollo nese passwordet perputhen
        if ($_POST["newPassword"] != $_POST["confirmPassword"])
        { 
            showAlert("Fjalëkalimet nuk përputhen!");
            render("change_password_admin.php", ["title" => "Ndërro fjalëkalimin", "id" => $_POST["id"]]);
            return;
        }

        /* // kontrollo fortesine e passwordit
        if(!preg_match('/^(?=.*\d)(?=.*[A-Za-z])[0-9A-Za-z!@#$%]{8,30}$/', $_POST["newPassword"])) 
        {
            showAlert("Fjalëkalimi nuk plotëson kushtet.");
            render("change_password_admin.php", ["title" => "Ndërro fjalëkalimin"]);
            return;
        } */

        // hidh passwordin e ri ne databaze 
        if (query("UPDATE users SET password = ? WHERE id = ?", password_hash($_POST["newPassword"], PASSWORD_DEFAULT), $_POST["id"]) === false) 
            apologize("Nuk mund të ndryshohet fjalëkalimi për momentin. Provoni përsëri.");

        // nese gjithcka shkon mire
        success("Fjalëkalimi u ndryshua me sukses.");
    }

?>
