<?php

namespace Config;

$routes = Services::routes();

$routes->get('creneaux', 'CreneauxController::index');
$routes->post('reserver/(:num)', 'CreneauxController::reserver/$1');