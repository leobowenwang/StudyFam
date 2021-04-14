<?php
session_start();
include ('./classes/user.php');
$server = $_SERVER['HTTP_HOST'];

// Logout User
if (!isset($_SESSION['admin'])) {
    if (!User::isLoggedIn()) {
        die("Not logged in.");
        header("Location: http://$server/studyfam/");
    } else {
        User::Logout();
        if (isset($_COOKIE['SNID'])) {
            unset($_COOKIE['SNID']);
            setcookie('SNID', "1", time()-3600, '/');
            header("Location: http://$server/studyfam/");
            die();
        }
    }
} elseif (isset($_SESSION['admin']) && $_SESSION['admin']==1) {
    session_unset();
    header("Location: http://$server/studyfam/");
    die();
}