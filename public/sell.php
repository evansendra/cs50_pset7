<?php

	// configuration
	require("../includes/config.php");

	// check if the user already submitted the form
	if ($_SERVER['REQUEST_METHOD'] == "POST")
	{
		if ( empty($_POST["to_sell"]) )
			apologize("Please choose one of your stocks to be sold.");
		else
		{
			// delete row in holdings where id = sesh_id and stock = $_POST['to_sell']
			$stock_to_sell = $_POST["to_sell"];

			$res = query("SELECT * FROM holdings WHERE id = ? AND stock = ?", 
				$_SESSION["id"], $stock_to_sell);

			
			if ($res === false)
				apologize("We're sorry; we couldn't sell " . $stock_to_sell . ".");
			else if ( empty($res) )
				apologize("It seems you don't have any " . $stock_to_sell . ".");

			// obtain amount of cash to give from the sale
			$stock_info = lookup( $res[0]["stock"] );

			$cash_made = $stock_info["price"] * $res[0]["shares"];

			// credit the user's account with that cash
			query("UPDATE users SET cash = cash + ? WHERE id = ?",
					$cash_made, $_SESSION["id"]);

			query("DELETE FROM holdings WHERE id = ? AND stock = ?", 
				$_SESSION["id"], $stock_to_sell);	

			// get remaining stocks after sale
			$stocks = query("SELECT * FROM holdings WHERE id = ?", $_SESSION["id"]);

			// repopulate the sell page with success message and remaining stocks
			$params = 
			[
				"stocks" => $stocks,
				"sold_stock" => $stock_to_sell,
				"cash_made" => $cash_made,
				"title" => "Sell Stock"
			];

			render("sell_form.php", $params);
		}
	}
	else
	{
		$stocks = query("SELECT * FROM holdings WHERE id = ?", $_SESSION["id"]);
		if ($stocks === false)
		{
			apologize("Sorry, you don't have any stocks to sell.");
		}

		$params = 
		[
			"sold_stock" => false,
			"stocks" => $stocks,
			"title" => "Sell Stock"
		];

		render("sell_form.php", $params);
	}

?>