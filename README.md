# StudyFam

A social network for students all across Austria.

# Team
- Alexander Bauer
- Fabio Birnegger
- Sebastian Chmel

# Installation

1) Start up xampp or any other server tool and start up 'MySQL Database' & 'Apache Web Server'.

2) Open 'InitialserverChanges.tut' and change the given settings for your server.

3) Import 'studyfam.sql' into your MySQL Database (localhost/phpmyadmin) and it will automatically create the 'studyfam' database, with following tables:
- admins
- chat_message
- filesup
- login_details
- login_tokens
- users

# Admin Page
The admin page can be accessed by visiting 'hidden' link - localhost/studyfam/adlog.php

username/email:'studyfamfh@gmail.com';
passwd:'studyfam';

# User Page
Users can login/register, when visiting the initial website and then clicking on the corresponding button, which redirects the user to the login/register page. 
When a user is registered and uses the login page, after a successful login attempt, the user will be redirected to the Homepage with:
1) Searchbar for filtering learning material
2) A button for uploading material
3) Feed (current uploaded material)
4) Sidebar with a navigation with which the user can navigate through the different available pages

Functionalities:
- chat function
- material search
- material upload
- usersearch by university
- password change (profile page)
- dark mode (profile page)

# MoSCoW Criteria
# MUST
- Users must be able to register and login.
- Admins must be able to login.
- Admins must be able to manage saved Users.
- Users must be able to communicate with each other and with Admins.
# SHOULD
- Users should be able to filter other Users by university, semester and similar attributes.
- User data, like university, semester and course should be saved.
# COULD
- Sharing learning material with other Users could be possible.
- Lectures could be planned in a timetable.
