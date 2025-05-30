<?php

class Citas
{

    // DB stuff
    private $conn;
    private $table = 'appointment';

    // Post Properties
    public $id;
    public $name;
    public $email;
    public $datetime;
    public $personas;


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

        $stmt = $this->conn->prepare($query);

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
        $this->email = $row['email'];
        $this->datetime = $row['datetime'];
        $this->personas = $row['people'];
    }
    public function create()
    {
        $query = 'INSERT INTO ' . $this->table . '
        SET name = :name,
        email = :email,
        datetime = :datetime,
        people = :personas';
        // Prepare statement
        $stmt = $this->conn->prepare($query);
        // Clean data
        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->datetime = htmlspecialchars(strip_tags($this->datetime));
        $this->personas = htmlspecialchars(strip_tags($this->personas));

        // Bind data
        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':datetime', $this->datetime);
        $stmt->bindParam(':personas', $this->personas);

        // Execute query
        if ($stmt->execute()) {
            return true;
        }
        // print error if something goes wrong
        printf("Error: %s.\n", $stmt->error);
    }
    /*
    public function update(){
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
        if($stmt->execute()){
            return true;
        }
        // print error if something goes wrong
        printf("Error: %s.\n", $stmt->error);
        return false;
    } */
}
