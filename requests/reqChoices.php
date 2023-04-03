<?php
        // se connceter à la BD
        include "connectBD.php";

        // créer la requête
        $requete="SELECT year,language FROM fichiers";

        $response = array(
            "status" => "",
            "message" => array(
                "years"=>array(),
                "languages"=>array(),
            ),
        );
        
        if ($reponse=$pdo->query($requete)) {
            while($enr=$reponse->fetchAll(PDO::FETCH_ASSOC)){
                foreach ($enr as $row){
                    if (!in_array($row["year"],$response["message"]["years"])){
                        array_push($response["message"]["years"],$row["year"]);
                    }
                    if (!in_array($row["language"],$response["message"]["languages"])){
                        array_push($response["message"]["languages"],$row["language"]);
                    }
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
