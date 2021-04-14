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

//array wird mit daten von chatusers.php gefüllt (über ajax POST)
$data = array(
    ':to_user_id'  => $_POST['to_user_id'],
    ':from_user_id'  => $user_id,
    ':chat_message'  => $_POST['chat_message'],
    ':status'   => '1'
);

// query um in datenbank neue row für neue chat nachricht anzulegen
$query = "
INSERT INTO chat_message 
(to_user_id, from_user_id, chat_message, status) 
VALUES (:to_user_id, :from_user_id, :chat_message, :status)
";

$statement = $connect->prepare($query);

if($statement->execute($data))
{
    echo fetch_user_chat_history($user_id, $_POST['to_user_id'], $connect);
}

