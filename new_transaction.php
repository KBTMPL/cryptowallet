<?php
/**
 * Created by PhpStorm.
 * User: Krzysztof
 * Date: 28.12.2018
 * Time: 20:27
 */

include 'conf.php';

/*
    to do
    - transaction history
    - transaction troubleshooting
    - unification of startup config to one file !!!
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

    <h3 class="mt-5 text-center"><?php echo($crypto_name); ?> quick transfer</h3>

    <form method="post">

        <div class="form-group">
            <label for="f_addr_from">Your <?php echo($crypto_name); ?> wallet address:</label>
            <input type="text" class="form-control" id="f_addr_from" name="f_addr_from" required>
        </div>

        <div class="form-group">
            <label for="f_addr_to">Destination address for your <?php echo($crypto_name); ?>:</label>
            <input type="text" class="form-control" id="f_addr_to" name="f_addr_to" required>
        </div>

        <div class="form-group">
            <label for="f_amount">Amount of <?php echo($crypto_name); ?> to send:</label>
            <input type="number" class="form-control" id="f_amount" name="f_amount" step="any" required>
        </div>

        <div class="form-group">
            <label for="password">Your password:</label>
            <input type="password" class="form-control" id="password" name="password" required>
        </div>

        <div class="text-center">
            <button type="submit" name="send_crypto" class="btn btn-default">Send <?php echo($crypto_name); ?></button>
        </div>

    </form>

    <?php
    $addr_from = $_POST['f_addr_from'];
    $addr_to = $_POST['f_addr_to'];
    $amount = $_POST['f_amount'];
    $password = $_POST['password'];

    if (isset($_POST['send_crypto']) and $db_conn) {
        if (strpos($amount, ',') !== false) {
            $amount = str_replace(',', '.', $amount);
        }
        if (is_numeric($amount)) {
            $addr_from_escaped = pg_escape_string($addr_from);
            $addr_to_escaped = pg_escape_string($addr_to);
            $password_hashed = hash("sha512", $password);
            $sending_query_output = pg_query($db_conn, "SELECT send_crypto('" . $amount . "','" . $addr_from_escaped . "','" . $addr_to_escaped . "','" . $password_hashed . "')");
            print_r(pg_fetch_row($sending_query_output));
            /*
            if ($sending_query_output) {
                echo('<h3 class="mt-5 text-center text-success">Transfer succeded!</h3>');
            } else {
                echo('<h3 class="mt-5 text-center text-danger">Something went wrong, please retry ' . $crypto_name . ' transfer</h3>');
            }
            */
        } else {
            echo('<h3 class="mt-5 text-center text-danger">Amount of ' . $crypto_name . ' you want to send is not a number</h3>');
        }

    }
    ?>

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