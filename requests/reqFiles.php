<?php
        // se connceter à la BD
        include "connectBD.php";

        // créer la requête
        $requete="SELECT username,filename,year,language FROM fichiers";

        $response = array(
            "status" => "",
            "message" => array(),
        );
        
        if ($reponse=$pdo->query($requete)) {
            while($enr=$reponse->fetchAll(PDO::FETCH_ASSOC)){
                foreach ($enr as $row){
                    $data=array(
                        "username"=>$row['username'],
                        "filename"=>$row['filename'],
                        "year"=>$row['year'],
                        "language"=>$row['language'],
                    );
                    array_push($response["message"],$data);
                }
            };
            $response["status"]="success";                
        }else{
            $response["status"]="error";
            $response["message"]="Echec exécution requête!";
        }
            
        
        // transformer le format à JSON
        $json_response = json_encode($response);
        
       header("Content-Type: application/json;charset=utf-8");
       // envoyer la réponse
       echo $json_response;
