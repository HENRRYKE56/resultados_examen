<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';
require APPPATH .'/libraries/Pdf.php';

/**
 * Class : Task (TaskController)
 * Task Class to control task related operations.
 * @author : Kishor Mali
 * @version : 1.5
 * @since : 19 Jun 2022
 */
class Oficios extends BaseController
{
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Oficios_model', 'tm');
        $this->isLoggedIn();
        $this->module = 'Oficios';
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->load->library('upload');
        $this->load->library('session');
        

    }
    function imprimir($oficio){
              
$pdf = new Pdf(PDF_PAGE_ORIENTATION, PDF_UNIT, "LETTER", true, 'UTF-8', false);
ini_set('max_execution_time', 300); // 300 segundos = 5 minutos 
ini_set('memory_limit', '512M');
// Configura la información del documento
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('HLANDEROS');


// Configura las cabeceras y los pies de página
$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);

// Crear instancia de TCPDF
$pdf = new Pdf();
$pdf->SetTitle('Justificante de Incidencias');

// Agregar página
$pdf->AddPage("P", "letter");

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

$informacionOficio = $this->tm->ObtenerInfoOficio1($oficio);

 
$fecha_actual = new DateTime($informacionOficio->fecha_oficio);

    $fecha=$fecha_actual->format('d-m-Y');
$fecha=explode("-", $fecha);
$dia1 = $fecha[0];
$mes1 = $fecha[1];
$anio1 = $fecha[2];
   $meses1 = array(
    '01' => 'enero',
    '02' => 'febrero',
    '03' => 'marzo',
    '04' => 'abril',
    '05' => 'mayo',
    '06' => 'junio',
    '07' => 'julio',
    '08' => 'agosto',
    '09' => 'septiembre',
    '10' => 'octubre',
    '11' => 'noviembre',
    '12' => 'diciembre'
);

    $mes1 = $meses1[$mes1]; // Obtener el nombre del mes correspondiente    
    $fecha=$dia1." de ".$mes1." de ".$anio1;





// Configurar fuente y encabezado
$pdf->SetFont('helvetica', '', 9);
$pdf->SetXY(138, 40);
$pdf->Cell(50, 20, 'Toluca México., a '.$fecha, 0, 0,"R");

$pdf->SetXY(140, 50);
$pdf->SetFont('helvetica', '', 9); // normal
$pdf->Cell(30, 10, 'No. De Oficio:    228C0101140001L / ', 0, 0, 'R');



$pdf->SetXY(168, 50);
$pdf->SetFont('helvetica', 'B', 9); // normal
$pdf->Cell(10, 10, $informacionOficio->no_oficio, 0, 0, 'R');
$pdf->SetFont('helvetica', '', 9); // normal
$pdf->Cell(10, 10, ' / '.$anio1, 0, 1, 'R');

echo "<pre>"; print_r($informacionOficio);die("aqui va el otro formato");

$nombreMayusculas = mb_strtoupper($informacionOficio->dirigido_a, 'UTF-8');
$pdf->SetFont('helvetica', 'B', 9);
$pdf->SetXY(20, 69);
$pdf->Cell(50, 10,$nombreMayusculas, 0, 1, 'L');
$dependencia = mb_strtoupper($informacionOficio->puesto. $informacionOficio->des_dependencia, 'UTF-8');
$pdf->SetXY(20, 73);
$pdf->Cell(50, 10,$dependencia, 0, 1, 'L');
$pdf->SetXY(20, 77);
$pdf->Cell(50, 10,"P R E S E N T E", 0, 1, 'L');



if($informacionOficio->cuerpo_oficio==""){

$pdf->SetXY(20, 90);
$pdf->Cell(50, 10,$informacionOficio->asunto , 0, 1, 'L');
/// {$informacionOficio->lugar_origen}
$pdf->SetDrawColor(0, 0, 0);       // Color del borde (negro)
$pdf->Rect(20, 107, 50, 15); // Posición X, Y, ancho, alto, opciones
$pdf->Rect(20, 122, 50, 15); // Posición X, Y, ancho, alto, opciones
$pdf->Rect(20, 137, 50, 8); // Posición X, Y, ancho, alto, opciones
$pdf->Rect(20, 145, 50, 8); // Posición X, Y, ancho, alto, opciones
$pdf->Rect(20, 153, 50, 8); // Posición X, Y, ancho, alto, opciones
$pdf->Rect(20, 161, 50, 8); // Posición X, Y, ancho, alto, opciones



 $pdf->SetFont('helvetica', '', 9);

$html = '<b>LUGAR DE ORIGEN</b><br><small>(DOMICILIO Y UNIDAD ADMINISTRATIVA)</small>';
$html1 = '<b>LUGAR DESTINO</b><br/><small>(DOMICILIO Y UNIDAD ADMINISTRATIVA)</small>';
$html2 = '<b>FECHA DE LA COMISIÓN</b>';
$html3 = '<b>MOTIVO DE LA COMISIÓN</b>';
$html4 = '<b>KILOMETRAJE</b>';
$html5 = '<b>MODALIDAD</b>';
$pdf->writeHTMLCell(50, 15, '20', '110', $html, 1, 1, false, true, 'C', true);
$pdf->writeHTMLCell(50, 15, '20', '124', $html1, 1, 1, false, true, 'C', true);
$pdf->writeHTMLCell(50, 15, '20', '139', $html2, 1, 1, false, true, 'C', true);
$pdf->writeHTMLCell(50, 15, '20', '147', $html3, 1, 1, false, true, 'C', true);
$pdf->writeHTMLCell(50, 15, '20', '155', $html4, 1, 1, false, true, 'C', true);
$pdf->writeHTMLCell(50, 15, '20', '163', $html5, 1, 1, false, true, 'C', true);


$html = 'DEPARTAMENTO DE FORMACIÓN PROFESIONAL DE SEIEM, UBICADO EN AV. AGRIPÍN GARCÍA ESTRADA NO. 1306, STA. CRUZ ATZCAPOTZALTONGO, TOLUCA, ESTADO DE MÉXICO';

$pdf->Rect(70, 107, 120, 15); // Posición X, Y, ancho, alto, opciones
$pdf->Rect(70, 122, 120, 15); // Posición X, Y, ancho, alto, opciones
$pdf->Rect(70, 137, 120, 8); // Posición X, Y, ancho, alto, opciones
$pdf->Rect(70, 145, 120, 8); // Posición X, Y, ancho, alto, opciones
$pdf->Rect(70, 153, 120, 8); // Posición X, Y, ancho, alto, opciones
$pdf->Rect(70, 161, 120, 8); // Posición X, Y, ancho, alto, opciones

$pdf->writeHTMLCell(120, 15, 70, 108, $html, 1, 1, false, true, 'C', true);
$pdf->writeHTMLCell(120, 15, 70, 125, mb_strtoupper($informacionOficio->lugar_comision,"UTF-8"), 1, 1, false, true, 'C', true);
$pdf->writeHTMLCell(120, 8, 70, 139,$dia_comsion. " DE ".$mes_comision." DE ".$año_comsion, 1, 1, false, true, 'L', true);
$pdf->writeHTMLCell(120, 8, 70, 146,mb_strtoupper($informacionOficio->motivo_comision,"UTF-8"), 1, 1, false, true, 'L', true);
$pdf->writeHTMLCell(120, 8, 70, 154, $informacionOficio->kilometraje, 1, 1, false, true, 'L', true);
$pdf->writeHTMLCell(120, 8, 70, 162, $informacionOficio->modalidad, 1, 1, false, true, 'L', true);




    }else{
      //  echo"<pre>yes";
       // echo $informacionOficio->cuerpo_oficio;
//print_r($informacionOficio);
//die();
//die("aqui va el otro formato");

    }

$pdf->SetFont('helvetica', 'B', 9);

