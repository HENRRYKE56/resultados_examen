<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';
require APPPATH .'/libraries/Pdf.php';

class Exam2025 extends BaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Exam_model', 'em');
        $this->isLoggedIn();
        $this->module = 'alumno';//importante revisar que esta en la tabla de menus
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

            $this->global['pageTitle'] = 'SEIEM : Reporte de Resultados Examen 2025';

            $this->loadViews("Exam2025/panel", $this->global, $this->data, NULL);
        }
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


    public function inicio()
    {
        
    }

  
public function reporte()
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

    $vino = array(110, 0, 20);

    $ies      = $this->input->post('ies');
    $sede     = $this->input->post('sede');
    $programa = $this->input->post('programa');

    // *** CAMBIO CLAVE #1: UNA SOLA CONSULTA A LA BD ***
    // Usamos nuestra nueva función optimizada
    $todos_los_resultados_crudos = $this->em->get_all_resultados_for_report($ies, $sede, $programa);

    // *** CAMBIO CLAVE #2: AGRUPAR DATOS EN PHP (en lugar de en la BD) ***
    // Transformamos el array plano en un array anidado por alumno
    $resultados_por_alumno = [];
    foreach ($todos_los_resultados_crudos as $fila) {
        $nombre_alumno = $fila['nombre_alumno'];
        
        // Si es la primera vez que vemos a este alumno, creamos su entrada
        if (!isset($resultados_por_alumno[$nombre_alumno])) {
            $nombre_archivo=$fila['ies'] . '_' . $fila['sede'] . '_' . $fila['programa'];
            $resultados_por_alumno[$nombre_alumno] = [
                'ies'      => $fila['ies'],
                'sede'     => $fila['sede'],
                'programa' => $fila['programa'],
                'rubros'   => [] // Aquí guardaremos sus resultados
            ];
        }
        
        // Añadimos el resultado de este rubro a su lista personal
        $resultados_por_alumno[$nombre_alumno]['rubros'][] = [
            'rubro'            => $fila['rubro'],
            'calificacion_rubro' => $fila['calificacion_rubro']
        ];
    }

    // *** CAMBIO CLAVE #3: RECORRER LOS DATOS YA AGRUPADOS ***
    // Ahora el bucle es mucho más ligero porque no hay consultas a la BD
    foreach ($resultados_por_alumno as $nombre_alumno_reporte => $datos_alumno) {

        $pdf->AddPage();

        // ----------------------------
        // ENCABEZADO Y DATOS
        // ----------------------------
 $html1 = 'Resultados del Examen General de Conocimientos de 7° Semestre 2025<br><br>';

                $pdf->SetXY(0, 30);
        $pdf->SetFont('gothamblack', '', 10);
        $pdf->writeHTML($html1, false, false, false, '', 'C');


        $html = '
            <b>Institución:</b> ' . $datos_alumno['ies'] . ' <br>
            <b>Sede:</b> ' . $datos_alumno['sede'] . ' <br>
            <b>Programa:</b> ' . $datos_alumno['programa'] . '<br>
            <b>Sustentante:</b> ' . $nombre_alumno_reporte . '<br>
            <b>Fecha de aplicación:</b> 10 de diciembre de 2025<br><br>';

        $pdf->SetXY(22, 36);
        $pdf->SetFont('gothamblack', '', 10);
        $pdf->writeHTML($html, false, false, false, '');

       

        // --------------------------------------------------------------
        // CONTENIDO DEL REPORTE
        // --------------------------------------------------------------

        $pdf->SetFont('gothambook', '', 10);
        $pdf->SetTextColor(0,0,0);

        // *** CAMBIO CLAVE #4: CALCULAR EL TOTAL UNA SOLA VEZ ***
        $valorObtenido = 0;
        foreach ($datos_alumno['rubros'] as $rubro) {
            $valorObtenido += $rubro['calificacion_rubro'];
        }

        // ... (El resto de tu código para el PDF, la barra de puntaje, etc., va aquí y funciona igual) ...
        // No lo repito para no hacerlo más largo, pero puedes pegarlo tal cual.
        

// --------------------------------------------------------------
// CONTENIDO DEL REPORTE
// --------------------------------------------------------------

$pdf->SetFont('gothamblack', 'B', 10);
$pdf->SetTextColor(0,0,0);

$valorObtenido = 0;
$resultados_por_alumno = $datos_alumno['rubros'];
for ($k = 0; $k < count($resultados_por_alumno); $k++) {
$valorObtenido += $resultados_por_alumno[$k]['calificacion_rubro'];
}
$valorObtenido=number_format(($valorObtenido/4), 2); // PROMEDIO GENERAL
// --------------------------------------------------------------
// PUNTAJE GLOBAL
// --------------------------------------------------------------

$pdf->SetFont('gothamblack', 'B', 12);
$pdf->SetFillColor($vino[0], $vino[1], $vino[2]);
$pdf->SetTextColor(255,255,255);

$pdf->Cell(50, 10, 'Puntaje Obtenido', 0, 0, 'C', 1);

$pdf->SetFont('gothamblack', 'B', 16);
$pdf->SetTextColor(0,0,0);
$pdf->Cell(30, 10, $valorObtenido, 0, 1, 'C', 0);

$pdf->Ln(4);

$puntaje = $valorObtenido; // puntaje dinámico
$inicioX = 20;

$pdf->SetXY(20, 75);
$y = $pdf->GetY();

$anchoBarra = 180;
$altoBarra = $puntaje;

$pdf->SetLineWidth(0.3);
$pdf->SetDrawColor(110, 0, 20);
$pdf->Rect($inicioX, $y, $anchoBarra, $altoBarra);

// NUMERACIÓN DEL 0 AL 10
$pdf->SetFont('gothambook', 'B', 8);

for ($n = 0; $n <= 10; $n++) {

$xNum = $inicioX + ($anchoBarra / 10) * $n;

$pdf->Text($xNum - 2.5, $y + $altoBarra + 2, (string)$n);

$pdf->Line($xNum, $y, $xNum, $y + $altoBarra);
}

// SUBDIVISIONES
for ($n = 0; $n < 10; $n++) {

$segmentoInicio = $inicioX + ($anchoBarra / 10) * $n;
$segmentoAncho = $anchoBarra / 10;

for ($j = 1; $j <= 9; $j++) {
$xSub = $segmentoInicio + ($segmentoAncho / 10) * $j;
$pdf->Line($xSub, $y, $xSub, $y + ($altoBarra / 2));
}
}

// MARCADOR DEL PUNTAJE
$posicionPuntaje = $inicioX + ($puntaje / 10) * $anchoBarra;

$pdf->SetDrawColor(145, 0, 30);
$pdf->SetLineWidth(1);
$pdf->Line($posicionPuntaje, $y - 2, $posicionPuntaje, $y + $altoBarra + 2);

// Línea fina restaurada
$pdf->SetLineWidth(0.2);

$pdf->Ln(8);

        // --- EJEMPLO DE CÓMO SE USA EL NUEVO ARRAY ---
        $pdf->Ln(8);
        $listaRubros = [
            "Políticas, gestión y evaluación educativas",
            "Docencia, Formación y Orientación Educativa",
            "Didáctica y currículo",
            "Investigación educativa"
        ];

        foreach ($listaRubros as $nombreRubro) {
            $puntajeRubro = 0;
            // Buscamos el rubro en los datos del alumno que ya están en memoria
            foreach ($datos_alumno['rubros'] as $rubro_data) {
                if ($rubro_data['rubro'] === $nombreRubro) {
                    $puntajeRubro = $rubro_data['calificacion_rubro'];
                    break;
                }
            }
            
            $porcentaje = ($puntajeRubro / 10) * 100;

            $this->bloqueModulo(
                $pdf,
                $nombreRubro,
                number_format($puntajeRubro, 2),
                array('Valor Obtenido' => $porcentaje),
                $vino
            );
        }
    } // END FOR ALUMNOS

    $pdf->Output($nombre_archivo.'.pdf', 'I');
}


    /** ----------------------------------------
     *   FUNCIÓN CORRECTA DE BLOQUE
     * ---------------------------------------- */
   function bloqueModulo($pdf, $titulo, $puntaje, $items, $colorTitulo) {

    // --------------------------------------------------------------
    // TÍTULO DEL RUBRO
    // --------------------------------------------------------------
    $pdf->SetFillColor($colorTitulo[0], $colorTitulo[1], $colorTitulo[2]);
    $pdf->SetTextColor(255,255,255);
    $pdf->SetFont('gothambook', 'B', 12);
    $pdf->Cell(180, 9, $titulo ." ".     $puntaje, 0, 1, 'L', 1);
    $pdf->Ln(3);

    // --------------------------------------------------------------
    // REGLA TIPO GLOBAL (para cada rubro)
    // --------------------------------------------------------------
    $puntaje_valor = floatval($puntaje); // 0 a 10
    if ($puntaje_valor < 0) $puntaje_valor = 0;
    if ($puntaje_valor > 10) $puntaje_valor = 10;

    $pdf->SetFont('gothambook', '', 10);
    $pdf->SetTextColor(0,0,0);

    // posición Y de la barra
    $inicioX = 20;
    $y = $pdf->GetY();

    $anchoBarra = 180;
    $altoBarra = 10; // altura fija para todos los rubros

    // Marco
    $pdf->SetLineWidth(0.3);
    $pdf->SetDrawColor(110, 0, 20);
    $pdf->Rect($inicioX, $y, $anchoBarra, $altoBarra);

    // NUMERACIÓN 0–10
    $pdf->SetFont('gothambook', 'B', 7);

    for ($i = 0; $i <= 10; $i++) {
        $xNum = $inicioX + ($anchoBarra / 10) * $i;
        $pdf->Text($xNum - 1.2, $y + $altoBarra + 2.5, (string)$i);
        $pdf->Line($xNum, $y, $xNum, $y + $altoBarra);
    }

    // SUBDIVISIONES 10 por segmento
    for ($i = 0; $i < 10; $i++) {
        $segmentoInicio = $inicioX + ($anchoBarra / 10) * $i;
        $segmentoAncho = $anchoBarra / 10;

        for ($j = 1; $j <= 9; $j++) {
            $xSub = $segmentoInicio + ($segmentoAncho / 10) * $j;
            $pdf->Line($xSub, $y, $xSub, $y + ($altoBarra / 2));
        }
    }

   // MARCADOR DE PUNTAJE
$posicionPuntaje = $inicioX + ($puntaje_valor / 10) * $anchoBarra;

// Color según puntaje
if ($puntaje_valor < 6) {
    // amarillo
    $pdf->SetDrawColor(255, 200, 0);
} else {
    // verde claro
    $pdf->SetDrawColor(0, 180, 70);
}

$pdf->SetLineWidth(1);
$pdf->Line($posicionPuntaje, $y - 2, $posicionPuntaje, $y + $altoBarra + 2);

// Restaurar grosor
$pdf->SetLineWidth(0.2);


    $pdf->Ln($altoBarra + 5);
}


}

?>
