new Vue({
  el: "#Visits",
  data: {
    visit: {},
    token: "",
    error: "",
    message: "",
    form: false,
    list: true,
    view: false,
  },
  created() {
    this.getToken();
    setTimeout(() => {
      this.getVisitList();
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
    getVisitList() {
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
            url: `./api/visitas`,
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
    registerVisit() {
      const apiUrl = "./api/visitas";
      const headers = {
        Authorization: "Bearer " + this.token,
      };
      axios
        .post(apiUrl, this.visit, { headers })
        .then((response) => {
          this.error = "";
          this.message = response.data.message;
          this.getVisitList();
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
        $(document).on("click", ".view", function (e) {
          const id = $(this).val();
          const apiUrl = `./api/visitas/${id}`;
          const headers = {
            Authorization: "Bearer " + vm.token,
          };
          vm.checkPermissions(["view_content"], (result) => {
            if (result) {
              axios
                .get(apiUrl, { headers })
                .then((response) => {
                  vm.error = "";
                  vm.visit = response.data;
                  vm.list = false;
                  vm.view = true;
                  //vm.setRegisterVisitButton();
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
    setRegisterVisitButton() {
      this.checkPermissions(["create_content"], (result) => {
        if (result) {
          this.form = true;
          this.view = false;
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
      this.view = false;
      this.list = true;
      this.getVisitList();
      this.visit = {};
    },
    sweetalert2(tittle, message, type) {
      Swal.fire("¡" + tittle + "!", "" + message + "", "" + type + "");
    },
  },
});
