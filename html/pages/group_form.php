<main>
    <?php

    if (!empty($_GET['id'])):


    $dbh = new Dbh();
    $dbh->startConnection();

    $groupInfo = new GroupInfo();
	$group_info = $groupInfo->getGroupInfo([
        'id' => $_GET['id']
    ]);

    ?>

    <form action="/src/formHandler.php" method="POST">
        <p><b>[<?= $group_info['id'] ?>]</b> <?= $group_info['name'] ?></p>
        <input type="hidden" name="id" id="id" value="<?= $group_info['id'] ?>">
        <label for="name">Group name:</label>
        <input type="text" name="name" id="name" value="<?= $group_info['name'] ?>">
        <label for="description">Description:</label>
        <textarea name="description" id="description"><?= $group_info['description'] ?></textarea>
        <input type="submit" name="submit-group_update" id="submit" value="Update group information">
    </form>

    <?php else: ?>

    <form action="/src/formHandler.php" method="POST">
        <label for="name">Group name:</label>
        <input type="text" name="name" id="name" placeholder="Pick a cool group name">
        <label for="description">Description:</label>
        <textarea name="description" id="description" placeholder="Type something about the group"></textarea>
        <input type="submit" name="submit-group_create" id="submit" value="Create a new group">
    </form>

    <?php endif; ?>

</main>
