<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <i class="fa fa-user-circle-o" aria-hidden="true"></i> Generar Incidencia
    
    
      </h1>
    </section>
    
    <section class="content">
    
        <div class="row">
            <!-- left column -->
            <div class="col-md-12">
              <!-- general form elements -->
                
                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">Ingresar la información de la Incidencia</h3>
                    </div><!-- /.box-header -->
                    <!-- form start -->
                    <?php $this->load->helper("form"); ?>
                    <form role="form" id="addTask" action="<?php echo base_url('guardarIncidencia') ?>" method="post" role="form">
                        <div class="box-body">
                            <div class="row">
                            
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="description">Fecha de la Incidencia</label>
                                        <?php echo  form_input($fecha_comision) ?>
                                      </div>
                                </div>
                                
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="tipo_omision">Tipo de Incidencia</label>
                                        <?php echo  form_dropdown($tipo_omision) ?>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="omision">Justificar</label>
                                        <?php echo  form_dropdown($omision) ?>
                                    </div>
                                </div>
                           
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="description">Motivo de la Omisión</label>
                                        <?php echo  form_input($descripcion_omision) ?>
                                      </div>
                                </div>
                            </div>
                        </div><!-- /.box-body -->
    
                        <div class="box-footer">
                            <input type="submit" class="btn btn-primary" value="Guardar" />
                            <input type="reset" class="btn btn-default" value="Borrar Campos" />
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-md-4">
                <?php
                    $this->load->helper('form');
                    $error = $this->session->flashdata('error');
                    if($error)
                    {
                ?>
                <div class="alert alert-danger alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <?php echo $this->session->flashdata('error'); ?>                    
                </div>
                <?php } ?>
                <?php  
                    $success = $this->session->flashdata('success');
                    if($success)
                    {
                ?>
                <div class="alert alert-success alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <?php echo $this->session->flashdata('success'); ?>
                </div>
                <?php } ?>
                
                <div class="row">
                    <div class="col-md-12">
                        <?php echo validation_errors('<div class="alert alert-danger alert-dismissable">', ' <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button></div>'); ?>
                    </div>
                </div>
            </div>
        </div>    
    </section>
    
</div>
<script>
$(document).ready(function() {
    $('#tipo_omision').change(function() {
        var tipoOmision = $(this).val();
        
        $.ajax({
            url: 'getOmisiones', // Ruta al controlador en CodeIgniter
            type: 'POST',
            data: { tipo_omision: tipoOmision },
            dataType: 'json',
            success: function(response) {
                $('#omision').empty();
                $.each(response, function(key, value) {
                    $('#omision').append('<option value="'+ key +'">'+ value +'</option>');
                });
            }
        });
    });
});
    </script>

