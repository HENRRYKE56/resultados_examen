<style>
.direccion-text {
  display: block;
  white-space: normal;
  overflow-wrap: break-word;
  word-wrap: break-word;
  line-height: 1.4;
}
</style>

<div class="content-wrapper">
  <section class="content-header">
    <div class="container-fluid">
      <h1>
        <i class="fas fa-tachometer-alt"></i> Directorio de Instituciones de Educación Superior de Seiem
      </h1>
    </div>
  </section>

  <section class="content">
    <div class="container-fluid">
      <div class="row">

        <?php foreach ($directorio as $dependencia): ?>
          <div class="col-lg-4 col-md-6 col-12 mb-3">
            <div class="card card-primary h-100">

              <div class="card-header">
                <h5 class="mb-0">
                  <strong><?= htmlspecialchars($dependencia['nombre_corto']); ?></strong>
                </h5>
              </div>

              <div class="card-body">
                <div class="row">

                  <!-- Información -->
                  <div class="col-9">
                    <p class="mb-2">
                      <strong>Enlace:</strong><br>
                      <?= htmlspecialchars($dependencia['enlace_dependencia']); ?>
                    </p>

                    <ul class="fa-ul mb-0">
                      <li class="mb-2">
                        <span class="fa-li">
                          <i class="fas fa-envelope"></i>
                        </span>
                        <?= htmlspecialchars($dependencia['correo_dependencia']); ?>
                      </li>

                      <li class="mb-2">
                        <span class="fa-li">
                          <i class="fas fa-phone"></i>
                        </span>
                        <?= htmlspecialchars($dependencia['telefono_dependencia']); ?>
                      </li>

                      <li>
                        <span class="fa-li">
                          <i class="fas fa-map-marker-alt"></i>
                        </span>
                        <span class="direccion-text">
                          <?= nl2br(htmlspecialchars($dependencia['direccion_depedencia'], ENT_QUOTES, 'UTF-8')); ?>
                        </span>
                      </li>
                    </ul>
                  </div>

                  <!-- Imagen -->
                  <div class="col-3 text-center d-flex align-items-center justify-content-center">
                    <img src="<?= base_url("assets/dist/img/" . $dependencia['imagen']) ?>" 
                         alt="Logo"
                         class="img-fluid img-circle"
                         style="max-width: 80px;">
                  </div>

                </div>
              </div>

            </div>
          </div>
        <?php endforeach; ?>

      </div>
    </div>
  </section>
</div>
