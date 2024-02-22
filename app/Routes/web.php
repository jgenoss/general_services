<?php

$router->get('/', 'ViewController@showIndex');

// Ruta relacionadas con el inicio de sesion
$router->get('/login', 'ViewController@showLogin');

// Rutas relacionadas con las vistas y el login
$router->get('/panel', 'ViewController@showPanel');
$router->get('/usuarios', 'ViewController@showUsers');
$router->get('/clientes', 'ViewController@showClients');
$router->get('/establecimiento', 'ViewController@showEstablishment');
$router->get('/pedidos', 'ViewController@showOrders');
$router->get('/servicios', 'ViewController@showServices');
$router->get('/productos', 'ViewController@showProducts');
$router->get('/visitas', 'ViewController@showVisits');