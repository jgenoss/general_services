<div id="Clients" class="pc-container">
   <div class="pcoded-content">
      <div class="page-header">
         <div class="page-block">
            <div class="row align-items-center">
               <div class="col-md-6">
                  <div class="page-header-title">
                     <h5 class="m-b-10">Gestion de Clientes</h5>
                  </div>
                  <ul class="breadcrumb">
                     <li class="breadcrumb-item">
                        <a href="/panel">Panel</a>
                     </li>
                     <li class="breadcrumb-item">Clientes</li>
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
                  <button type="button" class="btn btn-primary" @click="setRegisterClientButton">Registrar
                     Cliente</button>
               </div>
               <div class="card-body table-responsive">
                  <table id="example" class="table table-head-fixed text-nowrap">
                     <thead>
                        <tr>
                           <th>Op</th>
                           <th>Nombres</th>
                           <th>Apellidos</th>
                           <th>Documento</th>
                           <th>Telefono</th>
                           <th>Email</th>
                           <th>direccion</th>
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
                     <h4>Registrar Cliente</h4>
                     <div v-if="error" class="alert alert-success alert-dismissible fade show" role="alert">
                        <strong>Alerta</strong> {{error}}. <button type="button" class="close" data-dismiss="alert"
                           aria-label="Close">
                           <span aria-hidden="true">×</span>
                        </button>
                     </div>
                  </div>
               </div>
               <form @submit.prevent="registerClient" class="form-v1">
                  <div class="card-body">
                     <div class="row">
                        <div class="col-md-6 col-lg-3">
                           <div class="form-group">
                              <label for="nombres">Nombres:</label>
                              <input v-model="client.nombres" type="text" class="form-control" id="nombres" required>
                           </div>
                        </div>
                        <div class="col-md-6 col-lg-3">
                           <div class="form-group">
                              <label for="apellidos">Apellidos:</label>
                              <input v-model="client.apellidos" type="text" class="form-control" id="apellidos"
                                 required>
                           </div>
                        </div>
                        <div class="col-md-6 col-lg-2">
                           <div class="form-group">
                              <label for="tipo_identificacion">Tipo de Identificación:</label>
                              <select v-model="client.tipo_identificacion" class="form-control"
                                 v-model="client.tipo_documento" id="tipo_identificacion">
                                 <option value="NIT">NIT</option>
                                 <option value="CC">CC</option>
                                 <option value="CE">CE</option>
                              </select>
                           </div>
                        </div>
                        <div class="col-md-6 col-lg-2">
                           <div class="form-group">
                              <label for="no_identificacion">Número de Identificación:</label>
                              <input v-model="client.no_identificacion" type="text" class="form-control"
                                 id="no_identificacion" required>
                           </div>
                        </div>
                        <div class="col-md-6 col-lg-2">
                           <div class="form-group">
                              <label for="juridico">¿Es una persona jurídica?</label>
                              <select v-model="client.juridico" class="form-control" id="juridico" required>
                                 <option value="true">Sí</option>
                                 <option selected value="false">No</option>
                              </select>
                           </div>
                        </div>
                        <div class="col-md-6 col-lg-2">
                           <div class="form-group">
                              <label for="telefono_cliente">Teléfono:</label>
                              <input v-model="client.telefono" type="tel" class="form-control" id="telefono_cliente"
                                 required>
                           </div>
                        </div>
                        <div class="col-md-6 col-lg-4">
                           <div class="form-group">
                              <label for="Email">Email:</label>
                              <input v-model="client.email" type="email" class="form-control" id="Email" required>
                           </div>
                        </div>
                        <div class="col-md-6 col-lg-6">
                           <div class="form-group">
                              <label for="direccion_cliente">Dirección:</label>
                              <input v-model="client.direccion" type="text" class="form-control" id="direccion_cliente"
                                 required>
                           </div>
                        </div>
                        <div class="col-md-6 col-lg-6">
                           <div class="form-group">
                              <label>Información tributaria:</label>
                              <div class="form-check">
                                 <input v-model="client.regimen_comun" type="checkbox" class="form-check-input"
                                    id="regimen_comun">
                                 <label class="form-check-label" for="regimen_comun">Regimen Común</label>
                              </div>
                              <div class="form-check">
                                 <input v-model="client.regimen_simplificado" type="checkbox" class="form-check-input"
                                    id="regimen_simplificado">
                                 <label class="form-check-label" for="regimen_simplificado">Regimen Simplificado</label>
                              </div>
                              <div class="form-check">
                                 <input v-model="client.gran_contribuyente" type="checkbox" class="form-check-input"
                                    id="gran_contribuyente">
                                 <label class="form-check-label" for="gran_contribuyente">Gran Contribuyente</label>
                              </div>
                              <div class="form-check">
                                 <input v-model="client.autoretenedor" type="checkbox" class="form-check-input"
                                    id="autoretenedor">
                                 <label class="form-check-label" for="autoretenedor">Autoretenedor</label>
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