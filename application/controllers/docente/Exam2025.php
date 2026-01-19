<?php if(!defined('BASEPATH')) exit('No direct script access allowed');
//die("En Construcción");
require APPPATH . '/libraries/BaseController.php';
require APPPATH .'/libraries/Pdf.php';

class Exam2025 extends BaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('ingles/Exam_model_docente', 'em');
        $this->isLoggedIn();
        $this->module = 'Edocente';//importante revisar que esta en la tabla de menus
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->load->library('upload');
        $this->load->library('session');
    }



    public function index()
    {
        if(!$this->hasCreateAccess())
        { 
            $this->loadThis();
        }
        else
        {
            $ies = $this->em->ies();
            $options1 = array();
            foreach ($ies as $ie) {   
                $options1[$ie['cve_ies']] = $ie['ies'];
            }
            $ies = array('' => 'Seleccione') + $options1;

            $this->data['ies'] = array(
                'name'  => 'ies',
                'id'    => 'ies',
                'class' => 'form-control',
                'options' => $ies,
                'value' => $this->form_validation->set_value('ies'),
            );

            $this->data['sede'] = array(
                'name'  => 'sede',
                'id'    => 'sede',
                'class' => 'form-control',
                'options' => array('' => 'Seleccione'),
            );

            $this->data['programa'] = array(
                'name'  => 'programa',
                'id'    => 'programa',
                'class' => 'form-control',
                'options' => array('' => 'Seleccione'),
            );

            $this->global['pageTitle'] = 'SEIEM : Reporte de Resultados Examen de Inglés 2025';

            $this->loadViews("docente/panel", $this->global, $this->data, NULL);
        }
    }

   public function sede()
    {
        if(!$this->hasCreateAccess())
        { 
            $this->loadThis();
        }
        else
        {
            $ies = $this->em->ies();
            $options1 = array();
            foreach ($ies as $ie) {   
                $options1[$ie['cve_ies']] = $ie['ies'];
        }
            $ies = array('' => 'Seleccione') + $options1;

            $this->data['ies'] = array(
                'name'  => 'ies',
                'id'    => 'ies',
                'class' => 'form-control',
                'options' => $ies,
                'value' => $this->form_validation->set_value('ies'),
            );

     

            $this->global['pageTitle'] = 'SEIEM : Reporte de Resultados de Evaluación Docente por Sede Semestre Septiembre 2025 Enero 2026';

            $this->loadViews("docente/sedes", $this->global, $this->data, NULL);
        }
    }

public function estadistico()
    {
        if(!$this->hasCreateAccess())
        { 
            $this->loadThis();
        }
        else
        {
             $ies = $this->em->ies();
            $options1 = array();
            foreach ($ies as $ie) {   
                $options1[$ie['cve_ies']] = $ie['ies'];
            }
            $ies = array('' => 'Seleccione') + $options1;

            $this->data['ies'] = array(
                'name'  => 'ies',
                'id'    => 'ies',
                'class' => 'form-control',
                'options' => $ies,
                'value' => $this->form_validation->set_value('ies'),
            );

            $this->data['sede'] = array(
                'name'  => 'sede',
                'id'    => 'sede',
                'class' => 'form-control',
                'options' => array('' => 'Seleccione'),
            );

            $this->data['programa'] = array(
                'name'  => 'programa',
                'id'    => 'programa',
                'class' => 'form-control',
                'options' => array('' => 'Seleccione'),
            );
 $this->data['pass'] = array(
                'name'  => 'pass',
                'id'    => 'pass',
                'class' => 'form-control',
                'options' => array('' => 'Seleccione', 'Pass' => 'Pass', 'No Pass' => 'No Pass'),
            );
            $this->global['pageTitle'] = 'SEIEM : Reporte Estadístico de Resultados Examen de Inglés 2025';

            $this->loadViews("docente/estadistico", $this->global, $this->data, NULL);
        }
    }

///estadistico
public function contarPorCategoria($datos, $campo)
{
    $conteo = [
        'Reprobado (0 - 6.9)' => 0,
        'Suficiente (7 - 8.9)' => 0,
        'Excelente (> 9)' => 0
    ];

    foreach ($datos as $registro) {
        if (!isset($registro[$campo], $registro['total'])) continue;

        $valor = (float)$registro[$campo];
        $alumnos = (int)$registro['total'];

        if ($valor < 7) {
            $conteo['Reprobado (0 - 6.9)'] += $alumnos;
        } elseif ($valor < 9) {
            $conteo['Suficiente (7 - 8.9)'] += $alumnos;
        } else {
            $conteo['Excelente (> 9)'] += $alumnos;
        }
    }

    return $conteo;
}
function calcularEstadisticasPorExamen($datos_examen)
{
    $total = 0;
    $aprobados = 0;

    foreach ($datos_examen as $registro) {
        $total += (int)$registro['total'];

       
            $aprobados += (int)$registro['total'];
        
    }

    return [
        'total' => $total,
        'aprobados' => $aprobados,
        'reprobados' => $total - $aprobados,
        'porcentaje' => $total > 0 ? round(($aprobados / $total) * 100, 2) : 0
    ];
}



function calcularEstadisticasGenerales($datos)
{
    if (empty($datos)) {
        return [
            'total_alumnos' => 0,
            'aprobados' => 0,
            'reprobados' => 0,
            'porcentaje_aprobacion' => 0,
            'promedio_planeacion' => 0,
            'promedio_saberes' => 0,
            'promedio_habilidades' => 0,
            'promedio_recursos' => 0
        ];
    }

    $totalAlumnos = 0;
    $aprobados = 0;
    $sumaPromedio = 0;
    $sumaPlaneacion = 0;
    $sumaSaberes = 0;
    $sumaHabilidades = 0;
    $sunaRecursos = 0;

    foreach ($datos as $registro) {
        $totalAlumnos += (int)$registro['total'];

       
            $aprobados += (int)$registro['total'];
        
   
        $sumaPromedio += $registro['promedio'] * $registro['total'];
        $sumaPlaneacion += $registro['planeacion'] * $registro['total'];
        $sumaSaberes += $registro['saberes'] * $registro['total'];
        $sumaHabilidades += $registro['habilidades'] * $registro['total'];
        $sunaRecursos += $registro['recursos'] * $registro['total'];
    }
  
    return [
        'total_alumnos' => $totalAlumnos,
        'aprobados' => $aprobados,
        'reprobados' => $totalAlumnos - $aprobados,
        'porcentaje_aprobacion' => $totalAlumnos > 0 ? round(($aprobados / $totalAlumnos) * 100, 2) : 0,
        'promedio_general' => $totalAlumnos > 0 ? round($sumaPromedio / $totalAlumnos, 2) : 0,
        'promedio_planeacion' => $totalAlumnos > 0 ? round($sumaPlaneacion / $totalAlumnos, 2) : 0,
        'promedio_saberes' => $totalAlumnos > 0 ? round($sumaSaberes / $totalAlumnos, 2) : 0,
        'promedio_habilidades' => $totalAlumnos > 0 ? round($sumaHabilidades / $totalAlumnos, 2) : 0,
        'promedio_recursos' => $totalAlumnos > 0 ? round($sunaRecursos / $totalAlumnos, 2) : 0
    ];
}


