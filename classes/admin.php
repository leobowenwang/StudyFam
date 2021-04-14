<?php
class Admin {

    private $table_name = "admins";
    private $db;

    private $email;
    private $passwd;

    public function __construct($email, $passwd) {
        $this->email = $email;
        $this->passwd = $passwd;
        $this->db = new DB();
    }

    // SAME FUNCTION AS IN USER CLASS
    function getByQuery ($query) {
        $statement = $this->db->query($query);
        $statement->execute();
        return $statement->fetchAll();
    }

    // SAME FUNCTION AS IN USER CLASS
    function session_cookie ($token) {
        $statement = $this->db->query(
            'INSERT INTO login_tokens 
            SET
            token=:token, user_id=:user_id
        ');

        $user_id = $this->getByQuery('SELECT id FROM '. $this->table_name. ' WHERE email="'.$this->getEmail().'"')[0]['id'];
        $hash_token = sha1($token);

        $statement->bindParam(":token", $hash_token);
        $statement->bindParam(":user_id", $user_id);

        if ($statement->execute()) {
            return true;
        }
        return false;
    }

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
}
