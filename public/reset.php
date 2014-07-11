<?php

	require("../includes/config.php");
	
	// is there a valid code in the url?
	if ( !isset($_GET[ "code" ]) 
			|| preg_match("/[\W]+/", $_GET[ "code" ]))
	{
		redirect("/");
	}

	// is the code given contained in the db?
	$reset_check_query = query("SELECT * FROM reset_pass ".
		"WHERE code = ?", $_GET["code"]);

	if ($reset_check_query === false)
		apologize("Couldn't reset password at this time. Try again later.");
	else if ( empty($reset_check_query) )
		apologize("Sorry, this isn't a valid password reset.");

	// must have valid password reset link
	$user_row = query("SELECT * FROM users WHERE email = ?", $reset_check_query[0]["email"]);

	if ($user_row === false || empty($user_row))
		apologize("Couldn't reset your password. An error occurred : (");

	// we found the user's row...update the password
	render("pass_reset.php", ["title" => "Password Reset", "id" => $user_row[0]["id"]])
?>