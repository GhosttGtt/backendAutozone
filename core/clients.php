<?php

class Clients
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

        $this->id = $row['id'];
        $this->name = $row['name'];
        $this->phone = $row['phone'];
        $this->email = $row['email'];
        $this->lastname = $row['lastname'];
    }
    public function create()
    {
        $query = 'INSERT INTO '
            . $this->table .
            ' SET name = :name,
                lastname = :lastname,
                email = :email,
                phone = :phone, 
                password = :password';                // Prepare statement
        $stmt = $this->conn->prepare($query);
        // Clean data
        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->lastname = htmlspecialchars(strip_tags($this->lastname));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->phone = htmlspecialchars(strip_tags($this->phone));
        $this->password = htmlspecialchars(strip_tags($this->password));

        // Bind data
        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':lastname', $this->lastname);
        $stmt->bindParam(':phone', $this->phone);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':password', $this->password);


        // Execute query
        if ($stmt->execute()) {
            return true;
        }
        // print error if something goes wrong
        printf("Error: %s.\n", $stmt->error);
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

    // Soft delete: marcar como eliminado en vez de borrar físicamente
    public function softDelete()
    {
        $query = 'UPDATE ' . $this->table . ' SET deleted = 1 WHERE id = :id';
        $stmt = $this->conn->prepare($query);
        $this->id = htmlspecialchars(strip_tags($this->id));
        $stmt->bindParam(':id', $this->id);
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
    // Editar cliente solo con los campos llenos
    public function edit()
    {
        $fields = [];
        $params = [];
        if (!empty($this->name)) {
            $fields[] = 'name = :name';
            $params[':name'] = $this->name;
        }
        if (!empty($this->lastname)) {
            $fields[] = 'lastname = :lastname';
            $params[':lastname'] = $this->lastname;
        }
        if (!empty($this->phone)) {
            $fields[] = 'phone = :phone';
            $params[':phone'] = $this->phone;
        }
        if (!empty($this->email)) {
            $fields[] = 'email = :email';
            $params[':email'] = $this->email;
        }
        if (!empty($this->password)) {
            $fields[] = 'password = :password';
            $params[':password'] = $this->password;
        }
        if (empty($fields)) {
            return false; // No hay campos para actualizar
        }
        $query = 'UPDATE ' . $this->table . ' SET ' . implode(', ', $fields) . ' WHERE id = :id';
        $stmt = $this->conn->prepare($query);
        foreach ($params as $key => $value) {
            $stmt->bindParam($key, $value);
        }
        $stmt->bindParam(':id', $this->id);
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
}
