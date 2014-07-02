<?php

    // configuration
    require("../includes/config.php");

    // if form was submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST")
    {
        // ensure the form was correctly filled        
        if ( empty($_POST["username"]) || empty($_POST["password"]) )
        {
            apologize("You must have a username and password.");
        }
        else if ($_POST["password"] != $_POST["confirmation"]) 
        {
            apologize("The two passwords did not match.");
        }
        else
        {
            $starting_cash = 1000.00;
            $query_res = query("INSERT INTO users (username, hash, cash) VALUES (?, ?, ".$starting_cash.")",
                    $_POST["username"], crypt( $_POST["password"] ) );

            // type safety is important here, could return false from empty array which is okay
            if ($query_res === FALSE)
            {
                apologize("Username " . $_POST["username"] . " already taken.");
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