<div class="content-wrapper">
    <!-- Content Header (Page header) -->
   
    <section class="box-header">
    <section class="content-header">
    
    </section>
     
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
              <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Lista de Incidencias por Oficio de Comisión</h3>
                    <div class="box-tools">
                        <form action="<?php echo base_url("oficios/taskListing") ?>" method="POST" id="searchList">
                            <div class="input-group">
                              <input type="text" name="searchText" value="<?php echo $searchText; ?>" class="form-control input-sm pull-right" style="width: 150px;" placeholder="Buscar"/>
                              <div class="input-group-btn">
                                <button class="btn btn-sm btn-default searchList"><i class="fa fa-search"></i></button>
                              </div>
                            </div>
                        </form>
                    </div>
                </div><!-- /.box-header -->
                <div class="box-body table-responsive no-padding">
                  <table class="table table-hover">                        
                    <thead>
                        <tr>
                            <th  class="text-center" style="width: 5%;">No</th>
                            <th class="text-center" style="width: 7%;">Fecha de la Comisión</th>
                            <th class="text-center"  style="width: 15%;">Elaborado por:</th>
                             <th class="text-center" style="width: 20%;">Destinatario</th>
                            <th  class="text-center" style="width: 5%;">Justificar</th>
                            <th class="text-center" class="text-center" style="width: 5%;">Imprmir</th>
                        </tr>
                    </thead>
                    <tbody>
                   
                    <?php
                    if(!empty($records))
                    {
                        foreach($records as $record)
                        { 
                            
         
                    ?>
                    <tr>
                        <td class="text-center"><?php echo $record->no_oficio ?></td>
                        <td><?php echo date("d-m-Y", strtotime($record->fecha_comision)) ?></td>
                        <td><?php echo $record->elaborado ?></td>
                        <td><?php echo $record->destinatario ?></td>
                        <td><?php echo $record->omision ?></td>
                        <td class="text-center">
                            <a class="btn btn-sm " target="_blank" href="<?php echo base_url("pdf/").$record->no_oficio; ?>">
                            <i class="fa fa-file-pdf-o fa-3x"></i></a>
                        
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
    <!-- /.segunda Seccion -->
    <section class="box-header">
    <section class="content-header">
    
    </section>
        <div class="row">
            <div class="col-xs-12 text-right">
                <div class="form-group">
                    
                <a class="btn btn-primary" href="<?php echo base_url("oficios/addIncidencia"); ?>">INCIDENCIAS </a>
                </div>
            </div>
        </div>
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
              <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Lista de Incidencias</h3>
                    <div class="box-tools">
                        <form action="<?php echo base_url("oficios/taskListing") ?>" method="POST" id="searchList1">
                            <div class="input-group">
                              <input type="text" name="searchText1" value="<?php echo $searchText1; ?>" class="form-control input-sm pull-right" style="width: 150px;" placeholder="Buscar"/>
                              <div class="input-group-btn">
                                <button class="btn btn-sm btn-default searchList"><i class="fa fa-search"></i></button>
                              </div>
                            </div>
                        </form>
                    </div>
                </div><!-- /.box-header -->
                <div class="box-body table-responsive no-padding">
                  <table class="table table-hover">                        
                    <thead>
                        <tr>
                            <th  class="text-center" ">No</th>
                            <th class="text-center">Fecha de la Comisión</th>
                            <th class="text-center"  >Tipo de Incidencia</th>
                             <th class="text-center" >Incidencia</th>
                            <th  class="text-center" >Justificar</th>
                            <th class="text-center" class="text-center">Imprmir</th>
                        </tr>
                    </thead>
                    <tbody>
                   
                    <?php
                     
                    if(!empty($records1))
                    {$i=0;
                        foreach($records1 as $record)
                        { 
                            $i++;
                        
                    ?>
                    <tr> 
                        <td class="text-center"><?php echo $i ?></td>
                        <td><?php echo date("d-m-Y", strtotime($record->fecha_comision)) ?></td>
                        <td><?php echo $record->des_tipo_omision ?></td>
                        <td><?php echo $record->des_omision ?></td>
                        <td><?php echo $record->descripcion_omision ?></td>
                        <td class="text-center">
                            <a class="btn btn-sm " target="_blank" href="<?php echo base_url("pdf1/").$record->cve_incidencia; ?>">
                            <i class="fa fa-file-pdf-o fa-3x"></i></a>
                        
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
     <!-- /.fin segunda seccion -->
</div>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/common.js" charset="utf-8"></script>
<script type="text/javascript">
    jQuery(document).ready(function(){
        jQuery('ul.pagination li a').click(function (e) {
            e.preventDefault();            
            var link = jQuery(this).get(0).href;            
            var value = link.substring(link.lastIndexOf('/') + 1);
            jQuery("#searchList").attr("action", baseURL + "task/taskListing/" + value);
            jQuery("#searchList").submit();
        });
    });
</script>
