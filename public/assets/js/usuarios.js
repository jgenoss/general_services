new Vue({
   el: '#users',
   data: {
      user: {},
      token: '',
      error: '',
      message: '',
      form: false,
      list: true,
   },
   created() {
      this.initWebSocket();
      this.getToken();
      setTimeout(() => {
         this.getUserList();
      }, 1000);
      this.setButtons();
   },
   methods: {
      initWebSocket() {

      },
      getToken() {
         const apiUrl = './api/getToken';
         axios.get(apiUrl)
            .then(response => {
               this.token = response.data.token;
               //console.log(this.token);
            })
            .catch(error => {
               console.error('Error fetching token:', error);
            });
      },
      getUserList() {
         const vm = this;
         $(function () {
            new DataTable('#example', {
               "responsive": false,
               "autoWidth": false,
               "lengthMenu": [
                  [50, 100, -1],
                  [50, 100, "All"]
               ],
               "aProcessing": true,
               "aServerSide": true,
               "ajax": {
                  "url": `./api/usuarios`,
                  "type": "GET",
                  "headers": {
                     'Authorization': 'Bearer ' + vm.token
                  },
                  "error": function (e) {
                     console.log(e);
                  }
               },
               "bDestroy": true,
               "iDisplayLength": 40,
               "order": [
                  [0, "asc"]
               ]
            });
         });
      },
      registerUser() {
         const apiUrl = './api/usuarios';
         const headers = {
            'Authorization': 'Bearer ' + this.token
         };
         axios.post(apiUrl, this.user, { headers })
            .then(response => {
               this.error = '';
               this.message = response.data.message;
               this.getUserList();
               this.cancelButton();
            })
            .catch(error => {
               this.message = '';
               this.error = error.response.data.message;
            });
      },
      setButtons() {
         const vm = this;
         $(function () {
            $(document).on('click', '.edit', function (e) {
               const id = $(this).val();
               const apiUrl = `./api/usuarios/${id}`;
               const headers = {
                  'Authorization': 'Bearer ' + vm.token
               };
               axios.get(apiUrl, { headers })
                  .then(response => {
                     vm.error = '';
                     vm.user = response.data[0];
                     vm.setRegisterUserButton();
                     console.log(response);
                  })
                  .catch(error => {
                     vm.message = '';
                     vm.error = error.response.data.message;
                  });
            });
            $(document).on('click', '.trash', function (e) {
               const id = $(this).val();
               const apiUrl = `./api/usuarios/deleteId/${id}`;
               const headers = {
                  'Authorization': 'Bearer ' + vm.token
               };
               axios.get(apiUrl, { headers })
                  .then(response => {
                     vm.error = '';
                     vm.user = response.data[0];
                     vm.setRegisterUserButton();
                     //console.log(vm.user);
                  })
                  .catch(error => {
                     vm.message = '';
                     vm.error = error.response.data.message;
                  });
            });
         });
      },
      setRegisterUserButton() {
         this.form = true;
         this.list = false;
      },
      cancelButton() {
         this.form = false;
         this.list = true;
         this.getUserList();
         this.user = {};
      },
      sweetalert2(tittle, message, type) {
         Swal.fire("¡" + tittle + "!", "" + message + "", "" + type + "");
      },
   }
});
