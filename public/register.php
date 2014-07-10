<?php

    // configuration
    require("../includes/config.php");

    // if form was submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST")
    {
        // ensure the form was correctly filled        
        if ( empty($_POST["username"]) || empty($_POST["password"]) || empty($_POST["email"]) )
        {
            apologize("You must have a username, email address, and password.");
        }
        else if ( !filter_var( $_POST[ "email" ], FILTER_VALIDATE_EMAIL) )
        {
            apologize("Sorry, the email address " . $_POST["email"] . " is invalid.");
        }
        else if ($_POST["password"] != $_POST["confirmation"]) 
        {
            apologize("The two passwords did not match.");
        }
        else
        {
            $starting_cash = 1000.00;
            $query_res = query("INSERT INTO users (username, email, hash, cash) VALUES (?, ?, ?, ?)",
                    $_POST["username"], $_POST["email"], crypt( $_POST["password"] ), $starting_cash);

            // type safety is important here, could return false from empty array which is okay
            if ($query_res === FALSE)
            {
                apologize("Sorry, an account with username " . $_POST["username"] . 
                    " or  email " . $_POST["email"] . " is already in use.");
            }
            else 
            {
                // user has been successfully registered, redirect them as logged in user
                $rows = query ("SELECT LAST_INSERT_ID() AS id");
                $id = $rows[0]["id"];

                $_SESSION["id"] = $id;

                redirect("/");
            }
        }


    } 
    else
    {
        // else render form
        render("register_form.php", ["title" => "Register"]);
    }

?>