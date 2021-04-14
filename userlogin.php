<?php
$root = $_SERVER['DOCUMENT_ROOT'];
$server = $_SERVER['HTTP_HOST'];
include "$root/studyfam/classes/user.php";
include "$root/studyfam/classes/db.php";
session_start();
if (isset($_POST['login'])) {
    $setEmail = $_POST['email'];
    $setPasswd = $_POST['passwd'];
    $user = new User($setEmail, $setPasswd, "", "", "", "", "");

    if ($user->getByQuery('SELECT email FROM ' . $user->getTable() . ' WHERE email="' . $user->getEmail() . '"')) {
        $pass = $user->getByQuery('SELECT passwd FROM ' . $user->getTable() . ' WHERE email="' . $user->getEmail() . '"');
        if (password_verify($user->getPass(), $pass[0]['passwd'])) {
            $cstrong = True;
            // generate random 64 bytes as our cookie token
            $token = bin2hex(openssl_random_pseudo_bytes(64, $cstrong));
            // insert cookie token in DB
            $user->session_cookie($token);
            // set cookie in browser (expire date: one week)
            setcookie("SNID" , $token, time() + 60 * 60 * 24 * 7, '/', NULL, NULL, TRUE);

            // get userid of logged user
            $db = new DB();
            $hashtoken = sha1($token);
            $statement = $db->query('SELECT userid FROM login_tokens WHERE token=:token');
            $statement->bindParam(":token", $hashtoken);
            if ($statement->execute()) {
                $user_id = $statement->fetchAll();
            }
            // create login details for logged user (last activity, istyping)
            $user->create_details($user_id[0]['userid']);

            // redirect to index
            header("Location: http://$server/studyfam/");
            die();
        } else {
            echo "<script>alert('Incorrect Password!');</script>";
        }
    } else {
        echo "<script>alert('Account with this email does not exist. Please register!');</script>";
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
    <title>Login</title>
    <link rel="stylesheet" href="css/main.css?v=<?php echo time(); ?>">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/46f21292ec.js" crossorigin="anonymous"></script>
    <style>
        .logo-container {
            margin-top: 0%;
            background-color: white;
        }
        body {
            background-color: #ffa500;
        }
        h1 {
            text-align: center;
            margin-top: 6vh;
        }
        #logform {
            background-color: white;
            border: solid #bd7e01;
            height: auto;
            width: 45vh;
            text-align: center;
            display: inline-block;
            padding: 4vh;
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
        #logdiv {
            text-align: center;
            display: block;
            background-color: #ffa500;
        }
        #hlog {
            margin-bottom: 2vh;
        }
        #loginbutton {
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
        #loginbutton:hover {
            background-color: white;
            color: #ffa500;
        }

        input:focus {
            border: 2px solid #ffa500;
        }

        @media screen and (max-width: 1079px){
            .logo-container img {
                width: 123vw;
                height:auto;
                background-color: white;
            }
            #logform{
                background-color: white;
                border: solid #bd7e01;
                height: auto;
                width: 94vw;
                text-align: center;
                display: inline-block;
                padding: 0 4vw 4vw;
                margin-left: 15vw;
            }
            .inputfields{
                text-align: left;
                padding-left: 12vw;
                width: 85vw;
                height: 5vh;
                border: 2px solid #ddd;
                border-radius: 30px;
                margin-left: 0;
                font-size: 3vh;
                }



            #loginbutton {
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

            h1 {
                font-size: 3vh;
                margin-left: 27vw;
            }

        }

        @media screen and (min-width: 1080px){
            .symbol-input{
                padding-bottom: 3vh;
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
    <div id="logdiv">
        <h1>LOGIN</h1>
        <form id="logform" action="userlogin.php" method="post">
            <div class="regAtr"><span class="symbol-input"><i class="fas fa-envelope"></i></span><input class="inputfields" type="email" name="email" value="" placeholder="Email"><br></div>
            <div class="regAtr"><span class="symbol-input"><i class="fas fa-lock"></i></span><input class="inputfields" type="password" name="passwd" value="" placeholder="Password"><br></div>
            <div class="regAtr"><input id="loginbutton" type="submit" name="login" value="Login"></div>
        </form>
    </div>
</body>
</html>