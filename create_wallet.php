<?php
/**
 * Created by PhpStorm.
 * User: Krzysztof
 * Date: 28.12.2018
 * Time: 20:26
 */

// insert your fancy crypto currency name here
$crypto_name = 'cryptocurrency';

// enter root domain here ending with /
$root = '';

// establish connection to postgres database
$db_conn = pg_connect("host= dbname= user= password=");

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
            <label for="secret_to_encrypt">Your secret passphrase: (UpperCase, LowerCase, Number/SpecialChar and min 8
                Chars)</label>
            <input type="password" class="form-control" id="secret_to_encrypt" name="secret_to_encrypt"
                   placeholder="please remember it as it is necessary to send your <?php echo($crypto_name); ?>"
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

    if (isset($_POST['create_wallet']) and $db_conn) {

        // user defined secret phrase
        $secret_to_encrypt = $_POST['secret_to_encrypt'];
        // check if user did not overridden passphrase rules via frontend
        $is_matching = preg_match('/(?=^.{8,}$)((?=.*\d)|(?=.*\W+))(?![.\n])(?=.*[A-Z])(?=.*[a-z]).*$/', $secret_to_encrypt);

        if ($is_matching === 1) {
            // no need to escape as it will be ciphered
            // $secret_to_encrypt_escaped = pg_escape_string($secret_to_encrypt);
            // generate new key pair
            $res = openssl_pkey_new();
            // Get private key
            openssl_pkey_export($res, $private_key);
            // Get public key
            $public_key = openssl_pkey_get_details($res);
            $public_key = $public_key['key'];
            // encrypt secret with private key
            openssl_private_encrypt($secret_to_encrypt, $secret_encrypted, $private_key);
            $secret_encrypted_hashed = hash("sha256", $secret_encrypted);
            $creation_query_output = pg_query($db_conn, "SELECT create_wallet('" . $secret_encrypted_hashed . "','" . $public_key . "')");

            if ($creation_query_output) {
                echo('<h3 class="mt-5 text-center text-success">Wallet creation succeed!</h3>');
                echo('<label for="private_key">Your private key - necessary to send your crypto currency</label>');
                echo('<input type="text" class="form-control" id="private_key" name="private_key" value="' . $private_key . '" disabled>');
                echo('<label for="public_key">Your public key - check your wallet summary and share it when you want to trade with other people</label>');
                echo('<input type="text" class="form-control" id="public_key" name="public_key" value="' . $public_key . '" disabled>');
                echo('<h3 class="mt-5 text-center text-danger">Make sure to copy and store securely whole content of generated fields!</h3>');
            } else {
                echo('<h3 class="mt-5 text-center text-danger">Something went wrong, please retry ' . $crypto_name . ' wallet creation</h3>');
            }
        } else {
            echo('<h3 class="mt-5 text-center text-danger">Your secret passphrase does not meet conditions stated on our website</h3>');
        }
    }

    ?>

</main>

<footer class="footer">
    <div class="container">
        <span class="text-bold"><a class="text-muted" target="_blank"
                                   href="https://github.com/KBTMPL">github.com/KBTMPL</a> | components status: <?php if ($db_conn) {
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
