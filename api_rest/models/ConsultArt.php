<?php

class ConsultArt{
    private $art="article";
    private $connexion=null;
    private $rel="relecte";
    private $table="conference";
    
 
    public $titre;
    public $descr;
    public $statu;
    public $fich_pdf;
    public $idConf;
    

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

        $sql= "SELECT t.titre,t.descr,t.status,t.fich_pdf,t.avis /*,u.nom nom_user*/
        FROM $this->table c,$this->art t where c.idConf=t.idConf
        ORDER BY t.idConf DESC";

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
require_once'../models/ConsultArt.php';

if ($_SERVER['REQUEST_METHOD']==="GET") {

    $database =new Database();
    $db=$database->getConnexion();


    $ConsultArt=new ConsultArt($db);

    $statement = $ConsultArt->readAll();

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

?>