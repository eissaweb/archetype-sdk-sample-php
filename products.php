<?php 

require_once 'init.php';

use Archetype\Archetype;

header('Content-Type: application/json');

$response = Archetype::getProducts();


echo json_encode($response);
