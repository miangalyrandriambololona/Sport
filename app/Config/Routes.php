<?php

namespace Config;

$routes = Services::routes();

$routes->get('/',             'Auth::login');
$routes->get('home',          'Home::index');
$routes->get('stats',         'Stats::index');
$routes->get('add-food',      'Food::addForm');
$routes->post('add-food/save','Food::save');
$routes->get('swipe/next',    'Swipe::nextMeal');
$routes->post('swipe/do',     'Swipe::doSwipe');
$routes->get('login',         'Auth::login');
$routes->post('login/do',     'Auth::doLogin');
$routes->get('register',      'Auth::register');
$routes->post('register/do',  'Auth::doRegister');
$routes->get('logout',        'Auth::logout');