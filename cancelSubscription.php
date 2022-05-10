<?php 

require_once 'init.php';

use Archetype\Archetype;

header('Content-Type: application/json');
// pass custom UID
$response = Archetype::cancelSubscription('234902394eerui3');


echo json_encode($response);
