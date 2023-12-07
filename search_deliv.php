<?php
// Include your database connection file
include("./includes/db.php");

if ($_SERVER["REQUEST_METHOD"] == "POST")
 {
    // Get the delivery code from the form
    $deliveryCode = $_POST["delivery_code"];

    // Prepare and execute a query to search for delivery information
    $stmt = $conn->prepare("SELECT * FROM dashboard WHERE delivery_id = ?");
    $stmt->bind_param("s", $deliveryCode);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if delivery information exists
    if ($result->num_rows > 0)
     {
        // Fetch delivery data
        $deliveryData = $result->fetch_assoc();

        // Redirect to the tracker page with delivery details
        header("Location: tracker.php?delivery_id=" . $deliveryData['delivery_id']);
        exit();
    } 
    else 
    {
        // Redirect to an error page if delivery information is not found
        echo "<script>alert('Information de livraison non trouvée !')</script>";
        exit();
    }

    $stmt->close();
}

// Close the database connection
$conn->close();
?>
