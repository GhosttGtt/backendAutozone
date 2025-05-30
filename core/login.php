<?php

class Login
{

    // DB stuff
    private $conn;
    private $table = 'users';

    // Post Properties
    public $id;
    public $name;
    public $username;
    public $email;
    public $photo;
    public $password;
    public $role;


    // Constructor with DB connection
    public function __construct($db)
    {
        $this->conn = $db;
    }
    public function login($username, $password)
    {
        //create query
        $query = 'SELECT * FROM ' . $this->table . ' WHERE username = :username LIMIT 1';
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            $this->id = $user['id'];
            $this->name = $user['name'];
            $this->username = $user['username'];
            $this->email = $user['email'];
            $this->photo = $user['photo'];
            $this->password = $user['password'];
            $this->role = $user['role'];
            return true;
        } else {
            return false;
        }
    }
}
