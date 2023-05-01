<?php
// se connceter à la BD
include "connectBD.php";

$response = array(
    "status" => "",
    "message" => "",
);

if (isset($_POST['username']) && isset($_POST['language']) && isset($_POST['year']) && isset($_FILES['file'])){
    $username=$_POST['username'];
    $language = $_POST['language'];
    $year = $_POST['year'];
    $IDTOK = isset($_POST['IDTOK']) ? 1 : 0;
    $FORM = isset($_POST['FORM']) ? 1 : 0;
    $LEMMA = isset($_POST['LEMMA']) ? 1 : 0;
    $UPOS = isset($_POST['UPOS']) ? 1 : 0;
    $XPOS = isset($_POST['XPOS']) ? 1 : 0;
    $FEATS = isset($_POST['FEATS']) ? 1 : 0;
    $HEAD = isset($_POST['HEAD']) ? 1 : 0;
    $DEPREL = isset($_POST['DEPREL']) ? 1 : 0;
    $DEPS = isset($_POST['DEPS']) ? 1 : 0;
    $MISC = isset($_POST['MISC']) ? 1 : 0;
    $file = $_FILES['file'];

    if ($IDTOK==0 || $FORM==0 || $LEMMA==0 || $UPOS==0 || $HEAD==0 || $DEPREL==0){
        $response["status"]="error";
        $response["message"]="Les annotations (IDTOK,FORM,LEMMA,UPOS,HEAD,DEPREL) sont obligatoire!";
    }else{
        if ($res=$pdo->query("SELECT filename FROM fichiers")){
            while($enr=$res->fetchAll(PDO::FETCH_ASSOC)){
                $find_file=false;
                foreach ($enr as $row){
                    if ($row['filename']==$file["name"]){
                        $find_file=true;
                    }
                }
            };
            if ($find_file){
                $response["status"]="error";
                $response["message"]="Le fichier existe déjà !";
            }else{
                $file_content = file_get_contents($file['tmp_name']);
                $rows=explode("\n",trim($file_content));
                $content=array();
                foreach ($rows as $row){
                    $cols = array_filter(explode("\t", trim($row)), function($elem) {
                        return strlen(trim($elem)) > 0;
                    });
                    if (strpos($cols[0], '#') === 0 || $cols[0] === '') {
                        continue;
                    }else{
                        array_push($content,$cols);
                    }
                }
                if (count($content[0])!=10){
                    $response["status"]="error";
                    $response["message"]="L'annotation dans le fichier nécessite 10 colonnes !";  
                }else{
                    try {
                        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                        // insérer la donnée dans MySQL
                        $requete="INSERT INTO fichiers (username,filename,language,year,IDTOK,FORM,LEMMA,UPOS,XPOS,FEATS,HEAD,DEPREL,DEPS,MISC) VALUES (:username,:filename,:language,:year,:IDTOK,:FORM,:LEMMA,:UPOS,:XPOS,:FEATS,:HEAD,:DEPREL,:DEPS,:MISC)";
                        if ($stmt = $pdo->prepare($requete)){
                            // le traitemenr du fichier
                            $target_dir = "../stock/";
                            $target_file = $target_dir . basename($_FILES["file"]["name"]);
                            if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)){
                                chmod($target_file, 0777);
                                
                                $stmt->execute(array(
                                    ':username' => $username,
                                    ':filename' => $_FILES["file"]["name"],
                                ':language' => $language,
                                ':year' => $year,
                                ':IDTOK'=>$IDTOK,
                                ':FORM'=>$FORM,
                                ':LEMMA'=>$LEMMA,
                                ':UPOS'=>$UPOS,
                                ':XPOS'=>$XPOS,
                                ':FEATS'=>$FEATS,
                                ':HEAD'=>$HEAD,
                                ':DEPREL'=>$DEPREL,
                                ':DEPS'=>$DEPS,
                                ':MISC'=>$MISC
                                ));
                                $response["status"]="success";
                                $response["message"]="Vous avez réussi à insérer un fichier !";  
                            }else{
                                $response["status"]="error";
                                $response["message"]="On n'arrive pas à insérer le fichier dans le serveur !";  
                            };
                        }else{
                            $response["status"]="error";
                            $response["message"]="Une erreur s'est passée !";
                        };
                        
                    } catch(PDOException $e) {
                        $response["status"]="error";
                        $response["message"]=$e->getMessage();
                    }  
                }
            }
        }else{
            $response["status"]="error";
            $response["message"]="Echec exécution requête!";
        }
    }
}


// transformer le format à JSON
$json_response = json_encode($response);

header("Content-Type: application/json;charset=utf-8");
// envoyer la réponse
echo $json_response;  
?>