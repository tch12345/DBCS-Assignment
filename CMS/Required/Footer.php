  </main>
  <!--   Core JS Files   -->
  <script src="Plugin/assets/js/core/popper.min.js"></script>
  <script src="Plugin/assets/js/core/bootstrap.min.js"></script>
  <script src="Plugin/assets/js/plugins/perfect-scrollbar.min.js"></script>
  <script src="Plugin/assets/js/plugins/smooth-scrollbar.min.js"></script>
  <script type="text/javascript" src="Plugin/sweetalert2.min.js"></script>
  <script>
    var win = navigator.platform.indexOf('Win') > -1;
    if (win && document.querySelector('#sidenav-scrollbar')) {
      var options = {
        damping: '0.5'
      }
      Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
    }
  </script>
  <!-- Control Center for Material Dashboard: parallax effects, scripts for the example pages etc -->
  <script src="Plugin/assets/js/material-dashboard.min.js?v=3.2.0"></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script type="text/javascript" src="JS/script.js"></script>
   <script>
    $(document).ready(function() {
    <?php if (isset($script)) { echo $script; }?>
    });
  </script>
</body>

</html>
