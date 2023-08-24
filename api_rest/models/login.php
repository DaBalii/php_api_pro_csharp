<?php

class Login{
    private $user="users";
    private $connexion=null;
    

    
    public $adresse;
    public $password;
    

   



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

        $sql= "SELECT password,adresse  /*,u.nom nom_user*/
        FROM $this->user ORDER BY id_us DESC";

        //envoi de la requete
        $req=$this->connexion->query($sql);
        //retourne resultat
        return $req;
    }

    public function create(){
        $sql="INSERT INTO $this->user(
            password,adresse)VALUE(:password,:adresse)";
        //preparation de la requete
        $req=$this->connexion->prepare($sql);
        //execution de la requete
        $don=$req->execute([
            ":password"=>$this->password,
            ":adresse"=>$this->adresse 
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
require_once'../models/login.php';
$method = $_SERVER['REQUEST_METHOD'];

$database= new Database();
$db=$database->getConnexion();

$login = new Login($db);


switch ($method) {
    
   
    case 'GET':
        
        header("Access-control-Allow-origin:*");
        header("content-type: application/json;charset=UTF-8");
        header("Access-control-Allow-Methods:GET");


        require_once'../config/Database.php';
        require_once'../models/login.php';

        if ($_SERVER['REQUEST_METHOD']==="GET") {

            $database =new Database();
            $db=$database->getConnexion();


            $login=new Login($db);

            $statement = $login->readAll();

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
        require_once'../models/login.php';

        if($_SERVER['REQUEST_METHOD']==="POST"){
            //on instancie la basede donnée
            $database= new Database();
            $db=$database->getConnexion();

            //on istancie l'objet client
            $login = new Login($db);
            //on recupere les infos
            $data=json_decode(file_get_contents("php://input"));
            if(!empty($data->adresse)&& !empty($data->password)){

                //hydratation l'objet etudiant
                $login->adresse = htmlspecialchars($data->adresse);
                $login->password = htmlspecialchars($data->password);
                
               
                $result = $login->create();
                if($result){
                    http_response_code(201);
                    echo json_encode(["message"=>"User ajouter avec succes"]);
                }else{
                    http_response_code(503);
                    echo json_encode(["message"=>"ajout d'Utilsateur a echoué"]);
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


*/

?>