<?php

/**
 * allows users to view a history of their stock buys and sells
 */

require("../includes/config.php");

// get all transactions for our user
$query_transactions = query("SELECT * FROM history WHERE id = ?", $_SESSION["id"]);

$transactions = [];
$q_t_size = count($query_transactions);
$balance = 0;

// format the transactions appropriate for the view
if ($q_t_size > 0)
{
	// iterate in reverse order based on dates
	for ($i = $q_t_size - 1, $count = 0; $i >= 0; --$i)
	{
		// pick the current transaction to work on
		$transaction = $query_transactions[ $i ];

		// format the date appropriately
		$date = new DateTime( $transaction[ "date" ] );
		$str_date = $date->format("d F Y \a\\t g:i:sa");

		$cur_transaction = 
		[
			"is_buy" => ( (boolean) $transaction["is_buy"] ) ? "purchase" : "sale",
			"symbol" => $transaction["stock"],
			"shares" => $transaction["shares"],
			"price_per_share" => "$" . number_format($transaction["price_per_share"], 2),
			"total" => "$" . number_format($transaction["shares"] * $transaction["price_per_share"], 2),
			"balance" => "$" . number_format($transaction["balance"], 2),
			"date" => $str_date,
		];

		// add transaction to formatted data for view
		$transactions[ $count++ ] = $cur_transaction;

		// get current balance of user
		if ($i === $q_t_size - 1)
			$balance = $cur_transaction["balance"];
	}
}

// handoff the transactions array to our view to display
render("view_history.php", ["transactions" => $transactions, "title" => "History", "balance" => $balance]);

?>