<?php
/**
 * Created by PhpStorm.
 * User: Krzysztof
 * Date: 30.12.2018
 * Time: 18:50
 */

// insert your fancy crypto currency name here
$crypto_name = 'cryptocurrency';

// enter root domain here ending with /
$root = '';

// establish connection to postgres database
include 'postgresql_connection.php';

/*
    to do
    -
*/
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Chrome, Firefox OS and Opera -->
    <meta name="theme-color" content="#343a40">
    <!-- Windows Phone -->
    <meta name="msapplication-navbutton-color" content="#343a40">
    <!-- iOS Safari -->
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">

    <title><?php echo($crypto_name); ?> wallet</title>

    <link rel="stylesheet" href="bootstrap.min.css">
    <link href="style.css" rel="stylesheet">
</head>

<body>

<header id="TOP">
    <nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark">
        <a class="navbar-brand active" href="<?php echo($root); ?>"><?php echo($crypto_name); ?> wallet</a>

        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse"
                aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarCollapse">
            <ul class="navbar-nav mr-auto">

                <li class="nav-item" id="create_wallet">
                    <a class="text-success nav-link active" href="<?php echo($root); ?>create_wallet.php">Create
                        wallet</a>
                </li>
                <li class="nav-item" id="new_transaction">
                    <a class="text-warning nav-link active"
                       href="<?php echo($root); ?>new_transaction.php">Send <?php echo($crypto_name); ?></a>
                </li>
                <li class="nav-item" id="show_transactions">
                    <a class="text-info nav-link active" href="<?php echo($root); ?>show_transactions.php">Wallet
                        summary</a>
                </li>
                <li class="nav-item" id="delete_wallet">
                    <a class="text-danger nav-link active" href="<?php echo($root); ?>delete_wallet.php">Delete
                        wallet</a>
                </li>

            </ul>
        </div>
    </nav>
</header>

<main class="container">

    <h3 class="mt-5 text-center"><?php echo($crypto_name); ?> welcome page</h3>



</main>

<footer class="footer">
    <div class="container">
        <span class="text-bold"><a class="text-muted" target="_blank"
                                   href="https://github.com/KBTMPL/cryptowallet">github.com/KBTMPL</a> | <?php if ($db_conn) {
                echo('<span class="text-success">PostgreSQL</span>');
            } else {
                echo('<span class="text-danger">PostgreSQL</span>');
            }; ?></span>
    </div>
</footer>

<script src="jquery-3.3.1.js"></script>
<script src="popper.min.js"></script>
<script src="bootstrap.min.js"></script>
</body>
</html>
