<?php

namespace Config;

$routes = Services::routes();

$routes->get('register', 'UserController::register');
$routes->post('register_action', 'UserController::register_action');
$routes->get('login', 'UserController::login');
$routes->post('login_action', 'UserController::login_action');
$routes->get('logout', 'UserController::logout');

$routes->get('creneaux', 'ReservationController::index');
$routes->get('reserver/(:num)', 'ReservationController::reserver/$1');