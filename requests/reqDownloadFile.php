<?php
        
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
                // Exporter le contenu du fichier au format texte
                $text_file_name = '../stock/test2.txt';
                $text_file_handle = fopen($text_file_name, 'w');

                foreach ($file_content as $line) {
                    // Retirer les annotations CoNLL-U de la ligne
                    $line = preg_replace('/\t.*$/', '', $line);
                    
                    // Écrire la ligne dans le fichier texte
                    fwrite($text_file_handle, $line . "\n");
                }

                // Fermer le fichier texte
                fclose($text_file_handle);
                

                // Télécharger le fichier texte
                header('Content-Disposition: attachment; filename="'. $_POST['filename'].'"' );
                header('Content-Type: text/plain');
                header('Content-Length: ' . filesize($text_file_name));
                header("Content-Disposition","attachment");
                header("Pragma:No-cache");
                header("Cache-Control:No-cache");
                header("Expires:0");
                readfile($text_file_name);
            }

            if ($format=="CoNLL-U"){



                // Vérifier que le fichier a été ouvert avec succès
                if ($file_handle === false) {
                    die('Impossible d\'ouvrir le fichier ' . $_POST['filename']);
                }

                // Envoyer les en-têtes HTTP pour le téléchargement du fichier
                header('content-disposition: attachment; filename="' .$file_name.'"');
                header('Content-Type: application/x-conll');

                // Envoyer le contenu du fichier CoNLL-U au navigateur
                readfile($file_name);

                // Fermer le fichier
                fclose($file_handle);

            }
        }
?>