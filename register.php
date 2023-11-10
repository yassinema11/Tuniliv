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
    $checkStmt = $conn->prepare("SELECT id FROM users WHERE cin = ?");
    $checkStmt->bind_param("s", $cin);
    $checkStmt->execute();
    $checkResult = $checkStmt->get_result();

    if ($checkResult->num_rows > 0) 
    {
          echo "<script>alert(' This user Exist !')</script>";
    } 
    else
    {
        // User does not exist, proceed with insertion
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

<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Tuni-Livraison</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,600;1,700&family=Poppins:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&family=Inter:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
  <link href="assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
  <link href="assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">
  <link href="assets/vendor/aos/aos.css" rel="stylesheet">

  <!-- Template Main CSS File -->
  <link href="assets/css/main.css" rel="stylesheet">

  <style>
    /* Main Section Styles */
    .call-to-action {
      background-color: #007bff;
      color: #fff;
      padding: 100px 0;
    }

    /* Login Form Styles */
    form {
      max-width: 400px;
      margin: auto;
      background-color: #fff;
      padding: 20px;
      border-radius: 8px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    form input {
      width: 100%;
      padding: 10px;
      margin-bottom: 20px;
      border: 1px solid #ccc;
      border-radius: 5px;
      box-sizing: border-box;
    }

    form button {
      background-color: #007bff;
      color: #fff;
      border: none;
      padding: 10px 15px;
      border-radius: 5px;
      cursor: pointer;
      font-weight: bold;
    }

    form button:hover {
      background-color: #0056b3;
    }
  </style>

</head>

<body>

  <!-- ======= Header ======= -->
  <header id="header" class="header d-flex align-items-center fixed-top">
    <div class="container-fluid container-xl d-flex align-items-center justify-content-between">

      <a href="index.php" class="logo d-flex align-items-center">
        <!-- Uncomment the line below if you also wish to use an image logo -->
        <!-- <img src="assets/img/logo.png" alt=""> -->
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
      </nav><!-- .navbar -->

    </div>

  </header><!-- End Header -->
  <!-- End Header -->

  <!-- ======= Call To Action Section ======= -->
  <section id="call-to-action" class="call-to-action">
    <div class="container" data-aos="zoom-out">

    </div>
  </section><!-- End Call To Action Section -->

  <br><br><br>
  <div class="row justify-content-center">
    <div class="col-lg-8 text-center">
      <h3> S'inscrire </h3>
      <p> Inscrivez-vous à votre espace </p>

      <form method="POST" action="register.php">
        <input type="text" name="nom" placeholder="Nom" required><br><br>
        <input type="number" name="cin" placeholder="CIN" required><br><br>
        <input type="email" name="email" placeholder="Email" required><br><br>
        <input type="password" name="password" placeholder="Mot de passe" required><br><br>
        <input type="text" name="adresse" placeholder="Adresse" required><br><br>
        <button type="submit">S'inscrire</button>
      </form>
    </div>
  </div>

  <br><br><br><br>
  <br><br><br>

  <footer id="footer" class="footer">

    <div class="container">
      <div class="row gy-4">
        <div class="col-lg-5 col-md-12 footer-info">
          <a href="index.php" class="logo d-flex align-items-center">
            <span>TuniLiv</span>
          </a>
          <p>We are the best</p>
          <div class="social-links d-flex mt-4">
            <a href="#" class="twitter"><i class="bi bi-twitter"></i></a>
            <a href="#" class="facebook"><i class="bi bi-facebook"></i></a>
            <a href="#" class="instagram"><i class="bi bi-instagram"></i></a>
            <a href="#" class="linkedin"><i class="bi bi-linkedin"></i></a>
          </div>
        </div>

        <div class="col-lg-2 col-6 footer-links">
          <h4>Useful Links</h4>
          <ul>
            <li><a href="index.php">Accueil</a></li>
            <li><a href="connect.php">Se Conneter</a></li>
            <li><a href="register.php">S'inscrire</a></li>

          </ul>
        </div>
        <div class="col-lg-3 col-md-12 footer-contact text-center text-md-start">
          <h4>Contact Us</h4>
          <p>
            ISET RADES <br>
            2098 RADES<br>
            Ben Arous - Tunisia <br><br>
            <strong>Téléphone:</strong> +216 21 345 678 <br>
            <strong>Email:</strong> info@tuniliv.tn<br>
          </p>

        </div>

      </div>
    </div>

    <div class="container mt-4">
      <div class="copyright">
        2023 &copy; Copyright <strong><a href="index.php"><span>TUNILIV</span></a></strong>. All Rights Reserved
      </div>
      <div class="credits">
        Designed by       Designed by Yassine MANAI & Sofiene ZAYATI</>

      </div>
    </div>

  </footer><!-- End Footer -->
  <!-- End Footer -->

  <a href="#" class="scroll-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <div id="preloader"></div>

  <!-- Vendor JS Files -->
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/purecounter/purecounter_vanilla.js"></script>
  <script src="assets/vendor/glightbox/js/glightbox.min.js"></script>
  <script src="assets/vendor/swiper/swiper-bundle.min.js"></script>
  <script src="assets/vendor/aos/aos.js"></script>
  <script src="assets/vendor/php-email-form/validate.js"></script>

  <!-- Template Main JS File -->
  <script src="assets/js/main.js"></script>

</body>

</html>