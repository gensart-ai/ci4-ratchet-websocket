<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->setAutoRoute(true);
$routes->addRedirect('/', 'login');
$routes->get('chat', 'Home::index');
$routes->get('login', 'Home::login');
$routes->post('login', 'Home::registerUsername');
$routes->get('logout', 'Home::logout');
