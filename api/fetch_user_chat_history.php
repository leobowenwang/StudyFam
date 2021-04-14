<?php

include('database_connection.php');

$root = $_SERVER['DOCUMENT_ROOT'];
include "$root/studyfam/classes/db.php";
$db = new DB();
if (isset($_COOKIE['SNID'])) {
    $hashtoken = sha1($_COOKIE['SNID']);
    $statement = $db->query('SELECT userid FROM login_tokens WHERE token=:token');
    $statement->bindParam(":token", $hashtoken);
    if ($statement->execute()) {
        $userid = $statement->fetchAll();
    }
}
$user_id = $userid[0]['userid'];

echo fetch_user_chat_history($user_id, $_POST['to_user_id'], $connect);