<?php

// Allow every Webpage to use the service
header("Access-Control-Allow-Origin: *");
// Defines the responding Content-Type
header("Content-Type: application/json; charset=UTF-8");
// Defines the supported Methods
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");

$root = $_SERVER['DOCUMENT_ROOT'];

require_once "$root/Studyfam/classes/db.php";

$method = $_SERVER['REQUEST_METHOD'];

$db = new DB();
$tablename = "filesup";

// GET Files for showing in feed
switch ($method) {
    case 'GET':
        $stmt = $db->query('SELECT * FROM ' . $tablename . '');
        $stmt->execute();
        $num = $stmt->rowCount();
        if ($num>0) {
            $files=array();
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

                extract($row);

                $files_item = array(
                    "id" => $id,
                    "university" => $university,
                    "course" => $course,
                    "info" => $info,
                    "ftype" => $ftype,
                    "fname" => $fname,
                    "fdata" => $fdata
                );
                $files[$id] = $files_item;
            }
        
            http_response_code(200);
            echo json_encode($files);
        } else {
            http_response_code(404);

            echo json_encode(
                array("message" => "No files found.")
            );
        }
    break;
}
