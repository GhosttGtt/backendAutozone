<?php

class Users
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
    public $created_at;


    // Constructor with DB connection
    public function __construct($db)
    {
        $this->conn = $db;
    }

    // Get Posts from the database
    public function read()
    {
        //create query
        $query = 'SELECT 
            *
            
            FROM 
            ' . $this->table . '
            ORDER BY id ASC';
        // Prepare statement
        $stmt = $this->conn->prepare($query);

        // Execute query
        $stmt->execute();

        return $stmt;
    }

    public function read_single()
    {
        //create query
        $query = 'SELECT * FROM ' . $this->table . '
                WHERE  id = ? LIMIT 1 ';
        // Prepare statement
        $stmt = $this->conn->prepare($query);
        // Bind ID
        $stmt->bindParam(1, $this->id);

        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            $this->id = $row['id'];
            $this->name = $row['name'];
            $this->username = $row['username'];
            $this->email = $row['email'];
            $this->photo = $row['photo'];
            $this->password = $row['password'];
            $this->role = $row['role'];
            $this->created_at = $row['created_at'];
        } else {
            // Usuario no encontrado, limpiar propiedades
            $this->id = null;
            $this->name = null;
            $this->username = null;
            $this->email = null;
            $this->photo = null;
            $this->password = null;
            $this->role = null;
            $this->created_at = null;
        }
    }
    public function create()
    {
        $query = 'INSERT INTO ' . $this->table . ' SET name = :name, username = :username, email = :email, photo = :photo, password = :password, role = :role';
        // Prepare statement
        $stmt = $this->conn->prepare($query);
        // Clean data
        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->username = htmlspecialchars(strip_tags($this->username));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->photo = htmlspecialchars(strip_tags($this->photo));
        $this->password = htmlspecialchars(strip_tags($this->password));
        $this->role = htmlspecialchars(strip_tags($this->role));
        // Asignar la fecha actual automáticamente


        // Bind data
        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':username', $this->username);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':photo', $this->photo);
        $stmt->bindParam(':password', $this->password);
        $stmt->bindParam(':role', $this->role);


        // Execute query
        if ($stmt->execute()) {
            return true;
        }
        // print error if something goes wrong
        printf("Error: %s.\n", $stmt->error);
    }
    public function edit()
    {
        // Construir la consulta de actualización
        $query = 'UPDATE ' . $this->table . ' 
            SET name = :name, username = :username, email = :email, photo = :photo, password = :password, role = :role
            WHERE id = :id';

        // Preparar la sentencia
        $stmt = $this->conn->prepare($query);

        // Limpiar datos
        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->username = htmlspecialchars(strip_tags($this->username));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->photo = htmlspecialchars(strip_tags($this->photo));
        $this->password = htmlspecialchars(strip_tags($this->password));
        $this->id = htmlspecialchars(strip_tags($this->id));

        // Bind de datos
        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':username', $this->username);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':photo', $this->photo);
        $stmt->bindParam(':password', $this->password);
        $stmt->bindParam(':id', $this->id);

        // Ejecutar la consulta
        if ($stmt->execute()) {
            return true;
        }
        printf("Error: %s.\n", $stmt->error);
        return false;
    }
    public function delete()
    {
        $query = 'DELETE FROM ' . $this->table . ' WHERE id = :id';
        // Prepare statement
        $stmt = $this->conn->prepare($query);
        // Clean data
        $this->id = htmlspecialchars(strip_tags($this->id));
        // Bind data
        $stmt->bindParam(':id', $this->id);
        // Execute query
        if ($stmt->execute()) {
            return true;
        }
        // print error if something goes wrong
        printf("Error: %s.\n", $stmt->error);
        return false;
    }
}
