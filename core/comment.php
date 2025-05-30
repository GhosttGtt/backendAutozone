<?php

class Comment{
    // DB stuff
    private $conn;
    private $table = 'cars_comment';

    // Post Properties
    public $id;
    public $comment;
    public $car_id;
    public $stars;
    public $created_at;


    
    // Constructor with DB connection
    public function __construct($db){
        $this->conn = $db;
    } 

    // Get Posts from the database
    public function read(){
        //create query
        $query = 'SELECT 
        a.brand as car_brand,
        a.model as car_model,
        c.id,
        c.comment,
        c.stars,
        c.car_id,
        c.created_at
            FROM 
            ' . $this->table . ' c
            LEFT JOIN
                cars a ON c.car_id = a.id
                ORDER BY c.id DESC';

                // Prepare statement
                $stmt = $this->conn->prepare($query);

                $stmt->execute();
                return $stmt;
    }
    public function create(){
        $query = 'INSERT INTO ' . $this->table . ' SET comment = :comment, stars = :stars, car_id = :car_id';
        // Prepare statement
        $stmt = $this->conn->prepare($query);

        // Clean data
        $this->comment = htmlspecialchars(strip_tags($this->comment));
        $this->stars = htmlspecialchars(strip_tags($this->stars));
        $this->car_id = htmlspecialchars(strip_tags($this->car_id));

        // Bind data
        $stmt->bindParam(':comment', $this->comment);
        $stmt->bindParam(':stars', $this->stars);
        $stmt->bindParam(':car_id', $this->car_id);

        // Execute query
        if($stmt->execute()){
            return true;
        }
        return false;
    }
}