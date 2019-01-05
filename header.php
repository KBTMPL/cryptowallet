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
                        lookup</a>
                </li>
                <li class="nav-item" id="delete_wallet">
                    <a class="text-danger nav-link active" href="<?php echo($root); ?>delete_wallet.php">Delete
                        wallet</a>
                </li>

            </ul>
        </div>
    </nav>
</header>