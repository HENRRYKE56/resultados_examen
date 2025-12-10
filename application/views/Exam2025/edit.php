<?php

$id = $CorrespondenciaInfo->id;
$asunto = $CorrespondenciaInfo->asunto;
$no_oficio = $CorrespondenciaInfo->no_oficio;
$remitido_por = $CorrespondenciaInfo->remitido_por;
$fecha_registro = $CorrespondenciaInfo->fecha_registro;
$cve_dependencia = $CorrespondenciaInfo->cve_dependencia;
$observaciones = $CorrespondenciaInfo->observaciones;
$estado = $CorrespondenciaInfo->cve_estado;
$no_oficio_respuesta = $CorrespondenciaInfo->no_oficio_respuesta;
$fecha_respuesta = $CorrespondenciaInfo->fecha_respuesta;
$cve_area = $CorrespondenciaInfo->cve_area;
$registrado_por = $CorrespondenciaInfo->registrado_por;



$cve_area_seleccionada = explode(',', $cve_area); // Convierte la cadena en un array



?>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <i class="fa fa-user-circle-o" aria-hidden="true"></i> Administración de Correspondencia
   
      </h1>
    </section>
    
    <section class="content">
    
        <div class="row">
            <!-- left column -->
            <div class="col-md-12">
              <!-- general form elements -->
                
                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">Ingresa los detalles de la Correspondencia</h3>
                    </div><!-- /.box-header -->
                    <!-- form start -->
                    
                    <form role="form" action="<?php echo base_url("editar") ?>" method="post" id="editarCorrespondecia" enctype="multipart/form-data" role="form">
                    <div class="box-body">
                            <input type="hidden" value="<?php echo $id; ?>" name="cve_correspondencia" id="cve_correspondencia" />
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="no_oficio">Número de Oficio</label>
                                    <input type="text" class="form-control" id="no_oficio" placeholder="Ingresa Número de Oficio" value="<?php echo $no_oficio; ?>" name="no_oficio" maxlength="200">
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label for="asunto">Asunto</label>
                                    <input type="text" class="form-control" id="asunto" placeholder="Ingresa el asunto" name="asunto" value="<?php echo $asunto; ?>" maxlength="528">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="cve_dependencia">Dependencia de Procedencia</label>
                                     <?php 
                                       
                                       $options = array();
                                       
                                       foreach ($dependencias as $dependencia) {   
                                           $options[$dependencia['cve_dependencia']] = $dependencia['nombre_corto'];
                                       }
                                       
                                       echo form_dropdown(array('options'=>$options,'id' => 'cve_dependencia', 'name' => 'cve_dependencia'),  "",$cve_dependencia,'class="form-control select2"' 
                                   ); 
                                       ?>


                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="remitido_por">Remitido por:</label>
                                    <input type="text" class="form-control" id="remitido_por" placeholder="Remetido por" name="remitido_por" value="<?php echo $remitido_por; ?>" maxlength="100">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="fecha_registro">Fecha de registro</label>
                                    <input type="date" class="form-control" id="fecha_registro" placeholder="Fecha de recepción" name="fecha_registro" value="<?php echo $fecha_registro; ?>" maxlength="1000">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="cve_area">Area a la que se turna</label>
                                    <?php
                                    echo form_dropdown(array('options'=>$options,'id' => 'cve_area', 'multiple'=>"multiple", 'name' => 'cve_area'),  "",$cve_area,'class="form-control select2"');
                                    ?>
                                    
                                    
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="no_oficio_respuesta">Número de Oficio de respuesta</label>
                                    <input type="text" class="form-control" id="no_oficio_respuesta" placeholder="Número de Oficio de Respuesta" name="no_oficio_respuesta" value="<?php echo $no_oficio_respuesta; ?>" maxlength="1000">
                                </div>
                            </div>
                          
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="estado_oficio">Estado del Oficio</label>
                                    <?php 
                                       
                                       $options = array();
                                       
                                       foreach ($estados as $edo) {   
                                           $options[$edo['cve_estado']] = $edo['des_estado'];
                                       }
                                       
                                       echo form_dropdown(array('options'=>$options,'id' => 'cve_estado', 'name' => 'cve_estado'),  "",$estado,'class="form-control select2"' 
                                   ); 
                                       ?>

                                   
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="observaciones">Observaciones</label>
                                    <textarea rows="3" class="form-control" id="observaciones" placeholder="Observaciones" name="observaciones" maxlength="560"><?php echo $observaciones; ?></textarea>
                                </div>
                            </div>
                        </div>
    
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
        $('#cve_area').select2({
            placeholder: "Seleccione una o más áreas",
            allowClear: true
        });
    });
</script>