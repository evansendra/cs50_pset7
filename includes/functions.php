<?php

    /**
     * functions.php
     *
     * Computer Science 50
     * Problem Set 7
     *
     * Helper functions.
     */

    require_once("constants.php");
    require_once("PHPMailer/class.phpmailer.php");

    /**
     * Apologizes to user with message.
     */
    function apologize($message)
    {
        render("apology.php", ["message" => $message]);
        exit;
    }

    /**
     * Facilitates debugging by dumping contents of variable
     * to browser.
     */
    function dump($variable)
    {
        require("../templates/dump.php");
        exit;
    }

    /**
     * Logs out current user, if any.  Based on Example #1 at
     * http://us.php.net/manual/en/function.session-destroy.php.
     */
    function logout()
    {
        // unset any session variables
        $_SESSION = [];

        // expire cookie
        if (!empty($_COOKIE[session_name()]))
        {
            setcookie(session_name(), "", time() - 42000);
        }

        // destroy session
        session_destroy();
    }

    /**
     * Returns a stock by symbol (case-insensitively) else false if not found.
     */
    function lookup($symbol)
    {
        // reject symbols that start with ^
        if (preg_match("/^\^/", $symbol))
        {
            return false;
        }

        // reject symbols that contain commas
        if (preg_match("/,/", $symbol))
        {
            return false;
        }

        // open connection to Yahoo
        $handle = @fopen("http://download.finance.yahoo.com/d/quotes.csv?f=snl1&s=$symbol", "r");
        if ($handle === false)
        {
            // trigger (big, orange) error
            trigger_error("Could not connect to Yahoo!", E_USER_ERROR);
            exit;
        }

        // download first line of CSV file
        $data = fgetcsv($handle);
        if ($data === false || count($data) == 1)
        {
            return false;
        }

        // close connection to Yahoo
        fclose($handle);

        // ensure symbol was found
        if ($data[2] === "0.00")
        {
            return false;
        }

        // return stock as an associative array
        return [
            "symbol" => $data[0],
            "name" => $data[1],
            "price" => $data[2],
        ];
    }

    /**
     * Executes SQL statement, possibly with parameters, returning
     * an array of all rows in result set or false on (non-fatal) error.
     */
    function query(/* $sql [, ... ] */)
    {
        // SQL statement
        $sql = func_get_arg(0);

        // parameters, if any
        $parameters = array_slice(func_get_args(), 1);

        // try to connect to database
        static $handle;
        if (!isset($handle))
        {
            try
            {
                // connect to database
                $handle = new PDO("mysql:dbname=" . DATABASE . ";host=" . SERVER, USERNAME, PASSWORD);

                // ensure that PDO::prepare returns false when passed invalid SQL
                $handle->setAttribute(PDO::ATTR_EMULATE_PREPARES, false); 
            }
            catch (Exception $e)
            {
                // trigger (big, orange) error
                trigger_error($e->getMessage(), E_USER_ERROR);
                exit;
            }
        }

        // prepare SQL statement
        $statement = $handle->prepare($sql);
        if ($statement === false)
        {
            // trigger (big, orange) error
            trigger_error($handle->errorInfo()[2], E_USER_ERROR);
            exit;
        }

        // execute SQL statement
        $results = $statement->execute($parameters);

        // return result set's rows, if any
        if ($results !== false)
        {
            return $statement->fetchAll(PDO::FETCH_ASSOC);
        }
        else
        {
            return false;
        }
    }

    /**
     * Redirects user to destination, which can be
     * a URL or a relative path on the local host.
     *
     * Because this function outputs an HTTP header, it
     * must be called before caller outputs any HTML.
     */
    function redirect($destination)
    {
        // handle URL
        if (preg_match("/^https?:\/\//", $destination))
        {
            header("Location: " . $destination);
        }

        // handle absolute path
        else if (preg_match("/^\//", $destination))
        {
            $protocol = (isset($_SERVER["HTTPS"])) ? "https" : "http";
            $host = $_SERVER["HTTP_HOST"];
            header("Location: $protocol://$host$destination");
        }

        // handle relative path
        else
        {
            // adapted from http://www.php.net/header
            $protocol = (isset($_SERVER["HTTPS"])) ? "https" : "http";
            $host = $_SERVER["HTTP_HOST"];
            $path = rtrim(dirname($_SERVER["PHP_SELF"]), "/\\");
            header("Location: $protocol://$host$path/$destination");
        }

        // exit immediately since we're redirecting anyway
        exit;
    }

    /**
     * Renders template, passing in values.
     */
    function render($template, $values = [])
    {
        // if template exists, render it
        if (file_exists("../templates/$template"))
        {
            // extract variables into local scope
            extract($values);

            // render header
            require("../templates/header.php");

            // render template
            require("../templates/$template");

            // render footer
            require("../templates/footer.php");
        }

        // else err
        else
        {
            trigger_error("Invalid template: $template", E_USER_ERROR);
        }
    }

    /**
     * looks through a list of pages on the site on which the nav menu
     * should not be shown (i.e. login.php), returns true where the menu
     * should be displayed and false where it shouldn't
     */
    function show_nav_menu ($cur_page)
    {
        $no_menu_pages = unserialize( constant("NO_MENU_PAGES") );

        // return false if one of the no menu pages matches
        foreach ($no_menu_pages as $page)
        {
            // check if the page is a page on which we want hide menu
            $page_len = strlen( $page ) * -1;
            if ( substr( $cur_page, $page_len) === $page) 
            {
                return false;
            }
        } 

        // no match must need to show that menu
        return true;
    }

    /**
     * generates random n character ascii code for GET on pass reset page. returns
     * the generated string of random characters
     */
    function gen_reset_code( $n )
    {
        $gen_code = "";
        for ($i = 0; $i < $n; ++$i)
        {
            // whether we'll get num, lower char, upper char
            $pluck = mt_rand(0, 2);

            $rand = '';
            // generate random ascii num
            if ($pluck === 0)
                $rand = mt_rand(48, 57);
            // generate random ascii upper num
            else if ($pluck === 1)
                $rand = mt_rand(65, 90);
            // generate random ascii lower num
            else
                $rand = mt_rand(97, 122);

            $gen_code .= chr($rand);
        }
        return $gen_code;
    }


    /**
     * emails the user via $email a url with which they can reset their password. 
     * the expiration of the password reset is set to now + 30 minutes
     * returns false if sending mail fails else true
     */
    function email_pass ($email)
    {
        // generate a 30-char reset code and insert to the db
        $code = gen_reset_code(30);

        // set datetime expiration to now + 30 minutes
        $interval = new DateInterval("PT30M");
        $now = new DateTime();
        $expiry = $now->add($interval);
        // format date for mysql datetime type
        $exp_date = $expiry->format("Y-m-d H:i:s");

        // check if the user already has a sent code?
        $check_query = query("SELECT * FROM reset_pass WHERE email = ?");
        if ($check_query !== false)
        {
            dump($check_query);            
        }

        query("INSERT into reset_pass (email, code, expiration) " .
            "VALUES (?, ?, ?)", $email, $code, $exp_date);

        // build url with which user will be able to reset password
        $url = constant("FORGOT_PASS_BASE_URL");
        $url .= "/reset.php?code=$code"; 

        // send the user an email with the code to reset the password
        $mail = new PHPMailer();
        $mail->IsSMTP();
        // $mail->SMTPDebug = 2; // enable debuggin info

        $mail->SMTPAuth = true;
        $mail->SMTPSecure = "ssl";
        $mail->Host = "smtp.mail.yahoo.com";
        $mail->Port = 465;

        $mail->Username = constant("EMAIL_SENDER");
        $mail->Password = constant("EMAIL_PASS");

        $mail->SetFrom( constant("EMAIL_SENDER") ,
                    "C$50 Finance Bot");

        $mail->AddAddress($email);

        $mail->Subject = "C$50 Finance - Password Reset";

        $mail->Body = "<html><body>Hi, to reset your password, go to " .
            "<a href=\"$url\">$url</a></body></html>";

        $mail->AltBody = "Hi , to reset your password, copy and paste this " .
            " url into your browser: $url";

        return $mail->Send();
    }

?>