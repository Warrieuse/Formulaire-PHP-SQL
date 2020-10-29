<?php
session_start();

$bdd = new PDO('mysql:host=localhost;dbname=espace_membre;charset=utf8', 'root', '');

if(isset($_SESSION['id']))
{
    $getId = intval($_SESSION['id']); // sécurité dans le sens ou si quelqu'un se trompe est met une string alors c'est automatiquement converti en nbr
    $req_id = $bdd->prepare("SELECT * FROM membre WHERE Id = ?");
	$req_id->execute(array($getId));
    $id = $req_id->fetch();
    
    if(isset($_POST['new_pseudo']) AND !empty($_POST['new_pseudo']) AND $_POST['new_pseudo'] != $user['pseudo'])
    {
        $new_pseudo = htmlspecialchars($_POST['new_pseudo']);
        $insert_new_pseudo = $bdd->prepare("UPDATE membre SET Pseudo = ? WHERE Id = ?");
	    $insert_new_pseudo->execute(array($new_pseudo, $_SESSION['id']));
	    header('Location: profil.php?id='.$_SESSION['id']);
    
    }
        if (isset($_POST['new_mail']) AND !empty($_POST['new_mail']) AND $_POST['new_mail'] != $user['mail']) 
        {
            $new_mail = htmlspecialchars($_POST['new_mail']);
	        $insert_new_mail = $bdd->prepare("UPDATE membre SET Mail = ? WHERE Id = ?");
	        $insert_new_mail->execute(array($new_mail, $_SESSION['id']));
	        header('Location: profil.php?id='.$_SESSION['id']);
        }
        if(isset($_POST['new_password']) AND !empty($_POST['new_password']) AND isset($_POST['new_password_conf']) AND !empty($_POST['new_password_conf'])) 
        {
            $mdp1 = htmlspecialchars($_POST['new_password']);
            $mdp2 = htmlspecialchars($_POST['new_password_conf']);
            $mdpRegex = '/^(?=.{10,}$)(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*\W).*$/';
            $mdpTest = str_word_count($mdp1);// calcul du nbr de mot utilisé

            if (((preg_match($mdpRegex, $mdp1)) == 1) AND ($mdpTest == 1)) {
                
                if($mdp1 == $mdp2) 
                {
                    $new_mdp = password_hash($_POST['new_password'], PASSWORD_DEFAULT);
                    $insert_new_mdp = $bdd->prepare("UPDATE membre SET MotDEPasse = ? WHERE Id = ?");
                    $insert_new_mdp->execute(array($new_mdp, $_SESSION['id']));
                    header('Location: profil.php?id='.$_SESSION['id']);
                } 
                else 
                {
                $error = "Vos deux mdp ne correspondent pas !";
                }
            }
        }
    else
    {
        $error = "<p>Tout les champs sont obligatoire !!!</p>";
    }

    
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Espace de <?php echo $_SESSION['pseudo']?></title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <fieldset class="container">
        <h2>Edition de mon profil</h2>
        <p>Veuillez remplir votre nouveau profil</p>
        
        <form action="" method="post">
        <table>
            <tr style="text-align:right">
                <th><label for="new_pseudo">Entrez votre nouveau pseudo :</label></th>
                <th><input type="text" name="new_pseudo" id="" placeholder="Pseudo" size="40"> </th>
            </tr> 
            <tr style="text-align:right">
                <th><label for="new_mail">Entrez votre nouvelle adresse mail :</label></th>
                <th><input type="text" name="new_mail" id="" placeholder="Email" size="40"></th>
            </tr>  
            <tr style="text-align:right">
                <th><label for="new_password">Entrez votre nouveau mot de passe :</label></th>
                <th><input type="text" name="new_password" id="" placeholder="Mot de passe" size="40"></th>
            </tr> 
            <tr style="text-align:right">
                <th><label for="new_password_conf">Veuillez confirmer votre nouveau mot de passe :</label></th> 
                <th><input type="text" name="new_password_conf" id="" placeholder="confirmation de mot de passe" size="40"></th>
        </table><br>
            <input type="submit" name="btn_submit" value="mettre à jour mon profil" size="40">
        </form>

        <?php
            if(isset($error))
            {
                echo $error;
            }
        ?>

        <div id="ConditionMdp" style="display:block; text-align:center; border: 1px solid red; margin:0px 240px;">
            <ul>Votre mot de passe doit contenir : 
                <li>Au moins 10 caractères</li>
                <li>Au moins 1 majuscule</li>
                <li>Au moins 1 minuscule</li>
                <li>Au moins 1 chiffre</li>
                <li>Au moins 1 caractère spécial</li>
            </ul>
        </div>
        
        <script src="main.js"></script>
    </fieldset>
</body>
</html>
<?php
}
else
{
    header("Location: connection.php");  
}
?>