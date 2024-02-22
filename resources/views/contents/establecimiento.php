<div id="Establishments" class="pc-container">
    <div class="pcoded-content">
        <div class="page-header">
            <div class="page-block">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <div class="page-header-title">
                            <h5 class="m-b-10">Gestion de Establecimientos</h5>
                        </div>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="/panel">Panel</a>
                            </li>
                            <li class="breadcrumb-item">Establecimientos</li>
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
                        <button type="button" class="btn btn-primary" @click="setRegisterEstablishmentButton">Registrar
                            Establecimientos</button>
                    </div>
                    <div class="card-body table-responsive">
                        <table id="example" class="table table-head-fixed text-nowrap">
                            <thead>
                                <tr>
                                    <th>Op</th>
                                    <th>Nombre</th>
                                    <th>Direccion</th>
                                    <th>Telefono</th>
                                    <th>Email</th>
                                    <th>Cliente</th>
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
                            <h4>Registrar Establecimientos</h4>
                            <div v-if="error" class="alert alert-success alert-dismissible fade show" role="alert">
                                <strong>Alerta</strong> {{error}}. <button type="button" class="close"
                                    data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">×</span>
                                </button>
                            </div>
                        </div>
                    </div>
                    <form @submit.prevent="registerEstablishment" class="form-v1">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 col-lg-6">
                                    <div class="form-group">
                                        <label>Cliente:</label>
                                        <select class="form-control" v-model="establishment.id_cliente">
                                            <option v-for="client in clients"
                                                v-bind:value="client.id_cliente">{{client.tipo_identificacion}} - {{client.no_identificacion}} ({{client.nombres}} {{client.apellidos}})</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6 col-lg-6">
                                    <div class="form-group">
                                        <label for="nombre_establecimiento">Nombre del Establecimiento:</label>
                                        <input v-model="establishment.nombre" type="text" class="form-control" id="nombre_establecimiento" required>
                                    </div>
                                </div>
                                <div class="col-md-6 col-lg-6">
                                    <div class="form-group">
                                        <label for="direccion_establecimiento">Dirección del Establecimiento:</label>
                                        <input v-model="establishment.direccion" type="text" class="form-control" id="direccion_establecimiento" required>
                                    </div>
                                </div>
                                <div class="col-md-6 col-lg-6">
                                    <div class="form-group">
                                        <label for="telefono_establecimiento">Teléfono del Establecimiento:</label>
                                        <input v-model="establishment.telefono" type="tel" class="form-control" id="telefono_establecimiento" required>
                                    </div>
                                </div>
                                <div class="col-md-6 col-lg-6">
                                    <div class="form-group">
                                        <label for="correo_electronico_establecimiento">Correo Electrónico del
                                            Establecimiento:</label>
                                        <input v-model="establishment.correo_electronico" type="email" class="form-control" id="correo_electronico_establecimiento"
                                            required>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <div>
                                    <button @click="cancelButton" type="button"
                                        class="btn btn-secondary">Cancelar</button>
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