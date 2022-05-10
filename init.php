<?php 

require_once 'vendor/autoload.php';
use Archetype\Archetype;

$options = [
    'app_id' => '699dd288309f4c9992cb9437e39299be',
    'secret_key' => 'archetype_sk_test_473d910da6074cbca6898c89b74c6037',
];

Archetype::init($options);