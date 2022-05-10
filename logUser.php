<?php 

require_once 'init.php';

use Archetype\Archetype;

header('Content-Type: application/json');
// pass user apikey you get from Archetype::getUser method
$response = Archetype::log('887b3af4b62a43e98a6932ad31e3c012');


echo json_encode($response);
