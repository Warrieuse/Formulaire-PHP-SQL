<?php
session_start();

$bdd = new PDO('mysql:host=localhost;dbname=espace_membre;charset=utf8', 'root', '');

if(isset($_POST['validationConnect'])) // condition validation sur bouton
{
    $pseudoConnect = htmlspecialchars($_POST['pseudoconnect']);
    $mdpConnect = ($_POST['mdpconnect']); 

    // afficher la date réelle de la connexion
    date_default_timezone_set('Europe/Paris');
    $date = strftime('%Y-%m-%d %H:%M:%S');

       // condition tous champs doivent etre remplis
    if(!empty($pseudoConnect) AND !empty($mdpConnect))
    {
        //J'enregistre la tentative de connexion reussi ou non 
        $requPseudoUser = $bdd->prepare("SELECT Pseudo FROM membre WHERE Pseudo = ?");
        $requPseudoUser->execute(array($pseudoConnect));
        $userExist = $requPseudoUser->rowCount();/*On compte le nbr de colonne*/
        
        if ($userExist == 1)
        {
            $mdp = $bdd->prepare("SELECT MotDePasse FROM membre WHERE Pseudo = ?");
            $mdp->execute(array($pseudoConnect));
            $mdpfetch = $mdp->fetchAll(PDO::FETCH_ASSOC);
           

            if(password_verify($mdpConnect,$mdpfetch[0]['MotDePasse']))
            {
                $requUser = $bdd->prepare("SELECT * FROM membre WHERE Pseudo = '$pseudoConnect'");
                $requUser->execute();
                $userInfo = $requUser->fetch();
                $_SESSION['id'] = $userInfo['Id'];
                $_SESSION['pseudo'] = $userInfo['Pseudo'];
                $_SESSION['mail'] = $userInfo['Mail'];
                header("Location: profil.php?id=".$_SESSION['id']);
                echo "<br>".$userInfo['id'];
            }
            else
            {
                $error = "<p style = \"color: red;\">Votre mot de passe est incorrect !!</p>";
            }
        } 
        else 
        {
            $error = "<p style = \"color: red;\">Votre identifiant n'existe pas !!</p>";
        }              
    }            
    else
    {
        $error = "<p style = \"color: red;\">Tous les champs doivent être remplis</p>";
    }
}
else
{
    $error = "<p style = \"font-weight: bold;\">Veuillez entrez votre identifiant et votre mot de passe pour vous connecter</p>";
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion mon espace</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <fieldset class="container">
        <legend align="center" style="font-size:2em; font-weight;bold;">Connexion</legend>
        <br> 

        <form action="" method="POST" >
        <table>
            <tr>
            <td style="text-align:right;"><label for="pseudoconnect">Entrez votre pseudo : </label></td>
            <td><input type="text" name="pseudoconnect" id="pseudoconnect" placeholder="Votre pseudo" size="20" value="<?php if (isset($pseudoConnect)) {echo $pseudoConnect;}?>" ></td>                   
            </tr>
            <tr>
            <td><label for="mdpconnect">Entrez votre mot de passe : </label></td>
            <td><input type="password" name="mdpconnect" id="mdpconnect" placeholder="EntrezVotreMotDePasse*1" alt="Mot de passe" size="20"></td>
            </tr>
            </table>  
            <input type="submit" value="connection" name="validationConnect">

        </form>
        <?php
        
        if(isset($error))
        {
            echo $error;
        }
        ?>

        <script src="main.js"></script></tr>
    </fieldset>
</body>
</html>