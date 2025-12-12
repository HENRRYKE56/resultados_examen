<div class="content-wrapper">

<!-- Content Header -->
  <section class="content-header">
    <h1>
      <i class="fa fa-tachometer"></i> Departamento de Formación Profesional
    </h1>
  </section>

  <!-- Main Content -->
  <section class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="box box-primary">
          <div class="box-header">
            <div class="row">
            <h1>&nbsp;&nbsp;&nbsp; Resultados de Examen de Conocimientos Generales 7° Semestre 2025</h1>
 <div class="col-md-8">
        <h3 style="font-family: Arial; color:#6E0014;">Calificación por Rubro</h3>

              <div style="width: 1050px; height: 450px;">
                  <canvas id="graficaRubros"></canvas>
              </div>
        </div>
         <div class="col-md-4">
          <table style="width: 100%; border-collapse: collapse; font-family: Arial;">
    <thead>
        <tr style="background: #4a4a4a; color: white;">
            <th style="padding: 10px; border: 1px solid #ddd;">Rubro</th>
            <th style="padding: 10px; border: 1px solid #ddd;">Calificación</th>
        </tr>
    </thead>

    <tbody>
        <?php foreach ($directorio as $item): ?>
            <tr>
                <td style="padding: 10px; border: 1px solid #ddd;">
                    <?= $item['rubro']; ?>
                </td>
                <td style="padding: 10px; border: 1px solid #ddd; text-align:center; font-weight:bold;">
                    <?= $item['calificacion_rubro']; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

         </div>
<script>
    // ---- Etiquetas ----
    const labels = [
        <?php foreach ($directorio as $r) { echo "'" . $r['rubro'] . "',"; } ?>
    ];

    // ---- Valores ----
    const dataValues = [
        <?php foreach ($directorio as $r) { echo $r['calificacion_rubro'] . ","; } ?>
    ];

    // ---- Colores DIFERENTES por rubro ----
    const colores = [
        'rgba(110, 0, 20, 0.6)',     // vino
        'rgba(0, 90, 150, 0.6)',     // azul
        'rgba(0, 150, 70, 0.6)',     // verde
        'rgba(240, 140, 0, 0.6)'     // naranja
    ];

    const bordes = [
        'rgba(110, 0, 20, 1)',
        'rgba(0, 90, 150, 1)',
        'rgba(0, 150, 70, 1)',
        'rgba(240, 140, 0, 1)'
    ];

    const ctx = document.getElementById('graficaRubros').getContext('2d');

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                
                data: dataValues,
                backgroundColor: colores,
                borderColor: bordes,
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    max: 10
                }
            }
        }
    });
</script>

            
            </div> <!-- /.row -->
          </div> <!-- /.box-header -->
        </div> <!-- /.box -->
      </div> <!-- /.col-md-12 -->
    </div> <!-- /.row -->
  </section> <!-- /.content -->
</div> <!-- /.content-wrapper -->
