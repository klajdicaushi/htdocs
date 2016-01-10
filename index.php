<?php

    // konfigurimi
    require("/../site_folders/includes/config.php");

    // nese faqja po aksesohet nga nje student
    if ($_SESSION["type"] == "student") 
    {
        // merr emrin e studentit nga databaza
        if (($student = query("SELECT emri FROM student WHERE id = ?", $_SESSION["id"])) === false)
            apologize("Nuk mund të shfaqet faqja kryesore për momentin. Provoni sërish më vonë.");

        // merr pozicionet e punes ku studenti eshte i interesuar
        if (($pozicione = query("SELECT * FROM kandidate WHERE id_student = ?", $_SESSION["id"])) === false)
            apologize("Nuk mund të shfaqen pozicionet e punës ku jeni interesuar. Provoni sërish më vonë.");;

        render("home_student.php", ["title" => "Kreu", "student" => $student[0], "pozicione" => $pozicione]);
    }

    // nese faqja po aksesohet nga nje kompani
    elseif($_SESSION["type"] == "kompani")
    {
        // merr emrin e kompanise nga databaza
        if (($kompani = query("SELECT emri_kompani FROM kompani WHERE id = ?", $_SESSION["id"])) === false)
            apologize("Nuk mund të shfaqet faqja kryesore për momentin. Provoni sërish më vonë.");

        render("home_kompani.php", ["title" => "Kreu", "kompani" => $kompani[0]]);
    }

    // nese faqja po aksesohet nga admini
    elseif($_SESSION["type"] == "admin")
    {
        // merr username-in e adminit nga databaza
        if (($admin = query("SELECT username FROM users WHERE id = ?", $_SESSION["id"])) === false)
            apologize("Nuk mund të shfaqet faqja kryesore për momentin. Provoni sërish më vonë.");

        render("home_admin.php", ["title" => "Kreu", "admin" => $admin[0]]);
    }

?>