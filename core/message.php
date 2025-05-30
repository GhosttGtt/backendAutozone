<?php

class Message
{

    // DB stuff
    private $conn;
    private $table = 'message';

    // Post Properties
    public $id;
    public $name;
    public $phone;
    public $email;
    public $subject;
    public $message;
    public $status;
    public $create_at;


    // Constructor with DB connection
    public function __construct($db)
    {
        $this->conn = $db;
    }

    // Get Posts from the database
    public function read()
    {
        //create query
        $query = 'SELECT *
            FROM ' . $this->table . '
            ORDER BY id DESC';
        // Prepare statement
        $stmt = $this->conn->prepare($query);
        // Execute query
        $stmt->execute();
        return $stmt;
    }

    public function read_single()
    {
        //create query
        $query = 'SELECT * FROM ' . $this->table . ' WHERE  id = ? LIMIT 1 ';
        // Prepare statement
        $stmt = $this->conn->prepare($query);
        // Bind ID
        $stmt->bindParam(1, $this->id);

        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        $this->id = $row['id'];
        $this->name = $row['name'];
        $this->phone = $row['phone'];
        $this->email = $row['email'];
        $this->subject = $row['subject'];
        $this->message = $row['message'];
        $this->status = $row['status'];
    }
    public function create()
    {
        $query = 'INSERT INTO ' . $this->table . ' SET name = :name, phone = :phone, email = :email, subject = :subject, message = :message, status = :status';
        // Prepare statement
        $stmt = $this->conn->prepare($query);
        // Clean data
        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->phone = htmlspecialchars(strip_tags($this->phone));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->subject = htmlspecialchars(strip_tags($this->subject));
        $this->message = htmlspecialchars(strip_tags($this->message));
        $this->status = htmlspecialchars(strip_tags($this->status));

        // Bind data
        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':phone', $this->phone);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':subject', $this->subject);
        $stmt->bindParam(':message', $this->message);
        $stmt->bindParam(':status', $this->status);

        // Execute query
        if ($stmt->execute()) {
            return true;
        }
        // print error if something goes wrong
        printf("Error: %s.\n", $stmt->error);
    }
    public function update()
    {
        $query = 'Update ' . $this->table . ' SET status = :status WHERE id = :id';
        // Prepare statement
        $stmt = $this->conn->prepare($query);
        // Clean data
        $this->id = htmlspecialchars(strip_tags($this->id));
        $this->status = htmlspecialchars(strip_tags($this->status));
        // Bind data
        $stmt->bindParam(':id', $this->id);
        $stmt->bindParam(':status', $this->status);
        // Execute query
        if ($stmt->execute()) {
            return true;
        }
        // print error if something goes wrong
        printf("Error: %s.\n", $stmt->error);
        return false;
    }
}
