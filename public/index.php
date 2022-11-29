<?php

require_once dirname(__DIR__).'/vendor/autoload.php';

require_once '../env.php';

use TheMoiza\MvcCore\Core\Router;

use TheMoiza\MvcCore\Core\View;

Router::get('/', \App\Controllers\Index\IndexController::class, 'index');
Router::get('/grid', \App\Controllers\Index\IndexController::class, 'grid');
Router::get('/list', \App\Controllers\Index\IndexController::class, 'list');

Router::init();