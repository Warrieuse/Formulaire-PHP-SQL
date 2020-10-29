<?php
session_start();

$bdd = new PDO('mysql:host=localhost;dbname=espace_membre;charset=utf8', 'root', '');

if(isset($_GET['id']) AND $_GET['id'] > 0)
{
    $getid = intval($_GET['id']);
	$requser = $bdd->prepare('SELECT * FROM membre WHERE Id = ?');
	$requser->execute(array($getid));
	$userInfo = $requser->fetch();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Espace</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <fieldset class="container">
        <h2>Profil de  <?php echo $_SESSION['pseudo'];?></h2>
        <br> <br>
        Pseudo = <?php echo $_SESSION['pseudo'] ?>
        <br>
        Mail = <?php echo $_SESSION['mail'] ?>
    
        <?php
            if(isset($_SESSION['id']))
            {
            ?>
            <br><br>
            <a href="edition_profil.php">Editer mon profil</a>
            <a href="deconnection.php">Se déconnecter</a>
            <?php
            }
            ?>

        <?php
        
        if(isset($error))
        {
            echo $error;
        }
        ?>

        <script src="main.js"></script>
    </fieldset>
</body>
</html>
<?php
}
else
{
    echo "Non connecté";
}
?>