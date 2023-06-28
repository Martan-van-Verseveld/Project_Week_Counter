<main>
<?php

if (empty($_SESSION['user']) || !isset($_SESSION['user'])) Redirect::to('/index.php?page=home');

$classId = DataProcessor::sanitizeData($_GET['id']);
$isAdmin = $_SESSION['user']['role'] == 'teacher';

$class = SchoolClass::getClass($classId);
$members = SchoolClass::getClassMembers($classId);

echo "
    <div id='class-container'>
";
if ($isAdmin) {
    echo "
        <a href='/index.php?page=class_edit&id={$class['id']}'>Edit this class</a>
        <a href='/index.php?page=class_add&id={$class['id']}'>Put students in class</a>
    ";
}
echo "
        <div id='group-info'>
            <p id='group-name'>{$class['name']}</p>
            <p id='group-teacher'>Teacher: {$class['teacher']}</p>
        </div>
        <div id='users-container'>
";
foreach ($members as $member) {
    if ($isAdmin && $member['role'] != 'teacher') {
        echo "
            <form action='/src/inc/formHandler.inc.php' method='POST'>
                <input type='hidden' name='action' value='class-remove'>
                <input type='hidden' name='user_id' value='{$member['id']}'>
                <input type='hidden' name='class_id' value='{$class['id']}'>
                <input type='submit' value='Remove from class'>
            </form>
        ";
    }
    echo "
        <hr>
        <p id='user-email'>{$member['email']}</p>
        <p id='user-name'>{$member['firstname']} {$member['lastname']}</p>
        <br>
    ";
}
echo "</div>";

?>
</main>