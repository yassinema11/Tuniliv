<?php
session_start();

if (!isset($_SESSION['id'])) 
{
    header("Location: connect.php");
    exit();
}

include("./includes/db.php");

$id = $_SESSION['id'];
$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0)
{
    $user = $result->fetch_assoc();
} 
else 
{
    header("Location: connect.php");
    exit();
}

$stmt = $conn->prepare("SELECT * FROM dashboard WHERE user_id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$dashboardResult = $stmt->get_result();

if ($dashboardResult->num_rows > 0) 
{
    $dashboardData = $dashboardResult->fetch_assoc();
} 
else 
{
    $dashboardData = array(); 
}

if ($_SERVER["REQUEST_METHOD"] == "POST") 
{
    if (isset($_POST["add_delivery"])) 
    {
        $delivery_id = $_POST["delivery_id"];
        $delivery_name = $_POST["delivery_name"];
        $delivery_address = $_POST["delivery_address"];
        $delivery_cin = $_POST["delivery_cin"];


        $stmt = $conn->prepare("INSERT INTO dashboard (user_id, delivery_id, delivery_name, delivery_address, delivery_cin) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("issss", $id, $delivery_id, $delivery_name, $delivery_address, $delivery_cin);
        $stmt->execute();
    } 
    elseif (isset($_POST["delete_delivery"]))
    {
        $delete_delivery_id = $_POST["delete_delivery_id"];

        $stmt = $conn->prepare("DELETE FROM dashboard WHERE user_id = ? AND delivery_id = ?");
        $stmt->bind_param("ii", $id, $delete_delivery_id);
        $stmt->execute();
    } 
    elseif (isset($_POST["modify_delivery"])) 
    {
        $modify_delivery_id = $_POST["modify_delivery_id"];
        $new_delivery_address = $_POST["new_delivery_address"];
        $new_delivery_name = $_POST["new_delivery_name"];
        $new_delivery_cin = $_POST["new_delivery_cin"];

        $stmt = $conn->prepare("UPDATE dashboard SET delivery_address = ?, delivery_name = ?, delivery_cin = ? WHERE user_id = ? AND delivery_id = ?");
        $stmt->bind_param("sssii", $new_delivery_address, $new_delivery_name, $new_delivery_cin, $id, $modify_delivery_id);
        $stmt->execute();
    }
}

$stmt = $conn->prepare("SELECT * FROM dashboard WHERE user_id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$dashboardResult = $stmt->get_result();

if ($dashboardResult->num_rows > 0) 
{
    $dashboardData = $dashboardResult->fetch_assoc();
} 
else 
{
    $dashboardData = array(); 
}

$stmt->close();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["disconnect"]))
{
    session_destroy();
    header("Location: connect.php");
    exit();
}


$stmt = $conn->prepare("SELECT * FROM dashboard WHERE user_id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$allDeliveriesResult = $stmt->get_result();

$allDeliveries = $allDeliveriesResult->fetch_all(MYSQLI_ASSOC);

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["modify_profile"])) 
{
    $new_nom = $_POST["new_nom"];
    $new_cin = $_POST["new_cin"];
    $new_email = $_POST["new_email"];
    $new_password = $_POST["new_password"];
    $new_adresse = $_POST["new_adresse"];

    $updateStmt = $conn->prepare("UPDATE users SET nom = ?, cin = ?, email = ?, password = ?, adresse = ? WHERE id = ?");
    $updateStmt->bind_param("sssssi", $new_nom, $new_cin, $new_email, $new_password, $new_adresse, $id);
    $updateStmt->execute();

    if ($updateStmt->affected_rows > 0)
    {
        echo "Profil modifié avec succès.";
    } 
    else 
    {
        echo "Erreur lors de la modification du profil.";
    }

    $updateStmt->close();
}


?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Dashboard</title>

    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            text-align: center;
        }

        .container {
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            text-align: left; 
        }

        h2 {
            color: #333;
        }

        h3 {
            color: #555;
        }

        label {
            display: block;
            margin-bottom: 5px;
            color: #333;
        }

        input {
            width: 100%;
            padding: 8px;
            margin-bottom: 15px;
            box-sizing: border-box;
        }

        button {
            background-color: #0000FF;
            color: #fff;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-bottom: 15px;
        }

        button:hover {
            background-color: #89CFEF;
        }

        .hidden {
            display: none;
        }

        nav {
            background-color: #0000;
            padding: 10px;
            text-align: center;
        }

        nav button {
            margin: 0 10px;
        }


        #allDeliveries {
            border-collapse: collapse;
            width: 40%; 
            margin:  50px auto;; 
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            overflow: auto;
            text-align: center;
}


        #allDeliveries th, #allDeliveries td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
            text-align: center;

        }

        #allDeliveries th {
            background-color: #0000FF;
            color: white;
            text-align: center;

        }

        #allDeliveries tr:nth-child(even) 
        {
            background-color: #0000FF;
        }

        #allDeliveries tr:hover 
        {
            background-color: #0000FF;
        }
    </style>


    <script>
        function toggleForm(formId) 
        {
            var form = document.getElementById(formId);
            form.classList.toggle('hidden');
        }

        function showAllDeliveries() 
        {
            var allDeliveriesDiv = document.getElementById('allDeliveries');
            allDeliveriesDiv.style.display = (allDeliveriesDiv.style.display === 'none') ? 'block' : 'none';
        }
    </script>

