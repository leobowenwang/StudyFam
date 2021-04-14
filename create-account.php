<?php
$root = $_SERVER['DOCUMENT_ROOT'];
$server = $_SERVER['HTTP_HOST'];
include "$root/studyfam/classes/user.php";
include "$root/studyfam/classes/db.php";
session_start();
if (isset($_POST['createaccount'])) {
    $setEmail = $_POST['email'];
    $setPasswd = $_POST['passwd'];
    $Passwdrep = $_POST['passwdrep'];
    $setFname = $_POST['fname'];
    $setLname = $_POST['lname'];
    $setUni = $_POST['uni'];
    $setSemester = $_POST['semester'];
    $setCourse = $_POST['course'];
    // new User object
    $user = new User($setEmail, $setPasswd, $setFname, $setLname,$setUni,$setSemester,$setCourse);

    if (!empty($_POST['email']) && !empty($_POST['passwd']) && !empty($_POST['passwdrep']) && !empty($_POST['fname']) && !empty($_POST['lname']) && !empty($_POST['uni']) && !empty($_POST['semester']) && !empty($_POST['course'])) {
        if ($setPasswd == $Passwdrep) {
            if (!$user->getByQuery('SELECT email FROM '.$user->getTable().' WHERE email="'.$user->getEmail().'"')) {
                if (filter_var($user->getEmail(), FILTER_VALIDATE_EMAIL)) {
                    if (strlen($user->getPass()) >= 6 && strlen($user->getPass()) <= 60) {
                        // create user, when all passed parameters are valid
                        if ($user->create() == true) {
                            // redirect to login
                            header("Location: http://$server/studyfam/userlogin.php");
                            die();
                        }
                    } else {
                        echo "<script>alert('Invalid password.');</script>";
                    }
                } else {
                    echo "<script>alert('Invalid email.');</script>";
                }
            } else {
                echo "<script>alert('User already exists!');</script>";
            }
        } else {
            echo "<script>alert('Password and repeat must be identical!');</script>";
        }
    } else {
        echo "<script>alert('Fill out every field!');</script>";
    }
    
}
// check cookie and auto login if cookie in DB
if (!isset($_SESSION['admin'])) {
    if (User::isLoggedIn()) {
        header("Location: http://$server/studyfam/home.php");
        die();
    }
}
// just for set admin session
if (isset($_SESSION['admin']) && $_SESSION['admin']==1) {
    header("Location: http://$server/studyfam/home.php");
    die();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register</title>
    <link rel="stylesheet" href="css/main.css?v=<?php echo time(); ?>">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/46f21292ec.js" crossorigin="anonymous"></script>
    <style>
        .logo-container {
            margin-top: 0;
            background-color: white;
        }

        body{
            background-color: #ffa500;
        }

        h1 {
            text-align: center;
            margin-top: 6vh;
        }

        input {
            margin: 2vh;
            border: none;
            background: #e6e6e6;
            border-radius: 25px;
            line-height: 1.5;
            font-size: 15px;
            padding: 0 30px 0 50px;
            outline: none;
            transition: all .3s;
        }

        #regdiv {
            text-align: center;
            display: block;
            background-color: #ffa500;
        }
        #regbutton {
            width: 8rem;
            background-color: #ffa500;
            border: solid #ffa500;
            border-radius: 10px;
            text-transform: uppercase;
            color: white;
            font-size: 1.2rem;
            letter-spacing: .15rem;
            padding: 0;
        }
        #regbutton:hover {
            background-color: white;
            color: #ffa500;
        }
        input:focus {
            border: 2px solid #ffa500;
        }

        @media screen and (max-width: 1079px){
            #regform {
                background-color: white;
                border: solid #bd7e01;
                height: auto;
                width: 94vw;
                text-align: center;
                display: inline-block;
                padding: 0 4vw 4vw;
            }

            h1 {
                font-size: 3vh;
                padding-top: 2.5vh;
            }
            #regdiv {
                text-align: center;
                background-color: #ffa500;
                width: 140vw;
            }

            .regAtr {
                margin-top: 0;
            }

            .inputfields{
                text-align: left;
                padding-left: 12vw;
                position: center;
                width: 85vw;
                height: 5vh;
                border: 2px solid #ddd;
                border-radius: 30px;
                margin-left: 0;
                font-size: 3vh;
            }

            .logo-container img {
                height: auto;
                background-color: white;
                width: 140vw;
            }

            #regbutton {
                width: 70vw;
                background-color: #ffa500;
                border: solid #ffa500;
                border-radius: 10px;
                text-transform: uppercase;
                color: white;
                font-size: 3vh;
                letter-spacing: .15rem;
                padding: 0;
                height: 8vh;
            }
        }
        @media screen and (min-width: 1080px){
            #regform {
                background-color: white;
                border: solid #bd7e01;
                height: auto;
                width: 45vh;
                text-align: center;
                display: inline-block;
                padding: 4vh;
            }
        }



    </style>
</head>
<body>
    <div class="logo-container">
        <a href="/studyfam">
            <img src="img/st_logov1.png" alt="logo">
        </a>
    </div>
    <div id="regdiv">
        <h1>REGISTRATION</h1>
        <form id="regform" action="create-account.php" method="post">
            <div class="regAtr"><span class="symbol-input"><i class="fas fa-user-circle"></i></span><input class="inputfields" type="text" name="fname" value="" placeholder="First Name"></div>
            <div class="regAtr"><span class="symbol-input"><i class="fas fa-user-circle"></i></span><input class="inputfields" type="text" name="lname" value="" placeholder="Last Name"></div>
            <div class="regAtr"><span class="symbol-input"><i class="fas fa-university"></i></span><input class="inputfields" type="text" name="uni" value="" placeholder="University"></div>
            <div class="regAtr"><span class="symbol-input"><i class="fas fa-university"></i></span><input class="inputfields" type="number" name="semester" value="" placeholder="Semester" min="1" max=15></div>
            <div class="regAtr"><span class="symbol-input"><i class="fas fa-university"></i></span><input class="inputfields" type="text" name="course" value="" placeholder="Course"></div>
            <div class="regAtr"><span class="symbol-input"><i class="fas fa-envelope"></i></span><input class="inputfields" type="email" name="email" value="" placeholder="Email"></div>
            <div class="regAtr"><span class="symbol-input"><i class="fas fa-lock"></i></span><input class="inputfields" type="password" name="passwd" value="" placeholder="Password"></div>
            <div class="regAtr"><span class="symbol-input"><i class="fas fa-lock"></i></span><input class="inputfields" type="password" name="passwdrep" value="" placeholder="Password repeat"></div>
            <div class="regAtr"><input id="regbutton" type="submit" name="createaccount" value="Sign Up"></div>
        </form>
    </div>
</body>
</html>