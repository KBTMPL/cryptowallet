<?php
/**
 * Created by PhpStorm.
 * User: Krzysztof
 * Date: 28.12.2018
 * Time: 20:26
 */

include 'conf.php';

/*
 *
    to do (optionally)
    - will see

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

<?php include 'header.php'; ?>

<main class="container">

    <h3 class="mt-5 text-center"><?php echo($crypto_name); ?> wallet deletion</h3>

    <form method="post">

        <div class="form-group">
            <label for="addr">Your <?php echo($crypto_name); ?> wallet address:</label>
            <input type="text" class="form-control" id="addr" name="addr" required>
        </div>

        <div class="form-group">
            <label for="password">Your password:</label>
            <input type="password" class="form-control" id="password" name="password" required>
        </div>

        <div class="form-group">
            <label for="delete">Your delete code:</label>
            <input type="password" class="form-control" id="delete" name="delete" required>
        </div>

        <div class="text-center">
            <button type="submit" name="delete_wallet" class="btn btn-default">Delete my <?php echo($crypto_name); ?>
                wallet
            </button>
        </div>

    </form>

    <?php

    $addr = $_POST['addr'];
    $password = $_POST['password'];
    $delete_code = $_POST['delete'];

    if (isset($_POST['delete_wallet']) and $db_conn) {
        $addr_escaped = pg_escape_string(trim($addr));
        $password_hashed = hash("sha512", $password);
        $delete_code_hashed = hash("sha512", $delete_code);

        $select_id = pg_query($db_conn, "SELECT id FROM addresses WHERE address='" . $addr_escaped . "';");
        $id = pg_fetch_assoc($select_id)['id'];
        if (is_numeric($id)) {
            $deletion_query_output = pg_query($db_conn, "DELETE FROM wallets WHERE addresses_id=" . $id . " AND password_hashed='" . $password_hashed . "' AND delete_code_hashed='" . $delete_code_hashed . "'");
            if (pg_affected_rows($deletion_query_output) == 1) {
                echo('<h3 class="mt-5 text-center text-success">Wallet deletion succeeded!</h3>');
            } else {
                echo('<h3 class="mt-5 text-center text-danger">Password and/or delete code is wrong, please retry ' . $crypto_name . ' wallet deletion</h3>');
            }
        } else {
            echo('<h3 class="mt-5 text-center text-danger">There is no ' . $crypto_name . ' wallet with such address</h3>');
        }
    }
    ?>

</main>

<?php include 'footer.php'; ?>

<script src="jquery-3.3.1.js"></script>
<script src="popper.min.js"></script>
<script src="bootstrap.min.js"></script>
</body>
</html>