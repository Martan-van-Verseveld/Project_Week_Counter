<?php

$classSelect = (isset($_GET['c'])) ? DataProcessor::sanitizeData($_GET['c']) : null;
$orderType = (isset($_GET['o'])) ? DataProcessor::sanitizeData($_GET['o']) : null;
$scores = Score::getClassScores($orderType, $classSelect);
$groups = Group::getClassGroups($classSelect);

// print_p($scores);

if (!empty($_SESSION['user']) && DataProcessor::registeredValue('user', [
    'id' => $_SESSION['user']['id'],
    'role' => 'teacher'
])) {
    echo "
        <div class='score-add'>
            <select name='groups' id='selector'>
    ";

    foreach ($groups as $group) {
        echo "<option value='{$group['id']}'>{$group['name']}</option>";
    }

    echo "
            </select>
            <input type='submit' value='Add score' id='selector-submit'>
        </div>
    ";
}

echo "
    <a href='/index.php?page=scores&o=scoreDESC'>Normal group scores</a><br>
    <div class='score-add'>
        <select name='groups' id='order-selector'>
            <option value='scoreDESC' ". ($orderType == "scoreDESC" ? "selected" : "") .">score 9-0</option>
            <option value='scoreASC' ". ($orderType == "scoreASC" ? "selected" : "") .">score 0-9</option>
            <option value='nameAZ' ". ($orderType == "nameAZ" ? "selected" : "") .">name A-Z</option>
            <option value='nameZA' ". ($orderType == "nameZA" ? "selected" : "") .">name Z-A</option>
            <option value='descAZ' ". ($orderType == "descAZ" ? "selected" : "") .">description A-Z</option>
            <option value='descZA' ". ($orderType == "descZA" ? "selected" : "") .">description Z-A</option>
        </select>
        <input type='submit' value='Change layout' id='order-selector-submit'>
    </div>
";

$classes = SchoolClass::getClasses();
echo "
    <select name='classes' id='class-selector'>
";
foreach ($classes as $class) {
    echo "<option value='{$class['id']}' ". ($orderType == $class['id'] ? "selected" : "") .">{$class['name']}</option>";
}
echo "
    </select>
    <input type='submit' value='Change class view' id='class-selector-submit'>
";

foreach ($scores as $score) {
    echo "
        <div class='group'>
    ";

    if (!empty($_SESSION['user']) && DataProcessor::registeredValue('group_member', [
        'user_id' => $_SESSION['user']['id'], 
        'group_id' => $score['group_id']
    ])) {
        echo "<p id='group-name'><a href='/index.php?page=group&id={$score['group_id']}'>{$score['name']} [Your group]</a></p>";
    } else {
        echo "<p id='group-name'><a href='/index.php?page=group&id={$score['group_id']}'>{$score['name']}</a></p>";
    }

    echo "
            <p id='group-rank'>rank: {$score['rank']}</p>
            <p id='group-score'>Score: {$score['score']}</p>
            <p id='group-member_count'>Members: {$score['member_count']}</p>
        </div><br>
    ";
}

?>

<script>
    c_submit = document.getElementById('class-selector-submit');
    c_select = document.getElementById('class-selector');

    o_submit = document.getElementById('order-selector-submit');
    o_select = document.getElementById('order-selector');
    if (o_submit && o_select) o_submit.addEventListener('click', function(e) {
        event.preventDefault();
        console.log(o_select.value);
        window.location.href = `/index.php?page=scores_class&c=${c_select.value}&o=${o_select.value}`;
    });

    if (c_submit && c_select) c_submit.addEventListener('click', function(e) {
        event.preventDefault();
        console.log(c_select.value);
        window.location.href = `/index.php?page=scores_class&c=${c_select.value}&o=${o_select.value}`;
    });
</script>
