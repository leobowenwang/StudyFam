<?php

//fetch_user.php
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

$query = "
SELECT * FROM users
WHERE id != '$user_id'
";

$statement = $db->query($query);

$statement->execute();

$result = $statement->fetchAll();

$output = '
<table>
 <tr>
  <th>Username</th>
  <th>Status</th>
  <th>Chat</th>
 </tr>
';

foreach($result as $row)
{
    $status = '';
    $current_timestamp = strtotime(date("Y-m-d H:i:s") . '- 10 second');
    $current_timestamp = date('Y-m-d H:i:s', $current_timestamp);
    $user_last_activity = fetch_user_last_activity($row['id'], $connect);
    if($user_last_activity > $current_timestamp)
    {
        $status = '<span class="label label-success">Online</span>';
    }
    else
    {
        $status = '<span class="label label-danger">Offline</span>';
    }
    $output .= '
    <tr>
    <td>'.$row['fname'].' '.$row['lname'].'</td>
    <td>'.$status.'</td>
    <td><button type="button" class="btn btn-xs  start_chat" data-touserid="'.$row['id'].'" data-toname="'.$row['fname'].'">Start Chat</button></td>
    </tr>
    ';
}

$output .= '</table>';

echo $output;