</head>

<body>
    <br>
    <nav>
        <button onclick="toggleForm('addForm')">Ajouter Livraison</button>
        <button onclick="toggleForm('deleteForm')">Supprimer Livraison</button>
        <button onclick="toggleForm('modifyForm')"> Modifer Livraison </button>
        <button onclick="showAllDeliveries()"> Afficher tous les livraison</button>
        <button onclick="toggleForm('profileForm')">Modifier le profil</button>
    <br><br>
       

    </nav>

    <div class="container">
        <h2>Bienvenu dans votre Dashboard, <?php echo $user['nom']; ?>!</h2>
        <p>Votre ID: <?php echo $user['id']; ?></p>



        <form method="POST" action="" id="addForm" class="hidden">
            <label for="delivery_id"> ID de Livraison:</label>
            <input type="text" name="delivery_id" required>
            <label for="delivery_name">Ajouter Nom et Prénom:</label>
            <input type="text" name="delivery_name" required>
            <label for="delivery_cin">Ajouter CIN:</label>
            <input type="text" name="delivery_cin" required>
            <label for="delivery_address">Ajouter Address:</label>
            <input type="text" name="delivery_address" required>
            <button type="submit" name="add_delivery"> Ajouter Livraison</button>
        </form>

        <form method="POST" action="" id="deleteForm" class="hidden">
            <label for="delete_delivery_id">ID de livraison a supprimer:</label>
            <input type="text" name="delete_delivery_id" required>
            <button type="submit" name="delete_delivery">Supprimer Livraison</button>
        </form>

        <form method="POST" action="" id="modifyForm" class="hidden">
            <label for="modify_delivery_id">ID de Livraison a modifier:</label>
            <input type="text" name="modify_delivery_id" required>
            <label for="new_delivery_name">Nv Nom:</label>
            <input type="text" name="new_delivery_name" srequired>
            <label for="new_delivery_address">Nv Address:</label>
            <input type="text" name="new_delivery_address" required>
            <label for="new_delivery_cin">Nv CIN:</label>
            <input type="text" name="new_delivery_cin" required>
            <button type="submit" name="modify_delivery">Modifier Livraison</button>
        </form>

        <form method="POST" action="" id="profileForm" class="hidden">
            <label for="new_nom">Nouveau nom:</label>
            <input type="text" name="new_nom" value="<?php echo $user['nom']; ?>" required>
            <label for="new_cin">Nouveau CIN:</label>
            <input type="text" name="new_cin" value="<?php echo $user['cin']; ?>" required>
            <label for="new_email">Nouvel email:</label>
            <input type="email" name="new_email" value="<?php echo $user['email']; ?>" required>
            <label for="new_password">Nouveau mot de passe:</label>
            <input type="password" name="new_password" required>
            <label for="new_adresse">Nouvelle adresse:</label>
            <input type="text" name="new_adresse" value="<?php echo $user['adresse']; ?>" required>
            <button type="submit" name="modify_profile">Modifier le profil</button>
        </form>
    </div>


    <div id="allDeliveries" style="display: none;">
    <h3>Tous les information de Livraisons:</h3>
    <br>
    <table border="1">
        <thead>
            <tr>
                <th> ID de Livraison </th>
                <th>Nom</th>
                <th>Addresse</th>
                <th>CIN</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($allDeliveries as $delivery)
             {
                echo "<tr>";
                    echo "<td>{$delivery['delivery_id']}</td>";
                    echo "<td>{$delivery['delivery_name']}</td>";
                    echo "<td>{$delivery['delivery_address']}</td>";
                    echo "<td>{$delivery['delivery_cin']}</td>";
                    echo "<td>{$delivery['delivery_stat']}</td>";
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>
</div>

    </div>

    <form method="POST" action="">
            <button type="submit" name="disconnect"> Déconnecter </button>
        </form>
</body>

</html>
