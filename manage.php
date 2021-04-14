<?php
session_start();
include ('./classes/user.php');
$server = $_SERVER['HTTP_HOST'];
// check if admin session is set (only admins can access this page)
if (!isset($_SESSION['admin'])) {
    header("Location: http://$server/studyfam/");
    die();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Users</title>
    <link rel="stylesheet" href="css/main.css">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/46f21292ec.js" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <style>
        .logo-container-logged{
            background-color: white;
            float: left;
            width: 25%;
            margin-top: 0;
            height: 14%;
        }
        .logo-container-logged a img{
            max-width: 73%;
            max-height: 73%;
        }
        .content-block {
            background-color: white;
            float: left;
            width: 50%;
            margin-top: 6vw;
            height: 100%;

        }
        .sidebar {
            float: right;
        }
    </style>
</head>
<body>
    <div id="container">
        <div class="logo-container-logged">
                <a href="/studyfam"><img src="img/st_logov1.png" alt="logo"></a>
        </div>
        <div class="content-block">
            <button id="basicbtn">Basic Info</button>
            <button id="unibtn">University Info</button>
            <table id="manage-users">
                <tr>
                    <th></th>
                    <th class="thead">ID</th>
                    <th class="thead">Firstname</th>
                    <th class="thead">Lastname</th>
                    <th class="thead">Email</th>
                    <th class="thead">Password</th>
                    <th class="thead"></th>
                </tr>
            </table>
        </div>
        <div class="sidebar">
            <div class="nav"><i class="fas fa-bars"></i><h2 id="hello"></h2>
                <div class="dropdown-content">
                    <a href="home.php" class="login">Home</a>
                    <a href="profile.php" class="login">Profile</a>
                    <a href="chatusers.php" class="login">Chat</a>
                    <a href="search.php" class="login">User Search</a>
                    <a href="#" class="login">Contact</a>
                    <a href="logout.php" class="login">Logout</a>
                </div>
            </div>
        </div>
    </div> 
    <script src="js/manage.js"></script>
</body>
</html>