function generarTablaDistribucion($titulo,  $conteo)
{
    $html = '<h3>' . htmlspecialchars($titulo) . '</h3>';
    $html .= '<table border="1" cellpadding="3" cellspacing="0">
                <thead style="background-color:#f2f2f2; font-weight:bold;">
                    <tr>
                        <th>Categoría</th>
                        <th>Cantidad de Alumnos</th>
                    </tr>
                </thead>
                <tbody>';
    
    foreach ($conteo as $categoria => $cantidad) {
        $html .= '<tr>
                    <td>' . htmlspecialchars($categoria) . '</td>
                    <td style="text-align:center;">' . $cantidad . '</td>
                  </tr>';
    }
    
    $html .= '</tbody></table><br/>';
    return $html;
}


function generarTablaGeneral( $estadisticas)
{
    $html = '<h3>Resumen General de Resultados</h3>';
    $html .= '<table border="1" cellpadding="2" cellspacing="0">
                <thead style="background-color:#f2f2f2; font-gothambook:bold;">
                    <tr>
                        <th>Indicador</th>
                        <th>Valor</th>
                    </tr>
                </thead>
                <tbody>';

           

   
    $html .= '<tr><td>Total de Alumnos Evaluados</td><td style="text-align:center;">' . $estadisticas['total_alumnos'] . '</td></tr>';
    $html .= '<tr><td>Alumnos Aprobados (Pass)</td><td style="text-align:center;">' . $estadisticas['aprobados'] . '</td></tr>';
    $html .= '<tr><td>Alumnos Reprobados (No Pass)</td><td style="text-align:center;">' . $estadisticas['reprobados'] . '</td></tr>';
    $html .= '<tr><td>Porcentaje de Aprobación</td><td style="text-align:center;">' . $estadisticas['porcentaje_aprobacion'] . '%</td></tr>';
    $html .= '<tr><td>Promedio General de Calificaciones</td><td style="text-align:center;">' . $estadisticas['promedio_general'] . '</td></tr>';
    $html .= '<tr><td>Promedio de Planeación</td><td style="text-align:center;">' . $estadisticas['promedio_planeacion'] . '</td></tr>';
    $html .= '<tr><td>Promedio de Saberes</td><td style="text-align:center;">' . $estadisticas['promedio_saberes'] . '</td></tr>';
    $html .= '<tr><td>Promedio de Habilidades</td><td style="text-align:center;">' . $estadisticas['promedio_habilidades'] . '</td></tr>';
    $html .= '<tr><td>Promedio de Recursos</td><td style="text-align:center;">' . $estadisticas['promedio_recursos'] . '</td></tr>';

    $html .= '</tbody></table><br/>';
    return $html;
}


function agruparPorExamen( $datos)
{
    $datos_agrupados = [];
   
    foreach ($datos as $registro) {
        $tipo_examen = $registro['programa'];
        // Si el tipo de examen no existe como clave, lo creamos
        if (!isset($datos_agrupados[$tipo_examen])) {
            $datos_agrupados[$tipo_examen] = [];
        }
        // Añadimos el registro completo al grupo correspondiente
        $datos_agrupados[$tipo_examen][] = $registro;
    }
    return $datos_agrupados;
}


