<?php
session_start();
include ('./classes/user.php');
$server = $_SERVER['HTTP_HOST'];
// check cookie and redirect if not logged in
if (!isset($_SESSION['admin'])) {
    if (!User::isLoggedIn()) {
        header("Location: http://$server/studyfam/");
        die();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Search Users</title>
    <link rel="stylesheet" href="css/main.css">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/46f21292ec.js" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <style>

        .register:hover {
            font-size: 1.2rem;
        }
        .upload-btn {
            margin-top: 1.5%;
            text-align: center;
            display: flow-root;
        }

        .material a:hover {
            color:black;
            background-color: white;
        }

        #previewbtn {
            width: 100%;
            display: inline-block;
            text-align: center;
            margin-bottom: 1rem;
        }

        #universitytag {
            position: absolute;
            margin-top: 2px;
            margin-left: 27px;
        }
        #coursetag {
            position: absolute;
            margin-top: 2px;
            margin-left: 27px;
        }
        #infotag {
            position: absolute;
            margin-top: 2px;
            margin-left: 27px;
        }
        .materialcontent {
            float: left;
            width: 98%;
            color: black;
        }
        .materialtype{
            float: right;
            width: 30%;
            height: auto;
            text-align: center;
        }
        .typeimg {
            margin-top: 22px;
            text-align: right;
            width: 60%;
        }
        #feed h4 {
            text-align: center;
            border: 2px solid #ffa500;
            padding: 10px;
        }

        @media screen and (max-width: 1079px){
            #feed {
                font-size: 2vh;
                margin-top: 2px;
            }
            .material h5 {
                word-break: break-all;
                margin-left: 5vw;
                background-color: white;
                border-radius: 25px;
                line-height: 1.5;
                padding-left: 30px;
                width: 90%;
                height: 100%;
                color: black !important;
            }
            #nametag {
                position: absolute;
                margin-top: 3vh;
                margin-left: 27px;
            }
            .material a {
                text-decoration: none;
                padding: .5rem 1rem;
                border-radius: 7rem;
                background-color: #ffa500;
                color: white;
                height: 100%;
                letter-spacing: .15rem;
            }

            .content-block {
                float: left;
                margin-top: 6vw;
                height: 100%;
                margin-left: 2vw;
            }
            #searchusers {
                text-decoration: none;
                outline: none;
                width: 90%;
                height: 5vh;
                padding-bottom: 1vh;
                border: 2px solid #ddd;
                border-radius: 30px;
                font-size: 4vh;
                text-align: center;
            }

            #searchbtn{
                width: 12vw;
                height: 5vh;
                font-size: 4vh;
                margin-left: 1vw;
            }

            .fas.fa-search{
                margin-top:0 !important;
            }

            .material{
                font-size: 3vh;
                box-sizing: border-box;
                padding-top: 10px;
                margin-bottom: 2rem;
                margin-left: 2vw;
                border-radius: 15px;
                box-shadow: 4px 5px 10px 5px rgba(0,0,0,0.2);
                background: #eeeeee;
                height: 45vh;
            }
            #fheading {
                font-size: 50px;
            }
            #userresults h4 {
                font-size: 50px;
            }
        }
        @media screen and (min-width: 1080px){
            #feed {
                font-size: 17px;
            }
            .material h5 {
                margin-left: 18px;
                background-color: white;
                border-radius: 25px;
                line-height: 1.5;
                padding-left: 30px;
                width: 97%;
                color: black !important;
            }
            #nametag {
                position: absolute;
                margin-top: 26px;
                margin-left: 27px;
            }
            .material a {
                text-decoration: none;
                padding: .5rem 1rem;
                border-radius: 7rem;
                font-size: 17px;
                background-color: #ffa500;
                color: white;
                height: 100%;
                letter-spacing: .15rem;
            }
            .content-block {
                float: left;
                width: 50%;
                margin-top: 6vw;
                height: 100%;
            }
            .register:after {
                content: '';
                position: absolute;
                bottom: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background-color: #ffa500;
                border-radius: 10rem;
                z-index: -2;
            }
            .register:before {
                content: '';
                position: absolute;
                bottom: 0;
                left: 0;
                width: 0%;
                height: 100%;
                background-color: #bd7e01;
                transition: all .3s;
                border-radius: 10rem;
                z-index: -1;
            }
            .register:hover {
                color: #fff;
                font-size: 1.4rem;
            }
            .register:hover:before {
                width: 100%;
            }
            #searchusers {
                text-decoration: none;
                outline: none;
                width: 100%;
                border: 2px solid #ddd;
                border-radius: 30px;
                font-size: 17px;
                text-align: center;
            }

            .material {
                padding-top: 10px;
                margin-bottom: 2rem;
                border-radius: 15px;
                box-shadow: 4px 5px 10px 5px rgba(0,0,0,0.2);
                background: #eeeeee;
                height: 250px;
            }
        }

    </style>
</head>
<body>
    <div id="container">
        <div class="logo-container-logged">
                <a href="/studyfam"><img src="img/st_logov1.png" alt="logo"></a>
                <?php if (isset($_SESSION['admin']) && $_SESSION['admin']== 1) { ?>
                <div class="nav">
                    <a href="manage.php" style="color: white;font-size:1.5rem;">Manage Users</a>
                </div>
                <?php } ?>
        </div>
        <div class="content-block">
            <div class="search-bar">
                <input type="text" id="searchusers" placeholder="Search users by university.." value="" autocomplete="off">
                <a id="searchbtn" onclick="SearchUsers()"><i class="fas fa-search" style="margin-top:20%;"></i></a>
            </div>
            <div id="userresults"></div>
        </div>
        <div class="sidebar">
            <div class="nav"><i class="fas fa-bars"></i><h2 id="hello"></h2>
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
    </div>
    <script src="js/main.js"></script>
</body>
</html>
