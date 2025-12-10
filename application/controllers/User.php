<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';
require APPPATH .'/libraries/Pdf.php';

/**
 * Class : User (UserController)
 * User Class to control all user related operations.
 * @author : Kishor Mali
 * @version : 1.1
 * @since : 15 November 2016
 */
class User extends BaseController
{
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('user_model',"modelo");
        $this->isLoggedIn();
    }
    
    /**
     * This function used to load the first screen of the user
     */
    public function index()
    {
        $this->global['pageTitle'] = 'SEIEM : Gestión de Instituciones de Educación Superior';
        $this->data['directorio'] = $this->modelo->directorio();
      /********quitar cuando pase lo de ceneval */


        
        $this->loadViews("general/dashboard", $this->global, $this->data , NULL);
    }
    
    /**
     * This function is used to load the user list
     */
    public function ceneval_list()
    {
        if(!$this->isAdmin())
        {
            $this->loadThis();
        }
        else
        {        
            $searchText = '';
            if(!empty($this->input->post('searchText'))) {
                $searchText = $this->security->xss_clean($this->input->post('searchText'));
            }
            $data['searchText'] = $searchText;
          
            $this->load->library('pagination');
            
            $count = $this->user_model->cenevalCount($searchText);
            $returns = $this->paginationCompress ( "ceneval_list/", $count, 10 );
            $data['userRecords'] = $this->user_model->cenevalListing($searchText, $returns["page"], $returns["segment"]);
            $this->global['pageTitle'] = 'SEIEM : Listado de Ceneval';
            $this->loadViews("users/ceneval_list", $this->global, $data, NULL);
        }
    }
    public function ceneval(){


        $pdf = new Pdf(PDF_PAGE_ORIENTATION, PDF_UNIT, "LETTER", true, 'UTF-8', false);
        ini_set('max_execution_time', 300); // 300 segundos = 5 minutos 
        ini_set('memory_limit', '512M');
        // Configura la información del documento
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('HLANDEROS');
        
        
        // Configura las cabeceras y los pies de página
        $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);
        $pdf = new Pdf();
        $pdf->SetTitle('Ciclo escolar 2025-2026');
        
        // Agregar página
  
        
        $pdf->SetMargins(20, 15, 10); // Izquierda, Arriba, Derecha
        $pdf->SetAutoPageBreak(true, 5); // Habilita el salto de página y define margen inferior
        // Establecer la imagen de fondo
        $pagina_ancho = $pdf->getPageWidth(); // Obtener ancho de la página
        $pagina_alto = $pdf->getPageHeight(); // Obtener alto de la página
        
        $imagen_ancho = 210; // Ajusta el ancho de la imagen
        $imagen_alto = 200;  // Ajusta el alto de la imagen
        
        $pos_x = ($pagina_ancho - $imagen_ancho) / 2; // Centrar horizontalmente
        $pos_y = ($pagina_alto - $imagen_alto) / 2; // Centrar verticalmente
        
        $pdf->Image(base_url('assets/images/fondo.png'), $pos_x, 85, $imagen_ancho, $imagen_alto);
        /////aqui hacer la consulta para obtener la informacion de la incidencia////
      ini_set('memory_limit', '1025M');
        set_time_limit(1600); // 10 minutos
        // Crear instancia de TCPDF
       

