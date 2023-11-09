<?php 

include("./includes/db.php");
// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST")
{
    // Retrieve data from the form
    $email = $_POST["email"];
    $password = $_POST["password"];

    // Prepare and execute the SQL query to retrieve data from the database
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ? AND password = ?");
    $stmt->bind_param("ss", $email, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) 
    {
        // Correct data
        echo "Login successful.";
        
        // Start a session
        session_start();

        // Store the user information in the session variables
        $_SESSION["email"] = $email;
        $_SESSION["password"] = $password;

        // You can store more user information in session variables if needed
        // For example: $_SESSION["name"] = $name;

        // Redirect user to a logged-in page
        header("Location: dash.php");
        exit();

    } 
    else 
    {
        // Incorrect data
        echo "Invalid email, CIN, or password.";
    }

    // Close the prepared statement
    $stmt->close();
}

// Close the database connection
$conn->close();
?>