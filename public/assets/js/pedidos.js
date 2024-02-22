new Vue({
  el: "#Pedidos",
  data: {
    orders: {
      fechaPedido: "",
      estado: "Pendiente",
    },
    details: [],
    clients: [],
    establishments: [],
    token: "",
    error: "",
    form: false,
    form_edit: false,
    form_view: false,
    list: true,
    porcentajeIVA: 19,
  },
  created() {
    this.getToken();
    setTimeout(() => {
      this.getListaPedidos();
    }, 1000);
    this.setButtons();
  },
  methods: {
    getToken() {
      const apiUrl = "./api/getToken";
      axios
        .get(apiUrl)
        .then((response) => {
          this.token = response.data.token;
        })
        .catch((error) => {
          console.error("Error fetching token:", error);
        });
    },
    getListaPedidos() {
      const vm = this;
      $(function () {
        new DataTable("#example", {
          responsive: false,
          autoWidth: false,
          lengthMenu: [
            [50, 100, -1],
            [50, 100, "All"],
          ],
          aProcessing: true,
          aServerSide: true,
          ajax: {
            url: `./api/pedidos`,
            type: "GET",
            headers: {
              Authorization: "Bearer " + vm.token,
            },
            error: function (e) {
              console.log(e);
            },
          },
          bDestroy: true,
          iDisplayLength: 40,
          order: [[0, "asc"]],
          columns: [
            null,
            { data: "txid" },
            { data: "nombres" },
            { data: "nombre" },
            { data: "precio_sin_iva" },
            { data: "precio_total" },
            { data: "fecha_pedido" },
            { data: "status" },
          ],
          columnDefs: [
            {
              targets: 0, // La primera columna
              render: function (data, type, full, meta) {
                const buttons = `<div class="dropdown">
                <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-cog"></i></button>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <button id="view" value="${full.id}" class="view dropdown-item"><i class="fas fa-eye"></i> Ver</button>
                    <button id="edit" value="${full.id}" class="edit dropdown-item"><i class="fas fa-edit"></i> Editar</button>
                    <button id="trash" value="${full.id}" class="trash dropdown-item"><i class="fas fa-trash"></i> Eliminar</a>
                </div>
              </div>`;
                return buttons;
              },
            },
            {
              targets: 7, // La primera columna
              render: function (data, type, full, meta) {
                const buttons = (full.status == true) ? '<span class="badge badge-light-success">ACTIVE</span>' : '<span class="badge badge-light-danger">INACTIVE</span>';
                return buttons;
              },
            }
          ],
        });
      });
    },
    getListaProducts() {
      const vm = this;
      $(function () {
        new DataTable("#listProducts", {
          responsive: false,
          autoWidth: false,
          aProcessing: true,
          aServerSide: true,
          ajax: {
            url: `./api/pedidos/getProducts`,
            type: "GET",
            headers: {
              Authorization: "Bearer " + vm.token,
            },
            error: function (e) {
              console.log(e);
            },
          },
          bDestroy: true,
          iDisplayLength: 10,
          order: [[0, "asc"]],
        });
      });
    },
    checkPermissions(required_permissions, callback) {
      const apiUrl = "./api/usuarios/checkUserPermissions";
      const headers = {
        Authorization: "Bearer " + this.token,
      };
      const postData = {
        required_permissions: required_permissions,
      };
      axios
        .post(apiUrl, postData, { headers })
        .then((response) => {
          callback(response.data.permissions === "true");
        })
        .catch((error) => {
          console.error("Error fetching token:", error);
          callback(false);
        });
    },
    setRegistroPedido() {
      this.checkPermissions(["create_content"], (result) => {
        if (result) {
          this.getClient();
          this.form = true;
          this.list = false;
          this.orders = {
            codigoPedido: "",
            fechaPedido: "",
            estado: "Pendiente",
          };
          this.details = [];
        } else {
          this.sweetalert2(
            "error",
            "no tienes permisos para realizar esta accion",
            "error"
          );
        }
      });
    },
    cancelarRegistroPedido() {
      this.form_edit = false;
      this.form_view = false;
      this.form = false;
      this.list = true;
      this.getListaPedidos();
      this.orders = {
        codigoPedido: "",
        fechaPedido: "",
        estado: "Pendiente",
      };
      this.details = [];
    },
    getClient() {
      const headers = {
        Authorization: "Bearer " + this.token,
      };
      axios
        .get("./api/establecimiento/getClients", { headers })
        .then((response) => {
          this.clients = response.data;
          this.error = "";
          this.message = response.data.message;
        })
        .catch((error) => {
          this.message = "";
          this.error = error.response.data.message;
        });
    },
    getEstablishment(id) {
      const headers = {
        Authorization: "Bearer " + this.token,
      };
      axios
        .get(`./api/pedidos/getEstablishment/${id}`, { headers })
        .then((response) => {
          this.establishments = response.data;
          this.error = "";
          this.message = response.data.message;
        })
        .catch((error) => {
          this.message = "";
          this.error = error.response.data.message;
        });
    },
    count(index) {
      this.details[index].precio =
        this.details[index].precioOriginal * this.details[index].cantidad;
      const headers = {
        Authorization: "Bearer " + this.token,
      };
      const postData = {
        id_product: this.details[index].id,
        cantidad: this.details[index].cantidad,
      };
      axios
        .post(`./api/pedidos/checkStock`, postData, { headers })
        .then((response) => {
          //this.establishments = response.data;
          this.error = "";
          //this.message = response.data.message;
        })
        .catch((error) => {
          this.message = "";
          this.sweetalert2("Error", error.response.data.message, "error");
          this.details[index].cantidad = error.response.data.max;
          //console.log(response.data);
        });
    },
    calcularSubtotal(detail) {
      return detail.precio * detail.cantidad;
    },
    calcularSubtotalTotal() {
      let subtotalTotal = 0;
      for (const detail of this.details) {
        subtotalTotal += this.calcularSubtotal(detail);
      }
      this.orders.precioSinIva = subtotalTotal;
      return subtotalTotal;
    },
    calcularIVA() {
      return (this.calcularSubtotalTotal() * this.porcentajeIVA) / 100;
    },
    calcularTotalGeneral() {
      this.orders.precioTotal = this.calcularSubtotalTotal() + this.calcularIVA();
      return this.calcularSubtotalTotal() + this.calcularIVA();
    },
    getProduct(id) {
      const apiUrl = `./api/productos/${id}`;
      const headers = {
        Authorization: "Bearer " + this.token,
      };
      axios
        .get(apiUrl, { headers })
        .then((response) => {
          this.error = "";
          this.details.push(response.data[0]);
          const Toast = Swal.mixin({
            toast: true,
            position: "top-end",
            showConfirmButton: false,
            timer: 2000,
            timerProgressBar: true,
            didOpen: (toast) => {
              toast.addEventListener("mouseenter", Swal.stopTimer);
              toast.addEventListener("mouseleave", Swal.resumeTimer);
            },
          });
          Toast.fire({
            icon: "success",
            title: "Save to work",
          });
        })
        .catch((error) => {
          vm.message = "";
          vm.error = error.response.data.message;
        });
    },
    deleteDetail(index) {
      this.details.splice(index, 1);
    },
    setButtons() {
      const vm = this;
      $(function () {
        $(document).on("click", ".plus", function (e) {
          const id = $(this).val();
          vm.getProduct(id);
        });
        $(document).on("click", ".edit", function (e) {
          vm.getClient();
          const id = $(this).val();
          const apiUrl = `./api/pedidos/${id}`;
          const headers = {
            Authorization: "Bearer " + vm.token,
          };
          vm.checkPermissions(["edit_content"], (result) => {
            if (result) {
              axios
                .get(apiUrl, { headers })
                .then((response) => {
                  vm.list = false;
                  vm.form_edit = true;
                  vm.orders = response.data.orders;
                  vm.getEstablishment(response.data.orders.id_establecimiento);
                  /*response.data.details.forEach(element => {
                    vm.getProduct(element.id);
                  });*/

                  vm.details = response.data.details;
                })
                .catch((error) => {
                  //vm.message = "";
                  vm.error = error.response.data.message;
                  console.log(error.response.data);
                });
            } else {
              vm.sweetalert2(
                "error",
                "no tienes permisos para realizar esta accion",
                "error"
              );
            }
          });
        });
        $(document).on("click", ".view", function (e) {
          const id = $(this).val();
          const apiUrl = `./api/pedidos/${id}`;
          const headers = {
            Authorization: "Bearer " + vm.token,
          };
          vm.checkPermissions(["view_content"], (result) => {
            if (result) {
              axios
                .get(apiUrl, { headers })
                .then((response) => {
                  vm.list = false;
                  vm.form_view = true;
                  vm.orders = response.data.orders;
                  vm.details = response.data.details;
                })
                .catch((error) => {
                  //vm.message = "";
                  vm.error = error.response.data.message;
                  console.log(error.response.data);
                });
            } else {
              vm.sweetalert2(
                "error",
                "no tienes permisos para realizar esta accion",
                "error"
              );
            }
          });
        });
        $(document).on("click", ".trash", function (e) {
          const id = $(this).val();
          const shouldDelete = window.confirm("¿Estás seguro de que deseas eliminar este elemento?");
          if (shouldDelete) {
            const apiUrl = `./api/pedidos/delete/${id}`;
            const headers = {
              Authorization: "Bearer " + vm.token,
            };

            vm.checkPermissions(["delete_content"], (result) => {
              if (result) {
                axios
                  .get(apiUrl, { headers })
                  .then((response) => {
                    vm.list = true;
                    vm.form_edit = false;
                    vm.getListaPedidos();
                  })
                  .catch((error) => {
                    vm.error = error.response.data.message;
                    console.log(error.response.data);
                  });
              } else {
                vm.sweetalert2(
                  "error",
                  "No tienes permisos para realizar esta acción",
                  "error"
                );
              }
            });
          }
        });

      });
    },
    registrarPedido() {
      if (!this.details.length) {
        this.sweetalert2("info", "Agregue un producto", "info");
      } else {
        const apiUrl = "./api/pedidos";
        const headers = {
          Authorization: "Bearer " + this.token,
        };
        const data = {
          ...this.orders,
          details: this.details,
        };
        axios
          .post(apiUrl, data, { headers })
          .then((response) => {
            this.error = "";
            this.form = false;
            this.form_edit = false;
            this.list = true;
            this.getListaPedidos();
            this.orders = {
              fechaPedido: "",
              estado: "Pendiente",
            };
            this.details = [];
            this.sweetalert2("success", response.data.message, "success");
          })
          .catch((error) => {
            this.sweetalert2("error", error.response.data.message, "error");
            this.error = error.response.data.message;
          });
      }
    },
    sweetalert2(tittle, message, type) {
      Swal.fire("¡" + tittle + "!", "" + message + "", "" + type + "");
    },
  },
});
