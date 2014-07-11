<?php
	
	require("../includes/config.php");

	// if the form was submitted
	if ($_SERVER["REQUEST_METHOD"] == "POST")
	{
		// ensure that both fields are set
		if ( empty($_POST["password"]) || empty($_POST["confirm"]) )
			apologize("You must provide a new password and confirmation.");

		// ensure new passwords match
		if ( $_POST["password"] !== $_POST["confirm"] )
			apologize("Passwords must match.");

		// both passwords are set and they match so we'll insert to db
		// and log the user in
		$crypt = crypt( $_POST["password"] );
		query("INSERT INTO users (hash) VALUES (?)", $crypt);
		$user_query = query("UPDATE users SET hash = ? WHERE id = ?", $crypt, $id);
		$_SESSION["id"] = $id;
		redirect("/");
	}

?>