
<script src="assets/js/plugins/vendor-all.min.js"></script>
<script src="assets/js/plugins/bootstrap.min.js"></script>
<script src="assets/js/plugins/feather.min.js"></script>
<script src="assets/js/plugins/pcoded.min.js"></script>
<script src="assets/js/plugins/highlight.min.js"></script>
<script src="assets/js/plugins/clipboard.min.js"></script>
<script src="assets/js/plugins/uikit.min.js"></script>
<script>
   $(function() {
      $(document).on("click", "#logout", function(e) {
         const apiUrl = './api/logout';
         axios.get(apiUrl)
            .then(response => {
               location.reload();
               console.log(response.data);
               // Redirigir a la página de inicio o realizar otras acciones necesarias
            })
            .catch(error => {
               console.log(error.response);
            });
         e.preventDefault();
      });
   })
</script>
</body>

</html>