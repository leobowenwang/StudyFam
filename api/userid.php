<?php
$root = $_SERVER['DOCUMENT_ROOT'];
include "$root/studyfam/classes/db.php";
$db = new DB();

// GET ID of currently logged in User
if (isset($_COOKIE['SNID'])) {
    $hashtoken = sha1($_COOKIE['SNID']);
    $statement = $db->query('SELECT userid FROM login_tokens WHERE token=:token');
    $statement->bindParam(":token", $hashtoken);
    if ($statement->execute()) {
        $userid = $statement->fetchAll();
        // JSON can be accessed when making a HTTP GET Request
        echo json_encode($userid);
        return true;
    }
}
return false;