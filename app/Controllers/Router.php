<?php

namespace App\Controllers;
class Router
{
   private $routes = [];
   private $BASE_PATH;
   public function __construct()
   {
      $this->BASE_PATH = $_ENV['BASE_PATH'] ? : '';
   }
   public function get($url, $action)
   {
      $this->routes['GET'][$this->BASE_PATH . $url] = $action;
   }

   public function post($url, $action)
   {
      $this->routes['POST'][$this->BASE_PATH . $url] = $action;
   }

   public function run()
   {
      $rawInput = json_decode(file_get_contents("php://input"), true);
      if (is_array($rawInput)) {
         $_POST = filter_var_array($rawInput, 513); //FILTER_SANITIZE_STRING = 513

         $method = $_SERVER['REQUEST_METHOD'];
         $url = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
         $action = $this->findAction($method, $url);
         if ($action !== null) {
            $this->executeAction($action);
         } else {
            header('HTTP/1.1 404 Not Found');
            echo "Error 404: Página no encontrada.";
         }
      } else {

         $_POST = filter_input($rawInput, 513); //FILTER_SANITIZE_STRING = 513
         $method = $_SERVER['REQUEST_METHOD'];
         $url = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
         $action = $this->findAction($method, $url);
         if ($action !== null) {
            $this->executeAction($action);
         } else {
            header('HTTP/1.1 404 Not Found');
            echo "Error 404: Página no encontrada.";
         }
      }
   }
   private function findAction($method, $url)
   {
      if (array_key_exists($method, $this->routes)) {

         foreach ($this->routes[$method] as $route => $action) {
            // Convertimos las partes de la ruta en patrones de expresiones regulares
            $pattern = $this->convertToRegexPattern($route);
            // Si la URL coincide con el patrón, obtenemos los parámetros y pasamos la acción al controlador
            if (preg_match($pattern, $url, $matches)) {
               return [$action, array_slice($matches, 1)];
            }
         }
      }
      return null;
   }
   private function executeAction($action)
   {
      list($controllerName, $methodName) = explode('@', $action[0]);
      // Construir el nombre completo del controlador con el espacio de nombres
      $controllerClass = __NAMESPACE__ . '\\' . $controllerName;
      // Verificar si la clase existe antes de intentar crear una instancia
      if (class_exists($controllerClass)) {
         $controller = new $controllerClass();
         if (method_exists($controller, 'requiresAuthentication') && $controller->requiresAuthentication()) {
            // Si la acción requiere inicio de sesión, verificar si el usuario ha iniciado sesión
            $sessionController = SessionController::getInstance();
            if (!$sessionController->checkSession()) {
               // Si el usuario no ha iniciado sesión, redirigir al inicio de sesión o mostrar un error
               header('location:https://google.com');
               exit;
            }
         }
         if (method_exists($controller, $methodName)) {
            // Obtenemos solo el ID del array de coincidencias
            $id = $action[1]['id'] ?? null;
            $controller->{$methodName}($id); // Pasamos el ID como argumento al método
         } else {
            header('HTTP/1.1 404 Not Found');
            echo "Error 404: Método no encontrado.";
         }
      } else {
         header('HTTP/1.1 404 Not Found');
         echo "Error 404: Controlador no encontrado.";
      }
   }
   private function convertToRegexPattern($route)
   {
      // Convertimos las partes de la ruta en patrones de expresiones regulares
      $pattern = '/^' . str_replace('/', '\/', $route) . '$/';
      // Reemplazamos el patrón "{id}" con el patrón de expresión regular para capturar el ID
      $pattern = preg_replace('/\{(\w+)\}/', '(?<$1>\d+)', $pattern);
      return $pattern;
   }
}