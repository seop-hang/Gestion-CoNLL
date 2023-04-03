<?php
        // se connceter à la BD
        include "connectBD.php";

        // créer la requête
        $requete="SELECT * FROM fichiers";

        $response = array(
            "status" => "",
            "message" => array(),
        );
        
        if (isset($_POST['forme']) || isset($_POST['lemme']) || isset($_POST['upos']) || isset($_POST['feats']) || isset($_POST['structure'])) {
            $forme = $_POST['forme'] ? $_POST['forme']:'';
            $lemme = $_POST['lemme'] ? $_POST['lemme']:'';
            $upos = $_POST['upos'] ? $_POST['upos']:'';
            $feats = $_POST['feats'] ? $_POST['feats']:'';
            $structure = $_POST['structure'] ? $_POST['structure']:'';

            // Ouvrir le dossier de fichiers à rechercher
            $dir = opendir('../stock/');

            // Parcourir tous les fichiers dans le dossier
            while (false !== ($filename = readdir($dir))) {
            if (strpos($filename, '.conllu') !== false) {
                $content = file_get_contents('../stock/' . $filename);
                if ($forme == '' || (strpos($content, $forme) !== false)){
                    if ($lemme == '' || (strpos($content, $lemme) !== false)){
                        if ($upos == '' || (strpos($content, $upos) !== false)){
                            if ($feats == '' || (strpos($content, $feats) !== false)){
                                if ($structure == '' || (strpos($content, $structure) !== false)){
                                    $fileFiltre=array(
                                        "username"=>"",
                                        "filename"=>"",
                                        "year"=>"",
                                        "language"=>"",
                                    );
                            
                                    if ($reponse=$pdo->query($requete)) {
                                        while($enr=$reponse->fetchAll(PDO::FETCH_ASSOC)){
                                            foreach ($enr as $row){
                                                if ($row['filename']==$filename){
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
                                }
                            }
                        }
                    }
                }    
            }
        }
        closedir($dir);          
        }else{
            $response["status"]="error";
            $response["message"]="Echec !";
        }
        
    
    // transformer le format à JSON
    $json_response = json_encode($response);
    
    header("Content-Type: application/json;charset=utf-8");
    // envoyer la réponse
    echo $json_response;
