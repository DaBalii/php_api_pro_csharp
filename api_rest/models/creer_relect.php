<?php

/*d. De donner leur avis sur une conférence déjà passée à laquelle ils 
ont participé

    format pour creer un avis

{
    "nom": "SYtrR",
    "avis": "Bien",
    "idArt":10
}

*/

class Creer_relect{
    private $cop="co-president";
    private $connexion=null;
    private $rel="relecteur";
    
 
    public $nom;
    public $avis;
    public $idRel;
    public $idArt;

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

if($_SERVER['REQUEST_METHOD']==="POST"){
    //on instancie la basede donnée
    $database= new Database();
    $db=$database->getConnexion();

    //on istancie l'objet client
    $creer_relect = new Creer_relect($db);
    //on recupere les infos
    $data=json_decode(file_get_contents("php://input"));
    if(!empty($data->nom)&& !empty($data->idArt)){

        //hydratation l'objet etudiant
        $creer_relect->nom = htmlspecialchars($data->nom);    
        $creer_relect->idArt = intval($data->idArt);

        $result = $creer_relect->create();
        if($result){
            http_response_code(201);
            echo json_encode(["message"=>"relecteur ajouter avec succes"]);
        }else{
            http_response_code(503);
            echo json_encode(["message"=>"ajout de relecteur a echoué"]);
        }
    }else{
        echo json_encode(["message"=>"donnée incomplet"]);
    }
}else{
    http_response_code(405);
    echo json_encode(["message"=>"methode non autoriser"]);
}



//http://localhost/iai_2/api_rest/controllers/readAll.php
?>