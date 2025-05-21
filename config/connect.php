<?php

try {

    global $db;
    $db = new PDO('mysql:host=db;dbname=ecoride', 'root', 'root');
    $db->exec('SET NAMES "UTF8"');

} catch (PDOException $e){

    echo 'Erreur : '. $e->getMessage();
    die();

}