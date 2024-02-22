   <?php
   $sessionController = App\Controllers\SessionController::getInstance();
   if ($sessionController->checkSession()) {
      header('location:panel');
   }
   ?>

<!DOCTYPE html>
<html lang="en">
   <head>
      <title><?php echo ($_GET['view']); ?></title>
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
      <meta http-equiv="X-UA-Compatible" content="IE=edge" />
      <meta name="description" content="" />
      <meta name="keywords" content="">
      <meta name="author" content="JGenoss" />

      <!-- Favicon icon -->
      <link rel="icon" href="assets/images/favicon.svg" type="image/x-icon">

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
   </head>
   <body>
      <div id="app" class="auth-wrapper">
         <div class="auth-content">
            <div class="card">
               <div class="row align-items-center text-center">
                  <div class="col-md-12">
                     <div class="card-body">
                        <form @submit.prevent="login">
                           <img src="assets/images/logo.png" width="180px" alt="" class="img-fluid mb-4">
                           <h4 class="mb-3 f-w-400">Signin</h4>
                           <div class="input-group mb-3">
                              <div class="input-group-prepend">
                                 <span class="input-group-text"><i data-feather="mail"></i></span>
                              </div>
                              <input type="text" v-model="email" class="form-control" placeholder="Email address">
                           </div>
                           <div class="input-group mb-4">
                              <div class="input-group-prepend">
                                 <span class="input-group-text"><i data-feather="lock"></i></span>
                              </div>
                              <input type="password" v-model="password" class="form-control" placeholder="Password">
                           </div>
                           <button type="submit" class="btn btn-block btn-primary mb-4">Signin</button>
                        </form>
                        <div v-if="error">
                           <div class="alert alert-danger alert-dismissible fade show" role="alert">
                              <strong>
                                 <p>{{error}}</p>
                                 <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
                           </div>
                        </div>
                        <div v-if="message">
                           <div class="alert alert-success alert-dismissible fade show" role="alert">
                              <strong>
                                 <p>{{message}}</p>
                                 <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <script src="assets/js/plugins/bootstrap.min.js"></script>
      <script src="assets/js/plugins/feather.min.js"></script>
      <script src="assets/js/plugins/pcoded.min.js"></script>
      <script src="assets/js/plugins/highlight.min.js"></script>
      <script src="assets/js/plugins/clipboard.min.js"></script>
      <script src="assets/js/plugins/uikit.min.js"></script>
   </body>
</html>