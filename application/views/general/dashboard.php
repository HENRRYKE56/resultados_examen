<style>
.row-flex {
  display: flex;
  flex-wrap: wrap;
}

.row-flex > [class*='col-'] {
  display: flex;
}

.card-directorio {
  background: #7b0000;
  color: #fff;
  padding: 20px;
  width: 100%;
  display: flex;
  flex-direction: column;
}

.card-header-directorio {
  font-weight: bold;
  font-size: 18px;
  margin-bottom: 15px;
}

.card-body-directorio {
  display: flex;
  flex: 1;
}

.card-info {
  flex: 3;
}

.card-img {
  flex: 1;
  text-align: center;
}

.card-img img {
  max-width: 90px;
}

.direccion-text {
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
<div class="row row-flex">
<?php foreach ($directorio as $dependencia): ?>

  <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
    <div class="card-directorio">

      <div class="card-header-directorio">
        <?= htmlspecialchars($dependencia['nombre_corto']); ?>
      </div>

      <div class="card-body-directorio">

        <div class="card-info">
          <p><strong>Enlace:</strong><br>
            <?= htmlspecialchars($dependencia['enlace_dependencia']); ?>
          </p>

          <ul class="fa-ul">
            <li>
              <span class="fa-li"><i class="fa fa-envelope"></i></span>
              <?= htmlspecialchars($dependencia['correo_dependencia']); ?>
            </li>

            <li>
              <span class="fa-li"><i class="fa fa-phone"></i></span>
              <?= htmlspecialchars($dependencia['telefono_dependencia']); ?>
            </li>

            <li>
              <span class="fa-li"><i class="fa fa-map-marker"></i></span>
              <span class="direccion-text">
                <?= nl2br(htmlspecialchars($dependencia['direccion_depedencia'], ENT_QUOTES, 'UTF-8')); ?>
              </span>
            </li>
          </ul>
        </div>

        <div class="card-img">
          <img src="<?= base_url("assets/dist/img/" . $dependencia['imagen']) ?>" 
               class="img-circle">
        </div>

      </div>

    </div>
  </div>

<?php endforeach; ?>
</div>



      </div>
    </div>
  </section>
</div>
