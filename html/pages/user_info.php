<main>
    <?php
    
    if (!isset($_SESSION['user'])) die("Access denied!");

    $info = User::getUser($_GET['id']);
    print_p($info);

    ?>
</main>