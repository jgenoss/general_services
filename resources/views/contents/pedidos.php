<div id="Pedidos" class="pc-container">
   <div class="pcoded-content">
      <div class="page-header">
         <div class="page-block">
            <div class="row align-items-center">
               <div class="col-md-6">
                  <div class="page-header-title">
                     <h5 class="m-b-10">Gestión de Pedidos</h5>
                  </div>
                  <ul class="breadcrumb">
                     <li class="breadcrumb-item">
                        <a href="/panel">Panel</a>
                     </li>
                     <li class="breadcrumb-item">Pedidos</li>
                  </ul>
               </div>
            </div>
         </div>
      </div>
      <!-- [ breadcrumb ] end -->
      <!-- [ Main Content ] start -->
      <div class="row">
         <div v-if="list" class="col-lg-12">
            <!-- Tabla de Pedidos -->
            <div class="card">
               <div class="card-header">
                  <button type="button" class="btn btn-primary" @click="setRegistroPedido">Registrar pedido</button>
               </div>
               <div class="card-body table-responsive">
                  <table id="example" class="table table-head-fixed text-nowrap">
                     <thead>
                        <tr>
                           <th>Op</th>
                           <th>TXID</th>
                           <th>Cliente</th>
                           <th>Establecimiento</th>
                           <th>Precio sin IVA</th>
                           <th>Precio total+Iva</th>
                           <th>Fecha y Hora</th>
                           <th>Estado</th>
                        </tr>
                     </thead>
                  </table>
               </div>
            </div>
         </div>
         <div v-if="form" class="col-lg-7 justify-content-center" style="margin: 0 auto;">
            <!-- Formulario para registrar nuevos Pedidos -->
            <div class="card">
               <div class="card-header">
                  <div class="card-tittle">
                     <h4>Registrar Pedido</h4>
                     <div v-if="error" class="alert alert-success alert-dismissible fade show" role="alert">
                        <strong>Alerta</strong> {{error}}.
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                           <span aria-hidden="true">×</span>
                        </button>
                     </div>
                  </div>
               </div>
               <form @submit.prevent="registrarPedido" class="form-v1">
                  <div class="card-body">
                     <div class="row">
                        <div class="col-md-6 col-lg-4">
                           <div class="form-group">
                              <label>Cliente:</label>
                              <select class="form-control" v-model="orders.id_cliente" @change="getEstablishment(orders.id_cliente)" required>
                                 <option v-for="client in clients" v-bind:value="client.id_cliente">
                                    {{client.tipo_identificacion}} - {{client.no_identificacion}} ({{client.nombres}}
                                    {{client.apellidos}})</option>
                              </select>
                           </div>
                        </div>
                        <div class="col-md-6 col-lg-4">
                           <div class="form-group">
                              <label>Establecimiento:</label>
                              <select class="form-control" v-model="orders.id_establecimiento" required>
                                 <option v-for="establishment in establishments"
                                    v-bind:value="establishment.id_establecimiento">{{establishment.nombre}}</option>
                              </select>
                           </div>
                        </div>
                        <div class="col-md-6 col-lg-4">
                           <div class="form-group">
                              <label>Fecha de Pedido:</label>
                              <input v-model="orders.fechaPedido" type="datetime-local" class="form-control" required>
                           </div>
                        </div>
                        <div class="col-md-6 col-lg-12">
                           <div class="form-group">
                              <label>Observacion:</label>
                              <textarea class="form-control" v-model="orders.observacion" cols="10" rows="3"></textarea>
                           </div>
                        </div>
                     </div>
                     <div class="col-md-6 col-lg-12">
                        <label>Detalles del pedido:</label><br>
                        <a @click="getListaProducts" href="#" data-toggle="modal" data-target="#modalDetails"
                           class="btn btn-sm btn-primary mb-2">AGREGAR PRODUCTO</a>
                        <div class="table-responsive">
                           <table class="table table-bordered text-nowrap text-center">
                              <thead>
                                 <tr>
                                    <th>CODIGO</th>
                                    <th>NOMBRE</th>
                                    <th>PRECIO</th>
                                    <th>CANT</th>
                                 </tr>
                              </thead>
                              <tbody>
                                 <tr v-for="(detail, index) in details">
                                    <td>{{detail.codigo}}</td>
                                    <td>{{detail.nombre}}</td>
                                    <td class="text-center"><input @input="count(index)" style="width: 60px;" value="1"
                                          v-model="detail.cantidad" type="number" required></td>
                                    <td>{{calcularSubtotal(detail).toLocaleString('es-CO', { style: 'currency',
                                       currency: 'COP' })}}</td>
                                    <td class="text-center"><a href="#" v-on:click.prevent="deleteDetail(index)"
                                          class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></a></td>
                                 </tr>
                              </tbody>
                              <tfoot>
                                 <tr>
                                    <td colspan="3" class="text-right"><strong>Subtotal total:</strong></td>
                                    <td>{{ calcularSubtotalTotal().toLocaleString('es-CO', { style: 'currency',
                                       currency: 'COP' }) }}</td>
                                 </tr>
                                 <tr>
                                    <td colspan="3" class="text-right"><strong>Monto del IVA:</strong></td>
                                    <td>{{ calcularIVA().toLocaleString('es-CO', { style: 'currency', currency: 'COP' })
                                       }}</td>
                                 </tr>
                                 <tr>
                                    <td colspan="3" class="text-right"><strong>Total General:</strong></td>
                                    <td>{{ calcularTotalGeneral().toLocaleString('es-CO', { style: 'currency', currency:
                                       'COP' }) }}</td>
                                 </tr>
                              </tfoot>
                           </table>
                           <!-- Detalles del Pedido -->
                        </div>
                     </div>
                     <div class="card-footer">
                        <div>
                           <button @click="cancelarRegistroPedido" type="button" class="btn btn-secondary">Cancelar
                           </button>
                           <button type="submit" class="btn btn-primary float-right">Guardar Pedido</button>
                        </div>
                     </div>
                  </div>
               </form>
            </div>
         </div>
         <div v-if="form_edit" class="col-lg-7 justify-content-center" style="margin: 0 auto;">
            <!-- Formulario para registrar nuevos Pedidos -->
            <div class="card">
               <div class="card-header">
                  <div class="card-tittle">
                     <h4>Registrar Pedido</h4>
                     <div v-if="error" class="alert alert-success alert-dismissible fade show" role="alert">
                        <strong>Alerta</strong> {{error}}.
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                           <span aria-hidden="true">×</span>
                        </button>
                     </div>
                  </div>
               </div>
               <form @submit.prevent="registrarPedido" class="form-v1">
                  <div class="card-body">
                     <div class="row">
                        <div class="col-md-6 col-lg-4">
                           <div class="form-group">
                              <label>Cliente:</label>
                              <select class="form-control" v-model="orders.id_cliente" @change="getEstablishment(orders.id_cliente)" required>
                                 <option v-for="client in clients" v-bind:value="client.id_cliente">
                                    {{client.tipo_identificacion}} - {{client.no_identificacion}} ({{client.nombres}}
                                    {{client.apellidos}})</option>
                              </select>
                           </div>
                        </div>
                        <div class="col-md-6 col-lg-4">
                           <div class="form-group">
                              <label>Establecimiento:</label>
                              <select class="form-control" v-model="orders.id_establecimiento" required>
                                 <option v-for="establishment in establishments"
                                    v-bind:value="establishment.id_establecimiento">{{establishment.nombre}}</option>
                              </select>
                           </div>
                        </div>
                        <div class="col-md-6 col-lg-4">
                           <div class="form-group">
                              <label>Fecha de Pedido:</label>
                              <input v-model="orders.fechaPedido" type="datetime-local" class="form-control" required>
                           </div>
                        </div>
                        <div class="col-md-6 col-lg-12">
                           <div class="form-group">
                              <label>Observacion:</label>
                              <textarea class="form-control" v-model="orders.observacion" cols="10" rows="3"></textarea>
                           </div>
                        </div>
                     </div>
                     <div class="col-md-6 col-lg-12">
                        <label>Detalles del pedido:</label><br>
                        <a @click="getListaProducts" href="#" data-toggle="modal" data-target="#modalDetails"
                           class="btn btn-sm btn-primary mb-2">AGREGAR PRODUCTO</a>
                        <div class="table-responsive">
                           <table class="table table-bordered text-nowrap text-center">
                              <thead>
                                 <tr>
                                    <th>CODIGO</th>
                                    <th>NOMBRE</th>
                                    <th>PRECIO</th>
                                    <th>CANT</th>
                                 </tr>
                              </thead>
                              <tbody>
                                 <tr v-for="(detail, index) in details">
                                    <td>{{detail.codigo}}</td>
                                    <td>{{detail.nombre}}</td>
                                    <td class="text-center"><input @input="count(index)" style="width: 60px;" value="1"
                                          v-model="detail.cantidad" type="number" required></td>
                                    <td>{{calcularSubtotal(detail).toLocaleString('es-CO', { style: 'currency',
                                       currency: 'COP' })}}</td>
                                    <td class="text-center"><a href="#" v-on:click.prevent="deleteDetail(index)"
                                          class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></a></td>
                                 </tr>
                              </tbody>
                              <tfoot>
                                 <tr>
                                    <td colspan="3" class="text-right"><strong>Subtotal total:</strong></td>
                                    <td>{{ calcularSubtotalTotal().toLocaleString('es-CO', { style: 'currency',
                                       currency: 'COP' }) }}</td>
                                 </tr>
                                 <tr>
                                    <td colspan="3" class="text-right"><strong>Monto del IVA:</strong></td>
                                    <td>{{ calcularIVA().toLocaleString('es-CO', { style: 'currency', currency: 'COP' })
                                       }}</td>
                                 </tr>
                                 <tr>
                                    <td colspan="3" class="text-right"><strong>Total General:</strong></td>
                                    <td>{{ calcularTotalGeneral().toLocaleString('es-CO', { style: 'currency', currency:
                                       'COP' }) }}</td>
                                 </tr>
                              </tfoot>
                           </table>
                           <!-- Detalles del Pedido -->
                        </div>
                     </div>
                     <div class="card-footer">
                        <div>
                           <button @click="cancelarRegistroPedido" type="button" class="btn btn-secondary">Cancelar
                           </button>
                           <button type="submit" class="btn btn-primary float-right">Guardar Pedido</button>
                        </div>
                     </div>
                  </div>
               </form>
            </div>
         </div>
         <div v-if="form_view" class="col-lg-6 d-flex justify-content-center align-items-center"
            style="margin: 0 auto;">
            <!-- Formulario para registrar nuevos Pedidos -->
            <div class="card">
               <div class="card-header">
                  <div class="card-tittle">
                     <h4>Registrar Pedido</h4>
                     <div v-if="error" class="alert alert-success alert-dismissible fade show" role="alert">
                        <strong>Alerta</strong> {{error}}.
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                           <span aria-hidden="true">×</span>
                        </button>
                     </div>
                  </div>
               </div>
               <form @submit.prevent="registrarPedido" class="form-v1">
                  <div class="card-body">
                     <div class="row">
                        <div class="col-md-6 col-lg-4">
                           <div class="form-group">
                              <label>Cliente:</label>
                              <input v-model="orders.nombres" type="text" class="form-control" readonly>
                           </div>
                        </div>
                        <div class="col-md-6 col-lg-4">
                           <div class="form-group">
                              <label>TXID:</label>
                              <input v-model="orders.txid" type="text" class="form-control" readonly>
                           </div>
                        </div>
                        <div class="col-md-6 col-lg-4">
                           <div class="form-group">
                              <label>Fecha de Pedido:</label>
                              <input v-model="orders.fechaPedido" type="date" class="form-control" readonly>
                           </div>
                        </div>
                        <div class="col-md-6 col-lg-4">
                           <div class="form-group">
                              <label>Estado:</label>
                              <select v-model="orders.estado" class="form-control" readonly>
                                 <option value="pendiente">PENDIENTE</option>
                                 <option value="facturado">FACTURADO</option>
                              </select>
                           </div>
                        </div>
                        <div class="col-md-6 col-lg-12">
                           <div class="form-group">
                              <label>Observacion:</label>
                              <textarea class="form-control" v-model="orders.observacion" cols="10" rows="3"
                                 readonly></textarea>
                           </div>
                        </div>
                     </div>
                     <div class="col-md-6 col-lg-12">
                        <label>Detalles del pedido:</label><br>
                        <div class="table-responsive">
                           <table class="table table-bordered text-nowrap text-center">
                              <thead>
                                 <tr>
                                    <th>CODIGO</th>
                                    <th>NOMBRE</th>
                                    <th>CANT</th>
                                    <th>PRECIO</th>
                                 </tr>
                              </thead>
                              <tbody>
                                 <tr v-for="(detail, index) in details">
                                    <td>{{detail.codigo}}</td>
                                    <td>{{detail.nombre}}</td>
                                    <td class="text-center"><input @input="count(index)" style="width: 60px;"
                                          v-model="detail.cantidad" type="number" readonly></td>
                                    <td>{{calcularSubtotal(detail).toLocaleString('es-CO', { style: 'currency',
                                       currency: 'COP' })}}</td>
                                 </tr>
                              </tbody>
                              <tfoot>
                                 <tfoot>
                                    <tr>
                                       <td colspan="3" class="text-right"><strong>Subtotal total:</strong></td>
                                       <td>{{ calcularSubtotalTotal().toLocaleString('es-CO', { style: 'currency',
                                          currency: 'COP' }) }}</td>
                                    </tr>
                                    <tr>
                                       <td colspan="3" class="text-right"><strong>Monto del IVA:</strong></td>
                                       <td>{{ calcularIVA().toLocaleString('es-CO', { style: 'currency', currency: 'COP'
                                          })
                                          }}</td>
                                    </tr>
                                    <tr>
                                       <td colspan="3" class="text-right"><strong>Total General:</strong></td>
                                       <td>{{ calcularTotalGeneral().toLocaleString('es-CO', { style: 'currency',
                                          currency:
                                          'COP' }) }}</td>
                                    </tr>
                                 </tfoot>
                              </tfoot>
                           </table>
                           <!-- Detalles del Pedido -->
                        </div>
                     </div>
                     <div class="card-footer">
                        <div>
                           <button @click="cancelarRegistroPedido" type="button" class="btn btn-secondary">Cancelar
                        </div>
                     </div>
                  </div>
               </form>
            </div>
         </div>
         <div id="modalDetails" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modalDetailsLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
               <div class="modal-content">
                  <div class="modal-header">
                     <h5 class="modal-title" id="modalDetailsLabel">Modal Title</h5>
                     <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                           aria-hidden="true">&times;</span></button>
                  </div>
                  <div class="modal-body">
                     <div class="table-responsive">
                        <table id="listProducts" class="table table-bordered text-nowrap text-center">
                           <thead>
                              <tr>
                                 <th>OP</th>
                                 <th>NOMBRE</th>
                                 <th>CANT</th>
                                 <th>PRECIO</th>
                                 <th>STATUS</th>
                              </tr>
                           </thead>
                        </table>
                     </div>
                     <div class="modal-footer">
                        <button type="button" class="btn  btn-secondary" data-dismiss="modal">Close</button>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>

<script src="assets/js/plugins/jquery.dataTables.min.js"></script>
<script src="assets/js/plugins/dataTables.bootstrap4.min.js"></script>
<script src="assets/js/plugins/dataTables.responsive.min.js"></script>
<script src="assets/js/plugins/responsive.bootstrap4.min.js"></script>