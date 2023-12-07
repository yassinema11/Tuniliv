<?php
include("./includes/db.php");


if ($_SERVER["REQUEST_METHOD"] == "POST") 
{
    $nom = $_POST["nom"];
    $email = $_POST["email"];
    $cin = $_POST["cin"];
    $password = $_POST["password"];
    $adresse = $_POST["adresse"];

    // Check if the user already exists
    $checkStmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $checkStmt->bind_param("s", $email);
    $checkStmt->execute();
    $checkResult = $checkStmt->get_result();

    if ($checkResult->num_rows > 0) 
    {
        echo "Error: User with this email already exists.";
    } 
    else 
    {
        $insertStmt = $conn->prepare("INSERT INTO users (nom, email, cin, password, adresse) VALUES (?, ?, ?, ?, ?)");
        $insertStmt->bind_param("sssss", $nom, $email, $cin, $password, $adresse);
        $insertStmt->execute();

        if ($insertStmt->affected_rows > 0)
         {
            echo "Data added to the database successfully.";
            header('Location: connect.php');
        } 
        else 
        {
            echo "Error adding data to the database.";
        }

        $insertStmt->close();
    }

    $checkStmt->close();
    $conn->close();
}
?>
