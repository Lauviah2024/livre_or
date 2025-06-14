<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'LivreOrController::index');
$routes->post('/livre-dor/submit', 'LivreOrController::create');
$routes->get('livre-dor/image/(:any)', 'LivreOrController::image/$1');
$routes->get('/livre-dor/card/(:num)', 'LivreOrController::generateCard/$1');