$this->db->select('*');
$this->db->from('salones_aceptados a');
$this->db->order_by('a.cve_sede', 'ASC');
$this->db->order_by('a.observaciones', 'ASC');
$this->db->order_by('a.aulas', 'ASC');
$query1 = $this->db->get();
$distribucion = $query1->result_array();
$asignados_por_sede_plan = []; // Control de offset por sede y plan
$consecutivo_por_sede_plan = [];
$resumen_por_sede_plan = [];
$totales=array();
foreach ($distribucion as $row) {
 $sede = $row['sede'];
 $plan = $row['observaciones'];
 $capacidad = $row['capacidad'];

 $clave = $sede . '|' . $plan;

 if (!isset($resumen_por_sede_plan[$clave])) {
    $resumen_por_sede_plan[$clave] = 0;
}
$resumen_por_sede_plan[$clave]++;

 // Inicializar offset si no existe
 if (!isset($asignados_por_sede_plan[$clave])) {
   $asignados_por_sede_plan[$clave] = 0;
 }


if (!isset($consecutivo_por_sede_plan[$clave])) {
     $consecutivo_por_sede_plan[$clave] = 1; // Obtener el offset actual para la sede y plan

}
   
 $offset = $asignados_por_sede_plan[$clave];

 $this->db->select('*');
 $this->db->from('asignacion_examen a');
 $this->db->where('a.sede_apli', $sede);
 $this->db->where('a.plan', $plan);
 $this->db->where('a.estado', "aceptado");
 $this->db->order_by('a.apaterno', 'ASC');
 $this->db->order_by('a.amaterno', 'ASC');
 $this->db->order_by('a.nombre', 'ASC');
 $this->db->limit($capacidad);
 $this->db->offset($offset);
 $query = $this->db->get();
 $resultado = $query->result_array();

 // Aumentar el offset por la capacidad del aula (no por count($resultado))
$asignados_por_sede_plan[$clave] += $capacidad;

 $pdf->SetFont('helvetica', '', 10); // Texto normal
        $pdf->SetXY(115, 16);
        $pdf->Cell(0, 10, '18 de Junio del 2025', 0, 1, 'C');

   
    if (count($resultado) > 0) {
        $pdf->AddPage("P", "letter");
        $pdf->SetFont('helvetica', 'B', 10); // Negritas y subrayado
        $pdf->SetXY(15, 30);   
        $pdf->Cell(0, 10, 'LISTA DE ASPIRANTES ACEPTADOS CICLO ESCOLAR 2025-2026', 0, 1, 'C');

        $pdf->SetFont('helvetica', 'BU', 10); // Texto normal
       
        $pdf->SetXY(20, 38);
        $pdf->Cell(0, 10, 'INSTITUCIÓN: '.$sede, 0, 1, 'L');
        $pdf->SetXY(20, 45);
        $pdf->Cell(0, 10, 'PLAN: ' .$row['observaciones'], 0, 1, 'L');
      //  $pdf->SetXY(20, 50);
      //  $pdf->Cell(90, 10, 'AULA: '.$row['aulas'], 0, 1, 'L');

        // Tabla HTML
        $tt = '<style>
            table { border-collapse: collapse; width: 90%; }
            th { background-color: #cccccc; font-weight: bold; border: 0.1mm solid #000000; }
            td { border: 0.1mm solid #000000; }
        </style>';
        $tt .= '<table border="1">
            <tr>
                <th width="5%">No</th>
                <th width="25%">FOLIO SEIEM</th>
                <th width="70%">NOMBRE COMPLETO</th>
               
            </tr>';

        foreach ($resultado as $j => $aspirante) {
             $numero = $consecutivo_por_sede_plan[$clave];

            $bgcolor = ($j % 2 == 0) ? '#f2f2f2' : '#ffffff';
            $tt .= '<tr style="background-color:' . $bgcolor . '">';
            $tt .= '<td align="center">' . ($numero) . '</td>';
            $tt .= '<td>' . $aspirante['folio_seiem'] . '</td>';
            $tt .= '<td>' . $aspirante['apaterno'] . ' ' . $aspirante['amaterno'] . ' ' . $aspirante['nombre'] . '</td>';
            $tt .= '</tr>';

                // Incrementar el consecutivo
            $consecutivo_por_sede_plan[$clave]++;

         //   $this->db->where('folio_seiem', $aspirante['folio_seiem']);
       //     $this->db->update('asignacion_examen', ['estado' => 'aceptado']);


        }
        $totales[$clave] = $consecutivo_por_sede_plan[$clave] - 1; // Guardar el total de aspirantes asignados para esta sede y plan


        $tt .= '</table>';
        $pdf->SetFont('helvetica', '', 9); // Texto normal
 
        $pdf->SetXY(20, 60);
        $pdf->writeHTML($tt, true, false, true, false, '');
    }
}

$pdf->AddPage("P", "letter");
$pdf->SetFont('helvetica', 'B', 12);
$pdf->Cell(0, 10, 'Resumen General por Sede y Plan', 0, 1, 'C');

$pdf->SetFont('helvetica', '', 8);
$pdf->Ln(5);
$tt_resumen = '<style>
    table { border-collapse: collapse; width: 100%; margin-top: 10px; }
    th { background-color: #cccccc; font-weight: bold; border: 0.1mm solid #000000; }
    td { border: 0.1mm solid #000000; }
</style>';

$tt_resumen .= '<table border="1">
    <tr>
         <th width="4%">No</th>
        <th width="48%">Sede</th>
        <th width="38%">Plan</th>
        <th width="10%">Total Aspirantes</th>
    </tr>';


$total_general="";
$conta=1;
foreach ($totales as $clave => $total) {
    list($sede, $plan) = explode('|', $clave);
       $bgcolor = ($conta % 2 == 0) ? '#f2f2f2' : '#ffffff';
            $tt_resumen .= '<tr style="background-color:' . $bgcolor . '">';
 
     $tt_resumen .= '<td width="4%">' . ($conta) . '</td>';
    $tt_resumen .= '<td width="48%">' . htmlspecialchars($sede) . '</td>';
    $tt_resumen .= '<td width="38%">' . htmlspecialchars($plan) . '</td>';
    $tt_resumen .= '<td width="10%" align="center">' . $total . '</td>';
    $tt_resumen .= '</tr>';
    $conta++;
    $total_general += $total; // Sumar el total de aspirantes para el resumen general

}
  $tt_resumen .= '<tr><td colspan="3"><b>Total de Aspirantes</b></td><td align="center"><b>' . ($total_general) . '</b></td></tr>';
  
$tt_resumen .= '</table>';

$pdf->SetFont('helvetica', 'B', 12);
$pdf->Cell(0, 10, 'Resumen General por Sede y Plan', 0, 1, 'C');
$pdf->Ln(5);
$pdf->SetFont('helvetica', '', 8);
$pdf->writeHTML($tt_resumen, true, false, true, false, '');


// Mostrar el PDF
$pdf->Output('Aceptados.pdf', 'I');

  $pdf->Output('Aceptados.pdf', 'I');
  header("Content-type: application/pdf");
  header("Content-Disposition: inline; filename=Aceptados.pdf");
  readfile("Aceptados.pdf");


    }
      public function ceneval_2da(){


        $pdf = new Pdf(PDF_PAGE_ORIENTATION, PDF_UNIT, "LETTER", true, 'UTF-8', false);
        ini_set('max_execution_time', 300); // 300 segundos = 5 minutos 
        ini_set('memory_limit', '512M');
        // Configura la información del documento
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('HLANDEROS');
        
        
        // Configura las cabeceras y los pies de página
        $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);
        $pdf = new Pdf();
        $pdf->SetTitle('Ciclo escolar 2025-2026');
        
        // Agregar página
  
        
        $pdf->SetMargins(20, 15, 10); // Izquierda, Arriba, Derecha
        $pdf->SetAutoPageBreak(true, 5); // Habilita el salto de página y define margen inferior
        // Establecer la imagen de fondo
        $pagina_ancho = $pdf->getPageWidth(); // Obtener ancho de la página
        $pagina_alto = $pdf->getPageHeight(); // Obtener alto de la página
        
        $imagen_ancho = 210; // Ajusta el ancho de la imagen
        $imagen_alto = 200;  // Ajusta el alto de la imagen
        
        $pos_x = ($pagina_ancho - $imagen_ancho) / 2; // Centrar horizontalmente
        $pos_y = ($pagina_alto - $imagen_alto) / 2; // Centrar verticalmente
        
        $pdf->Image(base_url('assets/images/fondo.png'), $pos_x, 85, $imagen_ancho, $imagen_alto);
        /////aqui hacer la consulta para obtener la informacion de la incidencia////
      ini_set('memory_limit', '1025M');
        set_time_limit(1600); // 10 minutos
        // Crear instancia de TCPDF
       
$this->db->select('*');
$this->db->from('salones_aceptados_2da a');
// $this->db->where('a.sede', "UNIVERSIDAD PEDAGOGICA NACIONAL UNIDAD 151 TOLUCA");
$this->db->order_by('a.cve_sede', 'ASC');
$this->db->order_by('a.observaciones', 'ASC');
$this->db->order_by('a.aulas', 'ASC');
$query1 = $this->db->get();
$distribucion = $query1->result_array();

// Arreglos de control
$offset_por_sede_plan = [];       // Para controlar el OFFSET en la consulta
$consecutivo_por_sede_plan = [];  // Para numeración dentro del PDF
$totales = [];

foreach ($distribucion as $row) {
    $sede = $row['sede'];
    $plan = $row['observaciones'];
    $capacidad = $row['capacidad'];

    $clave = $sede . '|' . $plan;

    // Inicializar offset si no existe
    if (!isset($offset_por_sede_plan[$clave])) {
        $offset_por_sede_plan[$clave] = 0; // empieza en 0 (primer registro)
    }

    // Inicializar consecutivo si no existe
    if (!isset($consecutivo_por_sede_plan[$clave])) {
        $consecutivo_por_sede_plan[$clave] = 0; // empieza en 1 para numeración del PDF
    }

    // Consulta a BD
    $this->db->select('*');
    $this->db->from('asignacion_examen_segunda a');
    $this->db->where('a.sede_apli', $sede);
    $this->db->where('a.plan', $plan);
    $this->db->order_by('a.posicion_sustentante', 'ASC');
    $this->db->limit($capacidad);
    $this->db->offset($offset_por_sede_plan[$clave]); // offset correcto
    $query = $this->db->get();
    $resultado = $query->result_array();

    // Actualizar offset para la siguiente aula
    $offset_por_sede_plan[$clave] += $capacidad;

    // Si hay resultados, generar página
    if (count($resultado) > 0) {
        $pdf->AddPage("P", "letter");
        $pdf->SetFont('helvetica', 'B', 10); 
        $pdf->SetXY(15, 30);   
        $pdf->Cell(0, 10, 'LISTA DE ASPIRANTES ACEPTADOS CICLO ESCOLAR 2025-2026', 0, 1, 'C');

        $pdf->SetFont('helvetica', 'BU', 10);
        $pdf->SetXY(20, 38);
        $pdf->Cell(0, 10, 'INSTITUCIÓN: '.$sede, 0, 1, 'L');
        $pdf->SetXY(20, 45);
        $pdf->Cell(0, 10, 'PLAN: ' .$plan, 0, 1, 'L');
        
        // Tabla HTML
        $tt = '<style>
            table { border-collapse: collapse; width: 90%; }
            th { background-color: #cccccc; font-weight: bold; border: 0.1mm solid #000000; }
            td { border: 0.1mm solid #000000; }
        </style>';
        $tt .= '<table border="1">
            <tr>
                <th width="5%">No</th>
                <th width="25%">FOLIO SEIEM</th>
                <th width="70%">NOMBRE COMPLETO</th>
            </tr>';

        foreach ($resultado as $j => $aspirante) {
            $numero = ($consecutivo_por_sede_plan[$clave] + 1); // número en PDF

            $bgcolor = ($j % 2 == 0) ? '#f2f2f2' : '#ffffff';
            $tt .= '<tr style="background-color:' . $bgcolor . '">';
            $tt .= '<td align="center">' . $numero . '</td>';
            $tt .= '<td>' . $aspirante['folio_seiem'] . '</td>';
            $tt .= '<td>' . $aspirante['apaterno'] . ' ' . $aspirante['amaterno'] . ' ' . $aspirante['nombre'] . '</td>';
            
            $tt .= '</tr>';

            // Incrementar consecutivo
            $consecutivo_por_sede_plan[$clave]++;
        }

        $totales[$clave] = $consecutivo_por_sede_plan[$clave]; 

        $tt .= '</table>';
        $pdf->SetFont('helvetica', '', 9);
        $pdf->writeHTML($tt, true, false, true, false, '');
    }
}

$pdf->AddPage("P", "letter");
$pdf->SetFont('helvetica', 'B', 12);
$pdf->Cell(0, 10, 'Resumen General por Sede y Plan', 0, 1, 'C');

$pdf->SetFont('helvetica', '', 8);
$pdf->Ln(5);
$tt_resumen = '<style>
    table { border-collapse: collapse; width: 100%; margin-top: 10px; }
    th { background-color: #cccccc; font-weight: bold; border: 0.1mm solid #000000; }
    td { border: 0.1mm solid #000000; }
</style>';

$tt_resumen .= '<table border="1">
    <tr>
         <th width="4%">No</th>
        <th width="48%">Sede</th>
        <th width="38%">Plan</th>
        <th width="10%">Total Aspirantes</th>
    </tr>';


$total_general="";
$conta=1;
foreach ($totales as $clave => $total) {
    list($sede, $plan) = explode('|', $clave);
       $bgcolor = ($conta % 2 == 0) ? '#f2f2f2' : '#ffffff';
            $tt_resumen .= '<tr style="background-color:' . $bgcolor . '">';
 
     $tt_resumen .= '<td width="4%">' . ($conta) . '</td>';
    $tt_resumen .= '<td width="48%">' . htmlspecialchars($sede) . '</td>';
    $tt_resumen .= '<td width="38%">' . htmlspecialchars($plan) . '</td>';
    $tt_resumen .= '<td width="10%" align="center">' . $total . '</td>';
    $tt_resumen .= '</tr>';
    $conta++;
    $total_general += $total; // Sumar el total de aspirantes para el resumen general

}
  $tt_resumen .= '<tr><td colspan="3"><b>Total de Aspirantes</b></td><td align="center"><b>' . ($total_general) . '</b></td></tr>';
  
$tt_resumen .= '</table>';

$pdf->SetFont('helvetica', 'B', 12);
$pdf->Cell(0, 10, 'Resumen General por Sede y Plan', 0, 1, 'C');
$pdf->Ln(5);
$pdf->SetFont('helvetica', '', 8);
$pdf->writeHTML($tt_resumen, true, false, true, false, '');


// Mostrar el PDF
$pdf->Output('Aceptados.pdf', 'I');

  $pdf->Output('Aceptados.pdf', 'I');
  header("Content-type: application/pdf");
  header("Content-Disposition: inline; filename=Aceptados.pdf");
  readfile("Aceptados.pdf");


    }
    public function ceneval_ok(){


        $pdf = new Pdf(PDF_PAGE_ORIENTATION, PDF_UNIT, "LETTER", true, 'UTF-8', false);
        ini_set('max_execution_time', 300); // 300 segundos = 5 minutos 
        ini_set('memory_limit', '512M');
        // Configura la información del documento
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('HLANDEROS');
        
        
        // Configura las cabeceras y los pies de página
        $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);
        
        
        
        
        ini_set('memory_limit', '1025M');
        set_time_limit(1600); // 10 minutos
        // Crear instancia de TCPDF
        $pdf = new Pdf();
        $pdf->SetTitle('Listas de apliación de examen ceneval');
        
        // Agregar página
  
        
        $pdf->SetMargins(20, 15, 10); // Izquierda, Arriba, Derecha
        $pdf->SetAutoPageBreak(true, 5); // Habilita el salto de página y define margen inferior
        // Establecer la imagen de fondo
        $pagina_ancho = $pdf->getPageWidth(); // Obtener ancho de la página
        $pagina_alto = $pdf->getPageHeight(); // Obtener alto de la página
        
        $imagen_ancho = 210; // Ajusta el ancho de la imagen
        $imagen_alto = 200;  // Ajusta el alto de la imagen
        
        $pos_x = ($pagina_ancho - $imagen_ancho) / 2; // Centrar horizontalmente
        $pos_y = ($pagina_alto - $imagen_alto) / 2; // Centrar verticalmente
        
        $pdf->Image(base_url('assets/images/fondo.png'), $pos_x, 85, $imagen_ancho, $imagen_alto);
        /////aqui hacer la consulta para obtener la informacion de la incidencia////
    /*   
          $this->db->select('*');
  $this->db->from('distribucion_alumnos a');
  $this->db->order_by('a.cve_sede', 'ASC');
  $this->db->order_by('a.aulas', 'ASC');
  $query1 = $this->db->get();
  $distribucion= $query1->result_array(); // Devuelve los datos como un array asociativo
  
  foreach($distribucion as $row) {
      $sede = $row['sede'];
  
  
                      $this->db->select('*');
                  $this->db->from('asignacion_examen a');
           
                  $this->db->where('a.sede_apli', $sede);
                  $this->db->where('a.aula_aplicacion',"0");
                  $this->db->order_by('a.sede_apli', 'ASC');
                  $this->db->order_by('a.plan', 'ASC');
                  $this->db->limit($row['capacidad'], 0);
                
          
                  $query = $this->db->get();
                  $resultado= $query->result_array(); // Devuelve los datos como un array asociativo
      
              
              // Recorrer el array y generar las filas de la tabla
              foreach ($resultado as $fila) {
  
  
                  $this->db->where('folio_seiem', $fila['folio_seiem']); // Filtra por folio
                  $this->db->where('aula_aplicacion', "0"); // Filtra por folio
                    
              
                  $data = array(
                      'aula_aplicacion' => $row['aulas'] // Valor que deseas actualizar
                  );
                  
              
                  $this->db->update('asignacion_examen', $data); // Actualiza la tabla
  
  
                 
              }
             
          }  
          */
     
 $this->db->select("CONCAT('sede_apli=\"', a.sede_apli, '\" ') AS condicion", false);
$this->db->from('asignacion_examen a');
$this->db->group_by(['a.sede_apli']);
$this->db->order_by('a.sede_apli', 'ASC');
//$this->db->order_by('a.plan', 'ASC');
//$this->db->order_by('a.aula_aplicacion', 'ASC');


$condicion = $this->db->get();
$condiciones = $condicion->result_array();


  $pdf->SetFont('helvetica', '', 10);
  for($i=0; $i< count($condiciones); $i++){

     $pagina =(count( $condiciones[$i]['condicion']) /40)+1; // Recoge el número de página desde un parámetro GET o POST
$pagina = ($pagina) ? (int)$pagina : 1;
$por_pagina = 40;
$offset = ($pagina - 1) * $por_pagina;



          $this->db->select('*');
          $this->db->from('asignacion_examen a');
          $this->db->where($condiciones[$i]['condicion']);
          $this->db->order_by('a.apaterno', 'ASC');
          $this->db->order_by('a.amaterno', 'ASC');
           $this->db->order_by('a.nombre', 'ASC');
         // $this->db->limit($por_pagina, $offset); // Paginación
         
  
          $query = $this->db->get();
          $resultado = $query->result_array(); 
         $tt = "";
$registrosPorPagina =60;

for ($j = 0; $j < count($resultado); $j++) {
    // Si es el primer registro o cada 40 registros, agregar nueva página y encabezado
  if($resultado[$j]['sede_apli']=="UNIDAD DE DESARROLLO PROFESIONAL JIQUIPILCO")
  {
    $registrosPorPagina = 50;
  }
    if ($j % $registrosPorPagina == 0) {
        if ($j > 0) {
            $tt .= '</table>';
            $pdf->SetFont('helvetica', '', 7);
            $pdf->SetXY(15, 45);
            $pdf->writeHTML($tt, true, false, true, false, '');
        }

        $pdf->AddPage("P", "letter");
        $pdf->SetFont('helvetica', 'B', 10);
        $pdf->SetXY(0, 30);

       
            $pdf->Cell(0, 0, $resultado[$j]['sede_apli'], 0, 0, 'C');
        

        $tt = '<style>
            table {
                border-collapse: collapse;
                width: 100%;
            }
            th {
                background-color: #cccccc;
                font-weight: bold;
                border: 0.1mm solid #000000;
            }
            td {
                border: 0.1mm solid #000000;
            }
        </style>
        <table border="1">
            <tr>
                <th width="4%" align="center">No</th>
                <th width="10%" align="center">Folio Ceneval</th>
                <th width="30%" align="center">Nombre Completo</th>
                <th width="50%" align="center">Plan</th>
                <th width="6%" align="center">Aula</th>
            </tr>';
    }

    $bgcolor = ($j % 2 == 0) ? '#f2f2f2' : '#ffffff';

    $tt .= '<tr style="background-color:' . $bgcolor . '">
        <td align="center">' . ($j + 1) . '</td>
        <td>' . $resultado[$j]['folio_ceneval'] . '</td>
        <td>' . $resultado[$j]['apaterno'] . ' ' . $resultado[$j]['amaterno'] . ' ' . $resultado[$j]['nombre'] . '</td>
        <td>' . $resultado[$j]['plan'] . '</td>
        <td>' . $resultado[$j]['aula_aplicacion'] . '</td>
    </tr>';
}

// Escribir la última tabla si hay registros
if (!empty($tt)) {
    $tt .= '</table>';
    $pdf->SetFont('helvetica', '', 7);
    $pdf->SetXY(15, 38);
    $pdf->writeHTML($tt, true, false, true, false, '');
}

         
             // echo $tt;
          }
        
        
  
  // Generar y mostrar el PDF
  $pdf->Output('asignación.pdf', 'I');
  header("Content-type: application/pdf");
  header("Content-Disposition: inline; filename=asignacion.pdf");
  readfile("asignacion.pdf");
  /*
  echo"<pre>";
  print_r($tt);
  echo"</pre>";
  die;*/
  

    }
    function userListing()
    {
        if(!$this->isAdmin())
        {
            $this->loadThis();
        }
        else
        {        
            $searchText = '';
            if(!empty($this->input->post('searchText'))) {
                $searchText = $this->security->xss_clean($this->input->post('searchText'));
            }
            $data['searchText'] = $searchText;
          //  $data['grupo'] = $this->user_model->getUsergrupos();
          
            $this->load->library('pagination');
            
            $count = $this->user_model->userListingCount($searchText);

			$returns = $this->paginationCompress ( "userListing/", $count, 10 );
            
            $data['userRecords'] = $this->user_model->userListing($searchText, $returns["page"], $returns["segment"]);
            
            $this->global['pageTitle'] = 'SEIEM : User Listing';
            
            $this->loadViews("users/users", $this->global, $data, NULL);
        }
    }

    /**
     * This function is used to load the add new form
     */
    function addNew()
    {
        if(!$this->isAdmin())
        {
            $this->loadThis();
        }
        else
        {
            $this->load->model('user_model');
            $data['roles'] = $this->user_model->getUserRoles();
            $data['grupo'] = $this->user_model->getUsergrupos();
            $this->global['pageTitle'] = 'SEIEM : Nuevo usuario';

            $this->loadViews("users/addNew", $this->global, $data, NULL);
        }
    }

    /**
     * This function is used to check whether email already exist or not
     */
    function checkEmailExists()
    {
        $userId = $this->input->post("userId");
        $email = $this->input->post("email");

        if(empty($userId)){
            $result = $this->user_model->checkEmailExists($email);
        } else {
            $result = $this->user_model->checkEmailExists($email, $userId);
        }

        if(empty($result)){ echo("true"); }
        else { echo("false"); }
    }
    
    /**
     * This function is used to add new user to the system
     */
    function addNewUser()
    {
        if(!$this->isAdmin())
        {
            $this->loadThis();
        }
        else
        {
            $this->load->library('form_validation');
            
            $this->form_validation->set_rules('fname','Full Name','trim|required|max_length[128]');
         //  $this->form_validation->set_rules('email','Email','trim|required|valid_email|max_length[128]');
            $this->form_validation->set_rules('password','Password','required|max_length[20]');
            $this->form_validation->set_rules('cpassword','Confirm Password','trim|required|matches[password]|max_length[20]');
            $this->form_validation->set_rules('role','Role','trim|required|numeric');
            $this->form_validation->set_rules('mobile','Mobile Number','required|min_length[10]');
            
            if($this->form_validation->run() == FALSE)
            {
                $this->addNew();
            }
            else
            {
                $name = ucwords(strtolower($this->security->xss_clean($this->input->post('fname'))));
                $email = strtolower($this->security->xss_clean($this->input->post('email')));
                $password = $this->input->post('password');
                $roleId = $this->input->post('role');
                $mobile = $this->security->xss_clean($this->input->post('mobile'));
                $isAdmin = $this->input->post('isAdmin');
                
                $userInfo = array('email'=>$email, 'password'=>getHashedPassword($password), 'roleId'=>$roleId,
                        'name'=> $name, 'mobile'=>$mobile, 'isAdmin'=>$isAdmin,
                        'createdBy'=>$this->vendorId, 'createdDtm'=>date('Y-m-d H:i:s'));
                
                $this->load->model('user_model');
                $result = $this->user_model->addNewUser($userInfo);
                
                if($result > 0){
                    $this->session->set_flashdata('success', 'New User created successfully');
                } else {
                    $this->session->set_flashdata('error', 'User creation failed');
                }
                
                redirect('userListing');
            }
        }
    }

    
    /**
     * This function is used load user edit information
     * @param number $userId : Optional : This is user id
     */
    function editOld($userId = NULL)
    {
        if(!$this->isAdmin())
        {
            $this->loadThis();
        }
        else
        {
            if($userId == null)
            {
                redirect('userListing');
            }
            
            $data['roles'] = $this->user_model->getUserRoles();
            $data['userInfo'] = $this->user_model->getUserInfo($userId);

            $this->global['pageTitle'] = 'SEIEM : Edit User';
            
            $this->loadViews("users/editOld", $this->global, $data, NULL);
        }
    }
    
    
    /**
     * This function is used to edit the user information
     */
    function editUser()
    {
        if(!$this->isAdmin())
        {
            $this->loadThis();
        }
        else
        {
            $this->load->library('form_validation');
            
            $userId = $this->input->post('userId');
            
            $this->form_validation->set_rules('fname','Full Name','trim|required|max_length[128]');
          //  $this->form_validation->set_rules('email','Email','trim|required|valid_email|max_length[128]');
            $this->form_validation->set_rules('password','Password','matches[cpassword]|max_length[20]');
            $this->form_validation->set_rules('cpassword','Confirm Password','matches[password]|max_length[20]');
            $this->form_validation->set_rules('role','Role','trim|required|numeric');
            $this->form_validation->set_rules('mobile','Mobile Number','required|min_length[10]');
            
            if($this->form_validation->run() == FALSE)
            {
                $this->editOld($userId);
            }
            else
            {
                $name = ucwords(strtolower($this->security->xss_clean($this->input->post('fname'))));
                $email = strtolower($this->security->xss_clean($this->input->post('email')));
                $password = $this->input->post('password');
                $roleId = $this->input->post('role');
                $mobile = $this->security->xss_clean($this->input->post('mobile'));
                $isAdmin = $this->input->post('isAdmin');
                
                $userInfo = array();
                
                if(empty($password))
                {
                    $userInfo = array('email'=>$email, 'roleId'=>$roleId, 'name'=>$name, 'mobile'=>$mobile,
                        'isAdmin'=>$isAdmin, 'updatedBy'=>$this->vendorId, 'updatedDtm'=>date('Y-m-d H:i:s'));
                }
                else
                {
                    $userInfo = array('email'=>$email, 'password'=>getHashedPassword($password), 'roleId'=>$roleId,
                        'name'=>ucwords($name), 'mobile'=>$mobile, 'isAdmin'=>$isAdmin, 
                        'updatedBy'=>$this->vendorId, 'updatedDtm'=>date('Y-m-d H:i:s'));
                }
                
                $result = $this->user_model->editUser($userInfo, $userId);
                
                if($result == true)
                {
                    $this->session->set_flashdata('success', 'User Actualizado exitosamente');
                }
                else
                {
                    $this->session->set_flashdata('error', 'User updation failed');
                }
                
                redirect('userListing');
            }
        }
    }


    /**
     * This function is used to delete the user using userId
     * @return boolean $result : TRUE / FALSE
     */
    function deleteUser()
    {
        if(!$this->isAdmin())
        {
            echo(json_encode(array('status'=>'access')));
        }
        else
        {
            $userId = $this->input->post('userId');
            $userInfo = array('isDeleted'=>1,'updatedBy'=>$this->vendorId, 'updatedDtm'=>date('Y-m-d H:i:s'));
            
            $result = $this->user_model->deleteUser($userId, $userInfo);
            
            if ($result > 0) { echo(json_encode(array('status'=>TRUE))); }
            else { echo(json_encode(array('status'=>FALSE))); }
        }
    }
    
    /**
     * Page not found : error 404
     */
    function pageNotFound()
    {
        $this->global['pageTitle'] = 'SEIEM : 404 - Page Not Found';
        
        $this->loadViews("general/404", $this->global, NULL, NULL);
    }

    /**
     * This function used to show login history
     * @param number $userId : This is user id
     */
    function loginHistoy($userId = NULL)
    {
        if(!$this->isAdmin())
        {
            $this->loadThis();
        }
        else
        {
            $userId = ($userId == NULL ? 0 : $userId);

            $searchText = $this->input->post('searchText');
            $fromDate = $this->input->post('fromDate');
            $toDate = $this->input->post('toDate');

            $data["userInfo"] = $this->user_model->getUserInfoById($userId);

            $data['searchText'] = $searchText;
            $data['fromDate'] = $fromDate;
            $data['toDate'] = $toDate;
            
            $this->load->library('pagination');
            
            $count = $this->user_model->loginHistoryCount($userId, $searchText, $fromDate, $toDate);

            $returns = $this->paginationCompress ( "login-history/".$userId."/", $count, 10, 3);

            $data['userRecords'] = $this->user_model->loginHistory($userId, $searchText, $fromDate, $toDate, $returns["page"], $returns["segment"]);
            
            $this->global['pageTitle'] = 'SEIEM : User Login History';
            
            $this->loadViews("users/loginHistory", $this->global, $data, NULL);
        }        
    }

    /**
     * This function is used to show users profile
     */
    function profile($active = "details")
    {
        $data["userInfo"] = $this->user_model->getUserInfoWithRole($this->vendorId);
        $data["active"] = $active;
        
        $this->global['pageTitle'] = $active == "details" ? 'SEIEM : My Profile' : 'SEIEM : Change Password';
        $this->loadViews("users/profile", $this->global, $data, NULL);
    }

    /**
     * This function is used to update the user details
     * @param text $active : This is flag to set the active tab
     */
    function profileUpdate($active = "details")
    {
        $this->load->library('form_validation');
            
        $this->form_validation->set_rules('fname','Full Name','trim|required|max_length[128]');
        $this->form_validation->set_rules('mobile','Mobile Number','required|min_length[10]');
      //  $this->form_validation->set_rules('email','Email','trim|required|valid_email|max_length[128]|callback_emailExists');        
        
        if($this->form_validation->run() == FALSE)
        {
            $this->profile($active);
        }
        else
        {
            $name = ucwords(strtolower($this->security->xss_clean($this->input->post('fname'))));
            $mobile = $this->security->xss_clean($this->input->post('mobile'));
            $email = strtolower($this->security->xss_clean($this->input->post('email')));
            
            $userInfo = array('name'=>$name, 'email'=>$email, 'mobile'=>$mobile, 'updatedBy'=>$this->vendorId, 'updatedDtm'=>date('Y-m-d H:i:s'));
            
            $result = $this->user_model->editUser($userInfo, $this->vendorId);
            
            if($result == true)
            {
                $this->session->set_userdata('name', $name);
                $this->session->set_flashdata('success', 'Profile Actualizado exitosamente');
            }
            else
            {
                $this->session->set_flashdata('error', 'Profile updation failed');
            }

            redirect('profile/'.$active);
        }
    }

    /**
     * This function is used to change the password of the user
     * @param text $active : This is flag to set the active tab
     */
    function changePassword($active = "changepass")
    {
        $this->load->library('form_validation');
        
        $this->form_validation->set_rules('oldPassword','Old password','required|max_length[20]');
        $this->form_validation->set_rules('newPassword','New password','required|max_length[20]');
        $this->form_validation->set_rules('cNewPassword','Confirm new password','required|matches[newPassword]|max_length[20]');
        
        if($this->form_validation->run() == FALSE)
        {
            $this->profile($active);
        }
        else
        {
            $oldPassword = $this->input->post('oldPassword');
            $newPassword = $this->input->post('newPassword');
            
            $resultPas = $this->user_model->matchOldPassword($this->vendorId, $oldPassword);
            
            if(empty($resultPas))
            {
                $this->session->set_flashdata('nomatch', 'Your old password is not correct');
                redirect('profile/'.$active);
            }
            else
            {
                $usersData = array('password'=>getHashedPassword($newPassword), 'updatedBy'=>$this->vendorId,
                                'updatedDtm'=>date('Y-m-d H:i:s'));
                
                $result = $this->user_model->changePassword($this->vendorId, $usersData);
                
                if($result > 0) { $this->session->set_flashdata('success', 'Password updation successful'); }
                else { $this->session->set_flashdata('error', 'Password updation failed'); }
                
                redirect('profile/'.$active);
            }
        }
    }

    /**
     * This function is used to check whether email already exist or not
     * @param {string} $email : This is users email
     */
    function emailExists($email)
    {
        $userId = $this->vendorId;
        $return = false;

        if(empty($userId)){
            $result = $this->user_model->checkEmailExists($email);
        } else {
            $result = $this->user_model->checkEmailExists($email, $userId);
        }

        if(empty($result)){ $return = true; }
        else {
            $this->form_validation->set_message('emailExists', 'The {field} already taken');
            $return = false;
        }

        return $return;
    }
}

?>