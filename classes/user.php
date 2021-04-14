<?php
class User {

    private $table_name = "users";
    private $db;

    private $email;
    private $passwd;
    private $fname;
    private $lname;
    private $university;
    private $semester;
    private $studycourse;

    public function __construct($email, $passwd, $fname, $lname, $university, $semester, $studycourse) {
        $this->email = $email;
        $this->passwd = $passwd;
        $this->fname = $fname;
        $this->lname = $lname;
        $this->university = $university;
        $this->semester = $semester;
        $this->studycourse = $studycourse;
        $this->db = new DB();
    }

    // return all fetched objects from DB by given query
    function getByQuery ($query) {
        $statement = $this->db->query($query);
        $statement->execute();
        return $statement->fetchAll();
    }

    // almost same as the 'getByQuery' function, just no fetch (used in GET of api.php) - individual fetch in api.php
    function read ($query) {
        $statement = $this->db->query($query);
        $statement->execute();
        return $statement;
    }

    // creates a new User with set class parameters (INSERT INTO DB)
    function create () {
        $statement = $this->db->query(
            'INSERT INTO '. $this->getTable() .' 
            SET 
            email=:email, passwd=:passwd, fname=:fname, lname=:lname, university=:university, semester=:semester, studycourse=:studycourse
        ');
        
        // passwords are hashed with BCRYPT and saved in DB (better security)
        $hash = password_hash($this->getPass(), PASSWORD_BCRYPT);
        
        // bind parameters to the query
        $statement->bindParam(":email", $this->email);
        $statement->bindParam(":passwd", $hash);
        $statement->bindParam(":fname", $this->fname);
        $statement->bindParam(":lname", $this->lname);
        $statement->bindParam(":university", $this->university);
        $statement->bindParam(":semester", $this->semester);
        $statement->bindParam(":studycourse", $this->studycourse);
        
        if ($statement->execute()) {
            return true;
        }
        return false;
    }

    // deletes a User based on passed ID parameter (DELETE FROM DB)
    function delete ($id) {
        $statement = $this->db->query(
            'DELETE FROM '. $this->getTable() .' 
            WHERE id=:id
        ');

        $statement->bindParam(":id", $id);

        if ($statement->execute()) {
            return true;
        }
        return false;
    }

    // update a given ID User with different parameters (ID - must, other parameters - optional)
    function update ($id,$fname,$lname,$email,$passwd,$university,$semester,$course) {
        // With this one function only parameters, which are actually specified, will be updated. 
        // therefore we just need one function and not multiple for each parameter update/execute.
        $statement1 = $this->db->query(
            'UPDATE '. $this->getTable() .' 
            SET fname=:fname
            WHERE id=:id
        ');
        $statement1->bindParam(":fname", $fname);
        $statement1->bindParam(":id", $id);

        $statement2 = $this->db->query(
            'UPDATE '. $this->getTable() .' 
            SET lname=:lname
            WHERE id=:id
        ');
        $statement2->bindParam(":lname", $lname);
        $statement2->bindParam(":id", $id);

        $statement3 = $this->db->query(
            'UPDATE '. $this->getTable() .' 
            SET email=:email
            WHERE id=:id
        ');
        $statement3->bindParam(":email", $email);
        $statement3->bindParam(":id", $id);

        $statement4 = $this->db->query(
            'UPDATE '. $this->getTable() .' 
            SET passwd=:passwd
            WHERE id=:id
        ');
        $hash = password_hash($passwd, PASSWORD_BCRYPT);
        $statement4->bindParam(":passwd", $hash);
        $statement4->bindParam(":id", $id);

        $statement5 = $this->db->query(
            'UPDATE '. $this->getTable() .' 
            SET university=:university
            WHERE id=:id
        ');
        $statement5->bindParam(":university", $university);
        $statement5->bindParam(":id", $id);

        $statement6 = $this->db->query(
            'UPDATE '. $this->getTable() .' 
            SET semester=:semester
            WHERE id=:id
        ');
        $statement6->bindParam(":semester", $semester);
        $statement6->bindParam(":id", $id);

        $statement7 = $this->db->query(
            'UPDATE '. $this->getTable() .' 
            SET studycourse=:studycourse
            WHERE id=:id
        ');
        $statement7->bindParam(":studycourse", $course);
        $statement7->bindParam(":id", $id);
        
        // executed just when passed FNAME parameter, not empty string
        if ($fname != '') {
            if ($statement1->execute()) {
                echo "fname";
            }
        }
        // executed just when passed LNAME parameter, not empty string
        if ($lname != '') {
            if ($statement2->execute()) {
                echo "lname";
            }
        }
        // executed just when passed EMAIL parameter, not empty string
        if ($email != '') {
            if ($statement3->execute()) {
                echo "email";
            }
        }
        // executed just when passed PASSWD parameter, not empty string
        if ($passwd != '') {
            if ($statement4->execute()) {
                echo "<script>alert('Password changed succesfully!')</script>";
            }
        }
        // executed just when passed UNIVERSITY parameter, not empty string
        if ($university != '') {
            if ($statement5->execute()) {
                echo "university";
            }
        }
        // executed just when passed SEMESTER parameter, not empty string
        if ($semester != '') {
            if ($statement6->execute()) {
                echo "semester";
            }
        }
        // executed just when passed COURSE parameter, not empty string
        if ($course != '') {
            if ($statement7->execute()) {
                echo "course";
            }
        }
    }

    // inserts generated cookie token from userlogin.php into our DB
    function session_cookie ($token) {
        $table_name = "login_tokens";
        $statement = $this->db->query(
            'INSERT INTO '. $table_name .'
            SET
            token=:token, userid=:userid
        ');
        
        // get User ID from specified user
        $user_id = $this->getByQuery('SELECT id FROM '. $this->table_name. ' WHERE email="'.$this->getEmail().'"')[0]['id'];
        // tokens are stored as hashes (one-way function sha1)
        $hash_token = sha1($token);

        // store User ID with token, as a identifier, whose token it is
        $statement->bindParam(":token", $hash_token);
        $statement->bindParam(":userid", $user_id);

        if ($statement->execute()) {
            return true;
        }
        return false;
    }

    // check if set Cookie is in DB (if yes -> return true + auto login)
    public static function isLoggedIn () {
        require_once ('./classes/db.php');
        $db = new DB();
        if (isset($_COOKIE['SNID'])) {
            $hash_token = sha1($_COOKIE['SNID']);
            $statement = $db->query('SELECT userid FROM login_tokens WHERE token=:token');
            $statement->bindParam(":token", $hash_token);
            if ($statement->execute()) {
                return true;
            }
        }
        return false;
    }

    // when User is logging out, Cookie deleted from DB
    public static function Logout () {
        require_once ('./classes/db.php');
        $db = new DB();
        if (isset($_COOKIE['SNID'])) {
            $statement = $db->query('DELETE FROM login_tokens WHERE token="'. sha1($_COOKIE['SNID']) . '"');
            if ($statement->execute()) {
                return true;
            }
        }
        return false;
    }

    // create details for chat messaging
    function create_details ($user_id) {
        $found = $this->getByQuery("SELECT * FROM login_details WHERE user_id='$user_id'");
        $is_type = 'no';

        // check if User already has login details, if yes update it
        if ($found) {
            $statement = $this->db->query(
                "UPDATE login_details
            SET 
            last_activity= now()
            WHERE
            user_id=:user_id
        ");
            $statement->bindParam(":user_id", $user_id);
        } 
        // create login details
        else { 
            $statement = $this->db->query(
                "INSERT INTO login_details 
                SET 
                user_id=:user_id, last_activity=now(), is_type=:is_type
            ");

            $statement->bindParam(":user_id", $user_id);
            $statement->bindParam(":is_type", $is_type);
        }

        if ($statement->execute()) {
            return true;
        }
        return false;
    }

    // Getters
    function getTable () {
        return $this->table_name;
    }
    function getEmail () {
        return $this->email;
    }
    function getPass () {
        return $this->passwd;
    }
    function getDB () {
        return $this->db;
    }
    function getFname () {
        return $this->fname;
    }
    function getLname () {
        return $this->lname;
    }
    function getUniversity () {
        return $this->university;
    }
}
