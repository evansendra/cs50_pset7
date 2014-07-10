<!DOCTYPE html>

<html>

    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <link href="/css/bootstrap.min.css" rel="stylesheet"/>
        <link href="/css/bootstrap-theme.min.css" rel="stylesheet"/>
        <link href="/css/styles.css" rel="stylesheet"/>

        <?php if (isset($title)): ?>
            <title>C$50 Finance: <?= htmlspecialchars($title) ?></title>
        <?php else: ?>
            <title>C$50 Finance</title>
        <?php endif ?>

        <script src="/js/jquery-1.10.2.min.js"></script>
        <script src="/js/bootstrap.min.js"></script>
        <script src="/js/scripts.js"></script>

    </head>

    <body>

        <div class="container">

            <div id="top">
                <h1>StockUp</h1>

                <?php 
                if ( show_nav_menu( $_SERVER["PHP_SELF"] ) ):
                ?>

                <div id="menu">
                    <ul class="nav nav-pills">
                        <li><a href="/">Portfolio</a></li>
                        <li><a href="buy.php">Buy</a></li>
                        <li><a href="sell.php">Sell</a></li>
                        <li><a href="history.php">History</a></li>
                        <li><a href="quote.php">Get Quote</a></li>
                    </ul>
                </div>

                <?php endif ?>
            </div>

            <div id="middle">
