<?php

class Login
{

    // DB stuff
    private $conn;
    private $table = 'clients';

    // Post Properties
    public $id;
    public $name;
    public $lastname;
    public $email;
    public $phone;
    public $password;


    // Constructor with DB connection
    public function __construct($db)
    {
        $this->conn = $db;
    }
    public function login($email, $password)
    {
        $query = 'SELECT * FROM ' . $this->table . ' WHERE email = :email AND deleted = 0 LIMIT 1';
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($user && password_verify($password, $user['password'])) {
            $this->id = $user['id'];
            $this->email = $user['email'];
            $this->name = $user['name'];
            $this->lastname = $user['lastname'];
            $this->phone = $user['phone'];
            return true;
        }
        return false;
    }
}
