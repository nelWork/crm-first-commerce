<?php

require_once "../vendor/autoload.php";


define('APP_PATH', dirname(__DIR__));
use App\App;
$app = new App();



$app->run();

?>
