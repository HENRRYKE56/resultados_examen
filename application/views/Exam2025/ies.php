

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <i class="fa fa-user-circle-o" aria-hidden="true"></i>  
        
       
      </h1>
    </section>
    
    <section class="content">
    
        <div class="row">
            <!-- left column -->
            <div class="col-md-12">
              <!-- general form elements -->
                
                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">Reporte del Examen de Conocimientos de 7° Semestre  2025 por IES</h3>
                    </div><!-- /.box-header -->
                    <!-- form start -->
                    <?php $this->load->helper("form"); ?>
                    <form role="form" autocomplete="off" id="reporte" action="<?php echo base_url("ries") ?>" target="_blank" method="post" enctype="multipart/form-data" role="form">
                       
                          
                        <div class="box-footer">
                            <input type="submit" class="btn btn-primary"  value="Generar Reporte" />
                            
                        </div>
                    </form>
                </div>
            </div>
          
        </div>    
    </section>
    
</div>
<script>$(function () {
    $('.select2').select2({
        multiple: true 
    })
    
});
</script>


<script>
$(document).ready(function() {

    // Cuando cambia IES
    $('#ies').change(function() {

        let cve_ies = $(this).val();

        // Limpiar combos
        $('#sede').html('<option value="">Seleccione</option>');
        $('#programa').html('<option value="">Seleccione</option>');

        if (cve_ies === '') return;

        $.ajax({
            url: "<?= base_url('exam2025/get-sedes'); ?>",
            type: "POST",
            data: { cve_ies: cve_ies },
            dataType: "json",
            success: function(data) {
                $.each(data, function(i, item) {
                    $('#sede').append('<option value="'+item.cve_sede+'">'+item.sede+'</option>');
                });
            }
        });
    });

    // Cuando cambia SEDE
    $('#sede').change(function() {

        let cve_sede = $(this).val();
        let cve_ies = $('#ies').val();

        $('#programa').html('<option value="">Seleccione</option>');

        if (cve_sede === '') return;

        $.ajax({
            url: "<?= base_url('exam2025/get-programas'); ?>",
            type: "POST",
            data: { cve_sede: cve_sede, cve_ies: cve_ies },
            dataType: "json",
            success: function(data) {
                $.each(data, function(i, item) {
                    $('#programa').append('<option value="'+item.cve_programa+'">'+item.programa+'</option>');
                });
            }
        });

    });

});
</script>
