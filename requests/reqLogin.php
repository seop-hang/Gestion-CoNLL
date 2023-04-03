<?php
        // se connceter à la BD
        include "connectBD.php";

        // créer la requête
        $requete="SELECT username,password FROM compte";

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
                        if ($row['username']==$_POST['username'] && $row['password']==$_POST['password']){
                            $find_user=true;
                        }
                    }
                };
                if ($find_user){
                    header("Authorization:".$_POST['username']);
                    $response["status"]="success";
                    $response["message"]="Vous avez réussi à vous connecter";
                }else{
                    $response["status"]="error";
                    $response["message"]="Veuillez vérifier votre compte ou mot de passe!";
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
