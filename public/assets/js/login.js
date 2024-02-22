new Vue({
    el: '#app',
    data: {
       email: '',
       password: '',
       error: '',
       message: ''
    },
    created() {

    },
    methods: {

       login() {
          $('[type="submit"]').attr("disabled", "disabled");
          const apiUrl = './api/login';
          const data = {
             email: this.email,
             password: this.password
          };
          axios.post(apiUrl, data)
             .then(response => {
                this.error = '';
                this.message = response.data.message;
                location.reload();
             })
             .catch(error => {
                this.message = '';
                $('[type="submit"]').removeAttr("disabled");
                this.error = error.response.data.message;
             });
       },
    }
 });