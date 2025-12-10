<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <i class="fa fa-user-circle-o" aria-hidden="true"></i> Nuevo Oficio
        <small>Agregar, Editar, Cancelar</small>
    
      </h1>
    </section>
    
    <section class="content">
    
        <div class="row">
            <!-- left column -->
            <div class="col-md-12">
              <!-- general form elements -->
                
                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">Ingresar la información del Oficio</h3>
                    </div><!-- /.box-header -->
                    <!-- form start -->
                    <?php $this->load->helper("form"); ?>
                    <form role="form" id="addTask" action="<?php echo base_url('nuevoOficio') ?>" method="post" role="form">
                        <div class="box-body">
                    
                                <div class="box-header">
                        <h3 class="box-title"><b>Ingresar Información para Oficios de Comisión</b></h3>
                    </div><!-- /.box-header -->

                            <div class="row">
                             
                                 <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="motivo_comision">Asunto del Oficio o Comisión</label>
                                        <?php echo  form_input($asunto) ?>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="motivo_comision">Dirigido a</label>
                                        <?php echo  form_input($dirigido_a  ) ?>
                                    </div>
                                </div>
                                 <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="description">IES Destino</label>
                                        <?php echo  form_dropdown($destinatario) ?>
                                      </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="description">Fecha de la Comisión</label>
                                        <?php echo  form_input($fecha_comision) ?>
                                      </div>
                                </div>
                               
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="description">Personal Comisionado</label>
                                        <?php echo  form_dropdown($personal_comisionado) ?>
                                      </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="quien_firma">Persona que firma</label>
                                        <?php echo  form_dropdown($quien_firma) ?>
                                    </div>
                                </div>

                                <div class="col-md-8">
                                    <div class="form-group">
                                        <label for="lugar_comision">Lugar de la Comisión</label>
                                        <?php echo  form_input($lugar_comision) ?>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="omision">Justificar</label>
                                        <?php echo  form_dropdown($omision) ?>
                                    </div>
                                </div>
                               
                                  <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="Kilometraje">Kilometraje</label>
                                        <?php echo  form_input($kilometraje) ?>
                                    </div>
                                </div>
                                  <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="modalidad">Modalidad</label>
                                        <?php echo  form_dropdown($modalidad) ?>
                                    </div>
                                </div>
                           
                                 <div class="col-md-12">
                                    <div class="form-group">
                                        <textarea  id="cuerpo_oficio" name="cuerpo_oficio"></textarea>
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
        $('#cuerpo_oficio').summernote({
            height: 200, // Altura inicial del editor
            minHeight: 150, // Altura mínima
            maxHeight: 400, // Altura máxima
            placeholder: "Escribe aquí tu contenido...",
            fontNames: ['Arial', 'Courier New', 'Helvetica', 'Times New Roman'],
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'italic', 'underline', 'clear']],
                ['fontname', ['fontname']],
                ['fontsize', ['fontsize']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['height', ['height']],
                ['insert', ['link', 'picture', 'table']],
                ['view', ['fullscreen', 'codeview', 'help']]
            ]
        });
    });
</script>