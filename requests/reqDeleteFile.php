<?php
        // se connceter à la BD
        include "connectBD.php";

        $response = array(
            "status" => "",
            "message" => "",
        );
        // créer la requête
        $requete="DELETE FROM fichiers WHERE filename = ?";

    if (isset($_POST['filename'])){
        $deleteFilename=$_POST['filename'];
        $file_path='../stock/'.$deleteFilename;
        if (file_exists($file_path)){
            if ($reponse=$pdo->prepare($requete)) {
                $reponse->execute([$deleteFilename]);
                if ($reponse->rowCount()>0){
                    unlink($file_path);
                    $response["status"]="success";
                    $response["message"]="Vous avez réussi à supprimer un fichier !";
                }else{
                    $response["status"]="error";
                    $response["message"]="Echec exécution requête!";
                }                    
            }else{
                $response["status"]="error";
                $response["message"]="Echec exécution requête!";
            }
        }else{
            $response["status"]="error";
            $response["message"]="Le fichier ".$deleteFilename." n'existe pas dans le serveur !";
        }
    } else {
        $response["status"]="error";
        $response["message"]="Données manquantes.";
    }
            
        
        // transformer le format à JSON
        $json_response = json_encode($response);
        
       header("Content-Type: application/json;charset=utf-8");
       // envoyer la réponse
       echo $json_response;
