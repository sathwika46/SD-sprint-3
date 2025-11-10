<?php
$DB_HOST = '127.0.0.1';
$DB_USER = 'webuser';
$DB_PASS = '12345';
$DB_NAME = 'ebiz_demo';

$mysqli = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);
if ($mysqli->connect_errno) {
    die('DB Connect Error: ' . $mysqli->connect_error);
}
$mysqli->set_charset('utf8mb4');
?>
