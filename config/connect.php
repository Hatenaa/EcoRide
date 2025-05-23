<?php

try {

    global $db;
    $db = new PDO('mysql:host=localhost;dbname=ecoride', 'username', 'password');
    $db->exec('SET NAMES "UTF8"');

} catch (PDOException $e){

    echo 'Erreur : '. $e->getMessage();
    die();

}
