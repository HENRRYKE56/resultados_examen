<style>
  .direccion-text {
  display: block;
  white-space: normal;
  word-break: break-word;
  line-height: 1.4;
}

.text-break {
  word-break: break-all;
}

</style>
<div class="content-wrapper">
  <!-- Content Header -->
  <section class="content-header">
    <h1>
      <i class="fa fa-tachometer"></i> Directorio de Instituciones de Educación Superior de Seiem
    </h1>
  </section>

  <!-- Main Content -->
  <section class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="box box-primary">
          <div class="box-header">
            <div class="row">
              <?php foreach ($directorio as $dependencia): ?>
                <div class="col-lg-4 col-md-4 col-sm-12 d-flex align-items-stretch mb-3">
                  <div class="card w-100 bg-aqua">
                    <div class="card-header border-bottom">
                      <h5 class="lead mb-0"> <strong><?= htmlspecialchars($dependencia['nombre_corto']); ?></strong></h5>
                    </div>

                    <div class="card-body pt-2">
                      <div class="row">
                        <div class="col-12 col-sm-9">
                          <h4 class="mb-2"><strong>Enlace:</strong><BR/> <?= htmlspecialchars($dependencia['enlace_dependencia']); ?></h4>

                          <ul class="fa-ul">
                           <li class="mb-2">
                              <span class="fa-li"><i class="fas fa-envelope-open"></i></span>
                              <span class="text-break">
                                <?= htmlspecialchars($dependencia['correo_dependencia']); ?>
                              </span>
                            </li>

                            <li class="mb-1">
                              <span class="fa-li"><i class="fas fa-phone"></i></span>
                              <?= htmlspecialchars($dependencia['telefono_dependencia']); ?>
                            </li>
                           <li class="mb-2">
                              <span class="fa-li">
                                <i class="fas fa-map-marker-alt"></i>
                              </span>
                              <span class="direccion-text">
    <?= nl2br(htmlspecialchars($dependencia['direccion_depedencia'], ENT_QUOTES, 'UTF-8')); ?>
</span>

                            </li>

                          </ul>
                        </div>

                      
                        <div class="col-2 col-sm-3 text-center d-flex align-items-center">
                          <img src="<?= base_url("assets/dist/img/" . $dependencia['imagen']) ?>" alt="Logo" class="img-circle img-fluid" style="max-width: 80px;">
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              <?php endforeach; ?>
            </div> <!-- /.row -->
          </div> <!-- /.box-header -->
        </div> <!-- /.box -->
      </div> <!-- /.col-md-12 -->
    </div> <!-- /.row -->
  </section> <!-- /.content -->
</div> <!-- /.content-wrapper -->
