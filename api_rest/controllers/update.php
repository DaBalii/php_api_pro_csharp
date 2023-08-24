<?php
//les entêtes requise
header("Access-control-Allow-origin:*");
header("content-type: application/json;charset=UTF-8");
header("Access-control-Allow-Methods:PUT");


require_once'../config/Database.php';
require_once'../models/auteur.php';

if($_SERVER['REQUEST_METHOD']==="PUT"){
    //on instancie la basede donnée
    $database= new Database();
    $db=$database->getConnexion();

    //on istancie l'objet client
    $auteur= new Auteur($db);
    //on recupere les infos
    $data=json_decode(file_get_contents("php://input"));
 
    if(!empty($data->idAu)&&!empty($data->nom) && !empty($data->affil)
        && !empty($data->email)&& !empty($data->idArt)){

        //hydratation l'objet etudiant
        $auteur->idAu = htmlspecialchars($data->idAu);
        $auteur->nom = htmlspecialchars($data->nom);
        $auteur->affil= htmlspecialchars($data->affil);
        $auteur->email = htmlspecialchars($data->email);     
        $auteur->idArt = intval($data->idArt);
    

        $result = $auteur->update();
        
        if($result){
            http_response_code(201);
            echo json_encode(["message"=>"etudiant Modifier avec succes"]);
        }else{
            http_response_code(503);
            echo json_encode(["message"=>"Modification de l'etudiant a echoué"]);
        }
    }else{
        echo json_encode(["message"=>"donnée incomplete "]);
    }
}else{
    http_response_code(405);
    echo json_encode(["message"=>"methode non autoriser"]);
}
?>