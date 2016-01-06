<?php
    // configuration
    require("/../site_folders/includes/config.php");
    
    if ($_SERVER["REQUEST_METHOD"] == "GET")
        render("buy_form.php", ["title" => "Buy"]);
        
    elseif ($_SERVER["REQUEST_METHOD"] == "POST")
    {
        // no symbol
        if (empty($_POST["symbol"]))
            apologize("You must specify a stock to buy.");
        
        // no shares
        if (empty($_POST["shares"]))
            apologize("You must specify a number of shares.");
        
        if (preg_match("/^\d+$/", $_POST["shares"]) == false)
            apologize("Invalid number of shares."); 
            
        // no symbol found
        if (($result = lookup($_POST["symbol"])) == false)
            apologize("Symbol not found.");
        
        // calculate cost of shares
        $cost = $_POST["shares"] * $result["price"];
        // get cmr information
        if (($cmr = query("SELECT cash FROM users WHERE id = ?", $_SESSION["id"])) === false)
            apologize("Can't check current amount of money");
        
        // check if cmr can afford to buy shares
        if ($cost > $cmr[0]["cash"])
            apologize("You can't afford that.");
        
        // add shares to table stocks
        if ( query("INSERT INTO stocks (id, symbol, shares) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE shares = shares + VALUES(shares)",
            $_SESSION["id"], strtoupper($_POST["symbol"]), $_POST["shares"]) === false)
            apologize("Can't buy shares now. Try again later.");
        
        // subtract cost from cmr's cash   
        if ( query("UPDATE users SET cash = cash - ?", $cost) === false )
            apologize("Can't complete transaction now.");
            
        // add transaction to history
        if ( query("INSERT INTO history (id, date, type, symbol, shares, price) VALUES (?, CURRENT_TIMESTAMP , 'BUY', ?, ?, ?)",
            $_SESSION["id"], strtoupper($_POST["symbol"]), $_POST["shares"], $result["price"]) === false)
            apologize("Can't add transaction to history.");
            
        // redirect to homepage
        redirect("/");      
    }
?>
