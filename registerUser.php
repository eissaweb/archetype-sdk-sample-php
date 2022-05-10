<?php 

require_once 'init.php';

use Archetype\Archetype;

header('Content-Type: application/json');

$response = Archetype::registerUser('234902394eerui3', 'Some Names', 'johDoes@domain.com');


echo json_encode($response);
