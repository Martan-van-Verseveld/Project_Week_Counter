<?php
if (!isset($_SESSION['user'])) Redirect::to('/index.php');

header('Location: https://letmegooglethat.com/?q=Help!');