<?php
//les entêtes requise
header("Access-control-Allow-origin:*");
header("content-type: application/json;charset=UTF-8");
header("Access-control-Allow-Methods:DELETE");


require_once'../config/Database.php';
require_once'../models/auteur.php';

if($_SERVER['REQUEST_METHOD']==="DELETE"){
    //on instancie la basede donnée
    $database= new Database();
    $db=$database->getConnexion();

    //on istancie l'objet client
    $auteur= new Auteur($db);
    //on recupere les infos
    $data=json_decode(file_get_contents("php://input"));

    if(!empty($data->idAu)){

        $auteur->idAu = $data->idAu;
        
        if($auteur->delete()){
            http_response_code(200);
            echo json_encode(array("message"=>"auteur supprimer avec succes !!"));
        }else{
            http_response_code(503);
            echo json_encode(["message"=>"echec de la suppression "]);

        }

    }else{
        echo json_encode(["message"=>"vous devez preciser id_reference"]);
    }
}else{
    http_response_code(405);
    echo json_encode(["message"=>"methode non autoriser"]);

}
?>