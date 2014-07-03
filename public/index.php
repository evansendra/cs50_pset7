<?php

    // configuration
    require("../includes/config.php"); 

    // look up the stock holdings of this user
    $rows = query("SELECT * FROM holdings WHERE id = ?", $_SESSION["id"]);
    $positions = [];
    foreach ($rows as $row)
    {
    	$stock = lookup($row["stock"]);
    	if ($stock !== false)
    	{
            $total = number_format( ($stock["price"] * $row["shares"]),
                2);

    		$positions[] = 
    		[
    			"name" => $stock["name"],
    			"price" => $stock["price"],
    			"shares" => $row["shares"],
    			"symbol" => $row["stock"],
                "total" => $total
    		];
    	}
    }

    

    // look up the current cash balance of the user
    $raw_balance = query("SELECT cash FROM users WHERE id = ?", $_SESSION["id"]);
    $balance = number_format($raw_balance[0]["cash"], 2);

    // render portfolio
    render("portfolio.php", ["title" => "Portfolio", "balance" => $balance, "positions" => $positions]);

?>
