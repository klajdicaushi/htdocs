<?php

    // configuration
    require("/../site_folders/includes/config.php");
    
    // if user reached page via GET (as by clicking a link)
    if ($_SERVER["REQUEST_METHOD"] == "GET")
    {
        render("quote_search.php", ["title" => "Get Quote"]);
    }
    
    // else if user reached page via POST (as by submitting a form via POST)
    elseif($_SERVER["REQUEST_METHOD"] == "POST")
    {
        // validate submission
        if (empty($_POST["symbol"]))
            apologize("You must provide a symbol.");
        
        // if symbol is not valid
        if ( ($result = lookup($_POST["symbol"])) == false )
            apologize("Symbol not found.");    
            
        render("quote_result.php", lookup($_POST["symbol"]));
    }
?>
