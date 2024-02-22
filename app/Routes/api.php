<?php

$router->post('/api/login', 'LoginController@loginUser');
$router->get('/api/logout', 'LoginController@logoutUser');

// Rutas relacionadas con usuarios
$router->get('/api/usuarios', 'UserController@getAllUsers');
$router->post('/api/usuarios', 'UserController@registerUserAndEdit');
$router->get('/api/getToken', 'UserController@getToken');
$router->get('/api/usuarios/{id}', 'UserController@getUserData');
$router->get('/api/usuarios/deleteId/{id}', 'UserController@deleteUserId');

$router->post('/api/usuarios/checkUserPermissions', 'UserController@checkUserPermissions');

$router->get('/api/usuarios/test/{id}', 'UserController@getUser');

// Rutas relacionadas con clientes
$router->get('/api/clientes', 'ClientController@getAllClients');
$router->post('/api/clientes', 'ClientController@registerUserAndEdit');
$router->get('/api/clientes/{id}', 'ClientController@getClient');

// Rutas relacionadas con establecimiento
$router->get('/api/establecimiento/getClients', 'EstablishmentController@getClients');
$router->post('/api/establecimiento', 'EstablishmentController@registerEstablishmentsAndEdit');
$router->get('/api/establecimiento', 'EstablishmentController@getAllEstablishments');
$router->get('/api/establecimiento/{id}', 'EstablishmentController@getEstablishment');

// Rutas relacionadas con productos
$router->get('/api/productos', 'ProductsController@getAllProducts');
$router->post('/api/productos', 'ProductsController@registerProductAndEdit');
$router->get('/api/productos/{id}', 'ProductsController@getProduct');

// Rutas relacionadas con servicios
$router->get('/api/servicios', 'ServicesController@showAllServices');
$router->post('/api/servicios', 'ServicesController@');
$router->get('', '');

// Rutas relacionadas con pedidos
$router->get('/api/pedidos/getEstablishment/{id}', 'OrdersController@getEstablishment');
$router->get('/api/pedidos/getProducts', 'OrdersController@getAllProducts');
$router->post('/api/pedidos/checkStock', 'OrdersController@checkStock');
$router->post('/api/pedidos', 'OrdersController@registerOrdersAndEdit');
$router->get('/api/pedidos/{id}', 'OrdersController@getOrderById');
$router->get('/api/pedidos/delete/{id}', 'OrdersController@deleteOrderById');
$router->get('/api/pedidos', 'OrdersController@getAllOrdersById');

$router->post('/api/visitas', 'VisitController@registerVistit');
$router->get('/api/visitas', 'VisitController@getVisits');
$router->get('/api/visitas/{id}', 'VisitController@getVisitById');


// Rutas relacionadas con ventas
//$router->post('/api/ventas', 'SalesController@createSale');

// Rutas relacionadas con productos

// Rutas relacionadas con inventario
//$router->put('/api/inventario/{id}', 'InventoryController@updateStock');