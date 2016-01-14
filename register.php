<?php

    /* Sherben per te zgjedhur llojin e perdoruesit qe do te regjistrohet */

    // konfigurimi
    require("/../site_folders/includes/config.php"); 

    // nese faqja eshte arritur nepermjet GET (link ose redirect)
    if ($_SERVER["REQUEST_METHOD"] == "GET")
    {            
        // shfaq formularin
        renderNoMenu("register_form.php", ["title" => "Regjistrohu"]);
    }

    // nese faqja eshte arritur nepermjet POST (duke plotesuar nje formular)
    else if ($_SERVER["REQUEST_METHOD"] == "POST")
    {
        // shfaq formularin e duhur ne baze te zgjedhjes se perdoruesit
        renderNoMenu("register_" . $_POST["type"] . ".php", ["title" => "Regjistrohu"]);
    }

?>
