<div id="Visits" class="pc-container">
    <div class="pcoded-content">
        <div class="page-header">
            <div class="page-block">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <div class="page-header-title">
                            <h5 class="m-b-10">Gestion de Visitas</h5>
                        </div>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="/panel">Panel</a>
                            </li>
                            <li class="breadcrumb-item">Visitas</li>
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
                        <button type="button" class="btn btn-primary" @click="setRegisterVisitButton">Registrar
                            Visita</button>
                    </div>
                    <div class="card-body table-responsive">
                        <table id="example" class="table table-head-fixed text-nowrap">
                            <thead>
                                <tr>
                                    <th>Op</th>
                                    <th>Nombre</th>
                                    <th>Observacion</th>
                                    <th>Direccion</th>
                                    <th>Fecha</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
            <div v-if="form" class="col-lg-6 justify-content-center" style="margin: 0 auto;">
                <div class="card">
                    <div class="card-header">
                        <div class="card-tittle">
                            <h4>Registrar Visitas</h4>
                            <div v-if="error" class="alert alert-success alert-dismissible fade show" role="alert">
                                <strong>Alerta</strong> {{error}}. <button type="button" class="close"
                                    data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">×</span>
                                </button>
                            </div>
                        </div>
                    </div>
                    <form @submit.prevent="registerVisit" class="form-v1">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 col-lg-6">
                                    <div class="form-group">
                                        <label>Nombre del Cliente:</label>
                                        <input v-model="visit.nombre" type="text" class="form-control" required>
                                    </div>
                                </div>
                                <div class="col-md-6 col-lg-6">
                                    <div class="form-group">
                                        <label>Fecha:</label>
                                        <input v-model="visit.fecha" type="date" class="form-control" required>
                                    </div>
                                </div>
                                <div class="col-md-6 col-lg-12">
                                    <div class="form-group">
                                        <label>Dirección:</label>
                                        <input v-model="visit.direccion" type="text" class="form-control" required>
                                    </div>
                                </div>
                                <div class="col-md-6 col-lg-12">
                                    <div class="form-group">
                                        <label>Observacion:</label>
                                        <textarea v-model="visit.observacion" class="form-control" cols="30"
                                            rows="10"></textarea>
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
            <div v-if="view" class="col-lg-6 justify-content-center" style="margin: 0 auto;">
                <div class="card">
                    <div class="card-header">
                        <div class="card-tittle">
                            <h4>Ver Visita</h4>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 col-lg-6">
                                <div class="form-group">
                                    <label>Nombre del Cliente:</label>
                                    <input v-model="visit.nombre" type="text" class="form-control" readonly>
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-6">
                                <div class="form-group">
                                    <label>Fecha:</label>
                                    <input v-model="visit.fecha" type="date" class="form-control" readonly>
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-12">
                                <div class="form-group">
                                    <label>Dirección:</label>
                                    <input v-model="visit.direccion" type="text" class="form-control" readonly>
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-12">
                                <div class="form-group">
                                    <label>Observacion:</label>
                                    <textarea v-model="visit.observacion" class="form-control" cols="30" rows="10"
                                        readonly></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div>
                            <button @click="cancelButton" type="button" class="btn btn-secondary">Volver</button>
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