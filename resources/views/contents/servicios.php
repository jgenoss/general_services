<div id="Services" class="pc-container">
   <div class="pcoded-content">
      <div class="page-header">
         <div class="page-block">
            <div class="row align-items-center">
               <div class="col-md-6">
                  <div class="page-header-title">
                     <h5 class="m-b-10">Gestión de Servicios</h5>
                  </div>
                  <ul class="breadcrumb">
                     <li class="breadcrumb-item">
                        <a href="/panel">Panel</a>
                     </li>
                     <li class="breadcrumb-item">Servicios</li>
                  </ul>
               </div>
            </div>
         </div>
      </div>
      <!-- [ breadcrumb ] end -->
      <!-- [ Main Content ] start -->
      <div class="row">
         <div v-if="list" class="col-lg-12">
            <div v-if="message" class="alert alert-success alert-dismissible fade show" role="alert">
               <strong>Alerta</strong> {{message}}.
            </div>
            <div class="card">
               <div class="card-header">
                  <button type="button" class="btn btn-primary" @click="setRegisterServiceButton">Registrar
                     Servicio</button>
               </div>
               <div class="card-body table-responsive">
                  <table id="example" class="table table-head-fixed text-nowrap">
                     <thead>
                        <tr>
                           <th>Op</th>
                           <th>Nombre</th>
                           <th>Descripción</th>
                           <th>Precio</th>
                           <th>Estado</th>
                        </tr>
                     </thead>
                     <tbody>
                        <!-- Aquí mostrarás los datos de los productos en la tabla -->
                     </tbody>
                  </table>
               </div>
            </div>
         </div>
         <div v-if="form" class="col-lg-12">
            <div class="card">
               <div class="card-header">
                  <div class="card-tittle">
                     <h4>Registrar Servicio</h4>
                     <div v-if="error" class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Alerta</strong> {{error}}. <button type="button" class="close" data-dismiss="alert"
                           aria-label="Close">
                           <span aria-hidden="true">×</span>
                        </button>
                     </div>
                  </div>
               </div>
               <form @submit.prevent="registerProduct" class="form-v1">
                  <div class="card-body">
                     <div class="row">
                        <div class="col-md-6">
                           <div class="form-group">
                              <label for="nombre">Nombre:</label>
                              <input v-model="service.service_name" type="text" class="form-control" id="nombre" required>
                           </div>
                        </div>
                        <div class="col-md-6">
                           <div class="form-group">
                              <label for="descripcion">Descripción:</label>
                              <textarea v-model="service.description" class="form-control" id="descripcion"></textarea>
                           </div>
                        </div>
                        <div class="col-md-6">
                           <div class="form-group">
                              <label for="precio">Precio:</label>
                              <input v-model="service.price" type="text" class="form-control" id="precio" required>
                           </div>
                        </div>
                     </div>
                     <div class="card-footer">
                        <div>
                           <button @click="cancelButton" type="button" class="btn btn-secondary">Cancelar</button>
                           <button type="submit" class="btn btn-primary float-right">Guardar Cambios</button>
                        </div>
                     </div>
                  </div>
               </form>
            </div>
         </div>
      </div>
   </div>
</div>
<script src="assets/js/plugins/jquery.dataTables.min.js"></script>
<script src="assets/js/plugins/dataTables.bootstrap4.min.js"></script>
<script src="assets/js/plugins/dataTables.responsive.min.js"></script>
<script src="assets/js/plugins/responsive.bootstrap4.min.js"></script>