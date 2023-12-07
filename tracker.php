<?php

include("./includes/db.php");


if (isset($_GET["delivery_id"]))
{
    $deliveryId = $_GET["delivery_id"];


    $stmt = $conn->prepare("SELECT * FROM dashboard WHERE delivery_id = ?");
    $stmt->bind_param("s", $deliveryId);
    $stmt->execute();
    $result = $stmt->get_result();


    if ($result->num_rows > 0) 
    {

        $deliveryData = $result->fetch_assoc();
    } 
    else 
    {

        echo "<script>alert('Information de livraison non trouvée !')</script>";
        header("Location: index.php");
        exit();
    }

    $stmt->close();
} 
else 
{
    echo "<script>alert('Information de livraison non trouvée !')</script>";
    header("Location: index.php");
    exit();
}


$conn->close();
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Tracker Livraison</title>

    <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">


    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,600;1,700&family=Poppins:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&family=Inter:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet">


    <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
    <link href="assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">
    <link href="assets/vendor/aos/aos.css" rel="stylesheet">


    <link href="assets/css/main.css" rel="stylesheet">


<body>

  <header id="header" class="header d-flex align-items-center fixed-top">
    <div class="container-fluid container-xl d-flex align-items-center justify-content-between">

      <a href="index.php" class="logo d-flex align-items-center">

        <h1>TuniLiv</h1>
      </a>

      <i class="mobile-nav-toggle mobile-nav-show bi bi-list"></i>
      <i class="mobile-nav-toggle mobile-nav-hide d-none bi bi-x"></i>
      <nav id="navbar" class="navbar">
        <ul>
          <li><a href="index.php" class="active"> Accueil </a></li>
        
          <li><a href="contact.php">Contact</a></li>

          <li><a href="register.php">S'inscrire</a></li>
          <li><a class="get-a-quote" href="connect.php">Se Connecter</a></li>
        </ul>
      </nav>
    </div>
  </header>


  <section id="hero" class="hero d-flex align-items-center">
    <div class="container">
      <div class="row gy-4 d-flex justify-content-between">
        <div class="col-lg-6 order-2 order-lg-1 d-flex flex-column justify-content-center">
        <br><br><br><br>
            <h2>Information de Livraison (ID: <?php echo $deliveryData['delivery_id']; ?>)</h2><br><br>
            <p><strong>Nom:</strong> <?php echo $deliveryData['delivery_name']; ?> <br><br>
            <strong>Address:</strong> <?php echo $deliveryData['delivery_address']; ?><br><br>
            <strong>Status:</strong> <?php echo $deliveryData['delivery_stat']; ?></p><br><br><br><br><br>
        </div>        
      </div>
    </div>
  </section>

</body>

</html>
