<?php
/**
 * Created by PhpStorm.
 * User: Krzysztof
 * Date: 30.12.2018
 * Time: 18:50
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

    <?php

    $result_id = $_GET['id'];
    $result_text = 'Nothing interesting here';

    switch ($result_id) {
        case 1:
            $result_text = "You can not send 0 or less crypto in a transfer";
            break;
        case 2:
            $result_text = "There is no such source wallet";
            break;
        case 3:
            $result_text = "There is no such destination wallet";
            break;
        case 4:
            $result_text = "Your password and or wallet combination does not match database entries";
            break;
        case 5:
            $result_text = "Your current balance is too low to proceed with this transfer";
            break;
        case 6:
            $result_text = "Transfer was aborted please try again";
            break;
        case 7:
            $result_text = "Transfer successfully completed";
            break;
    }

    echo('<h3 class="mt-5 text-center text-info">' . $result_text . '</h3>');

    ?>

</main>

<?php include 'footer.php'; ?>

<script src="jquery-3.3.1.js"></script>
<script src="popper.min.js"></script>
<script src="bootstrap.min.js"></script>
</body>
</html>