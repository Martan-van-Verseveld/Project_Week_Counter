<main>
    <?php

    if (!empty($_GET['id'])):


    $dbh = new Dbh();
    $dbh->startConnection();

    $userInfo = new UserInfo();
	$user_info = $userInfo->getUserInfo([
        'id' => $_GET['id']
    ]);

    ?>

    <form action="/src/formHandler.php" method="POST">
        <p><b>[<?= $user_info['id'] ?>]</b> <?= $user_info['firstname'] ." ". $user_info['lastname'] ?></p>
        <input type="hidden" name="id" id="id" value="<?= $user_info['id'] ?>">
        <label for="email">EMail:</label>
        <input type="email" name="email" id="email" value="<?= $user_info['email'] ?>">
        <label for="firstname">Firstname:</label>
        <input type="text" name="firstname" id="firstname" value="<?= $user_info['firstname'] ?>">
        <label for="lastname">Lastname:</label>
        <input type="text" name="lastname" id="lastname" value="<?= $user_info['lastname'] ?>">
        <label for="role">Role</label>
        <select name="role" id="role">
            <option value="student">Student</option>
            <option value="roleless">Other</option>
        </select>
        <input type="submit" name="submit-user_update" id="submit" value="Update user information">
    </form>

    <?php else:

    header('Location: index.php?page=user');
    exit;

    endif; ?>

</main>
