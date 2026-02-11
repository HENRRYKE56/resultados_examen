<style>
/* Hace que las columnas trabajen como flex */
.row-flex {
  display: flex;
  flex-wrap: wrap;
}

.row-flex > [class*='col-'] {
  display: flex;
}

/* Hace que todas las tarjetas tengan misma altura */
.card-directorio {
  background: #7b0000; /* tu rojo institucional */
  color: #fff;
  width: 100%;
  padding: 15px;
  position: relative;
}

/* Imagen alineada a la derecha */
.card-directorio img {
  max-width: 80px;
}

/* Dirección sin romper diseño */
.direccion-text {
  white-space: normal;
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

      <h4><strong><?= htmlspecialchars($dependencia['nombre_corto']); ?></strong></h4>

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

      <div style="position:absolute; top:15px; right:15px;">
        <img src="<?= base_url("assets/dist/img/" . $dependencia['imagen']) ?>" 
             class="img-circle">
      </div>

    </div>
  </div>

<?php endforeach; ?>
</div>


      </div>
    </div>
  </section>
</div>
