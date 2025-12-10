<div class="content-wrapper">
    <!-- Content Header (Page header) -->

  
        <div class="row">
            <!-- left column -->
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header">
                        <section class="content-header">
                                <h1>  <i class="fa fa-user-circle-o" aria-hidden="true"></i> Asignar Correspondencia </h1>  
                                <h3 class="box-title">Ingresa los detalles de la Correspondencia</h3>                      
                        </section>
                    </div>
                    <div class="box box-primary"></div>
                    <!-- /.box-header -->
                    <div class="box-body">
                    
                        <form role="form" action="<?php echo base_url("seguimiento") ?>" method="post" id="editarCorrespondecia" enctype="multipart/form-data" role="form">
                            <input type="hidden" value="<?php echo $id; ?>" name="cve_correspondencia" id="cve_correspondencia" />
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="no_oficio">Número de Oficio</label>
                                    <?php echo form_input($no_oficio);?> 
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label for="asunto">Asunto</label>
                                    <?php echo form_input($asunto);?> 
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="cve_dependencia">Dependencia de Procedencia</label>
                                     <?php   echo form_dropdown($cve_dependencia);  ?>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="remitido_por">Remitido por:</label>
                                    <?php   echo form_input($remitido_por);  ?>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="fecha_registro">Fecha de registro</label>
                                    <?php   echo form_input($fecha_registro);  ?>
                                </div>
                            </div>                       
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="no_oficio_respuesta">Número de Oficio de respuesta</label>
                                    <?php   echo form_input($no_oficio_respuesta);  ?>                                   
                                </div>
                            </div>                          
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="estado_oficio">Estado del Oficio</label>
                                    <?php  echo form_dropdown($estado_oficio); ?> 
                                 </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="estado_oficio">Encargado de dar Seguimiento</label>
                                    <?php  echo form_dropdown($asignado_a); ?> 
                                 </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="cve_area">Area a la que se turnó</label>
                                    <?php   echo form_dropdown($cve_area); ?>                               
                                </div>
                            </div>
                            <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="description">Oficio de respuesta en formato PDF</label>
                                        <?php   echo form_input($pdf_respuesta);  ?>
                                  
                                     </div>
                                </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="observaciones">Observaciones</label>
                                         <?php  echo form_textarea($observaciones); ?> 
                                </div>
                            </div>
                        </div>
    
                        <div class="box-footer">
                            <input type="submit" class="btn btn-primary" value="Guardar" />
                            <input type="reset" class="btn btn-default" value="Borrar Campos" />
                        </div>
                    </form>
                    
                    <div class="row">
                        <div class="col-md-12">
                            <div class="box box-primary">
                                <div class="box-header">
                                    <h3><?php echo '<img width="2%" src="' . base_url("assets/images/pdf.png") . '" alt="">' ?>     Documentación </h3>
                                </div>                  
                            </div>
                            <div class="box-body">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <a href="<?php echo base_url('descargar/' . $id); ?>" class="btn btn-sm btn-info">Descargar Oficio</a>
          
                                </div>
                            </div>    
                            <div class="col-md-12">
                                <div class="form-group">
                                <a href="<?php echo base_url('descargarAnexos/' . $id); ?>" class="btn btn-sm btn-info">Descargar Anexos</a>
          
                                 </div>
                            </div>   <div class="col-md-12">
                                <div class="form-group">
                                <a href="<?php echo base_url('descargarRespuesta/' . $id); ?>" class="btn btn-sm btn-info">Descargar Oficio de respuesta</a>
          

                                </div>
                            </div>  
                            
                           </div>
                        </div>
                    </div>

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
        

     
    

    
        
 
</div></div>





<script>
    $(document).ready(function() {
        $('#cve_area').select2({
            placeholder: "Seleccione una o más áreas",
            allowClear: true
        });
    });
</script>