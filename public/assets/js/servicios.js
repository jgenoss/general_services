new Vue({
   el: "#Services",
   data: {
      service: {
         service_name:'',
         description:'',
         price:'',
         iva:''
      },
      token: "",
      error: "",
      message: "",
      form: false,
      list: true,
   },
   created() {
      this.getToken();
      setTimeout(() => {
         this.getServiceList();
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
      getServiceList() {
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
                  url: `./api/servicios`,
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
            });
         });
      },
      registerClient() {
         const apiUrl = "./api/clientes";
         const headers = {
            Authorization: "Bearer " + this.token,
         };
         axios
            .post(apiUrl, this.service, { headers })
            .then((response) => {
               this.error = "";
               this.message = response.data.message;
               this.getServiceList();
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
                           vm.service = response.data[0];
                           vm.setRegisterServiceButton();
                           console.log(vm.service);
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
      setRegisterServiceButton() {
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
         this.getServiceList();
         this.service = {};
      },
      sweetalert2(tittle, message, type) {
         Swal.fire("¡" + tittle + "!", "" + message + "", "" + type + "");
      },
   },
});
