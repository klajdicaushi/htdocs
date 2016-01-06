<?php 
    // configuration
    require("/../site_folders/includes/config.php");
    
    // get transaction history
    if ( ($results = query("SELECT date, type, symbol, shares, price FROM history WHERE id = ?", $_SESSION["id"])) === false )
        apologize("Can't connect to database.");
    
    render("transactions.php", ["title" => "History", "results" => $results]);
?>
