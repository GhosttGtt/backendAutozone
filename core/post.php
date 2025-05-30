<?php

class Post{

    // DB stuff
    private $conn;
    private $table = 'posts';

    // Post Properties
    public $id;
    public $category_id;
    public $category_name;
    public $title;
    public $body;
    public $author;
    public $create_at;
    
    
    // Constructor with DB connection
    public function __construct($db){
        $this->conn = $db;
    } 

    // Get Posts from the database
    public function read(){
        //create query
        $query = 'SELECT 
            c.name as category_name,
            p.id,
            p.category_id,
            p.title,
            p.body,
            p.author,
            p.create_at
            FROM 
            ' . $this->table . ' p
            LEFT JOIN 
                categories c ON p.category_id = c.id
                ORDER BY p.create_at DESC';


                // Prepare statement
                $stmt = $this->conn->prepare($query);

                // Execute query
                $stmt->execute();
                
                return $stmt;
    }

    public function read_single(){
        //create query
        $query = 'SELECT 
            c.name as category_name,
            p.id,
            p.category_id,
            p.title,
            p.body,
            p.author,
            p.create_at
            FROM 
            ' . $this->table . ' p
            LEFT JOIN 
                categories c ON p.category_id = c.id
                WHERE  p.id = ? LIMIT 1 ';


                // Prepare statement
                $stmt = $this->conn->prepare($query);
                // Bind ID
                $stmt->bindParam(1, $this->id);

                $stmt->execute();

                $row = $stmt->fetch(PDO::FETCH_ASSOC);

                $this ->id = $row['id'];
                $this ->category_id = $row['category_id'];
                $this ->category_name = $row['category_name'];  
                $this ->title = $row['title'];
                $this ->body = $row['body'];
                $this ->author = $row['author'];
                $this ->create_at = $row['create_at'];
    }
               public function create(){
                $query = 'INSERT INTO ' . $this->table . ' SET title = :title, body = :body, author = :author, category_id = :category_id';
                // Prepare statement
                $stmt = $this->conn->prepare($query);
                // Clean data
                $this->title = htmlspecialchars(strip_tags($this->title));
                $this->body = htmlspecialchars(strip_tags($this->body));
                $this->author = htmlspecialchars(strip_tags($this->author));
                $this->category_id = htmlspecialchars(strip_tags($this->category_id));
                $this->category_name = htmlspecialchars(strip_tags($this->category_name));
                // Bind data
                $stmt->bindParam(':title', $this->title);
                $stmt->bindParam(':body', $this->body);
                $stmt->bindParam(':author', $this->author);
                $stmt->bindParam(':category_id', $this->category_id);
                $stmt->bindParam(':category_name', $this->category_name);
                // Execute query
                if($stmt->execute()){
                    return true;
                }
                // print error if something goes wrong
                printf("Error: %s.\n", $stmt->error);
               }

    
}