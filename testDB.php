<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php 
    
    require 'database-manager.php';

    $db = new DatabaseManager();
    $db->query("INSERT INTO `Customer` (`Customer_Name`, `Customer_Phone_Number`, `Customer_Email`, `Customer_Password`, `Customer_Gender`, `Customer_DOB`, `Customer_Age`, `Customer_Occupation`, `Customer_Nationality`, `Travel_Document_Number`) 
      VALUES ('Wathiq', '+01111442159', '43211abc@gmail.com', 'ggg', 'Male', '2002-10-09', '19', 'student', 'Oman', 'OM1234548')");
    //$db->query("");
    // ^ put ur query in the brackets
    
    ?>
</body>
</html>