$pdf->Rect(82, 187, 60, 30, 'D'); // Posición X, Y, ancho, alto, opciones
$pdf->SetFont('helvetica', 'B', 8);
$pdf->SetXY(90, 190);
$pdf->MultiCell(42, 10,"SELLO Y FIRMA DE\nCUMPLIMIENTO\nDE LA COMISIÓN", 0, "C", 'false');
$cargo=str_replace("|", "\n", $informacionOficio->puesto_jefe);

$pdf->SetXY(33, 235);
$pdf->SetFont('helvetica', 'B', 9);
$pdf->MultiCell(60, 10,"ATENTAMENTE\n\n\n\n".mb_strtoupper($informacionOficio->abreviatura_titulo.". ".$informacionOficio->nombre_jefe." ", 'UTF-8').$cargo, 0, "C", 'false');

$puesto_personal=str_replace("|", "\n", $informacionOficio->puesto_personal);

$pdf->SetXY(133, 235);
$pdf->MultiCell(60, 10,"RECIBE Y ACEPTA\n\n\n\n".mb_strtoupper($informacionOficio->nombre." ", 'UTF-8').$puesto_personal."\n"."(SERVIDOR PÚBLICO COMISIONADO)\nNOMBRE, FIRMA Y CARGO", 0, "C", 'false');
//echo"<pre>"; print_r($informacionOficio);die("incidencia sin oficio");



$pdf->Output('reporte_incidencias.pdf', 'I');

    }
