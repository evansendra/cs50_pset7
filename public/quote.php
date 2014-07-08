<?php

	// configuration
	require("../includes/config.php");

	// if no request has been made
	if ( $_SERVER['REQUEST_METHOD'] == "GET" 
		&& empty($_GET["symbol"]) )
	{
		render("quote_form.php", ["title" => "Get Quote"]);
	}
	// lookup the stock and show it to the user (if it exists)
	else
	{
		// show the quote which was requested
		$stock = lookup($_GET["symbol"]);

		if ($stock === false)
			apologize("Sorry, the " . $_GET["symbol"] . " symbol seems to be invalid.");

		// collect vars returned from lookup
		$symbol = $stock["symbol"];
		$name = $stock["name"];
		// 2 is number of decimals after the dot
		$price = number_format($stock["price"], 2);

		$display_data = array(
			"title" => $symbol . " Info",
			"symbol" => $symbol,
			"name" => $name,
			"price" => $price,
		);

		render("show_quote.php", $display_data);

	}


?>