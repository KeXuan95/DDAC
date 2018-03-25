<?php

//Components array
$health = array (
    'site' => false,
    'database' => false
);

//Check Site is working
try {
    require (__DIR__.'/controller/init.php');
    $health['site'] = true;
} catch (Exception $e) {
}

//Check database is working
try {			
    new PDO('mysql:host='.Config::get('db_host').';port='.Config::get('db_port').';dbname='.Config::get('db_name'),Config::get('db_username'),Config::get('db_password'));
    $health['database'] = true;
} catch(PDOException $e) {
}

//Respond with error 500 if any components aren't working
foreach ($health as $key => $value){
    if ($value == false){
        header("HTTP/1.1 500 Internal Server Error");
    }
}

//Return status of components as JSON
header('Content-Type: application/json');
echo json_encode($health);