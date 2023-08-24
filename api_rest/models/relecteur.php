<?php

class relecteur{
    private $rel="relecteur";
    private $connexion=null;
    private $art="article";


    public $nom;
    public $idArt;
    public $idRel;


    public function __construct($db)
    {
        
        if($this->connexion==null){
            $this->connexion=$db;
        }
    }
    
    public function readAll(){
        //ecriture de la requet
        $sql = "SELECT nom,idArt FROM $this->rel ";

        $req=$this->connexion->query($sql);
        //retourne resultat
        return $req;
    }

    public function create(){
        $sql="INSERT INTO $this->rel(
            nom,idArt)VALUE(:nom,:idArt)";
        //preparation de la requete
        $req=$this->connexion->prepare($sql);
        //execution de la requete
        $don=$req->execute([
            ":nom"=>$this->nom,
            "idArt" =>$this->idArt  
        ]);
        if($don){
            return true;
        }else{
            return false;
        }

    }
    public function update(){
        $sql="UPDATE $this->rel SET nom=:nom,idArt=:idArt where idRel=:idRel";

        //preparation de la requete
        $req=$this->connexion->prepare($sql);
        //execution de la requete
        $don=$req->execute([
            ":idRel"=>$this->idRel,
            ":nom"=>$this->nom,
            ":idArt"=>$this->idArt
        ]);
        if($don){
            return true;
        }else{
            return false;
        }
    }

    public function delete(){
        $sql="DELETE FROM $this->rel where idRel=:idRel";
        $req= $this->connexion->prepare($sql);

        $re = $req ->execute(array(":idRel"=>$this->idRel));

        if($re){
            return true;
        }else{
            return false;
        }

    }
}


//les entêtes requise
header("Access-control-Allow-origin:*");
header("content-type: application/json;charset=UTF-8");
header("Access-control-Allow-Methods:*");


require_once'../config/Database.php';

$method = $_SERVER['REQUEST_METHOD'];

$database= new Database();
$db=$database->getConnexion();

$relecteur=new relecteur($db);


switch ($method) {
    
   
    case 'GET':
        

        header("Access-control-Allow-origin:*");
        header("content-type: application/json;charset=UTF-8");
        header("Access-control-Allow-Methods:GET");


        require_once'../config/Database.php';

        if ($_SERVER['REQUEST_METHOD']==="GET") {

            $database =new Database();
            $db=$database->getConnexion();


            $relecteur=new relecteur($db);

            $statement = $relecteur->readAll();

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
            $relecteur = new relecteur($db);
            //on recupere les infos
            $data=json_decode(file_get_contents("php://input"));
            if( !empty($data->nom)&& !empty($data->idArt)){
                //hydratation l'objet etudiant
                
                $relecteur->nom = htmlspecialchars($data->nom);
                $relecteur->idArt= htmlspecialchars($data->idArt);
                
                $result = $relecteur->create();
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
            $relecteur = new relecteur($db);
            //on recupere les infos
            $data=json_decode(file_get_contents("php://input"));
         
            if(!empty($data->idRel)&& !empty($data->nom)
                && !empty($data->idArt)){
        
                //hydratation l'objet etudiant
                $relecteur->idRel = intval($data->idRel);
                $relecteur->nom = htmlspecialchars($data->nom);
                $relecteur->idArt = intval($data->idArt);
                
                $result = $relecteur->update();
                
                if($result){
                    http_response_code(201);
                    echo json_encode(["message"=>"relecteur Modifier avec succes"]);
                }else{
                    http_response_code(503);
                    echo json_encode(["message"=>"Modification du relecteur a echoué"]);
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

        if($_SERVER['REQUEST_METHOD']==="DELETE"){
            //on instancie la basede donnée
            $database= new Database();
            $db=$database->getConnexion();

            //on istancie l'objet client
            $relecteur= new relecteur($db);
            //on recupere les infos
            $data=json_decode(file_get_contents("php://input"));

            if(!empty($data->idRel)){

                $relecteur->idRel = $data->idRel;
                
                if($relecteur->delete()){
                    http_response_code(200);
                    echo json_encode(array("message"=>"relecteur supprimer avec succes !!"));
                }else{
                    http_response_code(503);
                    echo json_encode(["message"=>"echec de la suppression "]);

                }

            }else{
                echo json_encode(["message"=>"vous devez preciser id_relecteur"]);
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

