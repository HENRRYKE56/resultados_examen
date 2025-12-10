
<div class="content-wrapper">
        <div class="box">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <i class="fa fa-user-circle-o" aria-hidden="true"></i> Lista de Correspondencia de: <b><?php echo $_SESSION['name']; ?></b>
       
      </h1>
    </section>
    
    <section class="content">
      <?php
       $perfil=$_SESSION['role'];
       ///1 y 4 lo ven todo
         if($perfil==1 || $perfil==4){ ?>
        <div class="row">
            <div class="col-xs-12 text-right">
                <div class="form-group">
                    <a class="btn btn-primary" href="<?php echo base_url("correspondencia/add"); ?>"><i class="fa fa-plus"></i> Nueva Correspondencia</a>
                </div>
            </div>
        </div>
        <?php } ?>
        <div class="row">
            <div class="col-md-12">
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
        <div class="row">
            <div class="col-xs-12">
          
                <div class="box-header">
                    <div class="box-tools">
                        <form action="<?php echo base_url("correspondencia") ?>" method="POST" id="searchList">
                            <div class="input-group">
                              <input type="text" name="searchText" value="<?php echo $searchText; ?>" class="form-control input-sm pull-right" style="width: 150px;" placeholder="Buscar"/>
                              <div class="input-group-btn">
                                <button class="btn btn-sm btn-default searchList"><i class="fa fa-search"></i></button>
                              </div>
                            </div>
                        </form>
                    </div>
                </div><!-- /.box-header -->
                    </br/>
                <div class="box-body table-responsive no-padding">
                  <table class="table table-hover">
                  <tr>
                    
                        <th  style="width: 5%;">Documentos</th>
                         <th style="width: 20%;">Asunto</th> 
                         <th style="width: 10%;">No de Oficio</th> 
                         <th style="width: 10%;">Fecha de Registro</th> 
                         <th style="width: 10%;">Dependencia de Procendencia</th>                                          
                        <th style="width: 20%;">Remitido Por:</th>
                        <th style="width: 10%;">Áreas responsable</th>                        
                        <th style="width: 10%;">Estado</th>
                        <th  style="width: 5%;" class="text-center">Acciones</th>
                    </tr>
                    <?php
                    if(!empty($records))
                    {
                        foreach($records as $correspondencia_item){
                             $base_url = base_url("assets/pdf/".$correspondencia_item->correspondencia."/");
                         ?>
                         <tr>
                         <td>
                            <?php if (strpos($correspondencia_item->documento, 'oficio') !== false) {
                                     echo ' <a href="'.base_url('descargar/' . $correspondencia_item->correspondencia).'" target="_blank">
                                     <img width="15%" src="' .base_url("assets/images/pdf.png") . '" alt="">Oficio</a><br/>';                             
                                }                                
                                if (strpos($correspondencia_item->documento, 'anexos') !== false) {
                                    echo ' <a href="'.base_url('descargarAnexos/' . $correspondencia_item->correspondencia).'" target="_blank">
                                    <img width="15%" src="' .base_url("assets/images/pdf.png") . '" alt="">Anexos</a><br/>';
                                } 
                                if (strpos($correspondencia_item->documento, 'respuesta') !== false) {
                                    echo ' <a href="'.base_url('descargarRespuesta/' . $correspondencia_item->correspondencia).'" target="_blank">
                                    <img width="15%" src="' .base_url("assets/images/pdf.png") . '" alt="">Respuesta</a><br/>';
                                }
                                ?>
                        </td>
                                       
                             <td><?php echo $correspondencia_item->asunto; ?></td>
                            <td> <?php echo $correspondencia_item->no_oficio; ?></td>
                            <td><?php echo date('d/m/Y', strtotime($correspondencia_item->fecha_registro)); ?></td>
                            <td><?php echo $correspondencia_item->nombre_corto; ?></td>
                            
                            <td><?php echo $correspondencia_item->remitido_por; ?></td>
                             <td><?php echo $correspondencia_item->nombre_area; ?></td>
                             <td><?php echo $correspondencia_item->des_estado; ?></td>
                             <td>
                                <?php
                              //aa
                                if($perfil==1 || $perfil==4){
                                    if($correspondencia_item->des_estado<>'Concluido') {
                                       echo'<a class="btn btn-sm btn-info" href="'.base_url('editarCorrespondencia/'.$correspondencia_item->correspondencia).'" title="Editar"><i class="fas fa-pencil-alt"></i></a>';
                                     
                                       echo'<a class="btn btn-sm btn-success" href="'.base_url('adjuntar/'.$correspondencia_item->correspondencia).'" title="Adjuntar"><i class="fa fa-paperclip"></i></a>';
                                    }else{
                                        echo'<a class="btn btn-sm btn-success" href="'.base_url('adjuntar/'.$correspondencia_item->correspondencia).'" title="Adjuntar"><i class="fa fa-paperclip"></i></a>';
                                    }
                                } else
                                {
                                    if($correspondencia_item->des_estado<>'Concluido') {
                                        echo'<a class="btn btn-sm btn-info" href="'.base_url('asignarCorrespondencia/'.$correspondencia_item->correspondencia).'" title="Editar"><i class="fa fa-pencil-alt"></i></a>';
                                       
                                        echo'<a class="btn btn-sm btn-success" href="'.base_url('adjuntar/'.$correspondencia_item->correspondencia).'" title="Adjuntar"><i class="fa fa-paperclip"></i></a>';
                                    }else{
                                        echo'<a class="btn btn-sm btn-success" href="'.base_url('adjuntar/'.$correspondencia_item->correspondencia).'" title="Adjuntar"><i class="fa fa-paperclip"></i></a>';
                                    }
                                } ?>
                               
                            
                            
                             </td>
                           </tr>
                        <?php 
                                }
                            } 
                    
                    ?>
                  </table>  
                  
                </div><!-- /.box-body -->
                <div class="box-footer clearfix">
                    <?php echo $this->pagination->create_links(); ?>
                </div>
              </div><!-- /.box -->
            </div>
        </div>
    </section>
   
</div>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/common.js" charset="utf-8"></script>
<script type="text/javascript">
    jQuery(document).ready(function(){
        jQuery('ul.pagination li a').click(function (e) {
            e.preventDefault();            
            var link = jQuery(this).get(0).href;            
            var value = link.substring(link.lastIndexOf('/') + 1);
            jQuery("#searchList").attr("action", baseURL + "correspondencia/CorrespondenciaListing/" + value);
            jQuery("#searchList").submit();
        });
    });
</script>
