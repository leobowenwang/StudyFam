<?php
$root = $_SERVER['DOCUMENT_ROOT'];
include('database_connection.php');
include "$root/studyfam/classes/db.php";

$db = new DB();
$hashtoken = sha1($_COOKIE['SNID']);
$statement = $db->query('SELECT userid FROM login_tokens WHERE token=:token');
$statement->bindParam(":token", $hashtoken);
if ($statement->execute()) {
    $userid = $statement->fetchAll();
}

$user_id = $userid[0]['userid'];

$query = "
UPDATE login_details 
SET last_activity=now() 
WHERE user_id ='$user_id'
";

$statement = $connect->prepare($query);

($statement->execute());

