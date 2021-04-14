<?php
session_start();
$root = $_SERVER['DOCUMENT_ROOT'];
$server = $_SERVER['HTTP_HOST'];
include "$root/studyfam/classes/user.php";
include "$root/studyfam/classes/db.php";

$db = new DB();
if (isset($_COOKIE['SNID'])) {
    $hashtoken = sha1($_COOKIE['SNID']);
    $statement = $db->query('SELECT userid FROM login_tokens WHERE token=:token');
    $statement->bindParam(":token", $hashtoken);
    if ($statement->execute()) {
        $userid = $statement->fetchAll();
        // get logged user ID
        $userid = $userid[0]['userid'];
    }
}

// check cookie and redirect if not logged in
if (!User::isLoggedIn()) {
    header("Location: http://$server/studyfam/");
    die();
}

if (isset($_POST['changepw'])) {
    $setPasswd = $_POST['passwd'];
    $setnewPasswd = $_POST['newpasswd'];
    $user = new User("",$setPasswd, "", "", "", "","");
    $loggeduser = $user->getByQuery('SELECT * FROM ' . $user->getTable() . ' WHERE id="' . $userid . '"');
    if ($user->getByQuery('SELECT passwd FROM ' . $user->getTable() . ' WHERE fname="' . $loggeduser[0]['fname'] . '"')) {
        $pass = $user->getByQuery('SELECT passwd FROM ' . $user->getTable() . ' WHERE fname="' . $loggeduser[0]['fname'] . '"');
        if (password_verify($user->getPass(), $pass[0]['passwd'])) {
                // update password
                $user->update($userid,"","","",$setnewPasswd,"", "", "");
            }
        else echo "<script>alert('Password change failed')</script>";
        }
    else {echo "<script>alert('Internal Error')</script>";}
}

if (!User::isLoggedIn()) {
    header("Location: http://$server/studyfam/");
    die();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Profile</title>
    <link rel="stylesheet" href="css/main.css">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/46f21292ec.js" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <style>


        .pwchange{
            margin-top: 2vh;
            margin-left: 2vw;
            font-size: 18px;
        }
        .changebutton{
            margin-left: 2vw;
        }

        .settings{
            margin-top: 2vh;
        }

        .info {
            height: 100%;
            width: 45vw;
            margin-left: auto;
            margin-right: auto;
            padding: 20vh 3vh 3vh;
            font-size: 20px;
        }

        #theme {
            display:none;
        }

        @media screen and (max-width: 1079px){
            .infline{
                width: 100%;
                margin-bottom: 1.5rem;
                margin-top: 2vh;
            }
            .infline span {
                font-size: 3vh;
                width: 2vw;

                border-right: 4px;
                /* for responsive use table-cell display */
                display: table-cell;
            }
            .infline h7 {
                font-size: 3vh;
                height: 100%;
                border: none;
                background: #e6e6e6;
                color: black;
                border-radius: 25px;
                line-height: 1.5;
                padding: 0 30px 0 30px;
                outline: none;
            }
        }

        @media screen and (min-width: 1080px){
            .infline{
                width: 100%;
                margin-bottom: 1vh;
            }
            .infline span {
                font-size: 17px;
                width: 10rem;

                border-right: 4px;
                /* for responsive use table-cell display */
                display: inline-block;
            }
            .infline h7 {
                font-size: 17px;
                width: 15rem;
                height: 100%;
                border: none;
                background: #e6e6e6;
                color: black;
                border-radius: 25px;
                line-height: 1.5;
                padding: 0 30px 0 30px;
                outline: none;
            }
        }


    </style>
</head>
<body>
<div class="logo-container-logged">
    <a href="/studyfam"><img src="img/st_logov1.png" alt="logo"></a>
</div>
<div class="sidebar">
    <div class="nav"><i class="fas fa-bars"></i>
        <h2 id="hello"></h2>
        <div class="dropdown-content">
                <a href="home.php" class="login">Home</a>
                <a href="profile.php" class="login">Profile</a>
                <a href="chatusers.php" class="login">Chat</a>
                <a href="search.php" class="login">User Search</a>
                <a href="contactform.php" class="login">Contact</a>
                <a href="logout.php" class="login">Logout</a>
        </div>
    </div>
</div>
<div class="info">
    <div class="profilediv">
        <form id="infosheet" action="profile.php" method="post">
            <div class="infline"><span>Name:</span><h7 id="name"></h7></div>
            <div class="infline"><span>University:</span><h7 id="university"></h7></div>
            <div class="infline"><span>Semester:</span><h7 id="semester"></h7></div>
            <div class="infline"><span>Course:</span><h7 id="course"></h7></div>
            <div class="infline"><span>E-Mail address:</span><h7 id="email"></h7></div>
            <div class="infline" style="border-top: 2px solid black;"><div class="settings">Change Password:</div></div>
            <div class="pwchange"><span>Password:</span><p><input class="inputfieldspw" type="password" name="passwd" value="" placeholder="Password"></p></div>
            <div class="pwchange"><span>New Password:</span><p><input class="inputfieldspw" type="password" name="newpasswd" value="" placeholder="New Password"></p></div>
            <div class="changebutton"><input id="changebutton" type="submit" name="changepw" value="Change"></div>
            <div class="infline">Choose mode:</div>
            <button class="themebutton" onclick="themeToggle(); innerText()">Toggle dark mode</button><div id="theme"></div>
        </form>
    </div>
</div>
    <tr>
        <td></td>
    </tr>
</div>
<script src="js/main.js"></script>
</body>
</html>
