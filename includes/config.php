<?php

    /**
     * config.php
     *
     * Computer Science 50
     * Problem Set 7
     *
     * Configures pages.
     */

    // display errors, warnings, and notices
    ini_set("display_errors", true);
    error_reporting(E_ALL);

    // requirements
    require("constants.php");
    require("functions.php");

    // enable sessions
    session_start();

    // require authentication for most pages if not logged in 
    if (!preg_match("{(?:login|logout|register|forgot|reset|" .
        "email_pass|alert_pass_sent|reset|apology|new_pass)\.php(\?code=)*[\w]*$}", $_SERVER["PHP_SELF"]))
    {
        if (empty($_SESSION["id"]))
        {
            redirect("login.php");
        }
    }

?>
