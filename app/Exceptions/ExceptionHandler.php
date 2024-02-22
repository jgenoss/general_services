<?php

namespace App\Exceptions;

class ExceptionHandler
{
   public function handle(\Throwable $exception)
   {
      // Puedes personalizar la lógica para manejar diferentes tipos de excepciones aquí
      // En este ejemplo, simplemente mostramos el mensaje de error en una vista.

      $errorMessage = $exception->getMessage();

      include_once __DIR__ . '../views/error.php';
   }
}
