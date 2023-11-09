<?php

include("./includes/db.php");


// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST")
 {
    // Retrieve data from the form
    $email = $_POST["email"];
    $cin = $_POST["cin"];
    $password = $_POST["password"];
    $adresse = $_POST["adresse"];

    // Prepare and execute the SQL query to insert data into the database
    $stmt = $conn->prepare("INSERT INTO users (email, cin, password, adresse) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $email, $cin, $password, $adresse);
    $stmt->execute();

    if ($stmt->affected_rows > 0) 
    {
        // Data inserted successfully
        echo "Data added to the database successfully.";
        header('Location: connect.php');
    } 
    else
    {
        // Failed to insert data
        echo "Error adding data to the database.";
    }

    // Close the prepared statement
    $stmt->close();
}

// Close the database connection
$conn->close();
?>