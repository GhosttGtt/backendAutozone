<?php

class Sales
{

    // DB stuff
    private $conn;
    private $table = 'sales';

    // Post Properties
    public $id;
    public $client_id;
    public $client_name;
    public $client_lastname;
    public $dateSales;
    public $payment_id;
    public $payment_name;
    public $cars_id;
    public $cars_name;
    public $type_name;
    public $cars_model;
    public $cars_year;
    public $cars_motor;
    public $cars_fuel;
    public $cars_price;
    public $cars_image;
    public $cars_type;
    public $status_id;
    public $total;



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
        c.name as client_name,
        c.lastname as client_lastname,
        ca.brand as cars_name,
        ca.model as cars_model,
        ca.year as cars_year,
        ca.motor as cars_motor,
        ca.fuel as cars_fuel,
        ca.price as cars_price,
        ca.image as cars_image,
        ct.type_name as cars_type,
        py.type_payment as payment,       
        s.id,
        s.client_id,
        s.dateSales,
        s.payment_id,
        s.cars_id,
        s.status_id,
        s.total     
            FROM 
            ' . $this->table . ' s
            LEFT JOIN 
                clients c ON s.client_id = c.id
            LEFT JOIN
                cars ca ON s.cars_id = ca.id
            LEFT JOIN
                payment py ON s.payment_id = py.id
            LEFT JOIN
                type_car ct ON ca.type_id = ct.id
            
            ORDER BY s.id DESC';

        // Prepare statement
        $stmt = $this->conn->prepare($query);

        $stmt->execute();
        return $stmt;
    }
    public function read_single()
    {
        //create query
        $query = 'SELECT 
        c.name as client_name,
        c.lastname as client_lastname,
        ca.brand as cars_name,
        ca.model as cars_model,
        ca.year as cars_year,
        ca.motor as cars_motor,
        ca.fuel as cars_fuel,
        ca.price as cars_price,
        ca.image as cars_image,
        ct.type_name as cars_type,
        py.type_payment as payment,       
        s.id,
        s.client_id,
        s.dateSales,
        s.payment_id,
        s.cars_id,
        s.status_id,
        s.total     
            FROM 
            ' . $this->table . ' s
            LEFT JOIN 
                clients c ON s.client_id = c.id
            LEFT JOIN
                cars ca ON s.cars_id = ca.id
            LEFT JOIN
                payment py ON s.payment_id = py.id
            LEFT JOIN
                type_car ct ON ca.type_id = ct.id
                WHERE  s.id = ? LIMIT 1 ';


        // Prepare statement
        $stmt = $this->conn->prepare($query);
        // Bind ID
        $stmt->bindParam(1, $this->id);

        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        $this->id = $row['id'];
        $this->client_id = $row['client_id'];
        $this->client_name = $row['client_name'];
        $this->client_lastname = $row['client_lastname'];
        $this->dateSales = $row['dateSales'];
        $this->payment_id = $row['payment_id'];
        $this->payment_name = $row['payment'];
        $this->cars_id = $row['cars_id'];
        $this->cars_name = $row['cars_name'];
        $this->cars_model = $row['cars_model'];
        $this->cars_year = $row['cars_year'];
        $this->cars_motor = $row['cars_motor'];
        $this->cars_fuel = $row['cars_fuel'];
        $this->cars_price = $row['cars_price'];
        $this->cars_image = $row['cars_image'];
        $this->cars_type = $row['cars_type'];
        $this->status_id = $row['status_id'];
        $this->total = $row['total'];
    }
    public function create()
    {
        $query = 'INSERT INTO ' . $this->table . ' SET title = :title, body = :body, author = :author, category_id = :category_id';
        // Prepare statement
        $stmt = $this->conn->prepare($query);
        // Clean data
        $this->id = htmlspecialchars(strip_tags($this->id));
        $this->client_id = htmlspecialchars(strip_tags($this->client_id));
        $this->client_name = htmlspecialchars(strip_tags($this->client_name));
        $this->dateSales = htmlspecialchars(strip_tags($this->dateSales));
        $this->payment_id = htmlspecialchars(strip_tags($this->payment_id));
        $this->payment_name = htmlspecialchars(strip_tags($this->payment_name));
        $this->cars_id = htmlspecialchars(strip_tags($this->cars_id));
        $this->cars_name = htmlspecialchars(strip_tags($this->cars_name));
        $this->cars_model = htmlspecialchars(strip_tags($this->cars_model));
        $this->cars_year = htmlspecialchars(strip_tags($this->cars_year));
        $this->cars_motor = htmlspecialchars(strip_tags($this->cars_motor));
        $this->cars_fuel = htmlspecialchars(strip_tags($this->cars_fuel));
        $this->cars_price = htmlspecialchars(strip_tags($this->cars_price));
        $this->cars_image = htmlspecialchars(strip_tags($this->cars_image));
        $this->cars_type = htmlspecialchars(strip_tags($this->cars_type));
        $this->status_id = htmlspecialchars(strip_tags($this->status_id));
        $this->total = htmlspecialchars(strip_tags($this->total));

        // Bind data
        $stmt->bindParam(':id', $this->id);
        $stmt->bindParam(':client_id', $this->client_id);
        $stmt->bindParam(':client_name', $this->client_name);
        $stmt->bindParam(':dateSales', $this->dateSales);
        $stmt->bindParam(':payment_id', $this->payment_id);
        $stmt->bindParam(':payment_name', $this->payment_name);
        $stmt->bindParam(':cars_id', $this->cars_id);
        $stmt->bindParam(':cars_name', $this->cars_name);
        $stmt->bindParam(':cars_model', $this->cars_model);
        $stmt->bindParam(':cars_year', $this->cars_year);
        $stmt->bindParam(':cars_motor', $this->cars_motor);
        $stmt->bindParam(':cars_fuel', $this->cars_fuel);
        $stmt->bindParam(':cars_price', $this->cars_price);
        $stmt->bindParam(':cars_image', $this->cars_image);
        $stmt->bindParam(':cars_type', $this->cars_type);
        $stmt->bindParam(':status_id', $this->status_id);
        $stmt->bindParam(':total', $this->total);

        // Execute query
        if ($stmt->execute()) {
            return true;
        }
        // print error if something goes wrong
        printf("Error: %s.\n", $stmt->error);
    }
}
