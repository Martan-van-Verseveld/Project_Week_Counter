

    <footer>
        <?php
        
            echo "<div style='background: var(--quaternary); padding: 1rem; margin: 0 25% 1rem 25%; border-radius: 1rem; text-align: left;'>\$_SESSION:";
            print_p($_SESSION);
            echo "</div>";

        ?>
        <?= "&copy; Copyright | 2023-" . date("Y") . "\n" ?>
    </footer>    
</body>
</html>