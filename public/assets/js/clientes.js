new Vue({
   el: "#Clients",
   data: {
      client: {},
      token: "",
      error: "",
      message: "",
      form: false,
      list: true,
   },
   created() {
      this.getToken();
      setTimeout(() => {
         this.getClientList();
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
               console.log(this.token);
            })
            .catch((error) => {
               console.error("Error fetching token:", error);
            });
      },
      getClientList() {
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
                  url: `./api/clientes`,
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
                  { data: "nombres" },
                  { data: "apellidos" },
                  { data: "no_identificacion" },
                  { data: "telefono" },
                  { data: "email" },
                  { data: "direccion" },
                  { data: "status" },
               ],
               columnDefs: [
                  {
                     targets: 0, // La primera columna
                     render: function (data, type, full, meta) {
                        const buttons = `<button type="button" value="${full.id_cliente}" class="edit btn btn-sm btn-icon btn-outline-primary"><i class="fas fa-edit"></i></button>`;
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


      registerClient() {
         const apiUrl = "./api/clientes";
         const headers = {
            Authorization: "Bearer " + this.token,
         };
         axios
            .post(apiUrl, this.client, { headers })
            .then((response) => {
               this.error = "";
               this.message = response.data.message;
               this.getClientList();
               this.cancelButton();
            })
            .catch((error) => {
               this.message = "";
               this.error = error.response.data.message;
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
      setButtons() {
         const vm = this;
         $(function () {
            $(document).on("click", ".edit", function (e) {
               const id = $(this).val();
               const apiUrl = `./api/clientes/${id}`;
               const headers = {
                  Authorization: "Bearer " + vm.token,
               };
               vm.checkPermissions(["edit_content"], (result) => {
                  if (result) {
                     axios
                        .get(apiUrl, { headers })
                        .then((response) => {
                           vm.error = "";
                           vm.client = response.data[0];
                           vm.setRegisterClientButton();
                           console.log(vm.client);
                        })
                        .catch((error) => {
                           vm.message = "";
                           vm.error = error.response.data.message;
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
         });
      },
      setRegisterClientButton() {
         this.checkPermissions(["create_content"], (result) => {
            if (result) {
               this.form = true;
               this.list = false;
            } else {
               this.sweetalert2(
                  "error",
                  "no tienes permisos para realizar esta accion",
                  "error"
               );
            }
         });
      },
      cancelButton() {
         this.form = false;
         this.list = true;
         this.getClientList();
         this.client = {};
      },
      sweetalert2(tittle, message, type) {
         Swal.fire("¡" + tittle + "!", "" + message + "", "" + type + "");
      },
   },
});
