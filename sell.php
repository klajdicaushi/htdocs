<?php
    // configuration
    require("/../site_folders/includes/config.php");
    
    if ($_SERVER["REQUEST_METHOD"] == "GET") 
    {
        // get stocks from database
        if(($rows = query("SELECT symbol FROM stocks where id = ?", $_SESSION["id"])) === false)
            apologize("Can't retrieve stocks.");
    
        // no stocks
        if ($rows == false)
            apologize("Nothing to sell.");
       
        else 
            render("sell_form.php", ["stocks" => $rows, "title" => "Sell"]);
    }
    
    elseif ($_SERVER["REQUEST_METHOD"] == "POST")
    {
        // check that stocks are selected 
        if (empty($_POST["symbol"]))
            apologize("You must select some stocks");
        
        // retrieve the number of shares bought
        if (($stock = query("SELECT shares FROM stocks WHERE id = ? and symbol = ?", $_SESSION["id"], $_POST["symbol"])) === false) 
            apologize("Can't retrieve current stock shares.");
        
        // delete row from table stocks
        if (query("DELETE FROM stocks WHERE id = ? and symbol = ?", $_SESSION["id"], $_POST["symbol"]) === false)
            apologize("Can't sell stocks at the moment.");
        
        // check for current stock price
        if (($quote = lookup($_POST["symbol"])) === false)
            apologize("Can't check for current price.");
            
        // update the cash total in table users
        $amountToAdd = $stock[0]["shares"] * $quote["price"];
        if ((query("UPDATE users SET cash = cash + ? WHERE id = ?", $amountToAdd, $_SESSION["id"])) === false)
            apologize("Can't add money at the moment.");
            
        // add transaction to history
        if ( query("INSERT INTO history (id, date, type, symbol, shares, price) VALUES (?, CURRENT_TIMESTAMP, 'SELL', ?, ?, ?)",
            $_SESSION["id"], $_POST["symbol"], $stock[0]["shares"], $quote["price"]) === false)
            apologize("Can't add transaction to history.");
                   
        // redirect to portofolio
        redirect("/");
    }
?>
