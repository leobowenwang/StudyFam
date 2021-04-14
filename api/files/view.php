<?php
$root = $_SERVER['DOCUMENT_ROOT'];
require_once "$root/Studyfam/classes/db.php";

$id = $_GET['id'];
$db = new DB();

// used for displaying the specified file, based on its ID
if (isset($id)) {
    $stmt = $db->query('SELECT * FROM filesup WHERE id=:id');
    $stmt->bindParam(":id", $id);
    if ($stmt->execute()) {
        $row = $stmt->fetchAll();
        header('Content-Type:' . $row[0]['ftype']);
        $data = base64_decode($row[0]['fdata']);
        echo $data;
    }
}
?>
