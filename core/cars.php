<?php

class Cars
{

    // DB stuff
    private $conn;
    private $table = 'cars';

    // Post Properties
    public $id;
    public $brand;
    public $description;
    public $motor;
    public $fuel;
    public $model;
    public $year;
    public $price;
    public $image;
    public $type_id;
    public $type_name;
    public $stock;


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
        t.type_name as type_name,
        c.id,
        c.brand,
        c.description,
        c.motor,
        c.fuel,
        c.model,
        c.year,
        c.price,
        c.image,
        c.type_id,
        c.stock          
            FROM 
            ' . $this->table . ' c
            LEFT JOIN 
                type_car t ON c.type_id = t.id
                ORDER BY c.id DESC';

        // Prepare statement
        $stmt = $this->conn->prepare($query);

        $stmt->execute();
        return $stmt;
    }

    public function read_single()
    {
        //create query
        $query = 'SELECT 
        t.type_name as type_name,
        c.id,
        c.brand,
        c.description,
        c.motor,
        c.fuel,
        c.model,
        c.year,
        c.price,
        c.image,
        c.type_id,
        c.stock          
            FROM 
            ' . $this->table . ' c
            LEFT JOIN 
                type_car t ON c.type_id = t.id
                WHERE  c.id = ? LIMIT 1 ';
        // Prepare statement
        $stmt = $this->conn->prepare($query);
        // Bind ID
        $stmt->bindParam(1, $this->id);

        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            $this->id = $row['id'];
            $this->brand = $row['brand'];
            $this->description = $row['description'];
            $this->motor = $row['motor'];
            $this->fuel = $row['fuel'];
            $this->model = $row['model'];
            $this->year = $row['year'];
            $this->price = $row['price'];
            $this->image = $row['image'];
            $this->type_id = $row['type_id'];
            $this->type_name = $row['type_name'];
            $this->stock = $row['stock'];
        } else {
            return false; // Handle the case where no data is found
        }
    }
    public function create()
    {
        $query = 'INSERT INTO ' . $this->table . ' SET brand = :brand, description = :description, motor = :motor, fuel = :fuel, model = :model, year = :year, price = :price, image = :image, type_id = :type_id, stock = :stock';
        // Prepare statement
        $stmt = $this->conn->prepare($query);
        // Clean data

        $this->brand = htmlspecialchars(strip_tags($this->brand));
        $this->description = htmlspecialchars(strip_tags($this->description));
        $this->motor = htmlspecialchars(strip_tags($this->motor));
        $this->fuel = htmlspecialchars(strip_tags($this->fuel));
        $this->model = htmlspecialchars(strip_tags($this->model));
        $this->year = htmlspecialchars(strip_tags($this->year));
        $this->price = htmlspecialchars(strip_tags($this->price));
        $this->image = htmlspecialchars(strip_tags($this->image));
        $this->type_id = htmlspecialchars(strip_tags($this->type_id));
        $this->stock = htmlspecialchars(strip_tags($this->stock));


        // Bind data
        $stmt->bindParam(':brand', $this->brand);
        $stmt->bindParam(':description', $this->description);
        $stmt->bindParam(':motor', $this->motor);
        $stmt->bindParam(':fuel', $this->fuel);
        $stmt->bindParam(':model', $this->model);
        $stmt->bindParam(':year', $this->year);
        $stmt->bindParam(':price', $this->price);
        $stmt->bindParam(':image', $this->image);
        $stmt->bindParam(':type_id', $this->type_id);
        $stmt->bindParam(':stock', $this->stock);

        // Execute query
        if ($stmt->execute()) {
            return true;
        }
        // print error if something goes wrong
        printf("Error: %s.\n", $stmt->error);
    }
    public function update()
    {
        $query = 'UPDATE ' . $this->table . ' SET brand = :brand, description = :description, motor = :motor, fuel = :fuel, model = :model, year = :year, price = :price, image = :image, type_id = :type_id, stock = :stock WHERE id = :id';
        // Prepare statement
        $stmt = $this->conn->prepare($query);
        // Clean data

        $this->brand = htmlspecialchars(strip_tags($this->brand));
        $this->description = htmlspecialchars(strip_tags($this->description));
        $this->motor = htmlspecialchars(strip_tags($this->motor));
        $this->fuel = htmlspecialchars(strip_tags($this->fuel));
        $this->model = htmlspecialchars(strip_tags($this->model));
        $this->year = htmlspecialchars(strip_tags($this->year));
        $this->price = htmlspecialchars(strip_tags($this->price));
        $this->image = htmlspecialchars(strip_tags($this->image));
        $this->type_id = htmlspecialchars(strip_tags($this->type_id));

        $this->stock = htmlspecialchars(strip_tags($this->stock));

        // Bind data
        $stmt->bindParam(':brand', $this->brand);
        $stmt->bindParam(':description', $this->description);
        $stmt->bindParam(':motor', $this->motor);
        $stmt->bindParam(':fuel', $this->fuel);
        $stmt->bindParam(':model', $this->model);
        $stmt->bindParam(':year', $this->year);
        $stmt->bindParam(':price', $this->price);
        $stmt->bindParam(':image', $this->image);
        $stmt->bindParam(':type_id', $this->type_id);

        // Bind ID
        $stmt->bindParam(':id', $this->id);


        // Execute query
        if ($stmt->execute()) {
            return true;
        }
        // print error if something goes wrong
        printf("Error: %s.\n", $stmt->error);
    }
    public function delete()
    {
        //create query
        $query = 'DELETE FROM ' . $this->table . ' WHERE id = :id';
        // Prepare statement
        $stmt = $this->conn->prepare($query);
        // Clean data
        $this->id = htmlspecialchars(strip_tags($this->id));
        // Bind ID
        $stmt->bindParam(':id', $this->id);

        // Execute query
        if ($stmt->execute()) {
            return true;
        }
        // print error if something goes wrong
        printf("Error: %s.\n", $stmt->error);
    }
    public function edit()
    {
        // Solo actualizar los campos que no estén vacíos
        $fields = [];
        $params = [];

        if (!is_null($this->brand) && $this->brand !== '') {
            $fields[] = 'brand = :brand';
            $params[':brand'] = $this->brand;
        }
        if (!is_null($this->description) && $this->description !== '') {
            $fields[] = 'description = :description';
            $params[':description'] = $this->description;
        }
        if (!is_null($this->model) && $this->model !== '') {
            $fields[] = 'model = :model';
            $params[':model'] = $this->model;
        }
        if (!is_null($this->year) && $this->year !== '') {
            $fields[] = 'year = :year';
            $params[':year'] = $this->year;
        }
        if (!is_null($this->motor) && $this->motor !== '') {
            $fields[] = 'motor = :motor';
            $params[':motor'] = $this->motor;
        }
        if (!is_null($this->price) && $this->price !== '') {
            $fields[] = 'price = :price';
            $params[':price'] = $this->price;
        }
        if (!is_null($this->type_id) && $this->type_id !== '') {
            $fields[] = 'type_id = :type_id';
            $params[':type_id'] = $this->type_id;
        }
        if (!is_null($this->stock) && $this->stock !== '') {
            $fields[] = 'stock = :stock';
            $params[':stock'] = $this->stock;
        }
        if (!is_null($this->image) && $this->image !== '') {
            $fields[] = 'image = :image';
            $params[':image'] = $this->image;
        }

        if (empty($fields)) {
            return false; // No hay campos para actualizar
        }

        $query = 'UPDATE ' . $this->table . ' SET ' . implode(', ', $fields) . ' WHERE id = :id';
        $stmt = $this->conn->prepare($query);

        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        $stmt->bindValue(':id', $this->id);

        return $stmt->execute();
    }
}
