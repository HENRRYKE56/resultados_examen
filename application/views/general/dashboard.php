<style>

/* FLEX PARA EVITAR HUECOS */
.row-flex {
  display: flex;
  flex-wrap: wrap;
}

.row-flex > [class*='col-'] {
  display: flex;
  margin-bottom: 25px;
}

/* TARJETA */
.card-directorio {
  background: #8b0000;
  color: #fff;
  padding: 22px;
  width: 100%;
  display: flex;
  flex-direction: column;
  border-radius: 6px;
  box-shadow: 0 4px 12px rgba(0,0,0,0.25);
  transition: all 0.3s ease;
}

/* HOVER */
.card-directorio:hover {
  transform: translateY(-6px);
  box-shadow: 0 8px 20px rgba(0,0,0,0.35);
}

/* HEADER */
.card-header-directorio {
  font-size: 18px;
  font-weight: 700;
  letter-spacing: .5px;
  margin-bottom: 15px;
  border-bottom: 1px solid rgba(255,255,255,0.2);
  padding-bottom: 8px;
}

/* CUERPO FLEX */
.card-body-directorio {
  display: flex;
  flex: 1;
}

/* INFORMACIÓN */
.card-info {
  flex: 3;
  font-size: 14px;
}

.card-info p {
  margin-bottom: 8px;
}

.card-info strong {
  font-weight: 600;
}

/* LISTA ICONOS */
.fa-ul li {
  margin-bottom: 8px;
}

/* DIRECCIÓN */
.direccion-text {
  word-wrap: break-word;
  line-height: 1.5;
}

/* IMAGEN */
.card-img {
  flex: 1;
  text-align: center;
  display: flex;
  align-items: flex-start;
  justify-content: center;
}

.card-img img {
  width: 90px;
  height: 90px;
  object-fit: cover;
  border: 3px solid rgba(255,255,255,0.4);
  box-shadow: 0 3px 8px rgba(0,0,0,0.4);
}

/* RESPONSIVE */
@media (max-width: 768px) {
  .card-body-directorio {
    flex-direction: column;
    text-align: left;
  }

  .card-img {
    margin-top: 15px;
    justify-content: flex-start;
  }
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
