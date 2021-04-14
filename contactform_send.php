<?php
$errors = '';
$myemail = 'fbirnegger@gmail.com'; //passw: studyfam123

if(empty($_POST['name'])  ||
    empty($_POST['email']) ||
    empty($_POST['text']))
{
    $errors .= "\n Error: all fields are required";
}

$name = $_POST['name'];
$email_address = $_POST['email'];
$message = $_POST['text'];

if(empty($errors))

{

    $to = $myemail;

    $email_subject = "Contact form submission: $name";

    $email_body = "There is a new user request from www.studyfam.com ".

        " Here are the details:\n Name: $name \n ".

        "Email: $email_address\n Message: \n $message";

    $headers = "From: $myemail\n";

    $headers .= "Reply-To: $email_address";




    mail($to,$email_subject,$email_body,$headers);
        header('Location: home.php');

}
 if(!empty($errors)) {
    header('Location: contact_unable.php');
}

