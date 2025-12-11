

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
                        <h3 class="box-title">Constesta bien</h3>
                    </div><!-- /.box-header -->
                    <!-- form start -->
                    <?php $this->load->helper("form"); ?>
                    <form role="form" autocomplete="off" id="nuevaCorrespondencia" action="<?php echo base_url("nuevaCorrespondencia") ?>" method="post" enctype="multipart/form-data" role="form">
                        <div class="box-body">
                        <div class="col-md-3">                                
                                    <div class="form-group">
                                        <label for="no_oficio">No. Oficio</label>
                                        <input  type="text"  class="form-control required"id="no_oficio" name="no_oficio" maxlength="512" />
                                    </div>
                                    
                                </div>
                            <div class="col-md-9">                                
                                    <div class="form-group">
                                        <label for="asunto">Asunto</label>
                                        <input  type="text"  class="form-control required"id="asunto" name="asunto" maxlength="512" />
                                    </div>
                                    
                                </div>
                                <div class="col-md-4">                                 
                                    <div class="form-group">
                                        <label for="fecha_recibido_academica">Dependencia de Procedencia</label>
                                        <?php 
                                          $options = array();
                                          $options[''] = 'Seleccione dependencia';
                                      ?>
                                        
                                    </div>    
                                </div>  
                                <div class="col-md-4" id="otra_dependencia_container" style="display: none;"> <!-- Oculto inicialmente -->
                                    <div class="form-group">
                                        <label for="otra_dependencia">Otra dependencia</label>
                                        <input type="text" class="form-control required" id="otra_dependencia" name="otra_dependencia" maxlength="512" />
                                    </div>
                                </div>
                                <div class="col-md-4">                                
                                    <div class="form-group">
                                        <label for="remitido_por">Remitido Por</label>
                                        <input  type="text"  class="form-control required"id="remitido_por" name="remitido_por" maxlength="512" />
                                    </div>
                                    
                                </div>
                               
                               


                                <div class="col-md-4">                                
                                    <div class="form-group">
                                        <label for="fecha_registro">Fecha de Registro</label>
                                        <input  type="date"  class="form-control required"id="fecha_registro" value="<?php echo date('Y-m-d'); ?>" name="fecha_registro" maxlength="512" />
                                    </div>
                                    
                                </div>
                                    
                            
                                <div class="col-md-4">                                 
                                    <div class="form-group">
                                        <label for="fecha_recibido_academica">Área a la que se Turna</label>
                                        <?php 
                                          $options = array();
                                            $options[''] = 'Seleccione área';
                                            ?>
                                    </div>    
                                </div>
                                                    
                             <!--       
                            <div class="col-md-6">                                
                                    <div class="form-group">
                                        <label for="no_oficio_respuesta">No. Oficio de Respuesta</label>
                                        <input  type="text"  class="form-control required"id="no_oficio_respuesta" name="no_oficio_respuesta" maxlength="512" />
                                    </div>
                                    
                                </div>
                                -->

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="description">Oficio en formato PDF</label>
                                         <input class="form-control required" type="file" name="pdf_file_oficio" id="pdf_file_oficio" size="20" />
                                      </div>
                                     </div>
                                
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="description">Anexos en formato PDF</label>
                                         <input class="form-control required" type="file" name="pdf_file_anexos" size="20" />
                                  
                                     </div>
                                </div>
                            <div class="col-md-12">                                
                               <div class="form-group">
                               <label for="observaciones">Observaciones:</label>
                                <textarea class="form-control" id="observaciones" rows="3" name="observaciones" maxlength="512"></textarea>

                              
                                </div>
                                    
                            </div>
                           </div><!-- /.box-body -->
                          
                        <div class="box-footer">
                            <input type="submit" class="btn btn-primary" value="Guardar" />
                            <input type="reset" class="btn btn-default" value="Borrar" />
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
<script>$(function () {
    $('.select2').select2({
        multiple: true; // Habilita la selección múltiple
    })
    
});
</script>
<script>
    $(document).ready(function() {
        // Inicializa Select2 para el campo con ID 'cve_area'
        $('#cve_area').select2({
  multiple: true // Habilita la selección múltiple
        });
    });
</script>
<script>
    document.getElementById('cve_dependencia').addEventListener('change', function () {
        const selectedValue = this.value;
        const otraDependenciaContainer = document.getElementById('otra_dependencia_container');
        
        if (selectedValue === 'otro') { // Si selecciona "OTRA DEPENDENCIA"
            otraDependenciaContainer.style.display = 'block'; // Mostrar el campo
        } else {
            otraDependenciaContainer.style.display = 'none'; // Ocultar el campo
        }
    });
</script>