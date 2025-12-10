<style>
.details-row td {
  background-color: #f9f9f9;
  border-top: 1px solid #ddd;
  font-size: 14px;
}
</style>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
   
    <section class="box">
    <section class="content-header">
      <h1>
        <i class="fa fa-user-circle-o" aria-hidden="true"></i> GESTIÓN DE OFICIOS 
        <small>Agregar, Editar, Eliminar</small>
      </h1>
    </section>
        <div class="row">
            <div class="col-xs-12 text-right">
                <div class="form-group">
                    
                    <a class="btn btn-primary" href="<?php echo base_url("oficios/add"); ?>"><i class="fa fa-plus"></i> NUEVO OFICIO</a>
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



                    <h3 class="box-title">LISTA DE OFICIOS</h3>
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
                        <th  class="text-center" style="width: 5%;"></th>
                            <th  class="text-center" style="width: 5%;">No</th>
                            <th class="text-center" style="width: 7%;">Fecha</th>
                            <th class="text-center"  style="width: 15%;">Elaborado por:</th>
                            <th class="text-center" style="width: 25%;">Asunto</th>
                            <th class="text-center" style="width: 20%;">Turnado a</th>
                             <th  class="text-center" style="width: 5%;">Estado</th>
                            <th class="text-center" class="text-center" style="width: 5%;">ACCIONES</th>
                        </tr>
                    </thead>
                    <tbody>
                   <tbody>
<?php if (!empty($records)) : ?>
  <?php foreach ($records as $record) : ?>
    <!-- Fila principal -->
    <tr>
      <td class="text-center">
	   <?php 
          echo ' <a href="imprimir/' . $record->no_oficio.'" target="_blank">
            <img width="50%" src="' .base_url("assets/images/pdf.png") . '" alt=""></a>';                             
        ?>  
        <button class="btn btn-xs btn-default toggle-details"><i class="fa fa-plus"></i></button>
      </td>
      <td class="text-center"><?php echo $record->no_oficio ?></td>
      <td><?php echo date("d-m-Y", strtotime($record->fecha_oficio)) ?></td>
      <td><?php echo $record->creado_por ?></td>
      <td><?php echo $record->asunto ?></td>
      <td><?php echo $record->des_dependencia ?></td>
      <td>
        <?= ($record->estado <> 0) ? "Cancelado" : "Activo"; ?>
      </td>
      <td class="text-center">
        <a class="btn btn-sm btn-info" href="<?php echo base_url("editaroficio/") . $record->no_oficio; ?>" title="Editar"><i class="fas fa-pencil-alt"></i></a>
        <a class="btn btn-sm btn-danger deletetask" href="#" data-taskid="<?php echo $record->no_oficio; ?>" title="Eliminar"><i class="fa fa-trash"></i></a>
      </td>
    </tr>

    <!-- Fila oculta con más información -->
    <tr class="details-row" style="display: none;">
      <td colspan="8">
        <strong>Personal Comisionado:</strong> <?= htmlspecialchars(isset($record->personal_comisionado) ? $record->personal_comisionado : "") ?><br> 
        <strong>Firma:</strong> <?= htmlspecialchars(isset($record->quien_firma) ? $record->quien_firma : "") ?><br>
        <strong>Lugar de la Comisión:</strong> <?= htmlspecialchars(isset($record->lugar_comision) ? $record->lugar_comision : "") ?><br/>
        <strong>Modalidad:</strong> <?= htmlspecialchars(isset($record->modalidad) ? $record->modalidad : "") ?><br>
        <strong>Fecha de Comisión:</strong> <?= htmlspecialchars(isset($record->fecha_comision) ? date("d-m-Y", strtotime($record->fecha_comision)) : "") ?><br>
        <strong>Motivo de Comisión:</strong> <?= htmlspecialchars(isset($record->motivo) ? $record->motivo : "") ?><br>
      </td>
    </tr>
  <?php endforeach; ?>
<?php endif; ?>
</tbody>
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
            jQuery("#searchList").attr("action", baseURL + "task/taskListing/" + value);
            jQuery("#searchList").submit();
        });
    });
</script>
<script>
  $(document).ready(function() {
    $('.toggle-details').click(function() {
      var icon = $(this).find('i');
      var row = $(this).closest('tr').next('.details-row');

      row.toggle();

      icon.toggleClass('fa-plus fa-minus');
    });
  });
</script>

