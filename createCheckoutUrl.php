<?php 

require_once 'init.php';

use Archetype\Archetype;

header('Content-Type: application/json');

$response = Archetype::createCheckoutSession('234902394eerui3', 'prod_LbP4oEy2HURprT');


echo json_encode($response);
