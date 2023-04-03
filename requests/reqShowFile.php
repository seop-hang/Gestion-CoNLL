<?php
        $response = array(
            "status" => "",
            "message" => "",
        );
        
        if (isset($_POST['filename'])) {
            $filename = $_POST['filename'];
            $filepath='../stock/' . $filename;
            if (file_exists($filepath)){
                $content = file_get_contents($filepath);
                $response["status"]="success";
                $response["message"]=$content;  
            }else{
                $response["status"]="error";
                $response["message"]="Le fichier n'existe pas !";
            }               
        }else{
            $response["status"]="error";
            $response["message"]="Echec !";
        }
            
        
        // transformer le format à JSON
        $json_response = json_encode($response);
        
       header("Content-Type: application/json;charset=utf-8");
       // envoyer la réponse
       echo $json_response;
?>