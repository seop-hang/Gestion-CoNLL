<?php
        // se connceter à la BD
        include "connectBD.php";

        // créer la requête
        $requete="SELECT * FROM fichiers";

        $response = array(
            "status" => "",
            "message" => array(),
        );
        
        // obtenir les paramètres envoyés
        $selectedLang = isset($_GET['languages']) ? $_GET['languages'] : array();
        $selectedAnno = isset($_GET['Annotations']) ? $_GET['Annotations'] : array();
        $selectedYear = isset($_GET['years']) ? $_GET['years'] : array();

        $fileFiltre=array(
            "username"=>"",
            "filename"=>"",
            "year"=>"",
            "language"=>"",
        );

        if ($reponse=$pdo->query($requete)) {
            while($enr=$reponse->fetchAll(PDO::FETCH_ASSOC)){
                foreach ($enr as $row){
                    if (in_array($row['language'],$selectedLang) || count($selectedLang)==0){
                        if (in_array($row['year'],$selectedYear) || count($selectedYear)==0){
                            $allAnnoFound=true;
                            foreach($selectedAnno as $anno){
                                if ($row[$anno]==0){
                                    $allAnnoFound=false;
                                }
                            }
                            if ($allAnnoFound){
                                $response["status"]="success";
                                $fileFiltre["username"]=$row["username"];
                                $fileFiltre["filename"]=$row["filename"];
                                $fileFiltre["year"]=$row["year"];
                                $fileFiltre["language"]=$row["language"];
                                array_push($response["message"],$fileFiltre);
                            }
                        }
                    }
                }
            };        
        }else{
            $response["status"]="error";
            $response["message"]="Echec exécution requête!";
        }
            
        
        // transformer le format à JSON
        $json_response = json_encode($response);
        
       header("Content-Type: application/json;charset=utf-8");
       // envoyer la réponse
       echo $json_response;
