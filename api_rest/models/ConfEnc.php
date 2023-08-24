<?php

class ConfEnc{
    private $cal="calendrier";
    private $connexion=null;
    private $rel="relecte";
    private $table="conference";
    
 
    public $nom;
    public $sigle;
    public $theme;
    public $dateInsc;
    
    

    public function __construct($db)
    {
        
        if($this->connexion==null){
            $this->connexion=$db;
        }
    }

    public function readAll(){
        //ecriture de la requet
        /*$sql= "SELECT c.*,sigle,theme, u.prenom,a.*,u.nom nom_user
        FROM $this->table c,$this->tab u,$this->art a where c.idConf=u.id_us
        and c.idConf=a.idArt ORDER BY u.id_us DESC"; */

        $sql= "SELECT nom,sigle,theme,dateInsc /*,u.nom nom_user*/
        FROM $this->table  ORDER BY idConf DESC";

        //envoi de la requete
        $req=$this->connexion->query($sql);
        //retourne resultat
        return $req;
    }

}

header("Access-control-Allow-origin:*");
header("content-type: application/json;charset=UTF-8");
header("Access-control-Allow-Methods:GET");


require_once'../config/Database.php';
require_once'../models/ConfEnc.php';

if ($_SERVER['REQUEST_METHOD']==="GET") {

    $database =new Database();
    $db=$database->getConnexion();


    $ConfEnc=new ConfEnc($db);

    $statement = $ConfEnc->readAll();

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


/*

"nom": "AZERT",
"sigle": "QOP",
"theme": "areops",
"dateInsc": "2023-05-26 22:56:35"



*/

?>