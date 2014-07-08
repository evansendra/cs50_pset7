<?php

/**
 * allows users to "buy" stocks
 */

require("../includes/config.php");

if ($_SERVER["REQUEST_METHOD"] == "POST")
{
	// ensure we have both inputs
	if ( empty($_POST["symbol"]) || ( $_POST["shares"] !== "0" && empty($_POST["shares"]) ) )
		apologize("You must provide both a stock and a number of shares.");

	$symbol = strtoupper($_POST["symbol"]);
	$shares = $_POST["shares"];

	// ensure shares is an integer
	if ( !preg_match("/^\d+$/", $shares) || !($shares > 0) )
		apologize("Number of shares must be a positive, whole number.");

	// lookup the stock they asked for and ensure it exists
	$lookup = lookup($symbol);
	if ($lookup === false)
		apologize("Sorry, the symbol $symbol appears to not be valid stock.");

	// ensure user has sufficient cash to make the purchase
	$user = query("SELECT * FROM users WHERE id = ?", $_SESSION["id"]);
	$total = $lookup["price"] * $shares;
	$balance = $user[0]["cash"];

	$ftotal = number_format($total, 2);
	$fbalance = number_format($balance, 2);

	if ($total <= $balance)
	{
		// update user's holdings
		query("INSERT INTO holdings (id, stock, shares) VALUES (?, ?, ?) " .
				"ON DUPLICATE KEY UPDATE shares = shares + VALUES(shares)",
				$_SESSION["id"], $symbol, $shares);

		// update user's cash
		query("UPDATE users SET cash = cash - ? WHERE id = ?",
				$total, $_SESSION["id"]);

		// alert user of purchase
		$render_params =
		[
			"shares" => $shares,
			"symbol" => $symbol,
			"total" => $ftotal,
			"balance" => $fbalance
		];

		render("buy_confirmation.php", $render_params);
	}
	else
	{
		apologize("Your balance of \$$fbalance is insufficient to buy $shares shares of $symbol for " .
				"\$$ftotal.");
	}

}
else
{
	$params = ["title" => "Buy some stock"];
	render("buy_form.php", $params);
}

?>