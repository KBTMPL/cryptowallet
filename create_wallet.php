<?php
/**
 * Created by PhpStorm.
 * User: Krzysztof
 * Date: 28.12.2018
 * Time: 20:26
 */

// insert your fancy crypto currency name here
$crypto_name = 'Grünerium';

// enter root domain here ending with /
$root = '';

// establish connection to postgres database
$db_conn = pg_connect("host=localhost dbname=DB_PROJ_BULANDA user= password=");

/*
    to do (optionally)
    - check if escaped break lines in keys work in open_ssl php lib
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

    <h3 class="mt-5 text-center"><?php echo($crypto_name); ?> wallet creation</h3>


    <form method="post">

        <div class="form-group">
            <label for="password">Your password: (UpperCase, LowerCase, Number/SpecialChar and min 8
                Chars)</label>
            <input type="password" class="form-control" id="password" name="password"
                   placeholder="Make sure it contains all chars from set above"
                   pattern="(?=^.{8,}$)((?=.*\d)|(?=.*\W+))(?![.\n])(?=.*[A-Z])(?=.*[a-z]).*$"
                   required>
        </div>

        <div class="text-center">
            <button type="submit" name="create_wallet" class="btn btn-default">Create <?php echo($crypto_name); ?>
                wallet
            </button>
        </div>

    </form>

    <?php

    function generateRandomString($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    if (isset($_POST['create_wallet']) and $db_conn) {

        // user defined password
        $password = $_POST['password'];
        // check if user did not overridden password rules via frontend
        $is_matching = preg_match('/(?=^.{8,}$)((?=.*\d)|(?=.*\W+))(?![.\n])(?=.*[A-Z])(?=.*[a-z]).*$/', $password);

        if ($is_matching === 1) {
            // no need to escape as it will be hashed
            $password_hashed = hash("sha512", $password);
            // prepare address for wallet
            $epoch = time();
            $random = generateRandomString(64);
            $string = $epoch.$random;
            $address = $crypto_name[0].'#'.hash("sha256", $string);
            $creation_query_output = pg_query($db_conn, "SELECT create_wallet('" . $password_hashed . "','" . $address . "')");

            if ($creation_query_output) {
                echo('<h3 class="mt-5 text-center text-success">Wallet creation succeed!</h3>');
                echo('<label for="public_key">Address of generated wallet:</label>');
                echo('<input type="text" class="form-control" id="address" name="address" value="' . $address . '" disabled>');
                echo('<h3 class="mt-5 text-center text-danger">Make sure to copy and store securely whole content of generated field!</h3>');
            } else {
                echo('<h3 class="mt-5 text-center text-danger">Something went wrong, please retry ' . $crypto_name . ' wallet creation</h3>');
            }
        } else {
            echo('<h3 class="mt-5 text-center text-danger">Your password does not meet conditions stated on our website</h3>');
        }
    }

    ?>

</main>

<footer class="footer">
    <div class="container">
        <span class="text-bold"><a class="text-muted" target="_blank"
                                   href="https://github.com/KBTMPL/cryptowallet">github.com/KBTMPL</a> | components status: <?php if ($db_conn) {
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
