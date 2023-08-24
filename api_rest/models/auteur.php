<?php

class Auteur{
    private $table="conference";
    private $connexion=null;
    private $tab="users";
    private $art="article";
    private $aut="auteur";


    
    public $nom;
    public $idAu;
    public $prenom;
    public $avis;
    public $affil;
    public $email;
    public $arti;
    public $idArt;



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

        $sql= "SELECT nom,affil,email,idArt  /*,u.nom nom_user*/
        FROM $this->aut  ORDER BY idAu DESC";

        //envoi de la requete
        $req=$this->connexion->query($sql);
        //retourne resultat
        return $req;
    }

    public function create(){
        $sql="INSERT INTO $this->aut(
            nom,affil,email,idArt)VALUE(:nom,:affil,:email,:idArt)";
        //preparation de la requete
        $req=$this->connexion->prepare($sql);
        //execution de la requete
        $don=$req->execute([
            ":nom"=>$this->nom,
            ":affil"=>$this->affil,
            ":email"=>$this->email,
            ":idArt"=>$this->idArt
        ]);
        if($don){
            return true;
        }else{
            return false;
        }

    }

    public function update(){
        $sql="UPDATE $this->aut SET nom=:nom,affil=:affil,
        email=:email,idArt=:idArt where idAu=:idAu";

        //preparation de la requete
        $req=$this->connexion->prepare($sql);
        //execution de la requete
        $don=$req->execute([
            ":idAu"=>$this->idAu,
            ":nom"=>$this->nom,
            ":affil"=>$this->affil,
            ":email"=>$this->email,
            ":idArt"=>$this->idArt
        ]);
        if($don){
            return true;
        }else{
            return false;
        }
    }

    public function delete(){
        $sql="DELETE FROM $this->aut where idAu=:idAu";
        $req= $this->connexion->prepare($sql);

        $re = $req ->execute(array(":idAu"=>$this->idAu));

        if($re){
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
$method = $_SERVER['REQUEST_METHOD'];

$database= new Database();
$db=$database->getConnexion();

$auteur = new Auteur($db);



switch ($method) {
    
   
    case 'GET':
        
        header("Access-control-Allow-origin:*");
        header("content-type: application/json;charset=UTF-8");
        header("Access-control-Allow-Methods:GET");


        require_once'../config/Database.php';

        if ($_SERVER['REQUEST_METHOD']==="GET") {

            $database =new Database();
            $db=$database->getConnexion();


            $auteur = new Auteur($db);

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
        break; 

    case 'POST':

        header("Access-control-Allow-origin:*");
        header("content-type: application/json;charset=UTF-8");
        header("Access-control-Allow-Methods:POST");


        require_once'../config/Database.php';

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
                    echo json_encode(["message"=>"auteur ajouter avec succes"]);
                }else{
                    http_response_code(503);
                    echo json_encode(["message"=>"ajout d'auteur a echoué"]);
                }
            }else{
                echo json_encode(["message"=>"donnée incomplet"]);
            }
        }else{
            http_response_code(405);
            echo json_encode(["message"=>"methode non autoriser"]);
        }

        break;
            
    case 'PUT':
        header("Access-control-Allow-origin:*");
        header("content-type: application/json;charset=UTF-8");
        header("Access-control-Allow-Methods:PUT");


        require_once'../config/Database.php';
        

        if($_SERVER['REQUEST_METHOD']==="PUT"){
            //on instancie la basede donnée
            $database= new Database();
            $db=$database->getConnexion();

            //on istancie l'objet client
            $auteur = new Auteur($db);
            //on recupere les infos
            $data=json_decode(file_get_contents("php://input"));
        
            if(!empty($data->idAu)&&!empty($data->nom)&& !empty($data->affil)
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
                    echo json_encode(["message"=>"auteur Modifier avec succes"]);
                }else{
                    http_response_code(503);
                    echo json_encode(["message"=>"Modification d'auteur a echoué"]);
                }
            }else{
                echo json_encode(["message"=>"donnée incomplete "]);
            }
        }else{
            http_response_code(405);
            echo json_encode(["message"=>"methode non autoriser"]);
        }
                
        break;
    case 'DELETE':

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
            $auteur = new Auteur($db);
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
                echo json_encode(["message"=>"vous devez preciser id_auteur"]);
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

?>