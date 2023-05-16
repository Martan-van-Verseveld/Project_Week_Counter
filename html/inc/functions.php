<?php

$conn = require_once("db_config.php");

echo ($conn) ? "Connected!" : "Not Connected!";
