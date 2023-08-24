<?php

class Tr_art{
    private $table="conference";
    private $connexion=null;
    private $tab="users";
    private $art="article";
    private $aut="auteur";


    
    public $titre;
    public $status;
    public $descr;
    public $fich_pdf;
    public $affil;
    public $avis;
    public $crit_Eva;
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

        $sql= "SELECT titre,status,avis,crit_Eva  /*,u.nom nom_user*/
        FROM $this->art  ORDER BY idArt DESC";

        //envoi de la requete
        $req=$this->connexion->query($sql);
        //retourne resultat
        return $req;
    }

    public function create(){
        $sql="INSERT INTO $this->art(
            titre,descr,status,fich_pdf,idConf,affil,avis,crit_Eva)VALUE(:titre,:descr,:status
            ,:fich_pdf,:idConf,:affil,:avis,:crit_Eva)";
        //preparation de la requete
        $req=$this->connexion->prepare($sql);
        //execution de la requete
        $don=$req->execute([
            ":titre"=>$this->titre,
            ":descr"=>$this->descr,
            ":status"=>$this->status,
            ":fich_pdf"=>$this->fich_pdf,
            ":idConf"=>$this->idConf,
            ":affil"=>$this->affil,
            ":avis"=>$this->avis,
            ":crit_Eva"=>$this->crit_Eva
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
header("Access-control-Allow-Methods:PUT");


require_once'../config/Database.php';
require_once'../models/Tr_art.php';
$method = $_SERVER['REQUEST_METHOD'];

$database= new Database();
$db=$database->getConnexion();

$Tr_art = new Tr_art($db);


switch ($method) {
    
   
    case 'GET':
        
        header("Access-control-Allow-origin:*");
        header("content-type: application/json;charset=UTF-8");
        header("Access-control-Allow-Methods:GET");


        require_once'../config/Database.php';
        require_once'../models/Tr_art.php';

        if ($_SERVER['REQUEST_METHOD']==="GET") {

            $database =new Database();
            $db=$database->getConnexion();


            $Tr_art=new Tr_art($db);

            $statement = $Tr_art->readAll();

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
        break; 

    case 'POST':

        header("Access-control-Allow-origin:*");
        header("content-type: application/json;charset=UTF-8");
        header("Access-control-Allow-Methods:POST");


        require_once'../config/Database.php';
        require_once'../models/Tr_art.php';

        if($_SERVER['REQUEST_METHOD']==="POST"){
            //on instancie la basede donnée
            $database= new Database();
            $db=$database->getConnexion();

            //on istancie l'objet client
            $Tr_art = new Tr_art($db);
            //on recupere les infos
            $data=json_decode(file_get_contents("php://input"));
            if(!empty($data->titre)&& !empty($data->descr) && !empty($data->status)
            && !empty($data->fich_pdf)&& !empty($data->idConf)&& !empty($data->avis)&& !empty($data->affil)
            && !empty($data->crit_Eva)){

                //hydratation l'objet etudiant
                $Tr_art->titre = htmlspecialchars($data->titre);
                $Tr_art->descr = htmlspecialchars($data->descr);
                $Tr_art->status = htmlspecialchars($data->status);
                $Tr_art->fich_pdf = htmlspecialchars($data->fich_pdf);
                $Tr_art->idConf= intval($data->idConf);
                $Tr_art->avis= htmlspecialchars($data->avis);
                $Tr_art->affil = htmlspecialchars($data->affil);     
                $Tr_art->crit_Eva = htmlspecialchars($data->crit_Eva);

                $result = $Tr_art->create();
                if($result){
                    http_response_code(201);
                    echo json_encode(["message"=>"article  ajouter avec succes"]);
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

        break;
            
    
    default:
    // Méthode non prise en charge
    http_response_code(405);
    $response = array('message' => 'Méthode non autorisée');
    break;
}

/*
format d'insertion
{
"titre": "Barbaro",
"descr": "vente de papatu",
"status":"Accepté",
"fich_pdf":"pareter.pdf",
"idConf":6,
"id_us":2,
"affil":"Université&Entreprise",
"avis":"bien",
"crit_Eva":"10-12-14"

}

*/

?>