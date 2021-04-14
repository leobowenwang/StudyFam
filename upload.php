<?php
session_start();
include ('./classes/user.php');
include ('./classes/db.php');
$server = $_SERVER['HTTP_HOST'];
// check cookie and redirect if not logged in
if (!isset($_SESSION['admin'])) {
    if (!User::isLoggedIn()) {
        header("Location: http://$server/studyfam/");
        die();
    }
}
if (isset($_POST['submit'])) {
    $db = new DB();
    $university = $_POST['university'];
    $course = $_POST['course'];
    $info = $_POST['info'];
    if (!empty($_POST['university']) && !empty($_POST['course'])) {
        if ($_FILES['file']['size'] != 0) {
            $ftype = $_FILES['file']['type'];
            $fname = $_FILES['file']['name'];
            $tname = $_FILES['file']['tmp_name'];
            
            // get content of uploaded file and encode it
            $data = file_get_contents($tname);
            $fdata = base64_encode($data);

            // optional info (description)
            if ($info == '') {
                $stmt = $db->query("INSERT INTO `filesup` SET university=:university, course=:course, ftype=:ftype, fname=:fname, fdata=:fdata");
            } elseif (!empty($info)) {
                $stmt = $db->query("INSERT INTO `filesup` SET university=:university, course=:course, info=:info, ftype=:ftype, fname=:fname, fdata=:fdata");
                $stmt->bindParam(":info",$info);
            }

            // bind all parameters to query
            $stmt->bindParam(":university", $university);
            $stmt->bindParam(":course", $course);
            $stmt->bindParam(":ftype", $ftype);
            $stmt->bindParam(":fname", $fname);
            $stmt->bindParam(":fdata", $fdata);
            if ($stmt->execute()) {
                echo "<script>alert('Upload was successful!');</script>";
            } else {
                echo "<script>alert('Upload failed!');</script>";
            }
        } else {
            echo "<script>alert('You have not specified your file for upload.');</script>";
        }
    } else {
        echo "<script>alert('Please specify university & course! Try again.');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Upload</title>
    <link rel="stylesheet" href="css/main.css">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/46f21292ec.js" crossorigin="anonymous"></script>
    <style>


        .feed {
            text-align: center;
        }

        .register:hover {
            font-size: 1.2rem;
        }
        .upload-btn {
            margin-top: 1.5%;
            text-align: center;
        }
        .upload-bar {
            border-radius: 10px;
            display: grid;
            text-align: center;
        }
        #form-upload {
            display: grid;
        }

        .upload-label {
            text-align: left;
            height: 0.3rem;
        }


        #addText::placeholder {
            font-style: oblique;
        }

        @media screen and (max-width: 1079px){
            .content-block {
                float: left;
                font-size: 3vh;
                margin-top: 6vh;
                margin-left: 2vw;
            }

            #upload-field {
                text-decoration: none;
                outline: none;
                width: 100%;
                border: 2px solid #ddd;
                border-radius: 30px;
                font-size: 3vh;
                text-align: center;
            }

            #addText {
                text-decoration: none;
                outline: none;
                width: 100%;
                height: 13rem;
                border: 2px solid #ddd;
                border-radius: 20px;
                font-size: 2vh;
                text-align: left;
                font-family: Arial, Helvetica, sans-serif;
                padding: 10px;
                resize: none;
            }
            #file-field {
                text-decoration: none;
                outline: none;
                width: 100%;
                font-size: 2vh;
                text-align: center;
                color: #A9A9A9;
                margin-top: 2rem;
            }
            .register {
                font-size: 3vh;
                border: none;
            }

            .option{
                max-width: 10vw;
                height: auto;
                font-size: 0.5vh;
            }
        }
        @media screen and (min-width: 1080px){
            .content-block {
                float: left;
                width: 50%;
                margin-top: 6vw;
                height: 14%;
            }

            #upload-field {
                text-decoration: none;
                outline: none;
                width: 100%;
                border: 2px solid #ddd;
                border-radius: 30px;
                font-size: 17px;
                text-align: center;
            }

            #addText {
                text-decoration: none;
                outline: none;
                width: 100%;
                height: 13rem;
                border: 2px solid #ddd;
                border-radius: 20px;
                font-size: 17px;
                text-align: left;
                font-family: Arial, Helvetica, sans-serif;
                padding: 10px;
                resize: none;
            }
            #file-field {
                text-decoration: none;
                outline: none;
                width: 100%;
                font-size: 16px;
                text-align: center;
                color: #A9A9A9;
                margin-top: 2rem;
            }

            .register {
                font-size: 1rem;
                border: none;
            }
        }


    </style>
    </head>
<body>
    <div class="logo-container-logged">
            <a href="/studyfam"><img src="img/st_logov1.png" alt="logo"></a>
    </div>
    <div class="content-block">
        <div class="feed"><h2>Upload</h2></div>
        <div class="upload-bar">
            <h4 class="upload-label">University & Subject</h4>
            <form id="form-upload" method="post" enctype="multipart/form-data">
                <input type="text" name="university" id="upload-field" placeholder="University" value="" autocomplete="off">
                <select id="upload-field" name="course">
                    <option value="accounting" class="option">Accounting & Finance</option>
                    <option value="architecture" class="option">Architecture</option>
                    <option value="arts" class="option">Arts & Humanities</option>
                    <option value="business" class="option">Business & Management Studies</option>
                    <option value="computerscience" class="option">Computer Science & Information Systems</option>
                    <option value="economics" class="option">Economics & Econometrics</option>
                    <option value="engineering" class="option">Engineering & Technology</option>
                    <option value="mechanical" class="option">Mechanical, Aeronautical & Manufacturing Engineering</option>
                    <option value="medicine" class="option">Medicine</option>
                    <option value="law" class="option">Law</option>
                </select>
                <h4 class="upload-label" style="margin-top:2.5rem;">Additional Information</h4>
                <textarea type="text" id="addText" value="" autocomplete="off" name="info" placeholder="(Optional)"></textarea>
                <input type="file" name="file" id="file-field" value="" autocomplete="off">
                <div class="upload-btn"><button class="register" type="submit" name="submit">Upload</button></div>
            </form>
        </div>
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
        <tr>
            <td></td>
        </tr>
    </div>
    <script src="js/main.js"></script>
</body>
</html>