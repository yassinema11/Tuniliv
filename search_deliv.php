<?php

include("./includes/db.php");

if ($_SERVER["REQUEST_METHOD"] == "POST")
 {
    $deliveryCode = $_POST["delivery_code"];

    $stmt = $conn->prepare("SELECT * FROM dashboard WHERE delivery_id = ?");
    $stmt->bind_param("s", $deliveryCode);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0)
     {
        $deliveryData = $result->fetch_assoc();

        header("Location: tracker.php?delivery_id=" . $deliveryData['delivery_id']);
        exit();
    } 
    else 
    {
        echo "<script>alert('Information de livraison non trouvée !')</script>";
        exit();
    }

    $stmt->close();
}

$conn->close();
?>
