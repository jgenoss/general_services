<?php
$sessionController = App\Controllers\SessionController::getInstance();
if (!$sessionController->checkSession()) {
   header('location:login');
}
$userInfo = $sessionController->getSessionData();
?>
<!DOCTYPE html>
<html lang="en">

<head>
   <title><?php echo ( ' | '.ucfirst($_GET['view'])); ?></title>
   <meta charset="utf-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
   <meta http-equiv="X-UA-Compatible" content="IE=edge" />
   <meta name="description" content="" />
   <meta name="keywords" content="">
   <meta name="author" content="JGenoss" />

   <!-- Favicon icon -->
   <link rel="icon" href="assets/images/favicon.svg" type="image/x-icon">
   <link rel="stylesheet" href="assets/css/plugins/dataTables.bootstrap4.min.css">
   <link rel="stylesheet" href="assets/css/plugins/responsive.bootstrap4.min.css">
   <!-- font css -->
   <link rel="stylesheet" href="assets/fonts/font-awsome-pro/css/pro.min.css">
   <link rel="stylesheet" href="assets/fonts/feather.css">
   <link rel="stylesheet" href="assets/fonts/fontawesome.css">

   <!-- vendor css -->
   <link rel="stylesheet" href="assets/css/style.css">
   <link rel="stylesheet" href="assets/css/customizer.css">
   <script src="assets/js/plugins/jquery.min.js"></script>
   <script src="assets/js/plugins/vue.js"></script>
   <script src="assets/js/plugins/axios.min.js"></script>
   <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.20/dist/sweetalert2.min.css" rel="stylesheet">
   <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.20/dist/sweetalert2.all.min.js"></script>
   <script src="assets/js/plugins/socket.io.min.js"></script>
</head>

<body>
   <!-- [ Pre-loader ] End -->
   <!-- [ Mobile header ] start -->
   <div class="pc-mob-header pc-header">
      <div class="pcm-logo">
         <img src="assets/images/logo.png" alt="" class="logo logo-lg">
      </div>
      <div class="pcm-toolbar">
         <a href="#" class="pc-head-link" id="mobile-collapse">
            <div class="hamburger hamburger--arrowturn">
               <div class="hamburger-box">
                  <div class="hamburger-inner"></div>
               </div>
            </div>
            <!-- <i data-feather="menu"></i> -->
         </a>
         <a href="#!" class="pc-head-link" id="header-collapse">
            <i data-feather="more-vertical"></i>
         </a>
      </div>
   </div>
   <!-- [ Mobile header ] End -->

   <!-- [ navigation menu ] start -->
   <nav class="pc-sidebar ">
      <div class="navbar-wrapper">
         <div class="m-header">
            <a href="/panel" class="b-brand">
               <!-- ========   change your logo hear   ============ -->
               <img src="assets/images/logo.png" width="180px" alt="" class="logo logo-lg">
               <img src="assets/images/logo-sm.svg" alt="" class="logo logo-sm">
            </a>
         </div>
         <div class="navbar-content">
            <ul class="pc-navbar">
               <li class="pc-item pc-caption">
                  <label>Navegación</label>
               </li>
               <li class="pc-item">
                  <a href="panel" class="pc-link "><span class="pc-micon"><i data-feather="home"></i></span><span class="pc-mtext">Panel</span></a>
               </li>
               <?php if ($userInfo['user']['role'] == 'admin' || $userInfo['user']['role'] == 'developer') : ?>
                  <li class="pc-item">
                     <a href="usuarios" class="pc-link "><span class="pc-micon"><i data-feather="unlock"></i></span><span class="pc-mtext">Usuarios</span></a>
                  </li>
               <?php endif; ?>
               <li class="pc-item pc-hasmenu">
						<a href="#!" class="pc-link "><span class="pc-micon"><i data-feather="users"></i></span><span class="pc-mtext">Clientes</span><span class="pc-arrow"><i data-feather="chevron-right"></i></span></a>
						<ul class="pc-submenu">
							<li class="pc-item"><a class="pc-link" href="clientes">Clientes</a></li>
							<li class="pc-item"><a class="pc-link" href="establecimiento">Establecimientos</a></li>
						</ul>
					</li>
               <li class="pc-item pc-hasmenu">
                  <a href="#!" class="pc-link "><span class="pc-micon"><i data-feather="shopping-cart"></i></span><span class="pc-mtext">Perdidos</span><span class="pc-arrow"><i data-feather="chevron-right"></i></span></a>
                  <ul class="pc-submenu">
                     <li class="pc-item"><a class="pc-link" href="pedidos">Pedidos</a></li>
                     <li class="pc-item"><a class="pc-link" href="servicios">Servicios</a></li>
                     <li class="pc-item"><a class="pc-link" href="productos">Productos</a></li>
                  </ul>
               </li>
               <li class="pc-item">
                  <a href="visitas" class="pc-link "><span class="pc-micon"><i data-feather="list"></i></span><span class="pc-mtext">Visitas</span></a>
               </li>
            </ul>
         </div>
      </div>
   </nav>
   <!-- [ navigation menu ] end -->
   <!-- [ Header ] start -->
   <header class="pc-header ">
      <div class="header-wrapper">
         <div class="ml-auto">
            <ul class="list-unstyled">
               <li class="dropdown pc-h-item">
                  <a class="pc-head-link dropdown-toggle arrow-none mr-0" data-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                     <img src="assets/images/user/avatar-2.png" alt="user-image" class="user-avtar">
                     <span>
                        <span class="user-name"><?php echo ucwords($userInfo['user']['nombre']); ?></span>
                        <span class="user-desc"><?php echo ucwords($userInfo['user']['role']); ?></span>
                     </span>
                  </a>
                  <div class="dropdown-menu dropdown-menu-right pc-h-dropdown">
                     <div class=" dropdown-header">
                        <h6 class="text-overflow m-0">Welcome !</h6>
                     </div>
                     <a id="logout" href="#!" class="dropdown-item">
                        <i data-feather="power"></i>
                        <span>Logout</span>
                     </a>
                  </div>
               </li>
            </ul>
         </div>
      </div>
   </header>