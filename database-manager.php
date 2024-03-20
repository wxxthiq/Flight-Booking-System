<?php

if (!class_exists('DatabaseManager')) {


  class DatabaseManager
  {
    private $db;
    private $database = 'ABCDatabase'; // Will create a database for you in the myphpadmin mysql database
    private $port = 3306; // Change to your mysql port
    private $host = "127.0.0.1";  //change this to your host
    private $username = "root"; // username and password should be root and nothing by default,
    private $password = "";

    public function __construct()
    {

      $this->initDatabase();

      $this->connect();
    }

    public function connect()
    {
      $this->db = new mysqli($this->host, $this->username, $this->password);
      if ($this->db->connect_errno > 0) {
        die('Unable to connect to database [' . $this->db->connect_error . ']');
      }
      mysqli_select_db($this->db, $this->database);
    }

    public function query($sql) // call this function to perform sql queries
    {
      $result = $this->db->query($sql);
      if (!$result) {
        die('There was an error running the query [' . $this->db->error . ']');
      }
      return $result;
    }

    public function close()
    {
      $this->db->close();
    }

    private function seeders($db) // inserts records into the initialized db
    {

      //customer table
      $db->query("INSERT INTO `Travel_Document` (`Travel_Document_Type`, `Travel_Document_ExpiryDate`, `Travel_Document_Number`, `Travel_Document_Country`) 
      VALUES ('Passport', '2028-08-08', 'OM1234548', 'Oman')");

     
      // ==================

    }

    private function initDatabase()
    {
      $db = new mysqli($this->host, $this->username, $this->password);

      if ($db->query("SHOW DATABASES LIKE '{$this->database}';")->num_rows == 1)
        return;

      $db->query("CREATE DATABASE {$this->database};");

      mysqli_select_db($db, $this->database);


      $db->query("CREATE TABLE `Travel_Document` (
        `Travel_Document_Type` varchar(255) NOT NULL,
        `Travel_Document_ExpiryDate` date NOT NULL,
        `Travel_Document_Number` varchar(255) NOT NULL,
        `Travel_Document_Country` varchar(255) NOT NULL,
        PRIMARY KEY (Travel_Document_Number)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;"
      );

      $db->query("CREATE TABLE `Customer` (
        `Customer_Name` varchar(255) NOT NULL,
        `Customer_Phone_Number` varchar(255) NOT NULL,
        `Customer_Email` varchar(255) NOT NULL,
        `Customer_Password` varchar(255) NOT NULL,
        `Customer_Gender` varchar(255) NOT NULL,
        `Customer_DOB` date NOT NULL,
        `Customer_Age` int(3) NOT NULL,
        `Customer_Occupation` varchar(255) NOT NULL,
        `Customer_Nationality` varchar(255) NOT NULL,
        `Travel_Document_Number` varchar(255) NOT NULL,
        PRIMARY KEY (Customer_Email),
        FOREIGN KEY (Travel_Document_Number) 
        REFERENCES Travel_Document(Travel_Document_Number)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;"
      );

      $db->query("CREATE TABLE `Flight` (
        `Flight_Number` varchar(255) NOT NULL,
        `Flight_From` varchar(255) NOT NULL,
        `Flight_To` varchar(255) NOT NULL,
        `Flight_Departure_Date` varchar(255) NOT NULL,
        `Flight_Type` varchar(255) NOT NULL,
        `Flight_Return_Date` date NOT NULL,
        `Flight_Airplane` varchar(255) NOT NULL,
        PRIMARY KEY (Flight_Number) 
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;"
      );

      $db->query("CREATE TABLE `Ticket` (
        `Flight_Number` varchar(255) NOT NULL,
        `Ticket_Number` varchar(255) NOT NULL,
        `Ticket_Departure_Date` date NOT NULL,
        `Ticket_Departure_Time` time NOT NULL,
        `Ticket_Arrival_Date` date NOT NULL,
        `Ticket_Arrival_Time` time NOT NULL,
        `Ticket_Price` decimal(10) NOT NULL,
        `Ticket_Duration` decimal(10) NOT NULL,
        `Ticket_Class` varchar(255) NOT NULL,
        `Ticket_Meal` varchar(255) NOT NULL,
        `Ticket_Luggage` varchar(255) NOT NULL, 
        `Ticket_Special_Service` varchar(255) NOT NULL,
        `Ticket_Seat` varchar(255) NOT NULL,
        PRIMARY KEY (Ticket_Number),
        FOREIGN KEY (Flight_Number) REFERENCES Flight(Flight_Number)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;"
      );

      $db->query("CREATE TABLE `Promotion` (
        `Promotion_Number` varchar(255) NOT NULL,
        `Ticket_Number` varchar(255) NOT NULL,
        `Old_Price` decimal(10) NOT NULL,
        `New_Price` decimal(10) NOT NULL,
        `OrderIndex` decimal(10) NOT NULL,
        `Promotion_Expiry_Date` date NOT NULL,
        PRIMARY KEY (Promotion_Number),
        FOREIGN KEY (Ticket_Number) REFERENCES 
        Ticket(Ticket_Number)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;"
      );

      $db->query("CREATE TABLE `Voucher` (
        `Voucher_Number` varchar(255) NOT NULL,
        `Voucher_Code` varchar(255) NOT NULL,
        `Voucher_Discount` int(3) NOT NULL,
        `Voucher_Discount_Limit` int(5) NOT NULL,
        `Voucher_Purhcase_Minimum` int(5) NOT NULL,
        `Voucher_Expiry_Date` date NOT NULL,
        PRIMARY KEY (Voucher_Number)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;"
      );

      $db->query("CREATE TABLE `Purchase` (
        `Purchase_Number` varchar(255) NOT NULL,
        `Customer_Email` varchar(255) NOT NULL,
        `Promotion_Number` varchar(255) NOT NULL,
        `Voucher_Number` varchar(255) NOT NULL,
        `Purchase_Amount` varchar(255) NOT NULL,
        `Special_Service` varchar(255) NOT NULL,
        `Meal_Selection` varchar(255) NOT NULL,
        `Seat_Selection` varchar(255) NOT NULL,
        `Purchase_Status` varchar(255) NOT NULL,
        `Purchase_Date` date NOT NULL,
        PRIMARY KEY (Purchase_Number),
        FOREIGN KEY (Customer_Email) REFERENCES Customer(Customer_Email),
        FOREIGN KEY (Promotion_Number) REFERENCES Promotion(Promotion_Number),
        FOREIGN KEY (Voucher_Number) REFERENCES Voucher(Voucher_Number)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;"
      );

      $db->query("CREATE TABLE `Booking` (
        `Booking_Number` varchar(255) NOT NULL,
        `Flight_Number` varchar(255) NOT NULL,
        `Ticket_Number` varchar(255) NOT NULL,
        `Customer_Email` varchar(255) NOT NULL,
        `Purchase_Number` varchar(255) NOT NULL,
        PRIMARY KEY (Booking_Number),
        FOREIGN KEY (Flight_Number) REFERENCES Flight(Flight_Number),
        FOREIGN KEY (Ticket_Number) REFERENCES Ticket(Ticket_Number),
        FOREIGN KEY (Customer_Email) REFERENCES Customer(Customer_Email),
        FOREIGN KEY (Purchase_Number) REFERENCES Purchase(Purchase_Number)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;"
      );

      $db->query("CREATE TABLE `Card` (
        `Card_Number` char(16) NOT NULL,
        `Card_Holder_Name` varchar(255) NOT NULL,
        `Card_Expiry_Date` date NOT NULL,
        `Card_CVV` char(3) NOT NULL,
        `Card_issuance_Bank` varchar(255) NOT NULL,
        `Card_Issuance_Country` varchar(255) NOT NULL,
        `Customer_Email` varchar(255) NOT NULL,
        PRIMARY KEY (Card_Number),
        FOREIGN KEY (Customer_Email) REFERENCES Customer(Customer_Email)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;"
      );

      $db->query("CREATE TABLE `Administrator` (
        `Admin_Email` varchar(255) NOT NULL,
        `Admin_Name` varchar(255) NOT NULL,
        `Admin_Password` varchar(255) NOT NULL,
        `Admin_Phone_Number` varchar(255) NOT NULL,
        PRIMARY KEY (Admin_Email)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;"
      );

      $db->query("CREATE TABLE `Support` (
        `Case_Number` varchar(255) NOT NULL,
        `Case_Category` varchar(255) NOT NULL,
        `Case_Question` varchar(255) NOT NULL,
        `Case_Status` varchar(255) NOT NULL,
        `Date_Opened` date NOT NULL,
        `Date_Closed` date NOT NULL,
        `Customer_Email` varchar(255) NOT NULL,
        `Admin_Email` varchar(255) NOT NULL,
        PRIMARY KEY (Case_Number),
        FOREIGN KEY (Customer_Email) REFERENCES Customer(Customer_Email),
        FOREIGN KEY (Admin_Email) REFERENCES Administrator(Admin_Email)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;"
      );

      $db->query("CREATE TABLE `Meal` (
        `Meal_Number` varchar(255) NOT NULL,
        `Meal_Name` varchar(255) NOT NULL,
        `Meal_Price` decimal(10) NOT NULL,
        `Meal_Type` varchar(255) NOT NULL,
        `Meal_Category` varchar(255) NOT NULL
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;"
      );

      $db->query("CREATE TABLE `Special_Service` (
        `Service_Number` varchar(255) NOT NULL,
        `Service_Name` varchar(255) NOT NULL,
        `Service_Price` decimal(10) NOT NULL,
        `Service_Free` varchar(3) NOT NULL
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;"
      ); 

      $this->seeders($db);

      $db->close();
    }
  }
}
