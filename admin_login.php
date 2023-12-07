<?php
session_start();

include("./includes/db.php");

$stmt = $conn->prepare("SELECT * FROM admins WHERE admin_id = ?");
$stmt->bind_param("i", $admin_id);
$stmt->execute();
$result = $stmt->get_result();


$contactsResult = $conn->query("SELECT * FROM contact_messages");
$contact_messages = $contactsResult->fetch_all(MYSQLI_ASSOC);

$allDeliveriesResult = $conn->query("SELECT * FROM dashboard");
$allDeliveries = $allDeliveriesResult->fetch_all(MYSQLI_ASSOC);

$allusersResult = $conn->query("SELECT * FROM users");
$userlist = $allusersResult->fetch_all(MYSQLI_ASSOC);

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["modify_status"])) 
{
    $delivery_id = $_POST["delivery_id"];
    $new_status = $_POST["new_status"];

    $stmt = $conn->prepare("UPDATE dashboard SET delivery_stat = ? WHERE delivery_id = ?");
    $stmt->bind_param("si", $new_status, $delivery_id);
    $stmt->execute();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["modify_info"])) 
{
    $delivery_id = $_POST["delivery_id"];
    $new_name = $_POST["new_name"];
    $new_address = $_POST["new_address"];
    $new_cin = $_POST["new_cin"];

    $stmt = $conn->prepare("UPDATE dashboard SET delivery_name = ?, delivery_address = ?, delivery_cin = ? WHERE delivery_id = ?");
    $stmt->bind_param("sssi", $new_name, $new_address, $new_cin, $delivery_id);
    $stmt->execute();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["delete_user"])) 
{
    $user_id = $_POST["user_id"];

    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["disconnect"])) 
{
    session_destroy();
    header("Location: connect.php");
    exit();
}

$stmt->close();
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Admin Dashboard</title>

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
            background-color: #4CAF50;
            color: #fff;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-bottom: 15px;
        }

        button:hover {
            background-color: #45a049;
        }

        table {
            border-collapse: collapse;
            width: 100%;
            margin: auto;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            overflow: auto;
            text-align: center;
        }

        table th,
        table td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: center;
        }

        table th {
            background-color: #4CAF50;
            color: white;
            text-align: center;
        }

        table tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        table tr:hover {
            background-color: #f1f1f1;
        }

        nav {
            background-color: #333;
            padding: 10px;
            text-align: center;
        }

        nav button {
            margin: 0 10px;
        }

        .hidden 
        {
            display: none;
        }
    </style>
</head>

<body>
    <nav>
        <button onclick="showAllContacts()">Afficher tous les contacts</button>
        <button onclick="showAllDeliveries()">Afficher toutes les livraisons</button>
        <button onclick="toggleForm('modifyStatusForm')">Modifier le statut de livraison</button>
        <button onclick="toggleForm('modifyInfoForm')">Modifier les informations de livraison</button>
        <button onclick="toggleForm('userlist')">Liste des Utilisateurs </button>
        <button onclick="toggleForm('deleteUserForm')">Supprimer un utilisateur</button>
        <br><br>
        <form method="POST" action="">
            <button type="submit" name="disconnect">Déconnecter</button>
        </form>
    </nav>

    <div class="container">
        <h2>Bienvenue dans le tableau de bord de l'administrateur !</h2>

        <form method="POST" action="" id="modifyStatusForm" class="hidden">
            <label for="delivery_id">ID de Livraison:</label>
                <input type="text" name="delivery_id" required>
                <label for="new_status">Nouveau Statut:</label>
            <select name="new_status" required>
                <option value="ok">OK</option>
                <option value="En Cours">En Cours</option>
                <option value="no">Non</option>
            </select><br><br><br>
            <button type="submit" name="modify_status">Modifier</button>
        </form>


        <form method="POST" action="" id="modifyInfoForm" class="hidden">
            <label for="delivery_id">ID de Livraison:</label>
            <input type="text" name="delivery_id" required>
            <label for="new_name">Nouveau Nom:</label>
            <input type="text" name="new_name" required>
            <label for="new_address">Nouvelle Adresse:</label>
            <input type="text" name="new_address" required>
            <label for="new_cin">Nouveau CIN:</label>
            <input type="text" name="new_cin" required>
            <button type="submit" name="modify_info">Modifier Informations</button>
        </form>

        <form method="POST" action="" id="deleteUserForm" class="hidden">
            <label for="user_id">ID de l'Utilisateur à Supprimer:</label>
            <input type="text" name="user_id" required>
            <button type="submit" name="delete_user">Supprimer Utilisateur</button>
        </form>

        <table id="allContacts" class="hidden">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nom</th>
                    <th>Email</th>
                    <th>Sujet</th>
                    <th>Message</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($contact_messages as $contact) 
                {
                    echo "<tr>";
                    echo "<td>{$contact['id']}</td>";
                    echo "<td>{$contact['name']}</td>";
                    echo "<td>{$contact['email']}</td>";
                    echo "<td>{$contact['subject']}</td>";
                    echo "<td>{$contact['message']}</td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>

        <table id="allDeliveries" class="hidden">
            <thead>
                <tr>
                    <th>ID de Livraison</th>
                    <th>Nom</th>
                    <th>Adresse</th>
                    <th>CIN</th>
                    <th>Statut</th>
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

        <table id="userlist" class="hidden">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nom</th>
                    <th>CIN</th>
                    <th>Email</th>
                    <th>Adresse</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($userlist as $uls) 
                {
                    echo "<tr>";
                    echo "<td>{$uls['id']}</td>";
                    echo "<td>{$uls['nom']}</td>";
                    echo "<td>{$uls['email']}</td>";
                    echo "<td>{$uls['cin']}</td>";
                    echo "<td>{$uls['adresse']}</td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <script>
        function toggleForm(formId)
        
        {
            var form = document.getElementById(formId);
            form.classList.toggle('hidden');
        }

        function showAllContacts() 
        {
            var allContactsTable = document.getElementById('allContacts');
            allContactsTable.style.display = (allContactsTable.style.display === 'none') ? 'table' : 'none';
        }

        function showAllDeliveries() 
        {
            var allDeliveriesTable = document.getElementById('allDeliveries');
            allDeliveriesTable.style.display = (allDeliveriesTable.style.display === 'none') ? 'table' : 'none';
        }

        function showallusers() 
        {
            var allusers = document.getElementById('userlist');
            allusers.style.display = (allusers.style.display === 'none') ? 'table' : 'none';
        }
    </script>
</body>

</html>
