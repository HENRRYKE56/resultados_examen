

    <footer class="main-footer">
        <div class="pull-right hidden-xs">
          <b>SEIEM</b> EDOMEX 
        </div>
        <strong>Copyright &copy; 2025 <a href="<?php echo base_url(); ?>">HLANDEROS</a>.</strong> DERECHOS RESERVADOS.
    </footer>
   
<script>
// Bloquear clic derecho
document.addEventListener('contextmenu', event => event.preventDefault());

// Bloquear teclas de copia, inspección, etc.
document.onkeydown = function(e) {
    if (e.ctrlKey && (e.key === 'c' || e.key === 'x' || e.key === 'v')) return false;
    if (e.key === "PrintScreen") return false;
    if (e.ctrlKey && e.shiftKey && e.key === 'I') return false;
}
</script> 
    <script src="<?php echo base_url(); ?>assets/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>

    <!-- Bootstrap 4 -->
<script src="<?php echo base_url(); ?>assets/dist/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>


    <script src="<?php echo base_url(); ?>assets/dist/js/adminlte.min.js" type="text/javascript"></script>
     <script src="<?php echo base_url(); ?>assets/js/jquery.validate.js" type="text/javascript"></script>
    <script src="<?php echo base_url(); ?>assets/js/validation.js" type="text/javascript"></script>
    <script src="<?php echo base_url(); ?>assets/dist/plugins/select2/js/select2.full.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/bower_components/summernote/summernote-bs4.min.js"></script>
   
  </body>
</html>