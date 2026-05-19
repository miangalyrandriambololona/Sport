<?php

namespace Config;

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes = Services::routes();

$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('UserController');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(false);   // ← Très important

// ====================== ROUTES PUBLIQUES ======================
$routes->get('/', 'UserController::login');

$routes->get('login', 'UserController::login');
$routes->post('login_action', 'UserController::login_action');
$routes->get('register', 'UserController::register');
$routes->post('register_action', 'UserController::register_action');
$routes->get('logout', 'UserController::logout');

// Client
$routes->get('creneaux', 'ReservationController::index');
$routes->get('reserver/(:num)', 'ReservationController::reserver/$1');
$routes->get('mes-reservations', 'ReservationController::mes_reservations');
$routes->get('annuler/(:num)', 'ReservationController::annuler/$1');

// ====================== ROUTES ADMIN ======================
$routes->group('admin', ['namespace' => 'App\Controllers'], function ($routes) {

    $routes->get('creneaux', 'AdminController::index');
    $routes->get('creneaux/creer', 'AdminController::creer');
    $routes->post('creneaux/enregistrer', 'AdminController::enregistrer');
    $routes->get('creneaux/supprimer/(:num)', 'AdminController::supprimer/$1');

    $routes->get('reservations', 'AdminController::reservations');
    
    // Route améliorée pour gérer les accents
    $routes->post('reservations/statut/(:num)/(:any)', 'AdminController::changer_statut/$1/$2');
});
$routes->get('admin', 'AdminController::index');