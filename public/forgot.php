<?php

require("../includes/config.php");

	
	if ($_SERVER["REQUEST_METHOD"] == "POST")
	{
		// did they give a valid email to us
		if ( empty( $_POST[ "email" ] ) )
			apologize("You must provide an email.");
		else if ( !filter_var($_POST[ "email" ], FILTER_VALIDATE_EMAIL) )
			apologize($_POST["email"] . " isn't a valid email.");

		// is the email in the users table?
		$user_query = query("SELECT * FROM users WHERE email = ?", $_POST[ "email" ]);

		// check if we have a user with this email address
		if ($user_query === false)
			apologize("We're having some troubles right now : ( ... please try again.");
		else if ( empty ($user_query) )
			apologize("No user with email " . $_POST["email"] . " was found.");

		// must have found user with this email, send him mail for recovery
		if ( email_pass( $_POST["email"] ) )
		{
			render("alert_pass_sent.php", ["title" => "Password Sent"]);
		}
		else
		{
			apologize("We couldn't reset your password at this time; try again later.");
		}	
	}
	else
	{
		render("forgot_form.php", ["title" => "Forgot Password"]);
	}

?>