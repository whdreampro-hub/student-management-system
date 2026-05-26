<?php
function sanitizeInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

function generateRandomString($length = 10) {
    return substr(str_shuffle(str_repeat($x='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length/strlen($x)))), 1, $length);
}

function formatDate($date, $format = 'd/m/Y') {
    return date($format, strtotime($date));
}

function getAge($birthdate) {
    return date_diff(date_create($birthdate), date_create('now'))->y;
}
?>