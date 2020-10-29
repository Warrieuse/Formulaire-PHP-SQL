<?php
session_start();

function GetDataBaseConnection(){
}

function select_id_bdd_espace_membre($id){
    $getId = intval($_SESSION['id']); // sécurité dans le sens ou si quelqu'un se trompe est met une string alors c'est automatiquement converti en nbr
    $req_id = $bdd->prepare("SELECT * FROM membres WHERE id = ?");
	$requ_id->execute(array($getId));
	$id = $req_id->fetch();
}

function update_mail_bdd_espace_membre($new_mail){
        
}
?>