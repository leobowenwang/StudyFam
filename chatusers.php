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
include('api/database_connection.php');
?>
<html>
<head>
    <meta charset="UTF-8">
    <title>Home</title>
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/46f21292ec.js" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link rel="old stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="newest stylesheet" href="css/main.css">
    <style>

        html{
            font-size: 16px;
        }

        .form-group{
            margin-bottom: 0;
        }


        .sidebar {
            float: right;
        }

        .btn {
            background-color: #ffa500 !important;
            border-color: black !important;
            color: white !important;

        }

        .btn:hover, .btn:active, .btn:focus, .btn:visited {
            background-color: #bd7e01 !important;
            color: white !important;
        }

        td, th {
            padding: 1vh;
            border-style: solid;
            border-color: #A9A9A9;
            border-width: 0.3vh;
        }

        tr {
            padding: 10vh !important;
        }


        @media screen and (max-width: 1079px){
            .chath2 {
                padding-top: 7vh;
                padding-left: 2vh;
                font-size: 4vh;
            }

            table{
                margin-left: 2vw;
                height: auto;
                width: 100vw;
                font-size: 3vh;
            }

            button.btn.btn-xs.start_chat {
                font-size: 2vh;
                height:5vh;
                width:25vw;
                margin-left: 2vw;
            }

            .chat_history{
                height:40vh !important;
                margin-left: 3.5vw;
                word-break: break-all;
            }

            .user_dialog{
                font-size: 3vh;
                height: 50vh;
            }

            .form-control{
                margin-left: 3.5vw;
                width: 82vw;
                position: fixed;
                margin-bottom: 2vh;
                height: 10vh !important;
                font-size: 3vh;
                bottom:0;
                resize: none;

            }

           .send_chat {
                position: fixed !important;
                margin-bottom: 1.85vh;
               bottom: 0;
                right: 0;
                height: 10.25vh!important;
                width: 17vw!important;
                font-size: 2vh!important;
               margin-right: 2vw;
            }

        }

        @media screen and (min-width: 1080px){
            #hello {
                display: inline-block;
                margin: auto;
                letter-spacing: .07rem;
                font-size: 1.5rem;
                font-weight: bold;
                height: 29px;
                padding-top: 0.25vh;
            }

            .chath2 {
                padding-top: 7vh;
                padding-left: 2vh;
                margin-left: 20vw;
            }
            #user_details {
                margin-left: 25vw;
            }
            .chat_history{
                word-break: break-all;
                width: 80vw;
                margin-left: 10vw;
            }

            .form-control {
                width: 80vw;
                margin-left: 10vw;
                resize: none;
            }
            .send_chat{
                margin-right: 10vw;
                margin-top: 1vh;
            }
        }

    </style>
</head>
<body>
<div class="logo-container-logged">
    <a href="/studyfam"><img src="img/st_logov1.png" alt="logo"></a>
</div>
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
    <tr>
        <td></td>
    </tr>
</div>
</div>
<br><br><br><br><br><br><br>
<h2 class="chath2" id="hellochatter" allign="center"></h2>
<h2 class="chath2">Users:</h2>

<div id="user_details"></div>
<div id="user_model_details"></div>

</body>

</html>

<script src="js/main.js"></script>

<script>
    $(document).ready(function(){

        fetch_user();

        setInterval(function(){
            update_last_activity();
            fetch_user();
            update_chat_history_data();
        }, 2000);

        function fetch_user()
        {
            $.ajax({
                url:"api/fetch_user.php",
                method:"POST",
                success:function(data){
                    $('#user_details').html(data);
                }
            })
        }

        function update_last_activity()
        {
            $.ajax({
                url:"api/last_activity.php",
                success:function () {
                    
                }
            })
        }

        function make_chat_dialog_box(to_user_id, to_user_name)
        {
            let modal_content = '<div id="user_dialog_'+to_user_id+'" class="user_dialog" title="You currently have a chat with '+to_user_name+'">';
            modal_content += '<div style="height:300px; border:1px solid #ccc; overflow-y: scroll; margin-bottom:24px; padding:16px;" class="chat_history" data-touserid="'+to_user_id+'" id="chat_history_'+to_user_id+'">';
            modal_content += fetch_user_chat_history(to_user_id);
            modal_content += '</div>';
            modal_content += '<div class="form-group">';
            modal_content += '<textarea name="chat_message_'+to_user_id+'" id="chat_message_'+to_user_id+'" class="form-control"></textarea>';
            modal_content += '</div><div class="form-group" align="right">';
            modal_content+= '<button type="button" name="send_chat" id="'+to_user_id+'" class="btn btn-info send_chat">Send</button></div></div>';
            $('#user_model_details').html(modal_content);
        }

        //Beim klicken des start buttons werden die variablen mit daten gefüllt und dialogbox erstellt
        $(document).on('click', '.start_chat', function(){
            let to_user_id = $(this).data('touserid');
            let to_user_name = $(this).data('toname');
            make_chat_dialog_box(to_user_id, to_user_name);
            $("#user_dialog_"+to_user_id).dialog({
                autoOpen:false,
                width:400
            });
            $('#user_dialog_'+to_user_id).dialog('open');
        });

        //Beim klicken des senden buttons werden über einen ajax call die daten userid und chatmassage an insert_chat.php "gepostet"
        $(document).on('click', '.send_chat', function(){
            let to_user_id = $(this).attr('id');
            let chat_message = $('#chat_message_'+to_user_id).val();
            $.ajax({
                url:"api/insert_chat.php",
                method:"POST",
                data:{to_user_id:to_user_id, chat_message:chat_message},
                success:function(data)
                {
                    $('#chat_message_'+to_user_id).val('');
                    $('#chat_history_'+to_user_id).html(data);
                }
            })
        });

        function fetch_user_chat_history(to_user_id)
        {
            $.ajax({
                url:"api/fetch_user_chat_history.php",
                method:"POST",
                data:{to_user_id:to_user_id},
                success:function(data){
                    $('#chat_history_'+to_user_id).html(data);
                }
            })
        }

        function update_chat_history_data()
        {
            $('.chat_history').each(function(){
                let to_user_id = $(this).data('touserid');
                fetch_user_chat_history(to_user_id);
            });
        }

        $(document).on('click', '.ui-button-icon', function(){
            $('.user_dialog').dialog('destroy').remove();
        });

    });
</script>





