<?php
    try{
        $pdo=new PDO('mysql:host=localhost;dbname=du','du','&du;');
    }catch(Exception $e) {
        die ('Erreur : '.$e->getMessage());
    }
?>