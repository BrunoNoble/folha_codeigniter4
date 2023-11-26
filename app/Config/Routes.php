<?php

use CodeIgniter\Router\RouteCollection;
use App\Controllers\FolhaRegistrosController;


/**
 * @var RouteCollection $routes
 */

$routes->get('/', 'Home::index');


$routes->get('/folha', 'FolhaRegistrosController::index');