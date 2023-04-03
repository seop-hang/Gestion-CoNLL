<?php
        // se connceter à la BD
        include "connectBD.php";

        // créer la requête
        $requete="SELECT username FROM compte";

        $response = array(
            "status" => "",
            "message" => "",
        );
    
        //vérifier la réception des valeurs de username et password
        if (isset($_POST['username']) && isset($_POST['password'])){
            if ($reponse=$pdo->query($requete)) {
                while($enr=$reponse->fetchAll(PDO::FETCH_ASSOC)){
                    $find_user=false;
                    foreach ($enr as $row){
                        if ($row['username']==$_POST['username']){
                            $find_user=true;
                        }
                    }
                };
                if ($find_user){
                    $response["status"]="error";
                    $response["message"]="Le compte existe déjà!";
                }else{
                    try {
                    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                    // insérer une ligne de données
                    $sql = "INSERT INTO compte (username,password) VALUES (?,?)";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute([$_POST['username'],$_POST['password']]);

                    $response["status"]="success";
                    $response["message"]="Le compte a été crée!";
                    } catch(PDOException $e) {
                        $response["status"]="error";
                        $response["message"]=$e->getMessage();
                    }   
                }
            }else{
                $response["status"]="error";
                $response["message"]="Echec exécution requête!";
            }
            
        }
        // transformer le format à JSON
        $json_response = json_encode($response);
        
       header("Content-Type: application/json;charset=utf-8");
       // envoyer la réponse
       echo $json_response;     
?>
