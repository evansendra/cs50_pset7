<?php

	// configuration
	require("../includes/config.php");

	// check if the user already submitted the form
	if ($_SERVER['REQUEST_METHOD'] == "POST")
	{
		// delete the row for user in holdings
		// dump($_POST);

		if ( empty($_POST["to_sell"]) )
			apologize("Please choose one of your stocks to be sold.");
		else
		{
			// delete row in holdings where id = sesh_id and stock = $_POST['to_sell']
			// update user's new cash amount in users table
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
			"stocks" => $stocks,
			"title" => "Sell Stock"
		];

		render("sell_form.php", $params);
	}

?>