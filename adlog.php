<?php
$root = $_SERVER['DOCUMENT_ROOT'];
$server = $_SERVER['HTTP_HOST'];
include "$root/studyfam/classes/admin.php";
include "$root/studyfam/classes/db.php";
session_start();
if (isset($_POST['login'])) {
    $setEmail = $_POST['email'];
    $setPasswd = $_POST['passwd'];
    $admin = new Admin($setEmail, $setPasswd);

    if ($admin->getByQuery('SELECT email FROM ' . $admin->getTable() . ' WHERE email="' . $admin->getEmail() . '"')) {
        $pass = $admin->getByQuery('SELECT passwd FROM ' . $admin->getTable() . ' WHERE email="' . $admin->getEmail() . '"');
        if (password_verify($admin->getPass(), $pass[0]['passwd'])) {
            // set admin variable if admin login data true
            $_SESSION['admin'] = 1;
            
            header("Location: http://$server/studyfam/");
            die();
        } else {
            echo "<script>alert('Incorrect Password!');</script>";
        }
    } else {
        echo "<script>alert('Admin Account does not exist. Try again!!');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin</title>
    <link rel="stylesheet" href="css/main.css">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
    <style>
        body {
            background-color: #ffa500;
            font-family: Arial;
        }
        h1 {
            text-align: center;
            margin-top: 10vh;
        }
        .logo-container {
            margin-top: 0%;
            background-color: white;
        }
        #loginform {
            background-color: white;
            border: solid;
            height: 40vh;
            width: 45vh;
            text-align: left;
            display: inline-block;
            padding: 3vh;

        }
        input {
            margin: 2vh;
        }
        #formdiv {
            text-align: center;
            display: block;
        }
        #loginbutton {
            width: 7vh;
            height: 4vh;
            background-color: #ffa500;
        }
        .inputfields {
            display: block;
            text-align: center;
            margin: 0 auto;
        }
        .regAtr {
            margin-top: 2vh;
        }
        #loginbutton {
            width: 15vh;
            height: 4vh;
            background-color: #ffa500;
            border: none;
            border-radius: 10px;
        }
    </style>
</head>
<body>
    <div class="logo-container">
        <a href="/studyfam">
            <img src="img/st_logov1.png" alt="logo">
        </a>
    </div>
    <h1>ADMIN</h1>
    <div id="formdiv">
    <form id="loginform" action="adlog.php" method="post">
        <div class="regAtr"><i><b>E-mail address:</b></i><input class="inputfields" type="email" name="email" value="" placeholder="max@mustermann.at"><br></div>
        <div class="regAtr"><i><b>Password:</b></i><input class="inputfields" type="password" name="passwd" value="" placeholder="Password.."><br></div>
        <div class="regAtr"><input id="loginbutton" type="submit" name="login" value="Login"></div>
    </form>
    </div>
</body>
</html>