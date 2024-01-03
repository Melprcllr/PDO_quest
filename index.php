<?php
require_once '_connec.php';

$pdo = new \PDO(DSN, USER, PASS);

$query = "SELECT * FROM friend";
$statement = $pdo->query($query);
$friends = $statement->fetchAll();;
?>


<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <h1>FRIENDS</h1>

    <h2>Liste des Amis</h2>
    <ul>
        <?php foreach ($friends as $friend) { ?>
            <li><?= $friend['firstname'] ?> <?= $friend['lastname'] ?></li>
        <?php } ?>
    </ul>

    <h2>Ajouter un ami via le formulaire</h2>
    <form method="POST" action="index.php">
        <label for="firstname">Firstname :</label>
        <input type="text" id="firstname" name="firstname" required maxlength="45">
        <form method="POST" action="index.php">
            <label for="lastname">Lastname :</label>
            <input type="text" id="lastname" name="lastname" required maxlength="45">
            <input type="submit" value="Add friend">
        </form>


        <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            //stocke les valeurs insérées dans le formulaire
            $firstname = $_POST['firstname'];
            $lastname = $_POST['lastname'];

            //sécurise les données
            if (
                !empty($firstname)
                && !empty($lastname)
                && strlen($firstname) <= 45
                && strlen($lastname) <= 45
            ) {
                $insertquery = "INSERT into friend (firstname, lastname) VALUES (:firstname, :lastname)";
                $insertStatement = $pdo->prepare($insertquery);
                $insertStatement->bindValue(":firstname", $firstname);
                $insertStatement->bindValue(":lastname", $lastname);
            }

            if ($insertStatement->execute()) {
                header('location: index.php');
                exit;
            } else {
                echo "Erreur lors de l'insertion.";
            }
        } else {
            echo "Les prénom et nom doivent être remplis et avoir moins de 45 caractères.";
        }
        ?>
</body>

</html>