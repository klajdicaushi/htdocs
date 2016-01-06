<?php

    // konfigurimi
    require("/../site_folders/includes/config.php"); 

    render("home.php", ["title" => "Kreu"]);

    /*
    // get rows from table stocks
    $rows = query("SELECT symbol, shares FROM stocks WHERE id = ?", $_SESSION["id"]);
    
    if ($rows !== false) 
    {
        $positions = [];
        foreach($rows as $row) 
        {
            $quote = lookup($row["symbol"]);
            $positions[] = [
            "name" => $quote["name"],
            "symbol" => $quote["symbol"],  // mund te duhet modifikuar
            "price" => $quote["price"],
            "shares" => $row["shares"]
            ];
        }       
    }
    
    // get cash total from table users
    $total = query("SELECT cash FROM users WHERE id = ?", $_SESSION["id"]);
    
    if ($total === false)
    {
        // gjenero nje gabim
        trigger_error("Can't retrieve cash total.", E_USER_ERROR);
    }

    // render portfolio
    render("portfolio.php", ["positions" => $positions, "total" => $total[0]["cash"], "title" => "Portfolio"]);
    */
?>
