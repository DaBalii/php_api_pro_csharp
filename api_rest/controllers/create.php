<?php
//les entêtes requise
header("Access-control-Allow-origin:*");
header("content-type: application/json;charset=UTF-8");
header("Access-control-Allow-Methods:POST");


require_once'../config/Database.php';
require_once'../models/auteur.php';

if($_SERVER['REQUEST_METHOD']==="POST"){
    //on instancie la basede donnée
    $database= new Database();
    $db=$database->getConnexion();

    //on istancie l'objet client
    $auteur = new Auteur($db);
    //on recupere les infos
    $data=json_decode(file_get_contents("php://input"));
    if(!empty($data->nom)&& !empty($data->affil)
    && !empty($data->email)&& !empty($data->idArt)){

        //hydratation l'objet etudiant
        $auteur->nom = htmlspecialchars($data->nom);
        $auteur->affil= htmlspecialchars($data->affil);
        $auteur->email = htmlspecialchars($data->email);     
        $auteur->idArt = intval($data->idArt);

        $result = $auteur->create();
        if($result){
            http_response_code(201);
            echo json_encode(["message"=>"etudiant ajouter avec succes"]);
        }else{
            http_response_code(503);
            echo json_encode(["message"=>"ajout de l'etudiant a echoué"]);
        }
    }else{
        echo json_encode(["message"=>"donnée incomplet"]);
    }
}else{
    http_response_code(405);
    echo json_encode(["message"=>"methode non autoriser"]);
}

?>