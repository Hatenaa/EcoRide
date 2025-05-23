<?php

try {

    global $db;
    $db = new PDO('mysql:host=ecoride-mysql.render.com;dbname=ecoride', 'username', 'password');
    $db->exec('SET NAMES "UTF8"');

} catch (PDOException $e){

    echo 'Erreur : '. $e->getMessage();
    die();

}
