<?php

    /**
     * constants.php
     *
     * Computer Science 50
     * Problem Set 7
     *
     * Global constants.
     */

    // your database's name
    define("DATABASE", "pset7");

    // your database's password
    define("PASSWORD", "crimson");

    // your database's server
    define("SERVER", "localhost");

    // your database's username
    define("USERNAME", "jharvard");

    // your forgot password page's base url
    define("FORGOT_PASS_BASE_URL", "http://" . $_SERVER['HTTP_HOST']);

    // your email address to send emails to clients
    define("EMAIL_SENDER", "foob_ar@yahoo.com");

    // your password for the email account used to send emails
    define("EMAIL_PASS", "sillyPASS9");

    define("NO_MENU_PAGES",
            serialize( array
            (
            "login.php",
            "register.php",
            "forgot.php",
            "apologize.php",
            "alert_pass_sent.php",
            "reset.php",
            "apology.php",
            "new_pass.php"
            ) )
        );


?>