function generarTablaPorExamen($datos_examen,  $titulo_examen)
{
    // Si no hay datos para este examen, no mostramos nada.
    if (empty($datos_examen)) {
        return '';
    }
 

    $html = '<h2>Resultados para Examen: ' . htmlspecialchars($titulo_examen) . '</h2>';
    $html .= '<table border="1" cellpadding="4" cellspacing="0">
                <thead style="background-color:#f2f2f2; font-gothambook:bold;">
                    <tr>
                        <th>IES</th>
                        <th>Sede</th>
                        <th>Programa</th>
                        <th>Semestre</th>
                        <th>Prom. Planeación</th>
                        <th>Prom. Saberes</th>
                        <th>Prom. Habilidades</th>
                        <th>Prom. Recursos</th>
                        <th>Promedio General</th>
                        <th>Nivel</th>
                    </tr>
                </thead>
                <tbody>';



                
    foreach ($datos_examen as $registro) {
        // Determinar el color de la fila según el nivel
        $style_fila = ($registro['nivel'] === 'No Pass') ? 'style="background-color:#ffdddd;"' : '';
        
        $html .= '<tr ' . $style_fila . '>
                    <td>' . htmlspecialchars($registro['ies']) . '</td>
                    <td>' . htmlspecialchars($registro['sede']) . '</td>
                    <td>' . htmlspecialchars($registro['programa']) . '</td>
                    <td>' . htmlspecialchars($registro['cve_semestre']) . '</td>
                    <td style="text-align:center;">' . $registro['planeacion'] . '</td>
                    <td style="text-align:center;">' . $registro['saberes'] . '</td>
                    <td style="text-align:center;">' . $registro['habilidades'] . '</td>
                    <td style="text-align:center;">' . $registro['recursos'] . '</td>
                    <td style="text-align:center; font-gothambook:bold;">' . $registro['promedio'] . '</td>
                    <td style="text-align:center;">' . htmlspecialchars($registro['nivel']) . '</td>
                  </tr>';
    }

    $html .= '</tbody></table><br/>';
    return $html;
}




    /////////////////////////

    public function reporte_estadistico(){

     if (!$this->hasCreateAccess()) {
        $this->loadThis();
        return;
    }

    $nombre_archivo = "";

    // ----------------------------------------------------
    // CONFIGURACIÓN PDF
    // ----------------------------------------------------
    $pdf = new PDF('P', 'mm', 'LETTER', true, 'UTF-8', false);
    $pdf->SetCreator('HLANDEROS');
    $pdf->SetAuthor('HLANDEROS');
    $pdf->SetTitle('Reporte de Examen de Conocimientos 2025');
    $pdf->setPrintHeader(true);
    $pdf->setPrintFooter(true);
    $pdf->SetMargins(20, 20, 20);

    $vinos = [110, 0, 20];

    // ----------------------------------------------------
    // DATOS POST
    // ----------------------------------------------------
    $ies      = $this->input->post('ies');
    $sede     = $this->input->post('sede');
    $programa = $this->input->post('programa');

    // ----------------------------------------------------
    // CONSULTA ÚNICA A BD (OPTIMIZADA)
    // ----------------------------------------------------
    $resultados_crudos = $this->em->get_all_resultados_for_planes(
        $ies,
        $sede,
        $programa
    );
  


$conteo_grammar = $this->contarPorCategoria($resultados_crudos, 'grammar');
 $conteo_reading = $this->contarPorCategoria($resultados_crudos, 'reading');
 $conteo_vocabulary = $this->contarPorCategoria($resultados_crudos, 'vocabulary');
 $conteo_promedio = $this->contarPorCategoria($resultados_crudos, 'promedio');
 $estadisticas_generales =$this->calcularEstadisticasGenerales($resultados_crudos);
// 2. Procesar los datos para agruparlos por examen
$datos_por_examen = $this->agruparPorExamen($resultados_crudos);



 $html_reporte = '
<style>
    h1 { color: #003366; }
    h2 { color: #005599; }
    h3 { color: #0077BB; }
    table { width: 100%; border-collapse: collapse; margin-bottom: 12px; font-family: gothambook; }
    th, td { border: 1px solid #ddd; padding: 4px; }
    thead th { background-color: #f2f2f2; text-align: left; }
</style>
<h1>Reporte Estadístico de Resultados de Examen de Inglés por Tipo de Examen</h1>';




 $html_reporte .= '<b><ul>';
 $html_reporte .= '<li><strong>IES:</strong> ' . ($resultados_crudos[0]['institucion'] ? htmlspecialchars($resultados_crudos[0]['institucion']) : 'Todos') . '</li>';
 $html_reporte .= '<li><strong>Sede:</strong> ' . (!empty($resultados_crudos[0]['sede']) ? htmlspecialchars($resultados_crudos[0]['sede']) : 'Todas') . '</li>';
 $html_reporte .= '<li><strong>Programa:</strong> ' . (!empty($resultados_crudos[0]['programa']) ? htmlspecialchars($resultados_crudos[0]['programa']   ) : 'Todos') . '</li>';
 //$html_reporte .= '<li><strong>Nivel:</strong> ' . (!empty($resultados_crudos[0]['nivel']) ? htmlspecialchars($resultados_crudos[0]['nivel']   ) : 'Todos') . '</li>';
 $html_reporte .= '</ul></b>';

// Añadir las tablas al reporte
 $html_reporte .= $this->generarTablaDistribucion('Distribución de Calificaciones - Grammar', $conteo_grammar);
 $html_reporte .= $this->generarTablaDistribucion('Distribución de Calificaciones - Reading', $conteo_reading);
 $html_reporte .= $this->generarTablaDistribucion('Distribución de Calificaciones - Vocabulary', $conteo_vocabulary);
 $html_reporte .= $this->generarTablaDistribucion('Distribución del Promedio General', $conteo_promedio);
 $html_reporte .= $this->generarTablaGeneral($estadisticas_generales);
    
$pdf->AddPage();

if ($pdf->getPage() > 1) {
    $pdf->SetXY(22, 30);
    $pdf->setPageMark(); // ← MUY IMPORTANTE
}

      






foreach ($datos_por_examen as $tipo_examen => $datos) {

    $stats = $this->calcularEstadisticasPorExamen($datos);

    $html_reporte .= '<h2>Examen: ' . $tipo_examen . '</h2>';
    $html_reporte .= '<ul>
        <li><strong>Total de alumnos:</strong> ' . $stats['total'] . '</li>
        <li><strong>Aprobados:</strong> ' . $stats['aprobados'] . '</li>
        <li><strong>Reprobados:</strong> ' . $stats['reprobados'] . '</li>
        <li><strong>% Aprobación:</strong> ' . $stats['porcentaje'] . '%</li>
    </ul>';

    $html_reporte .= $this->generarTablaPorExamen($datos, $tipo_examen);
}









  $pdf->SetXY(22, 30);
        $pdf->SetFont('gothambook', '', 9);
        
// Escribir el contenido HTML
 $pdf->writeHTML($html_reporte, true, false, true, false, '');

// Cerrar y generar el PDF
// 'I' para mostrar en el navegador, 'D' para forzar la descarga, 'F' para guardar en un archivo
 $pdf->Output('reporte_estadistico.pdf', 'I');

    }


        public function botonIES()
        {   $this->global['pageTitle'] = 'SEIEM : Reporte de Resultados de Evaluación Docente Semestre Septiembre 2025 Enero 2026';
        $this->loadViews("docente/ies", $this->global, NULL);
        }
     public function reporte_ies()
    {
         if (!$this->hasCreateAccess()) {
        $this->loadThis();
        return;
    }



$nombre_archivo="";


    $pdf = new PDF('P', 'mm', 'LETTER', true, 'UTF-8', false);
    $pdf->SetCreator('HLANDEROS');
    $pdf->SetAuthor('Hlanderos');
    $pdf->SetTitle('Reporte de Examen de Inglés 2025');
    $pdf->setPrintHeader(true);
    $pdf->setPrintFooter(true);
    $pdf->SetMargins(20, 20, 20);

    $vinos = array(110, 0, 20);

     $resultados_crudos = $this->em->get_all_resultados_for_ies();

  $vinos = [110, 0, 20];

    // ----------------------------------------------------
    // DATOS POST
    // ----------------------------------------------------
    $ies      = $this->input->post('ies');
    
    // ----------------------------------------------------
    // CONSULTA ÚNICA A BD (OPTIMIZADA)
    // ----------------------------------------------------
   

    // ----------------------------------------------------
    // AGRUPACIÓN POR ALUMNO
    // ----------------------------------------------------

$resultados_por_grupo = [];

foreach ($resultados_crudos as $fila) {
    // 1. CREAMOS UNA CLAVE ÚNICA concatenando examen y sede
    $clave_grupo = $fila['institucion'] ;

    if (!isset($resultados_por_grupo[$clave_grupo])) {
        // Puedes ajustar cómo se genera el nombre del archivo si es necesario
        $nombre_archivo = $fila['institucion'] ;

        $resultados_por_grupo[$clave_grupo] = [
            'ies'       => $fila['institucion'],
           'promedio'  => $fila['promedio'],
            
            'rubros'    => []
        ];
    }

    // 3. USAMOS LOS NOMBRES DE COLUMNA CORRECTOS de la consulta SQL
    $resultados_por_grupo[$clave_grupo]['rubros'] = [
        'planeacion'    => $fila['planeacion'],
        'saberes' => $fila['saberes'],
        'habilidades'    => $fila['habilidades'],
        'recursos'    => $fila['recursos'],
        'etica'    => $fila['etica']
    ];
}

// ----------------------------------------------------
// GENERACIÓN DE PDF POR GRUPO
// ----------------------------------------------------
// Renombramos la variable $nombre_alumno a $clave_grupo para mayor claridad
foreach ($resultados_por_grupo as $clave_grupo => $datos) {
    $pdf->AddPage();

    // ... (El resto de tu código para generar el PDF está perfecto y no necesita cambios)
    // ... ya que accedes a los datos a través del array $datos, que es correcto.

    // ------------------------------------------------
    // ENCABEZADO
    // ------------------------------------------------
    $pdf->SetFont('gothamblack', '', 11);
    $pdf->SetXY(0, 30);
    $pdf->writeHTML(
        'Resultados de Evaluación Docente  Semestre  Septiembre 2025 Enero 2026',
        false,
        false,
        false,
        '',
        'C'
    );
 

        // ------------------------------------------------
        // DATOS GENERALES
        // ------------------------------------------------
        $htmlDatos = "
            <b>Institución:</b> {$datos['ies']}<br>
           
            <br><br>
          
        ";//nombre_docente

        $pdf->SetXY(22, 40);
        $pdf->SetFont('gothambook', '', 10);
        $pdf->writeHTML($htmlDatos, false, false, false, '');

        // ------------------------------------------------
        // PUNTAJE GENERAL
        // ------------------------------------------------

        $valorObtenido = number_format($datos['promedio'], 2);
        $puntaje = floatval($valorObtenido);

        $pdf->SetFont('gothamblack', '', 12);
        $pdf->SetFillColor($vinos[0], $vinos[1], $vinos[2]);
        $pdf->SetTextColor(255,255,255);
        $pdf->Cell(50, 10, 'Puntaje Obtenido', 0, 0, 'C', 1);

        $pdf->SetFont('gothamblack', '', 16);
        $pdf->SetTextColor(0,0,0);
        $pdf->Cell(30, 10, $valorObtenido, 0, 1, 'C');

        $pdf->Ln(6);

        // ------------------------------------------------
        // GRÁFICA DE BARRA (ESCALA 0–10)
        // ------------------------------------------------
        $inicioX = 20;
        $y = $pdf->GetY();

        $anchoBarra = 180;
        $altoBarra  = 10;

        $pdf->SetLineWidth(0.3);
        $pdf->SetDrawColor(110, 0, 20);
        $pdf->Rect($inicioX, $y, $anchoBarra, $altoBarra);

        // NUMERACIÓN DEL 0 AL 10
       $inicioX = 20;
    $anchoBarra = 180;
    $maxPuntaje = 18;
    $puntaje = max(0, min(floatval($puntaje), $maxPuntaje));

    // Contorno principal
    $pdf->SetDrawColor(110, 0, 15);
    $pdf->Rect($inicioX, $y, $anchoBarra, $altoBarra);

    // Escala principal 0–18
    $pdf->SetFont('gothambook', 'b', 8);
    for ($i = 0; $i <= $maxPuntaje; $i++) {
        $x = $inicioX + ($anchoBarra / $maxPuntaje) * $i;
        $pdf->Line($x, $y, $x, $y + $altoBarra);
        $pdf->Text($x - 2, $y + $altoBarra + 3, (string)$i);
    }

    // Subdivisiones (cada punto dividido en 2 = medios puntos)
    for ($i = 0; $i < $maxPuntaje; $i++) {
        $segmentoInicio = $inicioX + ($anchoBarra / $maxPuntaje) * $i;
        $segmentoAncho = $anchoBarra / $maxPuntaje;

        $xSub = $segmentoInicio + ($segmentoAncho / 2);
        $pdf->Line($xSub, $y, $xSub, $y + ($altoBarra / 2));
    }


     // Indicador de puntaje
    $posicion = $inicioX + ($puntaje / $maxPuntaje) * $anchoBarra;
    $pdf->SetLineWidth(1);
    $pdf->SetDrawColor(145, 0, 30);
    $pdf->Line($posicion, $y - 2, $posicion, $y + $altoBarra + 2);
    $pdf->SetLineWidth(0.2);



        $pdf->Ln(5);
    
/*
   [] => 17.50
            [] => 17.50
            [] => 17.50
            [] => 17.50
            [etica] => 17.50
            [evaluacion] => 17.50

*/
        // ------------------------------------------------
        // BLOQUE POR RUBROS
        // ------------------------------------------------
        $rubros = [
            'Planeacion'    => $datos['rubros']['planeacion'],
            'Saberes' => $datos['rubros']['saberes'],
            'Habilidades'    => $datos['rubros']['habilidades'],
            'Recursos'    => $datos['rubros']['recursos'],
            'Etica'    => $datos['rubros']['etica']
            
        ];

        foreach ($rubros as $nombreRubro => $valorRubro) {
            // Llama a la nueva función que incluye la regleta
            $regla = $this->determinarRegla($valorRubro);
            $this->bloqueModuloConRegleta(
                $pdf,
                $nombreRubro,
                number_format($valorRubro, 2),
                array('Puntaje' => $valorRubro, 'Regla' => $regla),
                $vinos
            );
        }
    }


    // ----------------------------------------------------
    // SALIDA PDF
    // ----------------------------------------------------


     

 $pdf->Output($nombre_archivo.'.pdf', 'I');
       
    }



    
public function reporte_planes()
{
    if (!$this->hasCreateAccess()) {
        $this->loadThis();
        return;
    }



$nombre_archivo="";


    $pdf = new PDF('P', 'mm', 'LETTER', true, 'UTF-8', false);
    $pdf->SetCreator('HLANDEROS');
    $pdf->SetAuthor('Hlanderos');
    $pdf->SetTitle('Reporte de Examen de Conocimientos 2025');
    $pdf->setPrintHeader(true);
    $pdf->setPrintFooter(true);
    $pdf->SetMargins(20, 20, 20);

    $vinos = array(110, 0, 20);

    $ies      = $this->input->post('ies');
    $sede     = $this->input->post('sede');
   
    // *** CAMBIO CLAVE #1: UNA SOLA CONSULTA A LA BD ***
    // Usamos nuestra nueva función optimizada
    $resultados_crudos = $this->em->get_all_resultados_for_planes($ies, $sede);
//cho '<pre>'; print_r( $todos_los_resultados_crudos); echo '</pre>'; die();
    // *** CAMBIO CLAVE #2: AGRUPAR DATOS EN PHP (en lugar de en la BD) ***
    // Transformamos el array plano en un array anidado por alumno
   
// ----------------------------------------------------
// AGRUPACIÓN POR EXAMEN Y SEDE (CORREGIDO)
// ----------------------------------------------------
$resultados_por_grupo = [];

foreach ($resultados_crudos as $fila) {
    // 1. CREAMOS UNA CLAVE ÚNICA concatenando examen y sede
    $clave_grupo = $fila['institucion'] . '_' . $fila['sede']. '_' . $fila['programa'];

    if (!isset($resultados_por_grupo[$clave_grupo])) {
        // Puedes ajustar cómo se genera el nombre del archivo si es necesario
        $nombre_archivo = $fila['institucion'] . '_' . $fila['sede']. '_' . $fila['programa'];

        $resultados_por_grupo[$clave_grupo] = [
            'ies'       => $fila['institucion'],
            'sede'      => $fila['sede'],
            'programa'  => $fila['programa'],
            'grado'    => $fila['grado'],
            'grupo'  => $fila['grupo'],
            'promedio'  => $fila['promedio'],
            
            'rubros'    => []
        ];
    }

    // 3. USAMOS LOS NOMBRES DE COLUMNA CORRECTOS de la consulta SQL
    $resultados_por_grupo[$clave_grupo]['rubros'] = [
        'planeacion'    => $fila['planeacion'],
        'saberes' => $fila['saberes'],
        'habilidades'    => $fila['habilidades'],
        'recursos'    => $fila['recursos'],
        'etica'    => $fila['etica']
    ];
}

// ----------------------------------------------------
// GENERACIÓN DE PDF POR GRUPO
// ----------------------------------------------------
// Renombramos la variable $nombre_alumno a $clave_grupo para mayor claridad
foreach ($resultados_por_grupo as $clave_grupo => $datos) {
    $pdf->AddPage();

    // ... (El resto de tu código para generar el PDF está perfecto y no necesita cambios)
    // ... ya que accedes a los datos a través del array $datos, que es correcto.

    // ------------------------------------------------
    // ENCABEZADO
    // ------------------------------------------------
    $pdf->SetFont('gothamblack', '', 11);
    $pdf->SetXY(0, 30);
    $pdf->writeHTML(
        'Resultados de Evaluación Docente  Semestre  Septiembre 2025 Enero 2026',
        false,
        false,
        false,
        '',
        'C'
    );
 

        // ------------------------------------------------
        // DATOS GENERALES
        // ------------------------------------------------
        $htmlDatos = "
            <b>Institución:</b> {$datos['ies']}<br>
            <b>Sede:</b> {$datos['sede']}<br>
            <b>Programa:</b> {$datos['programa']}<br>
          
        ";//nombre_docente

        $pdf->SetXY(22, 35);
        $pdf->SetFont('gothambook', '', 10);
        $pdf->writeHTML($htmlDatos, false, false, false, '');

        // ------------------------------------------------
        // PUNTAJE GENERAL
        // ------------------------------------------------

        $valorObtenido = number_format($datos['promedio'], 2);
        $puntaje = floatval($valorObtenido);

        $pdf->SetFont('gothamblack', '', 12);
        $pdf->SetFillColor($vinos[0], $vinos[1], $vinos[2]);
        $pdf->SetTextColor(255,255,255);
        $pdf->Cell(50, 10, 'Puntaje Obtenido', 0, 0, 'C', 1);

        $pdf->SetFont('gothamblack', '', 16);
        $pdf->SetTextColor(0,0,0);
        $pdf->Cell(30, 10, $valorObtenido, 0, 1, 'C');

        $pdf->Ln(6);

        // ------------------------------------------------
        // GRÁFICA DE BARRA (ESCALA 0–10)
        // ------------------------------------------------
        $inicioX = 20;
        $y = $pdf->GetY();

        $anchoBarra = 180;
        $altoBarra  = 10;

        $pdf->SetLineWidth(0.3);
        $pdf->SetDrawColor(110, 0, 20);
        $pdf->Rect($inicioX, $y, $anchoBarra, $altoBarra);

        // NUMERACIÓN DEL 0 AL 10
       $inicioX = 20;
    $anchoBarra = 180;
    $maxPuntaje = 18;
    $puntaje = max(0, min(floatval($puntaje), $maxPuntaje));

    // Contorno principal
    $pdf->SetDrawColor(110, 0, 15);
    $pdf->Rect($inicioX, $y, $anchoBarra, $altoBarra);

    // Escala principal 0–18
    $pdf->SetFont('gothambook', 'b', 8);
    for ($i = 0; $i <= $maxPuntaje; $i++) {
        $x = $inicioX + ($anchoBarra / $maxPuntaje) * $i;
        $pdf->Line($x, $y, $x, $y + $altoBarra);
        $pdf->Text($x - 2, $y + $altoBarra + 3, (string)$i);
    }

    // Subdivisiones (cada punto dividido en 2 = medios puntos)
    for ($i = 0; $i < $maxPuntaje; $i++) {
        $segmentoInicio = $inicioX + ($anchoBarra / $maxPuntaje) * $i;
        $segmentoAncho = $anchoBarra / $maxPuntaje;

        $xSub = $segmentoInicio + ($segmentoAncho / 2);
        $pdf->Line($xSub, $y, $xSub, $y + ($altoBarra / 2));
    }


     // Indicador de puntaje
    $posicion = $inicioX + ($puntaje / $maxPuntaje) * $anchoBarra;
    $pdf->SetLineWidth(1);
    $pdf->SetDrawColor(145, 0, 30);
    $pdf->Line($posicion, $y - 2, $posicion, $y + $altoBarra + 2);
    $pdf->SetLineWidth(0.2);



        $pdf->Ln(5);
    
/*
   [] => 17.50
            [] => 17.50
            [] => 17.50
            [] => 17.50
            [etica] => 17.50
            [evaluacion] => 17.50

*/
        // ------------------------------------------------
        // BLOQUE POR RUBROS
        // ------------------------------------------------
        $rubros = [
            'Planeacion'    => $datos['rubros']['planeacion'],
            'Saberes' => $datos['rubros']['saberes'],
            'Habilidades'    => $datos['rubros']['habilidades'],
            'Recursos'    => $datos['rubros']['recursos'],
            'Etica'    => $datos['rubros']['etica']
            
        ];

        foreach ($rubros as $nombreRubro => $valorRubro) {
            // Llama a la nueva función que incluye la regleta
            $regla = $this->determinarRegla($valorRubro);
            $this->bloqueModuloConRegleta(
                $pdf,
                $nombreRubro,
                number_format($valorRubro, 2),
                array('Puntaje' => $valorRubro, 'Regla' => $regla),
                $vinos
            );
        }
    }

    // ----------------------------------------------------
    // SALIDA PDF
    // ----------------------------------------------------


    $pdf->Output($nombre_archivo.'.pdf', 'I');
}
public function reporte()
{
    if (!$this->hasCreateAccess()) {
        $this->loadThis();
        return;
    }

    $nombre_archivo = "";

    // ----------------------------------------------------
    // CONFIGURACIÓN PDF
    // ----------------------------------------------------
    $pdf = new PDF('P', 'mm', 'LETTER', true, 'UTF-8', false);
    $pdf->SetCreator('HLANDEROS');
    $pdf->SetAuthor('HLANDEROS');
    $pdf->SetTitle('Reporte de Examen de Inglés 2025');
    $pdf->setPrintHeader(true);
    $pdf->setPrintFooter(true);
    $pdf->SetMargins(20, 20, 20);

    $vinos = [110, 0, 20];

    // ----------------------------------------------------
    // DATOS POST
    // ----------------------------------------------------
    $ies      = $this->input->post('ies');
    $sede     = $this->input->post('sede');
    $programa = $this->input->post('programa');

    // ----------------------------------------------------
    // CONSULTA ÚNICA A BD (OPTIMIZADA)
    // ----------------------------------------------------
    $resultados_crudos = $this->em->get_all_resultados_for_report(
        $ies,
        $sede,
        $programa
    );


    // ----------------------------------------------------
    // AGRUPACIÓN POR ALUMNO
    // ----------------------------------------------------
   // He renombrado la variable a $resultados_por_grupo para que sea más descriptiva
 $resultados_por_grupo = [];

foreach ($resultados_crudos as $fila) {
    // 1. CREAMOS UNA CLAVE ÚNICA concatenando examen y sede
    $clave_grupo = $fila['nombre_docente'] . '_' . $fila['asignatura']. '_' . $fila['grupo'];

    if (!isset($resultados_por_grupo[$clave_grupo])) {
        // Puedes ajustar cómo se genera el nombre del archivo si es necesario
        $nombre_archivo = $fila['institucion'] . '_' . $fila['sede']. '_' . $fila['programa'];

        $resultados_por_grupo[$clave_grupo] = [
            'ies'       => $fila['institucion'],
            'sede'      => $fila['sede'],
            'programa'  => $fila['programa'],
            'grado'    => $fila['grado'],
            'grupo'  => $fila['grupo'],
            'promedio'  => $fila['promedio'],
            'asignatura' => $fila['asignatura'],
            'nombre_docente' => $fila['nombre_docente'],
            'rubros'    => []
        ];
    }

    // 3. USAMOS LOS NOMBRES DE COLUMNA CORRECTOS de la consulta SQL
    $resultados_por_grupo[$clave_grupo]['rubros'] = [
        'planeacion'    => $fila['planeacion'],
        'saberes' => $fila['saberes'],
        'habilidades'    => $fila['habilidades'],
        'recursos'    => $fila['recursos'],
        'etica'    => $fila['etica']
    ];
}

// ----------------------------------------------------
// GENERACIÓN DE PDF POR GRUPO
// ----------------------------------------------------
// Renombramos la variable $nombre_alumno a $clave_grupo para mayor claridad
foreach ($resultados_por_grupo as $clave_grupo => $datos) {
    $pdf->AddPage();

    // ... (El resto de tu código para generar el PDF está perfecto y no necesita cambios)
    // ... ya que accedes a los datos a través del array $datos, que es correcto.

    // ------------------------------------------------
    // ENCABEZADO
    // ------------------------------------------------
    $pdf->SetFont('gothamblack', '', 11);
    $pdf->SetXY(0, 30);
    $pdf->writeHTML(
        'Resultados de Evaluación Docente  Semestre  Septiembre 2025 Enero 2026',
        false,
        false,
        false,
        '',
        'C'
    );
 

        // ------------------------------------------------
        // DATOS GENERALES
        // ------------------------------------------------
        $htmlDatos = "
            <b>Institución:</b> {$datos['ies']}<br>
            <b>Sede:</b> {$datos['sede']}<br>
            <b>Programa:</b> {$datos['programa']}<br>
            <b>Asignatura:</b> {$datos['asignatura']}<br>
            <b>Docente:</b> {$datos['nombre_docente']}<br>
            
        ";//nombre_docente

        $pdf->SetXY(22, 35);
        $pdf->SetFont('gothambook', '', 10);
        $pdf->writeHTML($htmlDatos, false, false, false, '');

        // ------------------------------------------------
        // PUNTAJE GENERAL
        // ------------------------------------------------

        $valorObtenido = number_format($datos['promedio'], 2);
        $puntaje = floatval($valorObtenido);

        $pdf->SetFont('gothamblack', '', 12);
        $pdf->SetFillColor($vinos[0], $vinos[1], $vinos[2]);
        $pdf->SetTextColor(255,255,255);
        $pdf->Cell(50, 10, 'Puntaje Obtenido', 0, 0, 'C', 1);

        $pdf->SetFont('gothamblack', '', 16);
        $pdf->SetTextColor(0,0,0);
        $pdf->Cell(30, 10, $valorObtenido, 0, 1, 'C');

        $pdf->Ln(6);

        // ------------------------------------------------
        // GRÁFICA DE BARRA (ESCALA 0–10)
        // ------------------------------------------------
        $inicioX = 20;
        $y = $pdf->GetY();

        $anchoBarra = 180;
        $altoBarra  = 10;

        $pdf->SetLineWidth(0.3);
        $pdf->SetDrawColor(110, 0, 20);
        $pdf->Rect($inicioX, $y, $anchoBarra, $altoBarra);

        // NUMERACIÓN DEL 0 AL 10
       $inicioX = 20;
    $anchoBarra = 180;
    $maxPuntaje = 18;
    $puntaje = max(0, min(floatval($puntaje), $maxPuntaje));

    // Contorno principal
    $pdf->SetDrawColor(110, 0, 15);
    $pdf->Rect($inicioX, $y, $anchoBarra, $altoBarra);

    // Escala principal 0–18
    $pdf->SetFont('gothambook', 'b', 8);
    for ($i = 0; $i <= $maxPuntaje; $i++) {
        $x = $inicioX + ($anchoBarra / $maxPuntaje) * $i;
        $pdf->Line($x, $y, $x, $y + $altoBarra);
        $pdf->Text($x - 2, $y + $altoBarra + 3, (string)$i);
    }

    // Subdivisiones (cada punto dividido en 2 = medios puntos)
    for ($i = 0; $i < $maxPuntaje; $i++) {
        $segmentoInicio = $inicioX + ($anchoBarra / $maxPuntaje) * $i;
        $segmentoAncho = $anchoBarra / $maxPuntaje;

        $xSub = $segmentoInicio + ($segmentoAncho / 2);
        $pdf->Line($xSub, $y, $xSub, $y + ($altoBarra / 2));
    }


     // Indicador de puntaje
    $posicion = $inicioX + ($puntaje / $maxPuntaje) * $anchoBarra;
    $pdf->SetLineWidth(1);
    $pdf->SetDrawColor(145, 0, 30);
    $pdf->Line($posicion, $y - 2, $posicion, $y + $altoBarra + 2);
    $pdf->SetLineWidth(0.2);



        $pdf->Ln(5);
    
/*
   [] => 17.50
            [] => 17.50
            [] => 17.50
            [] => 17.50
            [etica] => 17.50
            [evaluacion] => 17.50

*/
        // ------------------------------------------------
        // BLOQUE POR RUBROS
        // ------------------------------------------------
        $rubros = [
            'Planeacion'    => $datos['rubros']['planeacion'],
            'Saberes' => $datos['rubros']['saberes'],
            'Habilidades'    => $datos['rubros']['habilidades'],
            'Recursos'    => $datos['rubros']['recursos'],
            'Etica'    => $datos['rubros']['etica']
            
        ];

        foreach ($rubros as $nombreRubro => $valorRubro) {
            // Llama a la nueva función que incluye la regleta
            $regla = $this->determinarRegla($valorRubro);
            $this->bloqueModuloConRegleta(
                $pdf,
                $nombreRubro,
                number_format($valorRubro, 2),
                array('Puntaje' => $valorRubro, 'Regla' => $regla),
                $vinos
            );
        }
    }

    // ----------------------------------------------------
    // SALIDA PDF
    // ----------------------------------------------------
    $pdf->Output($nombre_archivo.'.pdf', 'I');
}
public function reporte_sedes(){
         if (!$this->hasCreateAccess()) {
        $this->loadThis();
        return;
    }



$nombre_archivo="";


    $pdf = new PDF('P', 'mm', 'LETTER', true, 'UTF-8', false);
    $pdf->SetCreator('HLANDEROS');
    $pdf->SetAuthor('Hlanderos');
    $pdf->SetTitle('Resultados del Examen de Inglés  Semestre Septiembre 2025 Enero 2026');
    $pdf->setPrintHeader(true);
    $pdf->setPrintFooter(true);
    $pdf->SetMargins(20, 20, 20);

    $vinos = array(110, 0, 20);

    $ies      = $this->input->post('ies');
    $sede     = $this->input->post('sede');
   
    // *** CAMBIO CLAVE #1: UNA SOLA CONSULTA A LA BD ***
    // Usamos nuestra nueva función optimizada
    $resultados_crudos = $this->em->get_all_resultados_for_sedes($ies, $sede);



//echo '<pre>'; print_r( $todos_los_resultados_crudos); echo '</pre>'; die();
    

// ----------------------------------------------------
// AGRUPACIÓN POR EXAMEN Y SEDE (CORREGIDO)
// ----------------------------------------------------
// He renombrado la variable a $resultados_por_grupo para que sea más descriptiva
 $resultados_por_grupo = [];

foreach ($resultados_crudos as $fila) {
    // 1. CREAMOS UNA CLAVE ÚNICA concatenando examen y sede
    $clave_grupo = $fila['institucion'] . '_' . $fila['sede'];

    if (!isset($resultados_por_grupo[$clave_grupo])) {
        // Puedes ajustar cómo se genera el nombre del archivo si es necesario
        $nombre_archivo = $fila['institucion'] . '_' . $fila['sede'];

        $resultados_por_grupo[$clave_grupo] = [
            'ies'       => $fila['institucion'],
            'sede'      => $fila['sede'],
          
            'grado'    => $fila['grado'],
            'grupo'  => $fila['grupo'],
            'promedio'  => $fila['promedio'],
            
            'rubros'    => []
        ];
    }

    // 3. USAMOS LOS NOMBRES DE COLUMNA CORRECTOS de la consulta SQL
    $resultados_por_grupo[$clave_grupo]['rubros'] = [
        'planeacion'    => $fila['planeacion'],
        'saberes' => $fila['saberes'],
        'habilidades'    => $fila['habilidades'],
        'recursos'    => $fila['recursos'],
        'etica'    => $fila['etica']
    ];
}

// ----------------------------------------------------
// GENERACIÓN DE PDF POR GRUPO
// ----------------------------------------------------
// Renombramos la variable $nombre_alumno a $clave_grupo para mayor claridad
foreach ($resultados_por_grupo as $clave_grupo => $datos) {
    $pdf->AddPage();

    // ... (El resto de tu código para generar el PDF está perfecto y no necesita cambios)
    // ... ya que accedes a los datos a través del array $datos, que es correcto.

    // ------------------------------------------------
    // ENCABEZADO
    // ------------------------------------------------
    $pdf->SetFont('gothamblack', '', 11);
    $pdf->SetXY(0, 30);
    $pdf->writeHTML(
        'Resultados de Evaluación Docente  Semestre  Septiembre 2025 Enero 2026',
        false,
        false,
        false,
        '',
        'C'
    );
 

        // ------------------------------------------------
        // DATOS GENERALES
        // ------------------------------------------------
        $htmlDatos = "
            <b>Institución:</b> {$datos['ies']}<br>
            <b>Sede:</b> {$datos['sede']}<br>
         
          
        ";//nombre_docente

        $pdf->SetXY(22, 35);
        $pdf->SetFont('gothambook', '', 10);
        $pdf->writeHTML($htmlDatos, false, false, false, '');

        // ------------------------------------------------
        // PUNTAJE GENERAL
        // ------------------------------------------------

        $valorObtenido = number_format($datos['promedio'], 2);
        $puntaje = floatval($valorObtenido);

        $pdf->SetFont('gothamblack', '', 12);
        $pdf->SetFillColor($vinos[0], $vinos[1], $vinos[2]);
        $pdf->SetTextColor(255,255,255);
        $pdf->Cell(50, 10, 'Puntaje Obtenido', 0, 0, 'C', 1);

        $pdf->SetFont('gothamblack', '', 16);
        $pdf->SetTextColor(0,0,0);
        $pdf->Cell(30, 10, $valorObtenido, 0, 1, 'C');

        $pdf->Ln(6);

        // ------------------------------------------------
        // GRÁFICA DE BARRA (ESCALA 0–10)
        // ------------------------------------------------
        $inicioX = 20;
        $y = $pdf->GetY();

        $anchoBarra = 180;
        $altoBarra  = 10;

        $pdf->SetLineWidth(0.3);
        $pdf->SetDrawColor(110, 0, 20);
        $pdf->Rect($inicioX, $y, $anchoBarra, $altoBarra);

        // NUMERACIÓN DEL 0 AL 10
       $inicioX = 20;
    $anchoBarra = 180;
    $maxPuntaje = 18;
    $puntaje = max(0, min(floatval($puntaje), $maxPuntaje));

    // Contorno principal
    $pdf->SetDrawColor(110, 0, 15);
    $pdf->Rect($inicioX, $y, $anchoBarra, $altoBarra);

    // Escala principal 0–18
    $pdf->SetFont('gothambook', 'b', 8);
    for ($i = 0; $i <= $maxPuntaje; $i++) {
        $x = $inicioX + ($anchoBarra / $maxPuntaje) * $i;
        $pdf->Line($x, $y, $x, $y + $altoBarra);
        $pdf->Text($x - 2, $y + $altoBarra + 3, (string)$i);
    }

    // Subdivisiones (cada punto dividido en 2 = medios puntos)
    for ($i = 0; $i < $maxPuntaje; $i++) {
        $segmentoInicio = $inicioX + ($anchoBarra / $maxPuntaje) * $i;
        $segmentoAncho = $anchoBarra / $maxPuntaje;

        $xSub = $segmentoInicio + ($segmentoAncho / 2);
        $pdf->Line($xSub, $y, $xSub, $y + ($altoBarra / 2));
    }


     // Indicador de puntaje
    $posicion = $inicioX + ($puntaje / $maxPuntaje) * $anchoBarra;
    $pdf->SetLineWidth(1);
    $pdf->SetDrawColor(145, 0, 30);
    $pdf->Line($posicion, $y - 2, $posicion, $y + $altoBarra + 2);
    $pdf->SetLineWidth(0.2);



        $pdf->Ln(5);
    
/*
   [] => 17.50
            [] => 17.50
            [] => 17.50
            [] => 17.50
            [etica] => 17.50
            [evaluacion] => 17.50

*/
        // ------------------------------------------------
        // BLOQUE POR RUBROS
        // ------------------------------------------------
        $rubros = [
            'Planeacion'    => $datos['rubros']['planeacion'],
            'Saberes' => $datos['rubros']['saberes'],
            'Habilidades'    => $datos['rubros']['habilidades'],
            'Recursos'    => $datos['rubros']['recursos'],
            'Etica'    => $datos['rubros']['etica']
            
        ];

        foreach ($rubros as $nombreRubro => $valorRubro) {
            // Llama a la nueva función que incluye la regleta
            $regla = $this->determinarRegla($valorRubro);
            $this->bloqueModuloConRegleta(
                $pdf,
                $nombreRubro,
                number_format($valorRubro, 2),
                array('Puntaje' => $valorRubro, 'Regla' => $regla),
                $vinos
            );
        }
    }


    // ----------------------------------------------------
    // SALIDA PDF
    // ----------------------------------------------------

    $pdf->Output($nombre_archivo.'.pdf', 'I');
        
    }
/**
 * Determina la regla de evaluación según el puntaje obtenido
 * @param float $puntaje Puntaje del rubro
 * @return array Descripción de la regla y color asociado
 */
function determinarRegla($puntaje) {
    $puntaje = floatval($puntaje);
    
    if ($puntaje >= 9.0) {
        return ["texto" => "Excelente", "color" => [0, 128, 0]]; // Verde
    } elseif ($puntaje >= 7.5) {
        return ["texto" => "Notable", "color" => [0, 0, 255]]; // Azul
    } elseif ($puntaje >= 6.0) {
        return ["texto" => "Aprobado", "color" => [255, 165, 0]]; // Naranja
    } else {
        return ["texto" => "Necesita mejorar", "color" => [255, 0, 0]]; // Rojo
    }
}

/**
 * Dibuja una regleta (gráfico de barras) con escala de 0 a 10.
 * @param TCPDF $pdf Instancia del PDF.
 * @param float $y Posición vertical Y donde comenzará a dibujarse la regleta.
 * @param float $puntaje El puntaje a marcar en la regleta (ej: 8.53).
 * @param int $altoBarra Altura de la barra del gráfico.
 */
private function dibujarRegletaPuntaje($pdf, $y, $puntaje, $altoBarra = 15)
{
    $inicioX = 20;
    $anchoBarra = 180;
    $maxPuntaje = 18;
    $puntaje = max(0, min(floatval($puntaje), $maxPuntaje));

    // Contorno principal
    $pdf->SetDrawColor(110, 0, 15);
    $pdf->Rect($inicioX, $y, $anchoBarra, $altoBarra);

    // Escala principal 0–18
    $pdf->SetFont('gothambook', 'b', 8);
    for ($i = 0; $i <= $maxPuntaje; $i++) {
        $x = $inicioX + ($anchoBarra / $maxPuntaje) * $i;
        $pdf->Line($x, $y, $x, $y + $altoBarra);
        $pdf->Text($x - 2, $y + $altoBarra + 3, (string)$i);
    }

    // Subdivisiones (cada punto dividido en 2 = medios puntos)
    for ($i = 0; $i < $maxPuntaje; $i++) {
        $segmentoInicio = $inicioX + ($anchoBarra / $maxPuntaje) * $i;
        $segmentoAncho = $anchoBarra / $maxPuntaje;

        $xSub = $segmentoInicio + ($segmentoAncho / 2);
        $pdf->Line($xSub, $y, $xSub, $y + ($altoBarra / 2));
    }

    // Indicador de puntaje
    $posicion = $inicioX + ($puntaje / $maxPuntaje) * $anchoBarra;
    $pdf->SetLineWidth(1);
    $pdf->SetDrawColor(145, 0, 30);
    $pdf->Line($posicion, $y - 2, $posicion, $y + $altoBarra + 2);
    $pdf->SetLineWidth(0.2);
}


function bloqueModuloConRegleta($pdf, $titulo, $puntaje, $valor, $colorTitulo)
{
    // --- Título del Rubro ---
    $pdf->SetFillColor($colorTitulo[0], $colorTitulo[1], $colorTitulo[2]);
    $pdf->SetTextColor(255, 255, 255);
    $pdf->SetFont('gothambook', 'B', 12);

    // Obtenemos el texto y el color de la regla
    $textoRegla = $valor['Regla']['texto'];
    $colorRegla = $valor['Regla']['color'];

    // Mostramos el título con el puntaje
    $pdf->Cell(180, 5, $titulo. " ".$valor['Puntaje'], 0, 1, 'L', 1);
    
    // Mostramos la regla con su color correspondiente
    $pdf->SetTextColor($colorRegla[0], $colorRegla[1], $colorRegla[2]);
    $pdf->SetFont('gothambook', 'B', 10);
    $pdf->Cell(180, 5, $textoRegla, 0, 1, 'L', 0);
    
    // Restablecemos el color del texto
    $pdf->SetTextColor(0, 0, 0);

    // --- Dibujo de la Regleta ---
    // Obtenemos la posición Y actual para dibujar la regleta justo debajo del título
    $y_regleta = $pdf->GetY();

    // Llama a nuestra función auxiliar para dibujar la regleta
    $this->dibujarRegletaPuntaje($pdf, $y_regleta, $puntaje);

    // Añade un espacio vertical después de la regleta para que no se amontone con el siguiente bloque
    $pdf->Ln(7);
}

    public function get_sedes()
    {
        if(!$this->hasCreateAccess())
        {
            $this->loadThis();
        }
        else
        {
            $cve_ies = $this->input->post('cve_ies');
            $sedes = $this->em->get_sedes_by_ies($cve_ies);
            echo json_encode($sedes);
        }
    }


    public function get_programas()
    {
        if(!$this->hasCreateAccess())
        {
            $this->loadThis();
        }
        else
        {
            $cve_ies  = $this->input->post('cve_ies');
            $cve_sede = $this->input->post('cve_sede');

            $programas = $this->em->get_programas_by_sede($cve_ies, $cve_sede);
            echo json_encode($programas);
        }
    }


    public function planes()
    {
        if(!$this->hasCreateAccess())
        { 
            $this->loadThis();
        }
        else
        {
            $ies = $this->em->ies();
            $options1 = array();
            foreach ($ies as $ie) {   
                $options1[$ie['cve_ies']] = $ie['ies'];
        }
            $ies = array('' => 'Seleccione') + $options1;

            $this->data['ies'] = array(
                'name'  => 'ies',
                'id'    => 'ies',
                'class' => 'form-control',
                'options' => $ies,
                'value' => $this->form_validation->set_value('ies'),
            );

            $this->data['sede'] = array(
                'name'  => 'sede',
                'id'    => 'sede',
                'class' => 'form-control',
                'options' => array('' => 'Seleccione'),
            );

          

            $this->global['pageTitle'] = 'SEIEM : Reporte de Resultados por Licenciatura Examen 2025';

            $this->loadViews("docente/planes", $this->global, $this->data, NULL);
        }
    }
}


?>
