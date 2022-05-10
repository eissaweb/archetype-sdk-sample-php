<?php 

require_once 'init.php';

use Archetype\Archetype;

header('Content-Type: application/json');
// you should call this end point with apkikey query param
$response = Archetype::authenticate();

// This is protected by archetype, if error happens, it will throw an error.
echo json_encode($response);
