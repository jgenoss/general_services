<div id="users" class="pc-container">
   <div class="pcoded-content">
      <div class="page-header">
         <div class="page-block">
            <div class="row align-items-center">
               <div class="col-md-6">
                  <div class="page-header-title">
                     <h5 class="m-b-10">Gestion de Usuarios</h5>
                  </div>
                  <ul class="breadcrumb">
                     <li class="breadcrumb-item"><a href="/panel">Panel</a></li>
                     <li class="breadcrumb-item">Usuarios</li>
                  </ul>
               </div>
            </div>
         </div>
      </div>
      <!-- [ breadcrumb ] end -->
      <!-- [ Main Content ] start -->
      <div class="row">
         <div v-if="list" class="col-lg-12">
            <div class="card">
               <div v-if="message" class="alert alert-success alert-dismissible fade show" role="alert">
                  <strong>Alerta</strong> {{message}}.
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                        aria-hidden="true">×</span></button>
               </div>
               <div class="card-header">
                  <button type="button" class="btn btn-primary" @click="setRegisterUserButton">Registrar
                     Usuario</button>
               </div>
               <div class="card-body table-responsive">
                  <table id="example" class="table table-head-fixed text-nowrap">
                     <thead>
                        <tr>
                           <th>Op</th>
                           <th>Nombre</th>
                           <th>Email</th>
                           <th>Rol</th>
                           <th>Status</th>
                        </tr>
                     </thead>
                  </table>
               </div>
            </div>
         </div>
         <div v-if="form" class="col-lg-12">
            <div class="card">
               <div class="card-header">
                  <div class="card-tittle">
                     <h4>Registrar usuario</h4>
                     <div v-if="error" class="alert alert-success alert-dismissible fade show" role="alert">
                        <strong>Alerta</strong> {{error}}.
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                              aria-hidden="true">×</span></button>
                     </div>
                  </div>
               </div>
               <form @submit.prevent="registerUser" class="form-v1">
                  <div class="card-body">
                     <div class="row">
                        <div class="col-sm-6 col-lg-3">
                           <div class="form-label-group">
                              <input v-model="user.email" type="email" id="inputEmail0" class="form-control" required
                                 autofocus>
                              <label for="inputEmail0">Correo *</label>
                           </div>
                        </div>
                        <div class="col-sm-6 col-lg-3">
                           <div class="form-label-group">
                              <input v-model="user.nombre" type="text" id="inputName" class="form-control" required
                                 autofocus>
                              <label for="inputName">Nombre Completo *</label>
                           </div>
                        </div>
                        <div class="col-sm-6 col-lg-2">
                           <div class="form-label-group">
                              <input v-model="user.contrasena" type="password" id="inputPassword0" class="form-control"
                                 required>
                              <label for="inputPassword0">Cotraseña *</label>
                           </div>
                        </div>
                        <div class="col-sm-6 col-lg-2">
                           <div class="form-label-group">
                              <select v-model="user.role_id" id="selectRole" class="form-control">
                                 <option value="1">Administrador</option>
                                 <option value="2">Editor</option>
                                 <option value="3">Usuario</option>
                              </select>
                           </div>
                        </div>
                        <div class="col-sm-6 col-lg-2">
                           <div class="form-label-group">
                              <select v-model="user.status" class="form-control">
                                 <option value="true">Activo</option>
                                 <option value="false">Inactivo</option>
                              </select>
                           </div>
                        </div>
                     </div>
                  </div>
                  <div class="card-footer">
                     <div>
                        <button @click="cancelButton" type="button" class="btn btn-secondary">Cancelar</button>
                        <button type="submit" class="btn btn-primary float-right">Guardar Cambios</button>
                     </div>
                  </div>
               </form>
            </div>
         </div>
      </div>
      <!-- [ Main Content ] end -->
   </div>
</div>
<script src="assets/js/plugins/jquery.dataTables.min.js"></script>
<script src="assets/js/plugins/dataTables.bootstrap4.min.js"></script>
<script src="assets/js/plugins/dataTables.responsive.min.js"></script>
<script src="assets/js/plugins/responsive.bootstrap4.min.js"></script>