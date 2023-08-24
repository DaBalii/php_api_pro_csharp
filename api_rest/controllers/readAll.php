<?php
header("Access-control-Allow-origin:*");
header("content-type: application/json;charset=UTF-8");
header("Access-control-Allow-Methods:GET");


require_once'../config/Database.php';
require_once'../models/auteur.php';

if ($_SERVER['REQUEST_METHOD']==="GET") {

    $database =new Database();
    $db=$database->getConnexion();


    $auteur=new Auteur($db);

    $statement = $auteur->readAll();

    if($statement->rowCount() > 0){
        
        $data=[];
        $data[]=$statement->fetchAll();

        http_response_code(200);
        echo json_encode($data);
    }else{
        echo json_encode(["Message"=>"aucun données à renvoyer"]);
    }
}else{
    http_response_code(405);
    echo json_encode(["message"=>"methode non autoriser"]);
}
//http://localhost/iai_2/api_rest/controllers/readAll.php
?>