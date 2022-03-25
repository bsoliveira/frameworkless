<?php

use App\App;

$container = require __DIR__ . '/../config/bootstrap.php';

$container->get(App::class)->run();
