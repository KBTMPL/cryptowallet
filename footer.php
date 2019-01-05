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