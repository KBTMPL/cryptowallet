<?php
/**
 * Created by PhpStorm.
 * User: Krzysztof
 * Date: 28.12.2018
 * Time: 20:26
 */

include 'conf.php';

/*
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

    function generateRandomString($length)
    {
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
            $string = $epoch . $random;
            $address = $crypto_name[0] . '#' . hash("sha256", $string);
            $delete_code = generateRandomString(8);
            $delete_code_hashed = hash("sha512", $delete_code);
            $creation_query_output = pg_query($db_conn, "SELECT create_wallet('" . $password_hashed . "','" . $address . "','" . $delete_code_hashed . "');");

            if ($creation_query_output) {
                echo('<h3 class="mt-5 text-center text-success">Wallet creation succeeded!</h3>');
                echo('<div class="form-group"><label for="public_key">Address of generated wallet:</label>');
                echo('<input type="text" class="form-control" id="address" name="address" value="' . $address . '" disabled>');
                echo('<label for="public_key">Delete code of generated wallet:</label>');
                echo('<input type="text" class="form-control" id="delete_code" name="delete_code" value="' . $delete_code . '" disabled></div>');
                echo('<h3 class="mt-5 text-center text-danger">Make sure to copy and store securely whole content of generated fields!</h3>');
            } else {
                echo('<h3 class="mt-5 text-center text-danger">Something went wrong, please retry ' . $crypto_name . ' wallet creation</h3>');
            }
        } else {
            echo('<h3 class="mt-5 text-center text-danger">Your password does not meet conditions stated on our website</h3>');
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