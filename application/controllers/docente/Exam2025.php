<?php
if(!defined('BASEPATH')) exit('No direct script access allowed');

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

private function distribucionPorRangos($datos)
{
    $rangos = [
        'Reprobado (0 - 6.9)'   => 0,
        'Suficiente (7 - 8.9)' => 0,
        'Excelente (9 - 10)'   => 0
    ];

    $gruposProcesados = [];

    foreach ($datos as $r) {

        if (!isset($r['grado'], $r['grupo'], $r['promedio_general'], $r['total_alumnos'])) {
            continue;
        }

        // 🔑 Clave única por grupo
        $claveGrupo = $r['grado'].'-'.$r['grupo'];

        // ❌ Evitar duplicar el mismo grupo por asignatura
        if (isset($gruposProcesados[$claveGrupo])) {
            continue;
        }

        $gruposProcesados[$claveGrupo] = true;

        $promedio = (float)$r['promedio_general'];
        $alumnos  = (int)$r['total_alumnos'];

        if ($promedio < 7) {
            $rangos['Reprobado (0 - 6.9)'] += $alumnos;
        } elseif ($promedio < 9) {
            $rangos['Suficiente (7 - 8.9)'] += $alumnos;
        } else {
            $rangos['Excelente (9 - 10)'] += $alumnos;
        }
    }

    return $rangos;
}



    private function agruparPorSemestreGrupo($datos)
    {
        $resultado = [];

        foreach ($datos as $r) {
            $key = $r['grado'] . '|' . $r['grupo'];

            if (!isset($resultado[$key])) {
                $resultado[$key] = [
                    'grado' => $r['grado'],
                    'grupo' => $r['grupo'],
                    'institucion' => $r['institucion'],
                    'sede' => $r['sede'],
                    'programa' => $r['programa'],
                    'total_alumnos' => $r['total_alumnos'],
                    'aprobados' => $r['aprobados'],
                    'reprobados' => $r['reprobados'],
                    'asignaturas' => []
                ];
            }

            $resultado[$key]['asignaturas'][] = $r;
        }

        return $resultado;
    }

    private function colorPromedio($valor)
    {
        if ($valor < 7) return '#f8d7da';      // rojo claro
        if ($valor < 9) return '#fff3cd';      // amarillo
        return '#d4edda';                      // verde
    }

  private function graficaBarrasTCPDF($pdf, $data)
{
    // Si no cabe, nueva página
    if ($pdf->GetY() > 200) {
        $pdf->AddPage();
        $pdf->SetXY(22, 30);
    }

    $x = 30;
    $y = $pdf->GetY() + 10; // 👈 toma posición real

    $max = max($data);
    $barWidth = 25;
    $gap = 20;
    $scale = 60 / max($max, 1);

    $i = 0;
    foreach ($data as $label => $value) {

        $height = $value * $scale;

        $pdf->SetFillColor(0, 102, 153);
        $pdf->Rect(
            $x + ($i * ($barWidth + $gap)),
            $y + 60 - $height,
            $barWidth,
            $height,
            'F'
        );

        // Valor
        $pdf->SetXY($x + ($i * ($barWidth + $gap)), $y + 60 - $height - 6);
        $pdf->SetFont('gothambook', '', 9);
        $pdf->MultiCell($barWidth, 5, $value, 0, 'C');

        // Etiqueta
        $pdf->SetXY($x + ($i * ($barWidth + $gap)), $y + 62);
        $pdf->SetFont('gothambook', '', 8);
        $pdf->MultiCell($barWidth, 5, $label, 0, 'C');

        $i++;
    }

    // Reservamos espacio después de la gráfica
    $pdf->Ln(80);
}

    private function generarTablaGrupo($grupo)
    {
        $html = '
        <h3>Semestre ' . $grupo['grado'] . ' Grupo ' . $grupo['grupo'] . '</h3>
        <p>
            <strong>Programa:</strong> ' . $grupo['programa'] . '<br>
            <strong>IES:</strong> ' . $grupo['institucion'] . '<br>
            <strong>Sede:</strong> ' . $grupo['sede'] . '<br>
            <strong>Total de alumnos que evaluaron:</strong> ' . $grupo['total_alumnos'] . '<br>
            <strong>Alumnos que les gusto la asignatura:</strong> ' . $grupo['aprobados'] . ' |
            <strong>Alumnos que <b>NO</b> les gusto alguna asignatura:</strong> ' . $grupo['reprobados'] . '
        </p>

     <table border="1" cellpadding="4" cellspacing="0" width="100%" style="table-layout:fixed;">
<thead style="background-color:#f2f2f2;font-weight:bold;">
<tr>
    <th width="22%">Asignatura</th>
    <th width="22%">Docente</th>
    <th width="8%" align="center">Plan.</th>
    <th width="8%" align="center">Sab.</th>
    <th width="8%" align="center">Hab.</th>
    <th width="8%" align="center">Rec.</th>
    <th width="8%" align="center">Eti.</th>
    <th width="8%" align="center">Eval.</th>
    <th width="8%" align="center">Prom.</th>
</tr>
</thead>
<tbody>
        ';

        foreach ($grupo['asignaturas'] as $a) {
            $color = $this->colorPromedio($a['promedio_general']);

            $html .= '
           <tr style="background-color: ' . $color . ';">
                <td width="22%">' . htmlspecialchars($a['asignatura']) . '</td>
                <td width="22%">' . htmlspecialchars($a['nombre_docente']) . '</td>
                <td width="8%" align="center">' . $a['planeacion'] . '</td>
                <td width="8%" align="center">' . $a['saberes'] . '</td>
                <td width="8%" align="center">' . $a['habilidades'] . '</td>
                <td width="8%" align="center">' . $a['recursos'] . '</td>
                <td width="8%" align="center">' . $a['etica_y_valores'] . '</td>
                <td width="8%" align="center">' . $a['evaluacion'] . '</td>
                <td width="8%" align="center"><strong>' . $a['promedio_general'] . '</strong></td>
            </tr>';
        }

        $html .= '</tbody></table><br>';

        return $html;
    }

   public function reporte_estadistico()
{
    if (!$this->hasCreateAccess()) {
        $this->loadThis();
        return;
    }

    // ----------------------------------------------------
    // CONFIGURACIÓN PDF
    // ----------------------------------------------------
    $pdf = new PDF('P', 'mm', 'LETTER', true, 'UTF-8', false);
    $pdf->SetCreator('HLANDEROS');
    $pdf->SetAuthor('HLANDEROS');
    $pdf->SetTitle('Reporte de Examen de Evaluación Docente 2025');
    $pdf->setPrintHeader(true);
    $pdf->setPrintFooter(true);
  $pdf->SetMargins(20, 32, 20); // ⬅️ antes era 20
$pdf->SetAutoPageBreak(true, 30);

    // ----------------------------------------------------
    // DATOS POST
    // ----------------------------------------------------
    $ies      = $this->input->post('ies');
    $sede     = $this->input->post('sede');
    $programa = $this->input->post('programa');

    // ----------------------------------------------------
    // CONSULTA BD
    // ----------------------------------------------------
    $datos = $this->em->get_all_resultados_for_planes_esta($ies, $sede, $programa);
    $grupos = $this->agruparPorSemestreGrupo($datos);

    // ----------------------------------------------------
    // PORTADA / RESUMEN GENERAL
    // ----------------------------------------------------
    $leyendas = $datos[0];
    $titulo = $leyendas['institucion'] . ' ' .
        (empty($leyendas['sede']) ? '' : $leyendas['sede'] . ' - ') .
        (empty($leyendas['programa']) ? '' : $leyendas['programa']);

    $pdf->AddPage();
    $pdf->Bookmark('RESUMEN GENERAL DE ' . $titulo, 0);

    $rangos = $this->distribucionPorRangos($datos);

    $html = '<h4>RESUMEN GENERAL DE ' . $titulo . '</h4>';
    $html .= $this->tablaDistribucion($rangos);

    $pdf->SetXY(20, 30);
    $pdf->writeHTML($html, true, false, true, false, '');

    // Gráfica
    $this->graficaBarrasTCPDF($pdf, $rangos);

  
    $pdf->SetFont('gothambook', '', 12);
    $pdf->writeHTML('
        <h4 align="center" style="font-weight:bold;">
            Reporte Estadístico de Evaluación Docente
        </h4>', true, false, true, false, '');

    // ----------------------------------------------------
    // NUMERACIÓN POR SEMESTRE
    // ----------------------------------------------------
    $semestreActual = null;
    $numSemestre = 0;
    $numGrupo = 0;


    foreach ($grupos as $grupo) {

        // Detectar cambio de semestre
        if ($semestreActual !== $grupo['grado']) {
            $semestreActual = $grupo['grado'];
            $numSemestre++;
            $numGrupo = 1;
        } else {
            $numGrupo++;
        }

        // Numeración tipo 1.1, 1.2, 2.1 ...
        $numeracion = $numSemestre . '.' . $numGrupo;

        // ------------------------------------------------
        // NUEVA PÁGINA POR CADA GRUPO
        // ------------------------------------------------
      $pdf->AddPage();
$pdf->SetXY(20, 20);   // ⬅️ antes 35
$pdf->setPageMark();


        // Bookmark correcto
        $pdf->Bookmark(
            $numeracion . ' Semestre ' . $grupo['grado'] . ' Grupo ' . $grupo['grupo'],
            1
        );

        // Título visible del grupo
        $pdf->SetFont('gothamblack', '', 11);
        $pdf->MultiCell(
            0,
            8,
            $numeracion . '  SEMESTRE ' . $grupo['grado'] . '  |  GRUPO ' . $grupo['grupo'],
            0,
            'L'
        );

        $pdf->Ln(2);

        // Tabla del grupo
        $pdf->SetFont('gothambook', '', 11);
        $html_grupo = $this->generarTablaGrupo($grupo);
        $pdf->writeHTML($html_grupo, true, false, true, false, '');
    }

    // ----------------------------------------------------
    // ÍNDICE
    // ----------------------------------------------------
    $pdf->addTOCPage();
    $pdf->Ln(2);
    $pdf->SetFont('gothamblack', '', 14);
    $pdf->MultiCell(0, 10, 'ÍNDICE', 0, 'C');
  

    $pdf->SetFont('gothambook', '', 10);
    $pdf->addTOC(
        2,
        'gothambook',
        '.',
        'Índice',
        'B',
        [0, 0, 0]
    );

    $pdf->endTOCPage();

    // ----------------------------------------------------
    // SALIDA
    // ----------------------------------------------------
    $pdf->Output('reporte_estadistico.pdf', 'I');
}

private function tablaDistribucion($rangos)
{
    $html = '
    <h3>Distribución de Resultados por Rango</h3>

    <table border="1" cellpadding="5" cellspacing="0" width="100%" style="table-layout:fixed;">
        <thead style="background-color:#003366;color:white;">
            <tr>
                <th width="70%" align="left">Rango de Calificación</th>
                <th width="30%" align="center">Cantidad de Alumnos</th>
            </tr>
        </thead>
        <tbody>';

    foreach ($rangos as $rango => $total) {
        $html .= '
        <tr>
            <td width="70%" align="left" style="word-wrap:break-word;">
                ' . htmlspecialchars($rango) . '
            </td>
            <td width="30%" align="center">
                <strong>' . (int)$total . '</strong>
            </td>
        </tr>';
    }

    $html .= '
        </tbody>
    </table>
    <br>';

    return $html;
}
    public function botonIES()
    {   
        $this->global['pageTitle'] = 'SEIEM : Reporte de Resultados de Evaluación Docente Semestre Septiembre 2025 Enero 2026';
        $this->loadViews("docente/ies", $this->global, NULL);
    }
    
    public function reporte_ies()
    {
        if (!$this->hasCreateAccess()) {
            $this->loadThis();
            return;
        }

        $nombre_archivo = "";

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

        $resultados_por_grupo = [];

        foreach ($resultados_crudos as $fila) {
            $clave_grupo = $fila['institucion'];

            if (!isset($resultados_por_grupo[$clave_grupo])) {
                $nombre_archivo = $fila['institucion'];

                $resultados_por_grupo[$clave_grupo] = [
                    'ies'       => $fila['institucion'],
                    'promedio'  => $fila['promedio'],
                    'rubros'    => []
                ];
            }

            $resultados_por_grupo[$clave_grupo]['rubros'] = [
                'planeacion'    => $fila['planeacion'],
                'saberes' => $fila['saberes'],
                'habilidades'    => $fila['habilidades'],
                'recursos'    => $fila['recursos'],
                'etica'    => $fila['etica_y_valores'],
                'evaluacion' => $fila['evaluacion']
            ];
        }

        foreach ($resultados_por_grupo as $clave_grupo => $datos) {
            $pdf->AddPage();

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

            $htmlDatos = "
                <b>Institución:</b> {$datos['ies']}<br>
                <br><br>
            ";

            $pdf->SetXY(22, 40);
            $pdf->SetFont('gothambook', '', 10);
            $pdf->writeHTML($htmlDatos, false, false, false, '');

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

            $inicioX = 20;
            $y = $pdf->GetY();

            $anchoBarra = 180;
            $altoBarra  = 10;

            $pdf->SetLineWidth(0.3);
            $pdf->SetDrawColor(110, 0, 20);
            $pdf->Rect($inicioX, $y, $anchoBarra, $altoBarra);

            $inicioX = 20;
            $anchoBarra = 180;
            $maxPuntaje = 18;
            $puntaje = max(0, min(floatval($puntaje), $maxPuntaje));

            $pdf->SetDrawColor(110, 0, 15);
            $pdf->Rect($inicioX, $y, $anchoBarra, $altoBarra);

            $pdf->SetFont('gothambook', 'b', 8);
            for ($i = 0; $i <= $maxPuntaje; $i++) {
                $x = $inicioX + ($anchoBarra / $maxPuntaje) * $i;
                $pdf->Line($x, $y, $x, $y + $altoBarra);
                $pdf->Text($x - 2, $y + $altoBarra + 3, (string)$i);
            }

            for ($i = 0; $i < $maxPuntaje; $i++) {
                $segmentoInicio = $inicioX + ($anchoBarra / $maxPuntaje) * $i;
                $segmentoAncho = $anchoBarra / $maxPuntaje;

                $xSub = $segmentoInicio + ($segmentoAncho / 2);
                $pdf->Line($xSub, $y, $xSub, $y + ($altoBarra / 2));
            }

            $posicion = $inicioX + ($puntaje / $maxPuntaje) * $anchoBarra;
            $pdf->SetLineWidth(1);
            $pdf->SetDrawColor(145, 0, 30);
            $pdf->Line($posicion, $y - 2, $posicion, $y + $altoBarra + 2);
            $pdf->SetLineWidth(0.2);

            $pdf->Ln(5);

            $rubros = [
                'Planeacion'    => $datos['rubros']['planeacion'],
                'Saberes' => $datos['rubros']['saberes'],
                'Habilidades'    => $datos['rubros']['habilidades'],
                'Recursos'    => $datos['rubros']['recursos'],
                'Etica'    => $datos['rubros']['etica']
            ];

            foreach ($rubros as $nombreRubro => $valorRubro) {
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

        $pdf->Output($nombre_archivo.'.pdf', 'I');
    }

    public function reporte_planes()
    {
        if (!$this->hasCreateAccess()) {
            $this->loadThis();
            return;
        }

        $nombre_archivo = "";

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
       
        $resultados_crudos = $this->em->get_all_resultados_for_planes($ies, $sede);

        $resultados_por_grupo = [];

        foreach ($resultados_crudos as $fila) {
            $clave_grupo = $fila['institucion'] . '_' . $fila['sede']. '_' . $fila['programa'];

            if (!isset($resultados_por_grupo[$clave_grupo])) {
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

            $resultados_por_grupo[$clave_grupo]['rubros'] = [
                'planeacion'    => $fila['planeacion'],
                'saberes' => $fila['saberes'],
                'habilidades'    => $fila['habilidades'],
                'recursos'    => $fila['recursos'],
                'etica'    => $fila['etica']
            ];
        }

        foreach ($resultados_por_grupo as $clave_grupo => $datos) {
            $pdf->AddPage();

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

            $htmlDatos = "
                <b>Institución:</b> {$datos['ies']}<br>
                <b>Sede:</b> {$datos['sede']}<br>
                <b>Programa:</b> {$datos['programa']}<br>
            ";

            $pdf->SetXY(22, 35);
            $pdf->SetFont('gothambook', '', 10);
            $pdf->writeHTML($htmlDatos, false, false, false, '');

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

            $inicioX = 20;
            $y = $pdf->GetY();

            $anchoBarra = 180;
            $altoBarra  = 10;

            $pdf->SetLineWidth(0.3);
            $pdf->SetDrawColor(110, 0, 20);
            $pdf->Rect($inicioX, $y, $anchoBarra, $altoBarra);

            $inicioX = 20;
            $anchoBarra = 180;
            $maxPuntaje = 18;
            $puntaje = max(0, min(floatval($puntaje), $maxPuntaje));

            $pdf->SetDrawColor(110, 0, 15);
            $pdf->Rect($inicioX, $y, $anchoBarra, $altoBarra);

            $pdf->SetFont('gothambook', 'b', 8);
            for ($i = 0; $i <= $maxPuntaje; $i++) {
                $x = $inicioX + ($anchoBarra / $maxPuntaje) * $i;
                $pdf->Line($x, $y, $x, $y + $altoBarra);
                $pdf->Text($x - 2, $y + $altoBarra + 3, (string)$i);
            }

            for ($i = 0; $i < $maxPuntaje; $i++) {
                $segmentoInicio = $inicioX + ($anchoBarra / $maxPuntaje) * $i;
                $segmentoAncho = $anchoBarra / $maxPuntaje;

                $xSub = $segmentoInicio + ($segmentoAncho / 2);
                $pdf->Line($xSub, $y, $xSub, $y + ($altoBarra / 2));
            }

            $posicion = $inicioX + ($puntaje / $maxPuntaje) * $anchoBarra;
            $pdf->SetLineWidth(1);
            $pdf->SetDrawColor(145, 0, 30);
            $pdf->Line($posicion, $y - 2, $posicion, $y + $altoBarra + 2);
            $pdf->SetLineWidth(0.2);

            $pdf->Ln(5);

            $rubros = [
                'Planeacion'    => $datos['rubros']['planeacion'],
                'Saberes' => $datos['rubros']['saberes'],
                'Habilidades'    => $datos['rubros']['habilidades'],
                'Recursos'    => $datos['rubros']['recursos'],
                'Etica'    => $datos['rubros']['etica']
            ];

            foreach ($rubros as $nombreRubro => $valorRubro) {
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

        $pdf->Output($nombre_archivo.'.pdf', 'I');
    }

    public function reporte()
    {
        if (!$this->hasCreateAccess()) {
            $this->loadThis();
            return;
        }

        $nombre_archivo = "";

        $pdf = new PDF('P', 'mm', 'LETTER', true, 'UTF-8', false);
        $pdf->SetCreator('HLANDEROS');
        $pdf->SetAuthor('HLANDEROS');
        $pdf->SetTitle('Reporte de Examen de Inglés 2025');
        $pdf->setPrintHeader(true);
        $pdf->setPrintFooter(true);
        $pdf->SetMargins(20, 20, 20);

        $vinos = [110, 0, 20];

        $ies      = $this->input->post('ies');
        $sede     = $this->input->post('sede');
        $programa = $this->input->post('programa');

        $resultados_crudos = $this->em->get_all_resultados_for_report($ies, $sede, $programa);

        $resultados_por_grupo = [];

        foreach ($resultados_crudos as $fila) {
            $clave_grupo = $fila['nombre_docente'] . '_' . $fila['asignatura']. '_' . $fila['grupo'];

            if (!isset($resultados_por_grupo[$clave_grupo])) {
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

            $resultados_por_grupo[$clave_grupo]['rubros'] = [
                'planeacion'    => $fila['planeacion'],
                'saberes' => $fila['saberes'],
                'habilidades'    => $fila['habilidades'],
                'recursos'    => $fila['recursos'],
                'etica'    => $fila['etica']
            ];
        }

        foreach ($resultados_por_grupo as $clave_grupo => $datos) {
            $pdf->AddPage();

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

            $htmlDatos = "
                <b>Institución:</b> {$datos['ies']}<br>
                <b>Sede:</b> {$datos['sede']}<br>
                <b>Programa:</b> {$datos['programa']}<br>
                <b>Asignatura:</b> {$datos['asignatura']}<br>
                <b>Docente:</b> {$datos['nombre_docente']}<br>
            ";

            $pdf->SetXY(22, 35);
            $pdf->SetFont('gothambook', '', 10);
            $pdf->writeHTML($htmlDatos, false, false, false, '');

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

            $inicioX = 20;
            $y = $pdf->GetY();

            $anchoBarra = 180;
            $altoBarra  = 10;

            $pdf->SetLineWidth(0.3);
            $pdf->SetDrawColor(110, 0, 20);
            $pdf->Rect($inicioX, $y, $anchoBarra, $altoBarra);

            $inicioX = 20;
            $anchoBarra = 180;
            $maxPuntaje = 18;
            $puntaje = max(0, min(floatval($puntaje), $maxPuntaje));

            $pdf->SetDrawColor(110, 0, 15);
            $pdf->Rect($inicioX, $y, $anchoBarra, $altoBarra);

            $pdf->SetFont('gothambook', 'b', 8);
            for ($i = 0; $i <= $maxPuntaje; $i++) {
                $x = $inicioX + ($anchoBarra / $maxPuntaje) * $i;
                $pdf->Line($x, $y, $x, $y + $altoBarra);
                $pdf->Text($x - 2, $y + $altoBarra + 3, (string)$i);
            }

            for ($i = 0; $i < $maxPuntaje; $i++) {
                $segmentoInicio = $inicioX + ($anchoBarra / $maxPuntaje) * $i;
                $segmentoAncho = $anchoBarra / $maxPuntaje;

                $xSub = $segmentoInicio + ($segmentoAncho / 2);
                $pdf->Line($xSub, $y, $xSub, $y + ($altoBarra / 2));
            }

            $posicion = $inicioX + ($puntaje / $maxPuntaje) * $anchoBarra;
            $pdf->SetLineWidth(1);
            $pdf->SetDrawColor(145, 0, 30);
            $pdf->Line($posicion, $y - 2, $posicion, $y + $altoBarra + 2);
            $pdf->SetLineWidth(0.2);

            $pdf->Ln(5);

            $rubros = [
                'Planeacion'    => $datos['rubros']['planeacion'],
                'Saberes' => $datos['rubros']['saberes'],
                'Habilidades'    => $datos['rubros']['habilidades'],
                'Recursos'    => $datos['rubros']['recursos'],
                'Etica'    => $datos['rubros']['etica']
            ];

            foreach ($rubros as $nombreRubro => $valorRubro) {
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

        $pdf->Output($nombre_archivo.'.pdf', 'I');
    }

    public function reporte_sedes()
    {
        if (!$this->hasCreateAccess()) {
            $this->loadThis();
            return;
        }

        $nombre_archivo = "";

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
       
        $resultados_crudos = $this->em->get_all_resultados_for_sedes($ies, $sede);

        $resultados_por_grupo = [];

        foreach ($resultados_crudos as $fila) {
            $clave_grupo = $fila['institucion'] . '_' . $fila['sede'];

            if (!isset($resultados_por_grupo[$clave_grupo])) {
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

            $resultados_por_grupo[$clave_grupo]['rubros'] = [
                'planeacion'    => $fila['planeacion'],
                'saberes' => $fila['saberes'],
                'habilidades'    => $fila['habilidades'],
                'recursos'    => $fila['recursos'],
                'etica'    => $fila['etica']
            ];
        }

        foreach ($resultados_por_grupo as $clave_grupo => $datos) {
            $pdf->AddPage();

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

            $htmlDatos = "
                <b>Institución:</b> {$datos['ies']}<br>
                <b>Sede:</b> {$datos['sede']}<br>
            ";

            $pdf->SetXY(22, 35);
            $pdf->SetFont('gothambook', '', 10);
            $pdf->writeHTML($htmlDatos, false, false, false, '');

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

            $inicioX = 20;
            $y = $pdf->GetY();

            $anchoBarra = 180;
            $altoBarra  = 10;

            $pdf->SetLineWidth(0.3);
            $pdf->SetDrawColor(110, 0, 20);
            $pdf->Rect($inicioX, $y, $anchoBarra, $altoBarra);

            $inicioX = 20;
            $anchoBarra = 180;
            $maxPuntaje = 18;
            $puntaje = max(0, min(floatval($puntaje), $maxPuntaje));

            $pdf->SetDrawColor(110, 0, 15);
            $pdf->Rect($inicioX, $y, $anchoBarra, $altoBarra);

            $pdf->SetFont('gothambook', 'b', 8);
            for ($i = 0; $i <= $maxPuntaje; $i++) {
                $x = $inicioX + ($anchoBarra / $maxPuntaje) * $i;
                $pdf->Line($x, $y, $x, $y + $altoBarra);
                $pdf->Text($x - 2, $y + $altoBarra + 3, (string)$i);
            }

            for ($i = 0; $i < $maxPuntaje; $i++) {
                $segmentoInicio = $inicioX + ($anchoBarra / $maxPuntaje) * $i;
                $segmentoAncho = $anchoBarra / $maxPuntaje;

                $xSub = $segmentoInicio + ($segmentoAncho / 2);
                $pdf->Line($xSub, $y, $xSub, $y + ($altoBarra / 2));
            }

            $posicion = $inicioX + ($puntaje / $maxPuntaje) * $anchoBarra;
            $pdf->SetLineWidth(1);
            $pdf->SetDrawColor(145, 0, 30);
            $pdf->Line($posicion, $y - 2, $posicion, $y + $altoBarra + 2);
            $pdf->SetLineWidth(0.2);

            $pdf->Ln(5);

            $rubros = [
                'Planeacion'    => $datos['rubros']['planeacion'],
                'Saberes' => $datos['rubros']['saberes'],
                'Habilidades'    => $datos['rubros']['habilidades'],
                'Recursos'    => $datos['rubros']['recursos'],
                'Etica'    => $datos['rubros']['etica']
            ];

            foreach ($rubros as $nombreRubro => $valorRubro) {
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
public function eval_docente(){
    
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

            $this->global['pageTitle'] = 'SEIEM : Reporte de Resultados Examen 2025';

            $this->loadViews("Exam2025/panel_formato", $this->global, $this->data, NULL);
        }
}
    
    public function formato_docente(){
    // $data['registros'] = $this->Reporte_model->getDatos();

        
    if (!$this->hasCreateAccess()) {
        $this->loadThis();
        return;
    }

  $pdf = new PDF('P', 'mm', 'LETTER', true, 'UTF-8', false);
    $pdf->SetCreator('HLANDEROS');
    $pdf->SetAuthor('Hlanderos');
    $pdf->SetTitle('Reporte de Examen de Conocimientos 2025');
    $pdf->setPrintHeader(true);
    $pdf->setPrintFooter(true);
    $pdf->SetMargins(20, 20, 20);

    $vino = array(110, 0, 20);



        $ies      = $this->input->post('ies');
        $sede     = $this->input->post('sede');
      $nombre_archivo ="";
        $resultados_crudos = $this->em->formato_docente($ies, $sede);
foreach ($resultados_crudos as $row) {

$pdf->AddPage();

// ----------------------------
// ENCABEZADO
// ----------------------------
$pdf->SetXY(0, 30);




$pdf->SetFont('gothamblack', '', 9);
$pdf->Cell(130, 5, "Asunto:", 0, 0, 'R');
$pdf->SetFont('gothambook', '', 9);
$pdf->Cell(0, 5, "Evaluación del Desempeño Docente", 0, 1, 'R');

$pdf->SetFont('gothamblack', '', 9);
$pdf->Cell(110, 3, "Periodo evaluado:", 0, 0, 'R');
$pdf->SetFont('gothambook', '', 9);
$pdf->Cell(0, 3, "Semestre Septiembre – Enero 2026", 0, 1, 'R');
$pdf->SetFont('gothamblack', '', 9);
$pdf->Cell(110, 3, "Lugar y fecha:", 0, 0, 'R');
$pdf->SetFont('gothambook', '', 9);
$pdf->Cell(0, 5, "Toluca, México, 15 de Marzo de 2026.", 0, 1, 'R');

// ----------------------------
// DATOS GENERALES
// ----------------------------

// ----------------------------
// MENSAJE INSTITUCIONAL
// ----------------------------
$pdf->SetXY(22, 50);
$pdf->SetFont('gothamblack', '', 10);
$pdf->Cell(20, 5,$row['nombre_docente'], 0, 0, 'L');
$pdf->SetXY(22, 55);
$pdf->Cell(0, 5,'Estimado(a) docente universitario(a)', 0, 1, 'L');



$escala10 = $row['escala_10'];
$escala5  = $row['escala_5'];
$escala35 = $row['escala_35'];

if ($escala5 >= 4.5) {
    $nivel = 'EXCELENTE';
} elseif ($escala5 >= 4.0) {
    $nivel = 'MUY BIEN';
} elseif ($escala5 >= 3.0) {
    $nivel = 'BIEN';
} elseif ($escala5 >= 2.0) {
    $nivel = 'REGULAR';
} else {
    $nivel = 'INSUFICIENTE';
}


$texto = '
<p>El Departamento de Formación Profesional hace de su conocimiento los resultados obtenidos en la Evaluación al Docente correspondiente al periodo <b>Febrero – Julio 2026</b>, con la finalidad de fortalecer los procesos de mejora continua, calidad académica y retroalimentación de la práctica educativa.</p>

<p>La evaluación fue aplicada al estudiantado y consideró las siguientes dimensiones:</p>

<ul>
<li>Planeación didáctica</li>
<li>Conocimiento y dominio de la asignatura</li>
<li>Habilidades pedagógicas</li>
<li>Uso de recursos materiales</li>
<li>Actitud ética y valores</li>
<li>Evaluación de los aprendizajes</li>
</ul>

<p>Cada reactivo fue valorado en una escala de 1 a 5, donde:</p>

<ul>
<li>5 = Excelente</li>
<li>4 = Muy bien</li>
<li>3 = Bien</li>
<li>2 = Regular</li>
<li>1 = Insuficiente</li>
</ul>
';

$pdf->SetXY(22, 65);
$pdf->SetFont('gothambook', '', 9);



$pdf->writeHTML($texto, true, false, true, false, 'J');
// ----------------------------
// RESULTADOS
// ----------------------------
$pdf->Ln(4);

$pdf->SetFont('gothamblack', 'B', 11);
$pdf->SetFillColor(110, 0, 20);
$pdf->SetTextColor(255,255,255);
$pdf->Cell(80, 8, 'Resultados Obtenidos', 0, 1, 'C', 1);

$pdf->SetFont('gothambook', '', 10);
$pdf->SetTextColor(0,0,0);
$pdf->SetXY(20, 163);
$texto = '
<ul>
<li><b>Escala de 5:</b> <span style="color:#6E0014;"><b>'.number_format($escala5,1).'</b></span></li>
<li><b>Escala de 10:</b> <span style="color:#6E0014;"><b>'.number_format($escala10,1).'</b></span></li>
<li><b>Escala de 35:</b> <span style="color:#6E0014;"><b>'.number_format($escala35,1).'</b></span></li>
<li><b>Nivel obtenido:</b> <span style="color:#6E0014;"><b>'.$nivel.'</b></span></li>
</ul>
';
$pdf->SetFont('gothambook', 'B', 9);
$pdf->writeHTML($texto, true, false, true, false, 'L');



// ----------------------------
// INTERPRETACIÓN
// ----------------------------
$pdf->Ln(7);
$interpretacion = '
<p>Los resultados obtenidos en la evaluación docente reflejan un desempeño profesional favorable, destacando fortalezas en el dominio disciplinar, la práctica pedagógica y el compromiso con la formación académica de las y los estudiantes.</p>

<p>Asimismo, se recomienda continuar fortaleciendo las áreas de oportunidad identificadas, en beneficio de la calidad educativa y del proceso de enseñanza-aprendizaje.</p>

<p>La presente evaluación tiene carácter formativo y constituye un insumo institucional para el fortalecimiento de la práctica docente y la mejora continua de los servicios educativos.</p>

Agradecemos su compromiso, dedicación y contribución al desarrollo académico de nuestra comunidad universitaria.<br><br>
';
$pdf->SetFont('gothambook', '', 9);

$pdf->writeHTML($interpretacion, true, false, true, false, 'J');

// ----------------------------
// FIRMA
// ----------------------------
$pdf->Ln(4);

$pdf->SetFont('gothamblack', '', 10);
$pdf->Cell(0, 5, 'ATENTAMENTE', 0, 1, 'L');

$pdf->Ln(12);

$pdf->Cell(0, 3, 'DRA. ERIKA GONZÁLEZ DE SALCEDA RAMÍREZ', 0, 1, 'L');
$pdf->Cell(0, 3, 'ENCARGADA DEL DESPACHO DEL DEPARTAMENTO ', 0, 1, 'L');
$pdf->Cell(0, 3, 'DE FORMACIÓN PROFESIONAL', 0, 1, 'L');
$nombre_archivo = str_replace(' ', '_', $row['institucion'].'_'.$row['sede']);
}
    $pdf->Output($nombre_archivo.'.pdf', 'I');

}

}
?>