function addIncidencia(){
    if(!$this->hasCreateAccess())
        {
            $this->loadThis();
        }
        else
        {
            

            $personal = $this->tm->jefes_departamento();
            $options = array();
            foreach ($personal as $persona) {   
                $options[$persona['cve_jefe']] = $persona['nombre_jefe'];
            }
            $personas =  $options;

            $dependencias = $this->tm->dependencias();
            $options1 = array();
            foreach ($dependencias as $dependencia) {   
                $options1[$dependencia['cve_dependencia']] = $dependencia['enlace_dependencia'];
            }
         $dependencias = array('' => 'Seleccione') + $options1;
       
            $this->data['asunto'] = array(
                'name'  => 'asunto',
                'id'    => 'asunto',
                'type'  => 'text',
                'class' => 'form-control',
                'value' => $this->form_validation->set_value('asunto'),
            );
            $this->data['destinatario'] = array(
                'name'  => 'destinatario',
                'id'    => 'destinatario',
                'class' => 'form-control',
                'options' => $dependencias,
                'value' => $this->form_validation->set_value('destinatario'),
               
            );
            $this->data['quien_firma'] = array(
                'name'  => 'quien_firma',
                'id'    => 'quien_firma',
                'class' => 'form-control',
                'options' => $personas,
                'value' => $this->form_validation->set_value('quien_firma'),
               
            );
            $this->data['fecha_comision'] = array(
                'name'  => 'fecha_comision',
                'id'    => 'fecha_comision',
                'type'  => 'date',
                'class' => 'form-control',
                'value' => $this->form_validation->set_value('fecha_comision'),
                
            );
          
            $this->data['lugar_comision'] = array(
                'name'  => 'lugar_comision',
                'id'    => 'lugar_comision',
                'class' => 'form-control',
                'type'  => 'text',
                'value' => $this->form_validation->set_value('lugar_comision'),
            );

            $tipo_omisiones = $this->tm->tipo_omision();
            $option5 = array();
            
            foreach ($tipo_omisiones as $tipo_omision) {   
                $option5[$tipo_omision['cve_tipo_omision']] = $tipo_omision['des_tipo_omision'];
            }
            $tipo_omisiones = array('' => 'Seleccione un tipo de Incidencia') + $option5;

           
     
           
            $this->data['tipo_omision'] = array(
                'name'  => 'tipo_omision',
                'id'    => 'tipo_omision',                
                'options' => $tipo_omisiones,
                'class' => 'form-control',
                'value' => $this->form_validation->set_value('tipo_omision'),
            );
            $this->data['omision'] = array(
                'name'  => 'omision',
                'id'    => 'omision',              
                'class' => 'form-control',
                'value' => $this->form_validation->set_value('omision'),
            );
            $this->data['descripcion_omision'] = array(
                'name'  => 'descripcion_omision',
                'id'    => 'descripcion_omision',              
                'class' => 'form-control',
                'type'  => 'text',
                'value' => $this->form_validation->set_value('descripcion_omision'),
            );

            $this->global['pageTitle'] = 'SEIEM : Incidencia';

            $this->loadViews("oficios/addIncidencia", $this->global, $this->data, NULL);
        }
}
function getOmisiones() {
  
    $tipo_omision = $this->input->post('tipo_omision');

    $omisiones = $this->tm->omisiones($tipo_omision);

    echo json_encode($omisiones);
}
    function incidencias(){
        if(!$this->hasListAccess())
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
            
            $count = $this->tm->incidenciasListingCount($searchText);
            $returns = $this->paginationCompress ( "incidencias/", $count, 10 );
            
            $data['records'] = $this->tm->incidenciasListing($searchText, $returns["page"], $returns["segment"]);

            ///////incidencias sin oficio 

            $searchText1 = '';
            if(!empty($this->input->post('searchText1'))) {
                $searchText1 = $this->security->xss_clean($this->input->post('searchText1'));
            }
            $data['searchText1'] = $searchText1;
            
            $this->load->library('pagination');
            
            $count1 = $this->tm->incidenciasListingCount1($searchText1);
            $returns1 = $this->paginationCompress ( "incidencias/", $count1, 10 );
            
            $data['records1'] = $this->tm->incidenciasListing1($searchText1, $returns1["page"], $returns1["segment"],$this->vendorId);
            
            $this->global['pageTitle'] = 'SEIEM : Incidencias ';
            
            $this->loadViews("oficios/Lista_incidencias", $this->global, $data, NULL);
        }
    }
    public function index()
    {
        redirect('oficios/OficiosListing');
    }
    
    /**
     * This function is used to load the task list
     */
    function OficiosListing()
    {
        if(!$this->hasListAccess())
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
            
            $count = $this->tm->oficiosListingCount($searchText);

			$returns = $this->paginationCompress ( "oficiosListing/", $count, 10 );
            
            $data['records'] = $this->tm->oficiosListing($searchText, $returns["page"], $returns["segment"]);
            
            $this->global['pageTitle'] = 'SEIEM : Oficios ';
            
            $this->loadViews("oficios/list", $this->global, $data, NULL);
        }
    }

    /**
     * This function is used to load the add new form
     */
    function add()
    {
        if(!$this->hasCreateAccess())
        {
            $this->loadThis();
        }
        else
        {
            

            $personal = $this->tm->jefes_departamento();
            $options = array();
            foreach ($personal as $persona) {   
                $options[$persona['cve_jefe']] = $persona['nombre_jefe'];
            }
            $personas =  $options;

         $personal_comisionado= $this->tm->personal_comisionado();
         $opc= array();
            foreach ($personal_comisionado as $personall) {   
                $opc[$personall['rfc_personal']] = $personall['nombre'];
            }
            $personal_comisionado = array('' => 'Seleccione') + $opc;
       




            $dependencias = $this->tm->dependencias();
            $options1 = array();
            foreach ($dependencias as $dependencia) {   
                $options1[$dependencia['cve_dependencia']] = $dependencia['des_dependencia'];
            }
         $dependencias = array('' => 'Seleccione una Dependencia') + $options1;
       
            $this->data['asunto'] = array(
                'name'  => 'asunto',
                'id'    => 'asunto',
                'type'  => 'text',
                'class' => 'form-control',
                'value' => $this->form_validation->set_value('asunto'),
            );
             $this->data['dirigido_a'] = array(
                'name'  => 'dirigido_a',
                'id'    => 'dirigido_a',
                'type'  => 'text',
                'class' => 'form-control',
                'value' => $this->form_validation->set_value('dirigido_a'),
            );
            $this->data['destinatario'] = array(
                'name'  => 'destinatario',
                'id'    => 'destinatario',
                'class' => 'form-control',
                'options' => $dependencias,
                'value' => $this->form_validation->set_value('destinatario'),
               
            );
               $this->data['personal_comisionado'] = array(
                'name'  => 'personal_comisionado',
                'id'    => 'personal_comisionado',
                'class' => 'form-control',
                'options' => $personal_comisionado,
                'value' => $this->form_validation->set_value('personal_comisionado'),
               
            );
            $this->data['quien_firma'] = array(
                'name'  => 'quien_firma',
                'id'    => 'quien_firma',
                'class' => 'form-control',
                'options' => $personas,
                'value' => $this->form_validation->set_value('quien_firma'),
               
            );
            $this->data['fecha_comision'] = array(
                'name'  => 'fecha_comision',
                'id'    => 'fecha_comision',
                'type'  => 'date',
                'class' => 'form-control',
                'value' => $this->form_validation->set_value('fecha_comision'),
                
            );
          
            $this->data['lugar_comision'] = array(
                'name'  => 'lugar_comision',
                'id'    => 'lugar_comision',
                'class' => 'form-control',
                'type'  => 'text',
                'value' => $this->form_validation->set_value('lugar_comision'),
            );
            $omision = array(
                '0' => 'Seleccione una Opción',
                '1' => 'Entrada',
                '2' => 'Salida',
                '3' => 'Ambos',
            );
            $this->data['omision'] = array(
                'name'  => 'omision',
                'id'    => 'omision',                
                'options' => $omision,
                'class' => 'form-control',
                'value' => $this->form_validation->set_value('omision'),
            );
            ///////////////////agreugar datos de la tabla de tipo de oficio
                $this->data['lugar_origen'] = array(
                'name'  => 'lugar_origen',
                'id'    => 'lugar_origen',                
                'type'  => 'text',
                'class' => 'form-control',
                'value' => $this->form_validation->set_value('lugar_origen'),
            );
         //  
            $this->data['lugar_destino'] = array(
                'name'  => 'lugar_destino',
                'id'    => 'lugar_destino',                
                'type'  => 'text',
                'class' => 'form-control',
                'value' => $this->form_validation->set_value('lugar_destino'),
            );
            $this->data['kilometraje'] = array(
                'name'  => 'kilometraje',
                'id'    => 'kilometraje',                
                'type'  => 'text',
                'class' => 'form-control',
                'value' => $this->form_validation->set_value('kilometraje'),
            );
               $this->data['motivo_comision'] = array(
                'name'  => 'motivo_comision',
                'id'    => 'motivo_comision',                
                'type'  => 'text',
                'class' => 'form-control',
                'value' => $this->form_validation->set_value('kilometraje'),
            );
            $this->data['modalidad'] = array(
                'name'  => 'modalidad',
                'id'    => 'modalidad',                
                 'options' => array(""=>"SELECCIONE","B2"=>'B2',"B3"=>"B3"),
                'class' => 'form-control',
                'value' => $this->form_validation->set_value('kilometraje'),
            );
            /***fin */

            $this->global['pageTitle'] = 'SEIEM : Nuevo Oficio';

            $this->loadViews("oficios/add", $this->global, $this->data, NULL);
        }
    }
    
    /**
     * This function is used to add new user to the system
     */
    function AgregarNuevoOficio()
    {
        if(!$this->hasCreateAccess())
        {
            $this->loadThis();
        }
        else
        {
            $this->load->library('form_validation');
            
            $this->form_validation->set_rules('asunto','Asunto','trim|callback_html_clean|required|max_length[256]');
            $this->form_validation->set_rules('destinatario','Destinatario','trim|callback_html_clean|required|max_length[1024]');
            //   $this->form_validation->set_rules('quien_firma','Quien FIrma','trim|callback_html_clean|required|max_length[1024]');
            //   $this->form_validation->set_rules('fecha_comision','Fecha de la comisión','trim|callback_html_clean|required|max_length[1024]');
            //   $this->form_validation->set_rules('lugar_comision','Lugar de la comisión','trim|callback_html_clean|required|max_length[1024]');

            if($this->form_validation->run() == FALSE)
            {
                $this->add();
            //die("validacion incorrecta");
            }
            else
            {
               
                              
                
                $OficioInfo = array('asunto' => $this->security->xss_clean($this->input->post('asunto')),
                                'fecha_oficio' => date('Y-m-d H:i:s'),
                                'oficio_creado_por' => $this->vendorId,
                                'dirigido_a' => $this->security->xss_clean($this->input->post('dirigido_a')),
                                'destinatario' => $this->security->xss_clean($this->input->post('destinatario')),
                                'quien_firma' => $this->security->xss_clean($this->input->post('quien_firma')),
                                'fecha_comision' => $this->security->xss_clean($this->input->post('fecha_comision')),
                                'lugar_comision' => $this->security->xss_clean($this->input->post('lugar_comision')),
                                'cuerpo_oficio' => $this->security->xss_clean($this->input->post('cuerpo_oficio')),
                                'omision' => $this->security->xss_clean($this->input->post('omision')),
                                'motivo_comision' => $this->security->xss_clean($this->input->post('motivo_comision')),
                                'kilometraje' => $this->security->xss_clean($this->input->post('kilometraje')),
                                'modalidad' => $this->security->xss_clean($this->input->post('modalidad')),
                                'personal_comisionado' => $this->security->xss_clean($this->input->post('personal_comisionado')),

                                  'cuerpo_oficio' => $this->security->xss_clean( $this->input->post('cuerpo_oficio')),
                                'estado'=>'0', 
                                'createdDtm'=>date('Y-m-d H:i:s'));
                
                $result = $this->tm->agregarNuevoOficio($OficioInfo);
                
                if($result > 0) {
                    $this->session->set_flashdata('success', 'Nuevo Oficio creado exitosamente');
                } else {
                    $this->session->set_flashdata('error', 'Oficio no se pudo crear');
                }
                
                redirect('oficios/OficiosListing');
            }
        }
    }

    
    /**
     * This function is used load task edit information
     * @param number $no_oficio : Optional : This is task id
     */
    function EditarOficio($no_oficio = NULL)
    {
        if(!$this->hasUpdateAccess())
        {
            $this->loadThis();
        }
        else
        {
            if($no_oficio == null)
            {
                redirect('oficios/OficiosListing');
            }
       

            $informacionOficio = $this->tm->ObtenerInfoOficio($no_oficio);

            $this->global['pageTitle'] = 'SEIEM : Editar Oficio';
            $oficio_creado_por= $this->tm->oficio_creado_por($informacionOficio->oficio_creado_por);
            $oficio_creado_por=$oficio_creado_por->name;
          

 $personal_comisionado= $this->tm->personal_comisionado();
         $opc= array();
            foreach ($personal_comisionado as $personall) {   
                $opc[$personall['rfc_personal']] = $personall['nombre'];
            }
            $personal_comisionado = array('' => 'Seleccione') + $opc;
       


            $personal = $this->tm->jefes_departamento();
            $options = array();
            foreach ($personal as $persona) {   
                $options[$persona['cve_jefe']] = $persona['nombre_jefe'];
            }
            $personas =  $options;

            $dependencias = $this->tm->dependencias();
            $options1 = array();
            foreach ($dependencias as $dependencia) {   
                $options1[$dependencia['cve_dependencia']] = $dependencia['des_dependencia'];
            }
         $dependencias = array('' => 'Seleccione una Dependencia') + $options1;
       
            $this->data['asunto'] = array(
                'name'  => 'asunto',
                'id'    => 'asunto',
                'type'  => 'text',
                'class' => 'form-control',
                'readonly' => 'readonly',
                'value' => $this->form_validation->set_value('asunto', $informacionOficio->asunto),
            );
            $this->data['no_oficio'] = ($no_oficio);
              $this->data['personal_comisionado'] = array(
                'name'  => 'personal_comisionado',
                'id'    => 'personal_comisionado',
                'class' => 'form-control',
                'options' => $personal_comisionado,
                 'selected' => $informacionOficio->personal_comisionado,
               
            );
            $this->data['destinatario'] = array(
                'name'  => 'destinatario',
                'id'    => 'destinatario',
                'class' => 'form-control',
                'options' => $dependencias,
                'readonly' => 'readonly',
               
                'selected' => $informacionOficio->destinatario,
                 
            );
            $this->data['oficio_creado_por'] = array(
                'name'  => 'oficio_creado_por',
                'id'    => 'oficio_creado_por',
                'type'  => 'text',
                'class' => 'form-control',
                'readonly' => 'readonly',
               
                'value' => $this->form_validation->set_value('asunto', $oficio_creado_por),
            );
          
            $this->data['quien_firma'] = array(
                'name'  => 'quien_firma',
                'id'    => 'quien_firma',
                'class' => 'form-control',
                'options' => $personas,
                'readonly' => 'readonly',
               
                'selected' => $informacionOficio->quien_firma,
                
            );
            $omision = array(
                '0' => 'Seleccione una Opción',
                '1' => 'Entrada',
                '2' => 'Salida',
                '3' => 'Ambos',
            );
            $this->data['omision'] = array(
                'name'  => 'omision',
                'id'    => 'omision',                
                'options' => $omision,
                'class' => 'form-control',
                'selected' => $informacionOficio->omision,
            
            );
            $this->data['fecha_comision'] = array(
                'name'  => 'fecha_comision',
                'id'    => 'fecha_comision',
                'type'  => 'date',
                'class' => 'form-control',
                'readonly' => 'readonly',
               
                
                'value' => $this->form_validation->set_value('fecha_comision', $informacionOficio->fecha_comision),
                
            );
            $this->data['fecha_oficio'] = array(
                'name'  => 'fecha_oficio',
                'id'    => 'fecha_oficio',
                'type'  => 'date',
                'class' => 'form-control',
                'readonly' => 'readonly',
               
                'value' => $this->form_validation->set_value('fecha_oficio', $informacionOficio->fecha_oficio),
                
            );
            $this->data['lugar_comision'] = array(
                'name'  => 'lugar_comision',
                'id'    => 'lugar_comision',
                'type'  => 'text',
                'class' => 'form-control',
                'readonly' => 'readonly',
               
                'value' => $this->form_validation->set_value('lugar_comision', $informacionOficio->lugar_comision),
            );
            
            $this->data['cuerpo_oficio'] = array(
                'name'  => 'cuerpo_oficio',
                'id'    => 'cuerpo_oficio',
                'class' => 'form-control',
                 'rows' => '5', // Número de filas del textarea
                'cols' => '50', // Opcional, número de columnas
                'value' => $this->form_validation->set_value('cuerpo_oficio', $informacionOficio->cuerpo_oficio),
            );
    
            $this->data['kilometraje'] = array(
                'name'  => 'kilometraje',
                'id'    => 'kilometraje',                
                'type'  => 'text',
                'class' => 'form-control',
                'value' => $this->form_validation->set_value('kilometraje' ,$informacionOficio->kilometraje),
            );
               $this->data['motivo_comision'] = array(
                'name'  => 'motivo_comision',
                'id'    => 'motivo_comision',                
                'type'  => 'text',
                'class' => 'form-control',
                'value' => $this->form_validation->set_value('motivo_comision',$informacionOficio->motivo_comision),
            );
            $this->data['modalidad'] = array(
                'name'  => 'modalidad',
                'id'    => 'modalidad',                
                 'options' => array(""=>"SELECCIONE","B2"=>'B2',"B3"=>"B3"),
                  'selected' => $informacionOficio->modalidad,
                'class' => 'form-control',
            );





            $this->loadViews("oficios/edit", $this->global, $this->data, NULL);
        }
    }
    
    
    /**
     * This function is used to edit the user information
     */
        function guardarIncidencia(){
            if(!$this->hasUpdateAccess())
            {
                $this->loadThis();
            }
            else
            {   
                $this->load->library('form_validation');
            
                $this->form_validation->set_rules('descripcion_omision','Descripción de la Omisión','trim|callback_html_clean|required|max_length[256]');
                $this->form_validation->set_rules('destinatario','destinatario','trim|callback_html_clean|required|max_length[1024]');
                $this->form_validation->set_rules('tipo_omision','Tipo de Comisión','trim|callback_html_clean|required|max_length[1024]');
                $this->form_validation->set_rules('fecha_comision','Fecha de la comisión','trim|callback_html_clean|required|max_length[1024]');
                
                
          
                $OficioInfo = array('descripcion_omision' => $this->security->xss_clean($this->input->post('descripcion_omision')),
                'oficio_creado_por' => $this->vendorId,
                'quien_firma' =>1,
                'fecha_comision' => $this->security->xss_clean($this->input->post('fecha_comision')),
                'omision' => $this->security->xss_clean($this->input->post('omision')),
                'tipo_omision' => $this->security->xss_clean($this->input->post('tipo_omision')),
                  
                'estado'=>'0', 
                'createdDtm'=>date('Y-m-d H:i:s'));
      
             

                $result = $this->tm->agregarNuevoIncidencia($OficioInfo);
                
                if($result > 0) {
                    $this->session->set_flashdata('success', 'Nuevo Oficio creado exitosamente');
                } else {
                    $this->session->set_flashdata('error', 'Oficio no se pudo crear');
                }
                
                redirect('oficios/incidencias');
            

            }
            
    }

    function guardarOficio()
    {
        if(!$this->hasUpdateAccess())
        {
            $this->loadThis();
        }
        else
        {
            $this->load->library('form_validation');
            
            $this->form_validation->set_rules('asunto','Asunto','trim|callback_html_clean|required|max_length[256]');
            $this->form_validation->set_rules('destinatario','destinatario','trim|callback_html_clean|required|max_length[1024]');
            $this->form_validation->set_rules('quien_firma','Quien FIrma','trim|callback_html_clean|required|max_length[1024]');
            $this->form_validation->set_rules('fecha_comision','Fecha de la comisión','trim|callback_html_clean|required|max_length[1024]');
            $this->form_validation->set_rules('lugar_comision','Lugar de la comisión','trim|callback_html_clean|required|max_length[1024]');
            $no_oficio = $this->input->post('no_oficio');
            if($this->form_validation->run() == FALSE)
            {
                $this->EditarOficio( $no_oficio);
            }
            else
            {
                
             

                $OficioInfo = array('asunto' => $this->security->xss_clean($this->input->post('asunto')),
                                'fecha_oficio' => date('Y-m-d H:i:s'),
                                'oficio_creado_por' => $this->vendorId,
                                'destinatario' => $this->security->xss_clean($this->input->post('destinatario')),
                                'quien_firma' => $this->security->xss_clean($this->input->post('quien_firma')),
                                'fecha_comision' => $this->security->xss_clean($this->input->post('fecha_comision')),
                                'lugar_comision' => $this->security->xss_clean($this->input->post('lugar_comision')),
                                'cuerpo_oficio' => $this->security->xss_clean($this->input->post('cuerpo_oficio')),
                                'omision' => $this->security->xss_clean($this->input->post('omision')),
                                'cuerpo_oficio' => $this->security->xss_clean( $this->input->post('cuerpo_oficio')),
                                'modalidad' => $this->security->xss_clean($this->input->post('modalidad')),
                'kilometraje' => $this->security->xss_clean($this->input->post('kilometraje')),
                'personal_comisionado' => $this->security->xss_clean($this->input->post('personal_comisionado')),
                'motivo_comision' => $this->security->xss_clean($this->input->post('motivo_comision')),


              
                                'estado'=>'0', 
                                'updatedBy'=>$this->vendorId,
                                'updatedDtm'=>date('Y-m-d H:i:s'));
                      
             
                $result = $this->tm->GuardarOficio($OficioInfo, $no_oficio);
                
                if($result > 0) {
                    $this->session->set_flashdata('success', 'Se actualizo el Oficio '.$no_oficio.'  exitosamente');
                } else {
                    $this->session->set_flashdata('error', 'El Oficio '.$no_oficio.' no se pudo actualizar');
                }
                
                redirect('oficios/OficiosListing');
            }
        }
    }
    function imprmirincidencia1($cve_incidencia = NULL)
    {
        if(!$this->hasUpdateAccess())
        {
            $this->loadThis();
        }
        else
        {
            if($cve_incidencia == null)
            {
                redirect('oficios/OficiosListing');
            }
        
$pdf = new Pdf(PDF_PAGE_ORIENTATION, PDF_UNIT, "LETTER", true, 'UTF-8', false);
ini_set('max_execution_time', 300); // 300 segundos = 5 minutos 
ini_set('memory_limit', '512M');
// Configura la información del documento
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('HLANDEROS');


// Configura las cabeceras y los pies de página
$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);






// Crear instancia de TCPDF
$pdf = new Pdf();
$pdf->SetTitle('Justificante de Incidencias');

// Agregar página
$pdf->AddPage("P", "letter");

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

$informacionIncidencia = $this->tm->ObtenerInfoIncidencia($cve_incidencia);
/*
echo"<pre>";

print_r($informacionIncidencia);die("incidencia sin oficio");
die("incidencia sin oficio");
*/

$fecha_actual = new DateTime($informacionIncidencia->fecha_comision);
$dias = 1;

// Lista de fechas feriadas
$feriados = [
    '2025-05-01',
    '2025-05-05',
    '2025-05-15',
    '2025-05-16'
];
 // Verificar si la fecha de comisión es el último día del mes
 if ($fecha_actual->format('d') == $fecha_actual->format('t')) {
    // Si es fin de mes, no modificar la fecha y salir del proceso
    $fecha_actual=$fecha_actual;
}else{

while ($dias > 0) {
    $fecha_actual->modify('+1 day'); // Avanzar un día
    $diaSemana = $fecha_actual->format('N'); // Obtener día de la semana (1=Lunes, 7=Domingo)
    $fechaStr = $fecha_actual->format('Y-m-d'); // Formato de fecha para comparación

   

    // Si la fecha es un día hábil (lunes a viernes)
    if ($diaSemana < 6) {
        // Si la fecha es feriado, avanzar un día más
        if (in_array($fechaStr, $feriados)) {
            $fecha_actual->modify('+1 day');
            $diaSemana = $fecha_actual->format('N'); // Actualizar día de la semana

            // Si el feriado es viernes, mover la fecha al lunes
            if ($diaSemana == 5) {
                $fecha_actual->modify('+3 days');
            }
        }
        $dias--;
    }
}
}
    $fecha=$fecha_actual->format('d-m-Y');
$fecha=explode("-", $fecha);
$dia1 = $fecha[0];
$mes1 = $fecha[1];
$anio1 = $fecha[2];
    $meses1 = array(
        '01' => 'ENERO',
        '02' => 'FEBRERO',
        '03' => 'MARZO',
        '04' => 'ABRIL',
        '05' => 'MAYO',
        '06' => 'JUNIO',
        '07' => 'JULIO',
        '08' => 'AGOSTO',
        '09' => 'SEPTIEMBRE',
        '10' => 'OCTUBRE',
        '11' => 'NOVIEMBRE',
        '12' => 'DICIEMBRE'
    );
    $mes1 = $meses1[$mes1]; // Obtener el nombre del mes correspondiente    
    $fecha=$dia1." DE ".$mes1." DE ".$anio1;




// Configurar fuente y encabezado
$pdf->SetFont('helvetica', 'B', 9);
$pdf->SetXY(0, 48);
$pdf->Cell(0, 10, 'JUSTIFICANTE DE INCIDENCIAS DE PUNTUALIDAD Y ASISTENCIA', 0, 1, 'C');
$pdf->SetXY(110, 57);
$pdf->Cell(50, 20, 'FECHA: '.$fecha, 0, 0,"L");


$pdf->SetDrawColor(0, 0, 0);       // Color del borde (negro)
$pdf->Rect(9, 70, 180, 40, 'D'); // Posición X, Y, ancho, alto, opciones

$pdf->Rect(9, 110, 180, 80, 'D'); // Posición X, Y, ancho, alto, opciones

$pdf->Rect(9, 190, 180, 10, 'D'); // Posición X, Y, ancho, alto, opciones

$pdf->Rect(9, 200, 90, 12, 'D'); // Posición X, Y, ancho, alto, opciones
$pdf->Rect(99, 200, 90, 12, 'D'); // Posición X, Y, ancho, alto, opciones
$pdf->Rect(9, 212, 180, 12, 'D'); // Posición X, Y, ancho, alto, opciones

$pdf->Line(19, 78, 189, 78); // (X1, Y1, X2, Y2)
$pdf->Line(10, 101, 100, 101); // (X1, Y1, X2, Y2)
$pdf->Line(120, 101, 189, 101); // (X1, Y1, X2, Y2)
$pdf->Line(30, 107, 189, 107); // (X1, Y1, X2, Y2)
$pdf->Line(110, 150, 189, 150); // (X1, Y1, X2, Y2)
$pdf->Line(108, 182, 189, 182); // (X1, Y1, X2, Y2)
$pdf->Line(108, 142, 189, 142); // (X1, Y1, X2, Y2)
$pdf->Line(108, 167, 189, 167); // (X1, Y1, X2, Y2)
$pdf->SetFont('helvetica', '', 10);
$pdf->SetXY(10, 71);
//$pdf->Cell(50, 10, 'C:', 0, 0);
$pdf->SetXY(25, 71);
$pdf->Cell(100, 9, 'MTRA. SILVIA SÁNCHEZ LARA', 0, 1,'L');
$pdf->SetXY(9, 76);
$pdf->SetFont('helvetica', '', 8);
$pdf->Cell(100, 10, 'RESPONSABLE DEL REGISTRO Y CONTROL DE ASISTENCIA Y PUNTUALIDAD', 0, 1,'L');
$pdf->SetXY(9, 80);
$pdf->Cell(100, 10, 'PRESENTE', 0, 1,'L');
$pdf->SetXY(9, 89);

$fecha_separada = explode("-", $informacionIncidencia->fecha_comision);
$dia = $fecha_separada[2];
$mes = $fecha_separada[1];
$anio = $fecha_separada[0];
$meses = array(
    '01' => 'ENERO',
    '02' => 'FEBRERO',
    '03' => 'MARZO',
    '04' => 'ABRIL',
    '05' => 'MAYO',
    '06' => 'JUNIO',
    '07' => 'JULIO',
    '08' => 'AGOSTO',
    '09' => 'SEPTIEMBRE',
    '10' => 'OCTUBRE',
    '11' => 'NOVIEMBRE',
    '12' => 'DICIEMBRE'
);
$mes = $meses[$mes]; // Obtener el nombre del mes correspondiente

$pdf->Cell(150, 10, 'SOLICITO A USTED EL REGISTRO DE LA JUSTIFICACIÓN DE LA INCIDENCIA DE LA C.', 0, 1,'L');
$pdf->SetXY(9, 94);
$pdf->SetFont('helvetica', '', 10);



$pdf->Cell(20, 10,mb_strtoupper($informacionIncidencia->name, 'UTF-8') , 0, 0);
$pdf->SetFont('helvetica', '', 8);
$pdf->SetXY(105, 94);
$pdf->Cell(50, 10, 'CON RFC:', 0, 1);
$pdf->SetXY(125, 94);
$pdf->SetFont('helvetica', '', 10);
$pdf->Cell(50, 10, 'LAMH8310086L1', 0, 1);
$pdf->SetXY(10, 100);
$pdf->SetFont('helvetica', '', 8);
$pdf->Cell(50, 10, 'ADSCRITO AL:', 0, 1);
$pdf->SetXY(30, 100);
$pdf->Cell(50, 10, 'DEPARTAMENTO DE FORMACIÓN PROFESIONAL', 0, 1);
$pdf->SetXY(80, 108);
$pdf->Cell(50, 10, 'INCIDENCIAS:', 0, 0);

$pdf->SetXY(10, 112);
$pdf->Cell(0, 10, '(  )    RETARDO “A”                        DÍA(S):_______________   MES:  __________________    AÑO  ________________ ', 0, 0);

$pdf->SetXY(10, 117);
$pdf->Cell(0, 10, '(  )    RETARDO “B”                        DÍA(S): _______________   MES:  __________________    AÑO  ________________ ', 0, 0);
$pdf->SetXY(10, 122);
$pdf->Cell(0, 10, '(  )  DÍA(S) ECONÓMICO(S):         DÍA(S): _______________   MES:  __________________    AÑO  ________________ ', 0, 0);
$pdf->SetFont('helvetica', '', 10);
switch($informacionIncidencia->tipo_omision){
    case 1:
        $tipo_omision="RETARDO “A”";
        $pdf->SetXY(10, 112);
        $pdf->Cell(0, 10, ' X  ', 0, 0); 
        $pdf->SetXY(80, 112);           
        $pdf->Cell(0, 10, $dia, 0, 0);
        $pdf->SetXY(115, 112); 
        $pdf->Cell(0, 10, $mes, 0, 0);
        $pdf->SetXY(150, 112); 
        $pdf->Cell(0, 10, $anio, 0, 0);
  
        break;
    case 2:
        $tipo_omision="RETARDO “B” ";
        $pdf->SetXY(10, 117);
        $pdf->Cell(0, 10, ' X  ', 0, 0); 
        $pdf->SetXY(80, 117);           
        $pdf->Cell(0, 10, $dia, 0, 0);
        $pdf->SetXY(115, 117); 
        $pdf->Cell(0, 10, $mes, 0, 0);
        $pdf->SetXY(150, 117); 
        $pdf->Cell(0, 10, $anio, 0, 0);
      
        
        break;
    case 3:
        $tipo_omision="INASISTENCIA";
        $pdf->SetXY(10, 127);
            $pdf->Cell(0, 10, ' X  ', 0, 0); 
            $pdf->SetXY(80, 127);           
            $pdf->Cell(0, 10, $dia, 0, 0);
            $pdf->SetXY(115, 127); 
            $pdf->Cell(0, 10, $mes, 0, 0);
            $pdf->SetXY(150, 127); 
            $pdf->Cell(0, 10, $anio, 0, 0);
 
            switch($informacionIncidencia->omision){
                case 1:
                    $pdf->SetXY(21, 135); 
                    $pdf->Cell(0, 10,"X" , 0, 0);
                break;
                case 2:
                    $pdf->SetXY(21, 143); 
                    $pdf->Cell(0, 10,"X" , 0, 0);
                break;
                case 4:
                    $pdf->SetXY(70, 143); 
                    $pdf->Cell(0, 10,"X" , 0, 0);
                break;
            }


       

        break;
    case 4:
        $tipo_omision="ENTRADA NO REGISTRADA";
        $pdf->SetXY(10, 150);
            $pdf->Cell(0, 10, ' X  ', 0, 0); 
            $pdf->SetXY(80, 150);           
            $pdf->Cell(0, 10, $dia, 0, 0);
            $pdf->SetXY(115, 150); 
            $pdf->Cell(0, 10, $mes, 0, 0);
            $pdf->SetXY(150, 150); 
            $pdf->Cell(0, 10, $anio, 0, 0);

           
            switch($informacionIncidencia->omision){
                 case 7:
                    $pdf->SetXY(21, 155); 
                    $pdf->Cell(0, 10,"X" , 0, 0);
                break;
                case 8:
                    $pdf->SetXY(68, 160); 
                    $pdf->Cell(0, 10,"X" , 0, 0);
                    $pdf->SetXY(115, 160);
                    $pdf->Cell(0, 10,$informacionIncidencia->descripcion_omision , 0, 0);
                    
                    
                break;
                case 0:
                    $pdf->SetXY(68, 160); 
                    $pdf->Cell(0, 10,"X" , 0, 0);
                    $pdf->SetXY(110, 160); 
                    $pdf->Cell(0, 10,$informacionIncidencia->descripcion_omision , 0, 0);
            }
          

        break;
    case 5:
        $tipo_omision="SALIDA NO REGISTRADA";
        $pdf->SetXY(10, 165);
            $pdf->Cell(0, 10, ' X  ', 0, 0); 
            $pdf->SetXY(80, 165);           
            $pdf->Cell(0, 10, $dia, 0, 0);
            $pdf->SetXY(115, 165); 
            $pdf->Cell(0, 10, $mes, 0, 0);
            $pdf->SetXY(150, 165); 
            $pdf->Cell(0, 10, $anio, 0, 0);
            switch($informacionIncidencia->omision){
                case 11:
                   $pdf->SetXY(21, 170); 
                   $pdf->Cell(0, 10,"X" , 0, 0);
               break;
               case 12:
                   $pdf->SetXY(68, 175); 
                   $pdf->Cell(0, 10,"X" , 0, 0);
                   $pdf->SetXY(115, 175);
$pdf->Cell(0, 10,$informacionIncidencia->descripcion_omision , 0, 0);
               break;
           }
        break;
        case 6:
            $tipo_omision="DÍA(S) ECONÓMICO(S)";
            $pdf->SetXY(10, 122);
            $pdf->Cell(0, 10, ' X  ', 0, 0); 
            $pdf->SetXY(80, 122);           
            $pdf->Cell(0, 10, $dia, 0, 0);
            $pdf->SetXY(115, 122); 
            $pdf->Cell(0, 10, $mes, 0, 0);
            $pdf->SetXY(150, 122); 
            $pdf->Cell(0, 10, $anio, 0, 0);
          
            
            break;
}
$pdf->SetFont('helvetica', '', 8);
$pdf->SetXY(10, 127);
$pdf->Cell(0, 10, '(  )    INASISTENCIA:                     DÍA(S): _______________   MES:  __________________    AÑO  ________________ ', 0, 0);
$pdf->SetXY(20, 135);
$pdf->Cell(0, 10, '(  )  INCAPACIDAD MÉDICA               (    )   OFICIO DE COMISIÓN', 0, 0);
$pdf->SetXY(20, 143);
$pdf->Cell(0, 10, '(  )  CUIDADOS FAMILIARES              (    )   OTRO, ESPECIFICAR:', 0, 0);
$pdf->SetXY(20, 143);
$pdf->Cell(0, 10, '(  )  CUIDADOS FAMILIARES              (    )   OTRO, ESPECIFICAR:', 0, 0);


$pdf->SetXY(10, 150);
$pdf->Cell(0, 10, '(  )  ENTRADA NO REGISTRADA: DÍA(S):         _______________   MES:  __________________    AÑO  ________________ ', 0, 0);
$pdf->SetXY(20, 155);
$pdf->Cell(0, 10, '(   )  JUSTIFICANTE MÉDICO  DE PERMANENCIA ', 0, 0);
$pdf->SetXY(20, 160);
$pdf->Cell(0, 10, '(   )  OFICIO DE COMISIÓN              (    )   OTRO, ESPECIFICAR:', 0, 0);

$pdf->SetXY(10, 165);
$pdf->Cell(0, 10, '(   )    SALIDA NO REGISTRADA:           DÍA(S):_______________   MES:  __________________    AÑO  ________________ ', 0, 0);
$pdf->SetXY(20, 170);
$pdf->Cell(0, 10, '(   )  JUSTIFICANTE MÉDICO  DE PERMANENCIA ', 0, 0);
$pdf->SetXY(20, 175);
$pdf->Cell(0, 10, '(   )  OFICIO DE COMISIÓN              (    )   OTRO, ESPECIFICAR:', 0, 0);

/*echo"<pre>";
print_r($informacionOficio );
die();*/
$pdf->SetXY(10, 190);
$pdf->MultiCell(0, 10, 'OBSERVACIONES:  ', 0,'L');


$pdf->SetFont('helvetica', '', 9);
$pdf->SetXY(20, 204);
$pdf->Cell(50, 10,mb_strtoupper( $informacionIncidencia->name, 'UTF-8') , 0, 0);
$pdf->SetFont('helvetica', '', 8);
$pdf->SetXY(20, 210);
$pdf->Cell(50, 10, 'FIRMA DEL SERVIDOR PÚBLICO', 0, 0);
//nombre_jefe
$pdf->SetFont('helvetica', '', 9);
$pdf->SetXY(105, 204);

$pdf->Cell(55, 10,mb_strtoupper( $informacionIncidencia->abreviatura_titulo." ". $informacionIncidencia->nombre_jefe, 'UTF-8'), 0, 0,'R');
$pdf->SetFont('helvetica', '', 8);
$pdf->SetXY(115, 213);
$pdf->MultiCell(55, 10, 'NOMBRE Y FIRMA DEL TITULAR DE LA UNIDAD ADMINISTRATIVA Y SELLO', 0, 0,'R');
$pdf->SetXY(10, 226);
$pdf->SetFont('helvetica', '', 7);
//$pdf->MultiCell(175, 10, 'NOTA: LOS JUSTIFICANTES DEBERÁN SER PRESENTADOS DENTRO DE LOS TRES DÍAS HÁBILES POSTERIORES A LA INCIDENCIA, DE ACUERDO A LO ESTABLECIDO EN EL “MANUAL DE OPERACIÓN DEL SISTEMA DE CONTROL DE PUNTUALIDAD Y ASISTENCIA PARA UNIDADES ADMINISTRATIVAS DE SERVICIOS EDUCATIVOS INTEGRADOS AL ESTADO DE MÉXICO”, EMITIDO POR LA DIRECCIÓN DE ADMINISTRACIÓN Y DESARROLLO DE PERSONAL.                              ', 0, "J");


// Generar y mostrar el PDF
$pdf->Output('reporte_incidencias.pdf', 'I');


       
        }

    }
    function imprmirincidencia($no_oficio = NULL)
    {
        if(!$this->hasUpdateAccess())
        {
            $this->loadThis();
        }
        else
        {
            if($no_oficio == null)
            {
                redirect('oficios/OficiosListing');
            }
        
$pdf = new Pdf(PDF_PAGE_ORIENTATION, PDF_UNIT, "LETTER", true, 'UTF-8', false);
ini_set('max_execution_time', 300); // 300 segundos = 5 minutos 
ini_set('memory_limit', '512M');
// Configura la información del documento
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('HLANDEROS');


// Configura las cabeceras y los pies de página
$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);






// Crear instancia de TCPDF
$pdf = new Pdf();
$pdf->SetTitle('Justificante de Oficio');

// Agregar página
$pdf->AddPage("P", "letter");

$pdf->SetMargins(20, 15, 10); // Izquierda, Arriba, Derecha
$pdf->SetAutoPageBreak(true, 5); // Habilita el salto de página y define margen inferior
// Establecer la imagen de fondo
$pagina_ancho = $pdf->getPageWidth(); // Obtener ancho de la página
$pagina_alto = $pdf->getPageHeight(); // Obtener alto de la página

$imagen_ancho = 210; // Ajusta el ancho de la imagen
$imagen_alto = 200;  // Ajusta el alto de la imagen

$pos_x = ($pagina_ancho - $imagen_ancho) / 2; // Centrar horizontalmente
$pos_y = ($pagina_alto - $imagen_alto) / 2; // Centrar verticalmente

//$pdf->Image(base_url('assets/images/fondo.png'), $pos_x, 85, $imagen_ancho, $imagen_alto);
$informacionOficio = $this->tm->ObtenerInfoOficio($no_oficio);
 $fecha_actual = new DateTime($informacionOficio->fecha_comision);
   


 // Lista de fechas feriadas
 $feriados = [
     '2025-05-01',
     '2025-05-05',
     '2025-05-15',
     '2025-05-16'
 ];
 $dias = 1;
 // Verificar si la fecha de comisión es el último día del mes
if ($fecha_actual->format('d') == $fecha_actual->format('t')) {
    // Si es fin de mes, no modificar la fecha y salir del proceso
    $fecha_actual=$fecha_actual;
}else{

 while ($dias > 0) {
     $fecha_actual->modify('+1 day'); // Avanzar un día
     $diaSemana = $fecha_actual->format('N'); // Obtener día de la semana (1=Lunes, 7=Domingo)
     $fechaStr = $fecha_actual->format('Y-m-d'); // Formato de fecha para comparación
 
     // Si es fin de mes, no modificar la fecha
     if ($fecha_actual->format('d') == $fecha_actual->format('t')) {
        $fecha_actual=$fecha_actual;
            break;
     }
 
     // Si la fecha es un día hábil (lunes a viernes)
     if ($diaSemana < 6) {
         // Si la fecha es feriado, avanzar un día más
         if (in_array($fechaStr, $feriados)) {
             $fecha_actual->modify('+1 day');
             $diaSemana = $fecha_actual->format('N'); // Actualizar día de la semana
 
             // Si el feriado es viernes, mover la fecha al lunes
             if ($diaSemana == 5) {
                 $fecha_actual->modify('+3 days');
             }
         }
         $dias--;
     }
 }
}
    $fecha=$fecha_actual->format('d-m-Y');

    $fecha=explode("-", $fecha);
    $dia1 = $fecha[0];
    $mes1 = $fecha[1];
    $anio1 = $fecha[2];
        $meses1 = array(
            '01' => 'ENERO',
            '02' => 'FEBRERO',
            '03' => 'MARZO',
            '04' => 'ABRIL',
            '05' => 'MAYO',
            '06' => 'JUNIO',
            '07' => 'JULIO',
            '08' => 'AGOSTO',
            '09' => 'SEPTIEMBRE',
            '10' => 'OCTUBRE',
            '11' => 'NOVIEMBRE',
            '12' => 'DICIEMBRE'
        );
        $mes1 = $meses1[$mes1]; // Obtener el nombre del mes correspondiente    
        $fecha=$dia1." DE ".$mes1." DE ".$anio1;


// Configurar fuente y encabezado

$pdf->SetFont('gothamblack', 'B', 9);
$pdf->SetXY(0, 32);
$pdf->Cell(0, 10, 'JUSTIFICANTE DE INCIDENCIAS DE PUNTUALIDAD Y ASISTENCIA', 0, 1, 'C');
$pdf->SetXY(110, 37);
$pdf->SetFont('gothambook', 'B', 9);
$pdf->Cell(50, 20, 'FECHA: '.$fecha, 0, 0,"L");

$pdf->SetDrawColor(0, 0, 0);       // Color del borde (negro)
$pdf->Rect(9, 50, 180, 40, 'L'); 
$pdf->Rect(9, 90, 180, 110, 'D'); 
$pdf->Rect(9, 200, 180, 15, 'D'); 
$pdf->Rect(9, 215, 180, 35, 'D'); 

$pdf->Line(15, 58, 189, 58);
$pdf->Line(10, 81, 100, 81);
$pdf->Line(120, 81, 189, 81);
$pdf->Line(30, 87, 189, 87);
$pdf->Line(129, 145, 189, 145);
$pdf->Line(123, 167, 189, 167);


$pdf->SetFont('gothambook', '', 11);
$pdf->SetXY(10, 51);
$pdf->SetXY(25, 49);
$pdf->Cell(100, 11, 'C. MTRA. SILVIA SÁNCHEZ LARA', 0, 1,'L');
$pdf->SetXY(9, 56);
$pdf->SetFont('gothambook', '', 10);
$pdf->Cell(100, 10, 'RESPONSABLE DEL REGISTRO Y CONTROL DE ASISTENCIA Y PUNTUALIDAD', 0, 1,'L');
$pdf->SetXY(9, 60);
$pdf->Cell(50, 11, 'PRESENTE', 0, 1,'L');
$pdf->SetXY(9, 67);

$fecha_separada = explode("-", $informacionOficio->fecha_comision);
$dia = $fecha_separada[2];
$mes = $fecha_separada[1];
$anio = $fecha_separada[0];
$meses = array(
    '01' => 'ENERO',
    '02' => 'FEBRERO',
    '03' => 'MARZO',
    '04' => 'ABRIL',
    '05' => 'MAYO',
    '06' => 'JUNIO',
    '07' => 'JULIO',
    '08' => 'AGOSTO',
    '09' => 'SEPTIEMBRE',
    '10' => 'OCTUBRE',
    '11' => 'NOVIEMBRE',
    '12' => 'DICIEMBRE'
);
$mes = $meses[$mes]; 
/*echo"<pre>";
print_r( $informacionOficio->personal_comisionado);
die();*/
$pdf->Cell(170, 10, 'SOLICITO A USTED EL REGISTRO DE LA JUSTIFICACIÓN DE LA INCIDENCIA DE LA C.', 0, 1,'L');
$pdf->SetXY(9, 74);
$pdf->Cell(20, 10,mb_strtoupper($informacionOficio->name, 'UTF-8') , 0, 0);
$pdf->SetXY(105, 74);
$pdf->Cell(50, 10, 'CON RFC:', 0, 1);
$pdf->SetXY(125, 74);
$pdf->Cell(50, 10, $informacionOficio->personal_comisionado, 0, 1);
$pdf->SetXY(10, 80);
$pdf->Cell(50, 10, 'ADSCRITO AL:', 0, 1);
$pdf->SetXY(40, 80);
$pdf->Cell(50, 10, 'DEPARTAMENTO DE FORMACIÓN PROFESIONAL', 0, 1);
$pdf->SetXY(80, 88);
$pdf->SetFont('gothamblack', 'B', 9);
$pdf->Cell(50, 10, 'INCIDENCIAS:', 0, 0);
$pdf->SetFont('gothambook', '', 9.3);
$pdf->SetXY(10, 94);
$pdf->Cell(0, 10, '(    )  RETARDO “A” EL(LOS) DÍA(S): _______________   MES:  ________________  AÑO  ________________ ', 0, 0);
$pdf->SetXY(10, 102);
$pdf->Cell(0, 10, '(    )  RETARDO “B” EL(LOS) DÍA(S): _______________   MES:  ________________  AÑO  ________________ ', 0, 0);
$pdf->SetXY(10, 112);
$pdf->Cell(0, 10, '(    )  DÍA(S) ECONÓMICO(S): DÍA(S): _______________   MES:  _______________  AÑO  ________________ ', 0, 0);

switch($informacionOficio->omision){
    case 1:
        $omision="ENTRADA";
        $pdf->SetXY(10, 130);
        $pdf->Cell(0, 10, ' X  ', 0, 0); 
        $pdf->SetXY(80, 130);           
        $pdf->Cell(0, 10, $dia, 0, 0);
        $pdf->SetXY(115, 130); 
        $pdf->Cell(0, 10, $mes, 0, 0);
        $pdf->SetXY(150, 130); 
        $pdf->Cell(0, 10, $anio, 0, 0);
        $pdf->SetXY(21, 140); 
        $pdf->Cell(0, 10,"X" , 0, 0);
        break;
    case 2:
        $omision="SALIDA";
        $pdf->SetXY(10, 170);
        $pdf->Cell(0, 10, ' X  ', 0, 0); 
        $pdf->SetXY(80, 170);           
        $pdf->Cell(0, 10, $dia, 0, 0);
        $pdf->SetXY(118, 170); 
        $pdf->Cell(0, 10, $mes, 0, 0);
        $pdf->SetXY(160, 170); 
        $pdf->Cell(0, 10, $anio, 0, 0);
        $pdf->SetXY(22, 186); 
        $pdf->Cell(0, 10,"X" , 0, 0);
        break;
    case 3:
        $omision="AMBOS";
        $pdf->SetXY(10, 107);
        $pdf->Cell(0, 10, ' X  ', 0, 0); 
        $pdf->SetXY(80, 107);           
        $pdf->Cell(0, 10, $dia, 0, 0);
        $pdf->SetXY(115, 107); 
        $pdf->Cell(0, 10, $mes, 0, 0);
        $pdf->SetXY(150, 107); 
        $pdf->Cell(0, 10, $anio, 0, 0);
        $pdf->SetXY(70, 115); 
        $pdf->Cell(0, 10,"X" , 0, 0);
        break;
}

$pdf->SetXY(10, 122);
$pdf->Cell(0, 10, '(  )    INASISTENCIA:         DÍA(S):_______________   MES:  __________________    AÑO  ________________ ', 0, 0);
$pdf->SetXY(20, 129);
$pdf->Cell(0, 10, '(  )  INCAPACIDAD MÉDICA               (    )   OFICIO DE COMISIÓN', 0, 0);
$pdf->SetXY(20, 138);
$pdf->Cell(0, 10, '(  )  CUIDADOS FAMILIARES              (    )   OTRO, ESPECIFICAR:', 0, 0);
$pdf->SetXY(10, 145);
$pdf->Cell(0, 10, '(  )  ENTRADA NO REGISTRADA:   DÍA(S):____________  MES:  __________________AÑO  ______________ ', 0, 0);
$pdf->SetXY(20, 153);
$pdf->Cell(0, 10, '(   )  JUSTIFICANTE MÉDICO  DE PERMANENCIA ', 0, 0);
$pdf->SetXY(20, 160);
$pdf->Cell(0, 10, '(   )  OFICIO DE COMISIÓN              (    )   OTRO, ESPECIFICAR:', 0, 0);

$pdf->SetXY(10, 170);
$pdf->Cell(0, 10, '(   )  SALIDA NO REGISTRADA: DÍA(S):_______________   MES:  ______________ AÑO  ________________ ', 0, 0);
$pdf->SetXY(20, 178);
$pdf->Cell(0, 10, '(   )  JUSTIFICANTE MÉDICO  DE PERMANENCIA ', 0, 0);
$pdf->SetXY(20, 186);
$pdf->Cell(0, 10, '(   )  OFICIO DE COMISIÓN              (    )   OTRO, ESPECIFICAR:', 0, 0);

$pdf->SetXY(10, 200);
$pdf->Cell(0, 10, 'OBSERVACIONES: SE ATENDIO EL OFICIO '.$informacionOficio->no_oficio, 0, 0);


$pdf->Line(12, 235, 100, 235);
$pdf->Line(105, 235, 189, 235);
$pdf->SetXY(18, 227);
$pdf->Cell(50, 10, mb_strtoupper($informacionOficio->name, 'UTF-8')  , 0, 0);

$pdf->SetXY(20, 233);
$pdf->Cell(50, 10, 'FIRMA DEL SERVIDOR PÚBLICO', 0, 0);

$pdf->SetXY(110, 230);
$pdf->MultiCell(75, 8,mb_strtoupper($informacionOficio->abreviatura_titulo." ".  $informacionOficio->nombre_jefe, 'UTF-8'), 0, "C");

$pdf->SetXY(105, 236);
$pdf->MultiCell(85, 10, $informacionOficio->puesto_jefe, 0, "C","","");

$pdf->SetFont('gothamblack', 'B', 7);
$pdf->SetXY(8, 250);
$pdf->MultiCell(182, 10, "NOTA: LOS JUSTIFICANTES DEBERÁN SER PRESENTADOS DENTRO DE LOS TRES DÍAS HÁBILES POSTERIORES A LA INCIDENCIA, DE ACUERDO A LO ESTABLECIDO EN EL “MANUAL  DE OPERACIÓN DEL SISTEMA DE CONTROL DE  PUNTUALIDAD Y ASISTENCIA PARA UNIDADES ADMINISTRATIVAS DE SERVICIOS EDUCATIVOS INTEGRADOS AL ESTADO DE MÉXICO”, EMITIDO POR LA DIRECCIÓN DE ADMINISTRACIÓN Y DESARROLLO DE PERSONAL.                                                      ", 0, "J","","");
// Generar y mostrar el PDF
$pdf->Output('reporte_incidencias.pdf', 'I');


       
        }

    }

    public function html_clean($s, $v)
    {
        return strip_tags((string) $s);
    }
}

?>