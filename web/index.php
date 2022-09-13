<?php

use Pecee\SimpleRouter\SimpleRouter as Router;

require_once '../vendor/autoload.php';
require_once '../config/routes.php';

try {
    Router::start();
} catch (Throwable $e) {
    echo $e->getMessage();
}