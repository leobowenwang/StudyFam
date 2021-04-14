<?php
session_start();
include('./classes/user.php');
$server = $_SERVER['HTTP_HOST'];
// check cookie and redirect if not logged in
if (!isset($_SESSION['admin'])) {
    if (!User::isLoggedIn()) {
        header("Location: http://$server/studyfam/");
        die();
    }
}
?>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Home</title>
    <link rel="stylesheet" href="css/main.css">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/46f21292ec.js" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <style>
        td, th {
            text-align: left;
            width: 30vh;
        }

        input {
            width: 38vh;
            height: 4vh;
        }

        textarea {
            width: 38vh;
        }

        button {
            width: 15vh;
            height: 4vh;
            border-radius: 30px;
        }


        #contactdiv {
            padding-top: 20vh;
            text-align: center;
        }

        #formdiv {
            height: 50vh;
            background-color: #cccccc;
        }
        table {
            padding: 3rem;
        }

        @media screen and (max-width: 1079px){

            table {
                padding: 0;
            }

            h1, h3 {
                font-size: 2.3vh;
                margin-left: 3vw;
            }

            td {
                font-size: 2vh;
                text-align: left;
                width: 30vh;
                padding-left: 2vw;
            }



            th{
                display:none;
            }

            input {
                width: 90vw;
                height: 4vh;
                font-size: 2vh;
                padding-left: 1vw;
                padding-top: 1vh;
                border-radius: 25px;
                margin-top: 2vh;
            }

            textarea {
                width: 90vw;
                font-size: 3vh;
                border-radius: 25px;
            }

            button {
                width: 15vh;
                height: 4vh;
                font-size: 2vh;
            }

            #formdiv {
                width: 104vw;
                border-radius: 25px;
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

<div id="contactdiv">
<p align="center"><h1>Contact Formular</h1></p>
<p text-align="center"><h3>If you need help or have any issues, fill out this form and an admin will respond immediately!</h3></p>
<br><br>
    <div id="formdiv">
<form method="post" name="emailform" action="./contactform_send.php">
    <table id="contacttable" align="center">
        <tr>
    <th>Enter your Name:</th>
    <td><input type="text" name="name" placeholder="Your Name"></td>
        </tr>
        <tr>
            <th>Enter your Email-address:</th>
            <td><input type="text" name="email" placeholder="Your E-mail Address"></td>
        </tr>
        <tr>
            <th>Tell us your issues:</th>
            <td><textarea name="text" rows="8" cols="50"></textarea></td>
        </tr>
    </table>
    <br><br>
    <button type="submit">Submit</button>
</form>
</div>
</div>

<script src="js/main.js"></script>

</body>

</html>
