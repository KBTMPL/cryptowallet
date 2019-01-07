<?php
/**
 * Created by PhpStorm.
 * User: Krzysztof
 * Date: 28.12.2018
 * Time: 20:27
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

    <h3 class="mt-5 text-center"><?php echo($crypto_name); ?> wallet lookup</h3>

    <form method="post">

        <div class="form-group">
            <label for="addr"><?php echo($crypto_name); ?> wallet address to lookup:</label>
            <input type="text" class="form-control" id="addr" name="addr" required>
        </div>

        <div class="text-center">
            <button type="submit" name="check_wallet" class="btn btn-default">Check this <?php echo($crypto_name); ?>
                wallet
            </button>
        </div>

    </form>

    <?php

    // user chosen wallet
    $addr = $_POST['addr'];
    $addr_escaped = pg_escape_string(trim($addr));

    if (isset($_POST['check_wallet']) and $db_conn) {

        $lookup_query_output = pg_query($db_conn, "SELECT id FROM addresses WHERE address = '" . $addr_escaped . "';");
        $addr_id = pg_fetch_assoc($lookup_query_output)['id'];

        // during development of this code creator was interrupted and forced to lower bass volume of his audio system as he was listening to Al Bano & Romina Power - Felicità for 1337 time that night

        if (is_numeric($addr_id)) {

            $amount_query_output = pg_query($db_conn, "SELECT id, cryptocurrency_amount as amount FROM wallets WHERE addresses_id = " . $addr_id . ";");
            $row = pg_fetch_assoc($amount_query_output);
            $id = $row['id'];
            $amount = $row['amount'];

            if (is_numeric($id)) {
                echo('<h3 class="mt-5 text-center">Current ' . $crypto_name . ' amount: ' . $amount . '</h3><br />');
                $history_query_output = pg_query($db_conn, "SELECT timestamp as ts, (SELECT a.address FROM addresses a WHERE a.id = t.id_from) as a_from, (SELECT a.address FROM addresses a WHERE a.id = t.id_to) as a_to, t.tr_amount as amount FROM transactions t WHERE t.id_from = " . $addr_id . " OR t.id_to = " . $addr_id . " ORDER BY ts ASC;");
                if ($history_query_output) {
                    echo('<div class="table-responsive"><table class="table table-striped"><thead><tr><th scope="col">Date</th><th scope="col">From</th><th scope="col">To</th><th scope="col">Amount</th></tr></thead><tbody>');
                    while ($history_row = pg_fetch_assoc($history_query_output)) {
                        $ts = $history_row["ts"];
                        $a_from = $history_row["a_from"];
                        $a_to = $history_row["a_to"];
                        $tr_amount = $history_row["amount"];

                        if ($a_from === $addr_escaped) {
                            $a_from = '<b>' . $a_from . '</b>';
                            $flow_flag = false;
                        }

                        if ($a_to === $addr_escaped) {
                            $a_to = '<b>' . $a_to . '</b>';
                            $flow_flag = true;
                        }

                        if ($flow_flag) {
                            $tr_amount = '<b class="text-success">' . $tr_amount . '</b>';
                        } else {
                            $tr_amount = '<b class="text-danger">' . $tr_amount . '</b>';
                        }

                        echo('<tr><td>' . date("d-m-Y H:i:s", $ts) . '</td><td>' . $a_from . '</td><td>' . $a_to . '</td><td>' . $tr_amount . '</td></tr>');
                    }
                    echo('</tbody></table></div>');
                } else {
                    echo('<h3 class="mt-5 text-center text-danger">This ' . $crypto_name . ' address has no transfer history</h3>');
                }
            } else {
                echo('<h3 class="mt-5 text-center">There is no ' . $crypto_name . ' wallet associated to this address</h3>');
            }
        } else {
            echo('<h3 class="mt-5 text-center text-danger">There is no such ' . $crypto_name . ' address</h3>');
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