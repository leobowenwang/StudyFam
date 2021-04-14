<?php

// Allow every Webpage to use the service
header("Access-Control-Allow-Origin: *");
// Defines the responding Content-Type
header("Content-Type: application/json; charset=UTF-8");
// Defines the supported Methods
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");

$root = $_SERVER['DOCUMENT_ROOT'];

require_once "$root/Studyfam/classes/user.php";
require_once "$root/Studyfam/classes/db.php";

$method = $_SERVER['REQUEST_METHOD'];

$user = new User("","","","","","","");
$tablename = "users";

// Own StudyFam API (GET, DELETE, PUT)
switch ($method) {
    case 'GET':
        $stmt = $user->read('SELECT id, fname, lname, email, passwd, university, semester, studycourse FROM ' . $tablename . '');
        $num = $stmt->rowCount();
        if ($num>0) {
            $users=array();
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

                extract($row);

                $user_item = array(
                    "id" => $id,
                    "fname" => $fname,
                    "lname" => $lname,
                    "email" => $email,
                    "passwd" => $passwd,
                    "university" => $university,
                    "semester" => $semester,
                    "studycourse" => $studycourse
                );
                $users[$id] = $user_item;
            }
        
            http_response_code(200);
            echo json_encode($users);
        } else {
            http_response_code(404);

            echo json_encode(
                array("message" => "No users found.")
            );
        } 
        break;
    case 'DELETE':
        $id = $_GET['id'];
        $stmt = $user->delete($id);
        if ($stmt == true) {
            http_response_code(200);
        }
        break;
    case 'PUT':
        $id = $_GET['id'];
        $fname = '';
        $lname = '';
        $email = '';
        $passwd = '';
        $university = '';
        $semester= '';
        $course= '';
        if (isset($_GET['fname'])) {
            $fname = $_GET['fname'];
        }
        if (isset($_GET['lname'])) {
            $lname = $_GET['lname'];
        }
        if (isset($_GET['email'])) {
            $email = $_GET['email'];
        }
        if (isset($_GET['passwd'])) {
            $passwd = $_GET['passwd'];
        }
        if (isset($_GET['uni'])) {
            $university = $_GET['uni'];
        }
        if (isset($_GET['semester'])) {
            $semester = $_GET['semester'];
        }
        if (isset($_GET['course'])) {
            $course = $_GET['course'];
        }
        $stmt = $user->update($id,$fname,$lname,$email,$passwd,$university,$semester,$course);
        break;
}
