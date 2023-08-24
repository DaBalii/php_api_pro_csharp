<?php


class reafectArt{
    private $conf="conference";
    private $connexion=null;
    private $rel="relecteur";
    
 
    public $nom;
    public $avis;
    public $idArt;
    public $titre;
    

    public function __construct($db)
    {
        
        if($this->connexion==null){
            $this->connexion=$db;
        }
    }

    public function create(){
        $sql="INSERT INTO $this->rel(
            nom,idArt)VALUE(:nom,:idArt)";
        //preparation de la requete
        $req=$this->connexion->prepare($sql);
        //execution de la requete
        $don=$req->execute([
            ":nom"=>$this->nom,
            ":idArt"=>$this->idArt
        ]);
        if($don){
            return true;
        }else{
            return false;
        }

    }
}


header("Access-control-Allow-origin:*");
header("content-type: application/json;charset=UTF-8");
header("Access-control-Allow-Methods:POST");


require_once'../config/Database.php';
require_once'../models/reafectArt.php';

if($_SERVER['REQUEST_METHOD']==="POST"){
    //on instancie la basede donnée
    $database= new Database();
    $db=$database->getConnexion();

    //on istancie l'objet client
    $reafectArt = new reafectArt($db);
    //on recupere les infos
    $data=json_decode(file_get_contents("php://input"));
    if(!empty($data->nom)&& !empty($data->idArt)){

        //hydratation l'objet etudiant
        $reafectArt->nom = htmlspecialchars($data->nom);
        $reafectArt->idArt = intval($data->idArt);

        $result = $reafectArt->create();
        if($result){
            http_response_code(201);
            echo json_encode(["message"=>"Article réafecter avec succes"]);
        }else{
            http_response_code(503);
            echo json_encode(["message"=>"ajout de reafectation a echoué"]);
        }
    }else{
        echo json_encode(["message"=>"donnée incomplet"]);
    }
}else{
    http_response_code(405);
    echo json_encode(["message"=>"methode non autoriser"]);
}

/*

"nom": "Servis",
"avis": "Pas bien",
"titre":"SARLI",
"idArt":7



*/

//http://localhost/iai_2/api_rest/controllers/readAll.php
?>