<?php


class ajoutConf{
    private $conf="conference";
    private $connexion=null;
    private $rel="relecte";
    
 
    public $nom;
    public $sigle;
    public $theme;
    

    public function __construct($db)
    {
        
        if($this->connexion==null){
            $this->connexion=$db;
        }
    }

    public function create(){
        $sql="INSERT INTO $this->conf(
            nom,sigle,theme)VALUE(:nom,:sigle,:theme)";
        //preparation de la requete
        $req=$this->connexion->prepare($sql);
        //execution de la requete
        $don=$req->execute([
            ":nom"=>$this->nom,
            ":sigle"=>$this->sigle,
            ":theme"=>$this->theme
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
require_once'../models/ajoutConf.php';

if($_SERVER['REQUEST_METHOD']==="POST"){
    //on instancie la basede donnée
    $database= new Database();
    $db=$database->getConnexion();

    //on istancie l'objet client
    $ajoutConf = new ajoutConf($db);
    //on recupere les infos
    $data=json_decode(file_get_contents("php://input"));
    if(!empty($data->nom)&& !empty($data->sigle)&& !empty($data->theme)){

        //hydratation l'objet etudiant
        $ajoutConf->nom = htmlspecialchars($data->nom);
        $ajoutConf->sigle = htmlspecialchars($data->sigle);     
        $ajoutConf->theme = htmlspecialchars($data->theme);

        $result = $ajoutConf->create();
        if($result){
            http_response_code(201);
            echo json_encode(["message"=>"conference ajouter avec succes"]);
        }else{
            http_response_code(503);
            echo json_encode(["message"=>"ajout de conference a echoué"]);
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