<?php
        $response = array(
            "status" => "",
            "message" => "",
        );

        if (isset($_POST['filename']) && isset($_POST['format'])) {
            $file_name = "../stock/".$_POST['filename'];
            $format=$_POST['format'];

            // Ouvrir le fichier CoNLL-U
            $file_handle = fopen($file_name, 'r');

            // Vérifier que le fichier a été ouvert avec succès
            if ($file_handle === false) {
                die('Impossible d\'ouvrir le fichier ' . $_POST['filename']);
            }

            // Créer un tableau pour stocker les lignes du fichier
            $file_content = array();

            // Lire le fichier ligne par ligne
            while (($line = fgets($file_handle)) !== false) {
                // Ajouter la ligne au tableau
                $file_content[] = $line;
            }

            // Fermer le fichier
            fclose($file_handle);

            
            if ($format=="TXT"){
                $download_name=explode(".",$_POST['filename'])[0].'.txt';
                // Exporter le contenu du fichier au format texte
                $text_file_name = '../stock/'.$download_name;
                $text_file_handle = fopen($text_file_name, 'w');

                foreach ($file_content as $line) {                    
                    // Écrire la ligne dans le fichier texte
                    fwrite($text_file_handle, $line . "\n");
                }
                
                // Fermer le fichier texte
                fclose($text_file_handle);

                $response["status"]="success";
                $response["message"]=$download_name;  
            }
            
            if ($format=="CoNLL-U"){
                $download_name=explode(".",$_POST['filename'])[0].'.conllu';
                // Vérifier que le fichier a été ouvert avec succès
                if ($file_handle === false) {
                    die('Impossible d\'ouvrir le fichier ' . $_POST['filename']);
                }

                $response["status"]="success";
                $response["message"]=$download_name;
            }
        }

        // transformer le format à JSON
        $json_response = json_encode($response);

        header("Content-Type: application/json;charset=utf-8");
        // envoyer la réponse
        echo $json_response;
?>