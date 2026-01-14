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

     

            $this->global['pageTitle'] = 'SEIEM : Reporte de Resultados de Inglés por Sede Semestre Septiembre 2025 Enero 2026';

            $this->loadViews("docente/sedes", $this->global, $this->data, NULL);
        }
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
