<?php

namespace App\Controllers;

class ViewController
{
   public function showIndex()
   {
      require_once __DIR__.'/../../resources/views/index.php';
      $this->loadJs();
   }
   public function showLogin()
   {
      require_once __DIR__.'/../../resources/views/login.php';
      $this->loadJs();
   }

   public function showPanel()
   {
      $this->loadTemplate('header');
      require_once __DIR__.'/../../resources/views/contents/panel.php';
      $this->loadTemplate('footer');
      $this->loadJs();
   }

   public function showUsers()
   {
      $this->loadTemplate('header');
      require_once __DIR__.'/../../resources/views/contents/usuarios.php';
      $this->loadTemplate('footer');
      $this->loadJs();
   }

   public function showClients()
   {
      $this->loadTemplate('header');
      require_once __DIR__.'/../../resources/views/contents/clientes.php';
      $this->loadTemplate('footer');
      $this->loadJs();
   }

   public function showEstablishment()
   {
      $this->loadTemplate('header');
      require_once __DIR__.'/../../resources/views/contents/establecimiento.php';
      $this->loadTemplate('footer');
      $this->loadJs();
   }
   public function showOrders()
   {
      $this->loadTemplate('header');
      require_once __DIR__.'/../../resources/views/contents/pedidos.php';
      $this->loadTemplate('footer');
      $this->loadJs();
   }
   public function showProducts()
   {
      $this->loadTemplate('header');
      require_once __DIR__.'/../../resources/views/contents/productos.php';
      $this->loadTemplate('footer');
      $this->loadJs();
   }
   public function showServices()
   {
      $this->loadTemplate('header');
      require_once __DIR__.'/../../resources/views/contents/servicios.php';
      $this->loadTemplate('footer');
      $this->loadJs();
   }
   public function showVisits()
   {
      $this->loadTemplate('header');
      require_once __DIR__.'/../../resources/views/contents/visitas.php';
      $this->loadTemplate('footer');
      $this->loadJs();
   }

   private function loadTemplate($template)
   {
      // Cargamos una plantilla común (por ejemplo, un encabezado o pie de página).
      $templatePath = __DIR__.'/../../resources/views/layout/' . $template . '.php';
      if (file_exists($templatePath)) {
         require_once $templatePath;
      }
   }

   public function loadJs()
   {
      if (isset($_GET['view']) && preg_match('/^[a-zA-Z0-9]+$/', $_GET['view'])) {
         $name = $_GET['view'];
         $path = 'assets/js/' . $name . '.js';
         $script = '<script src="' . $path . '"></script>';
         if (file_exists($path)) {
            print($script);
         }
      }
   }
}