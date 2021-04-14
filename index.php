<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>StudyFam</title>
    <link rel="stylesheet" href="css/main.css?v=<?php echo time(); ?>">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
    <style>
        html{
            border-left: 1px;
            border-right: 1px;
            margin-left: 1%;
            margin-right: 1%;
        }

        @media screen and (max-width: 1079px){
            h4{
                font-size: 2vh;
                margin-top: 8vh;
            }
            .register {
                margin-top: 2vh;
            }
            a.login{
                margin-top: 2vh;
            }
            .content-block {
                float: left;
                margin-top: 8vh;
                margin-left: -15vw;
            }
            body {
                text-decoration: none !important;
                font-family: 'Roboto', sans-serif;
                margin-left: 25vw;
                background-color: white;
                color: black;
            }
            .logo-container img {
                max-width: 100vw;
                height:auto;
                margin-left: -25vw;
            }

            html{
                border-left: 1px;
                border-right: 1px;
                margin-left: 1%;
                margin-right: 1%;
            }
        }

    </style>
</head>
<?php
    session_start();
    include ('./classes/user.php');
    $server = $_SERVER['HTTP_HOST'];
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
<body>
    <div class="logo-container">
        <a href="/studyfam">
            <img src="img/st_logov1.png" alt="logo">
        </a>
    </div>
    <div class="content-block">
        <div class="column"></div>
        <div class="column">
            <table class="login-container">
                <tr>
                    <td>
                        <h4 style="margin-bottom: 4rem">"We are all a big family when it comes to studying!"</h4>
                    </td>
                </tr>
                <tr>
                    <td><a href="create-account.php" class="register">Sign up for free!</a></td>
                </tr>
                <tr>
                    <td><a href="userlogin.php" class="login">Already registered? Log in</a></td>
                </tr>
            </table>
        </div>
        <div class="column"></div>
    </div>
</body>
<footer>
</footer>
</html>