<main>

<?php
if (empty($_SESSION['user']) || !isset($_SESSION['user'])) Redirect::to('/index.php?page=home');


$classes = SchoolClass::getClasses();
print_p($classes);

if (isset($_SESSION['user']) && !DataProcessor::registeredValue('class_member', [
    'user_id' => $_SESSION['user']['id']
]) && $_SESSION['user']['role'] == 'teacher') {
    echo "
        <div class='create-group'>
            <a href='/index.php?page=create_class'>Create a class</a>
        </div>
    ";
}


foreach ($classes as $class) {
    echo "
        <div class='class'>
            <p id='class-name'><b>{$class['name']}</b></p>
            <p id='class-member_count'>Members: {$class['member_count']}</p>
    ";

    if (isset($_SESSION['user']) && $_SESSION['user']['role'] == 'teacher') {
        echo "<a href='/index.php?page=class&id={$class['id']}'>Go to this class</a>";
    } else if (isset($_SESSION['user']) && DataProcessor::registeredValue('class_member', [
        'user_id' => $_SESSION['user']['id'], 
        'class_id' => $class['id']
    ])) {
        echo "<a href='/index.php?page=class&id={$class['id']}'>Go to your class</a>";
    }

    echo "
        </div><br>
    ";
}

?>  
</div>

</main>