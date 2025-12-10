<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

class Exam2025 extends BaseController
{
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('exam_model', 'em');
        $this->isLoggedIn();
        $this->module = 'Exam';
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->load->library('upload');
        $this->load->library('session');
        
    }

    /**
     * This is default routing method
     * It routes to default listing page
     */
    public function index()
    {
        redirect('examen');
    }
    

    function add()
    {
        if(!$this->hasCreateAccess())
        {
            $this->loadThis();
        }
        else
        {


            $data['userId']=$_SESSION['userId'];
            //$data['personas'] = $this->em->personal();
            //$data['areas'] = $this->em->areas();
            //$data['dependencias'] = $this->em->dependencias();

            $this->global['pageTitle'] = 'SEIEM : English Exam 2025';

            $this->loadViews("Exam2025/add", $this->global, $data, NULL);
        }
    }
    
    /**
     * This function is used to add new user to the system
     */
    function AgregarNuevaCorrespondencia()
    {
        if(!$this->hasCreateAccess())
        {
            $this->loadThis();
        }
        else
        {
           
            $otra_dependencia = $this->input->post('otra_dependencia');

            // Verificar si el campo está vacío
            if (empty($otra_dependencia)) {
                // Si está vacío, elimínalo del arreglo POST
               unset($_POST['otra_dependencia']); // Elimina el valor de `POST`
           
            } else {
                // Si tiene información, agrégalo a la tabla `catalogo_dependencias`
          /*  echo"<pre>";
            print_r($_POST);
            echo"</pre>";die();
            */
                $data = [
                    'des_dependencia' => $otra_dependencia,
                    'enlace_dependencia'=> $this->security->xss_clean($this->input->post('remitido_por'))
                ];
            
                // Insertar en la base de datos
                $this->db->insert('catalogo_dependencias', $data);
            
                // Obtener el ID generado (cve_dependencia)
                $cve_dependencia = $this->db->insert_id();
            
                // Opcional: Puedes agregar el `cve_dependencia` a otra lógica
                unset($_POST['otra_dependencia']); // Elimina el valor de `POST`
                $_POST['otra_dependencia']= $cve_dependencia; // Elimina el valor de `POST`
           
            }

      

        /////////////////////////////////////////



        $this->form_validation->set_rules('asunto', 'Asunto', 'required');
        // Añadir más reglas de validación según sea necesario

        
        if ($this->form_validation->run() === FALSE) {
            $this->loadViews("correspondencia/create", $this->global, $data, NULL);
           
        } else {
           $id= $this->cm->set_correspondencia($this->input->post());
            $upload_path= 'assets/pdf/'.$id."/";// Ejemplo de crear carpeta por fecha 
            
            // Crear la carpeta si no existe 
            if (!is_dir($upload_path)) { 
            mkdir($upload_path, 0755, true);
            }
            $config['upload_path'] =$upload_path;          
            $config['allowed_types'] = 'pdf';
            $config['max_size'] = 20048; // 10MB      
        
/*configuracion segundo archivo  */
// Configuración para el segundo archivo (Anexos) 
$config2['upload_path'] = $upload_path; 
$config2['allowed_types'] = 'pdf';
$config2['max_size'] = 20048; // 10MB  
 $config2['file_name'] = 'anexos_' .$id . '.' . pathinfo($_FILES['pdf_file_oficio']['name'], PATHINFO_EXTENSION); 
 $this->upload->initialize($config2);
/*fin*/

/*******subir el segundo documento******** */
if ($this->upload->do_upload('pdf_file_anexos')) {
    $upload_data_anexos = $this->upload->data();
    $file_info_anexos = array(
        'cve_correspondencia' => $id, // Supón que tienes un ID de correspondencia
        'file_type' => $upload_data_anexos['file_type'],
        'user_id' => $this->vendorId, // Supón que tienes un ID de usuario
        'cve_documento' => "anexos",
        'file_name' => $upload_data_anexos['file_name'],
        'file_path' => $upload_path . $upload_data_anexos['file_name'],
        'file_ext' => $upload_data_anexos['file_ext'],
        'uploaded_at' => date('Y-m-d H:i:s')
    );
   
    $this->cm->save_file_anexos($file_info_anexos);
} else {
    $error = array('error' => $this->upload->display_errors());
    print_r($error);
}
/****fin*******/

$new_name = "oficio_".$id . '.' . pathinfo($_FILES['pdf_file_oficio']['name'], PATHINFO_EXTENSION); 
$config['file_name'] = $new_name;


$this->upload->initialize($config); // Archivos a subir 

             
            $file='pdf_file_oficio';
            if (file_exists($upload_path . $_FILES[$file]['name'])) { 
                // Mostrar error si el archivo ya existe 
                $data['error']  = array('error' => 'El archivo ' . $_FILES[$file]['name'] . ' ya existe.'); 
               
              
               
                $this->loadViews('correspondencia/error', $data); return; 
            }
                if (!$this->upload->do_upload($file)) { 
                    // Mostrar errores si la carga falla 
                    $data['error'] = array('error' => $this->upload->display_errors()); 
                    $this->loadViews("correspondencia/create", $this->global, $data, NULL);
                   
                    return; 
                } 
                else { 


                    $upload_data = $this->upload->data(); 
                   
            
                     $config['pdf_file_oficio'] = $new_name;
                     $uploaded_files[$file] = $upload_data; // Guardar la información del archivo en la base de datos
                    
                     $file_info = array( 'cve_correspondencia' => $id, // Supongamos que tienes un ID de usuario, reemplázalo según sea necesario
                      'file_type' => $_FILES['pdf_file_oficio']['type'], 
                      'user_id'=>$this->vendorId,
                      'file_name' => "oficio_".$id. $upload_data['file_ext'], 
                      'cve_documento' => "oficio", 
                      'file_path' => $upload_path . $upload_data['file_name'],
                      'file_ext' =>  $upload_data['file_ext'],              
                      'uploaded_at' => date('Y-m-d H:i:s') );
                                         
                      $this->cm->save_file_info($file_info);
                    
            
                       unset($upload_data);
                        unset($file_info);
                        redirect('correspondencia');
                }
            
        }
    }
    }

    
    /**
     * This function is used load booking edit information
     * @param number $bookingId : Optional : This is booking id
     */
    
    function Editar($IdCorrespondencia = NULL)
    {
        if(!$this->hasUpdateAccess())
        {
            $this->loadThis();
        }
        else
        {
            if($IdCorrespondencia == null)
            {
                redirect('correspondencia');
            }
            
            $data['CorrespondenciaInfo'] = $this->cm->getOficiosInfo($IdCorrespondencia);
            $data['personas'] = $this->cm->personal();
            $data['personas'] = $this->cm->personal();
            $data['areas'] = $this->cm->areas();
            $data['dependencias'] = $this->cm->dependencias();
            $data['estados'] = $this->cm->estados();
            $this->global['pageTitle'] = 'SEIEM : Editar Correspondencia';
      
            $this->loadViews("correspondencia/edit", $this->global, $data, NULL);
        }
    }
    
    
    /**
     * This function is used to edit the user information
     */
    function descargarAnexos($IdCorrespondencia = NULL){
        if(!$this->hasUpdateAccess())
        {
            $this->loadThis();
        }
        else
        {
            if($IdCorrespondencia == null)
            {
                redirect('correspondencia');
            }
            
            $documentos= $this->cm->documentosAnexos($IdCorrespondencia);
            $ruta = FCPATH . $documentos['ruta']; // Genera la ruta absoluta
        
            if (file_exists($ruta)) {
                // Configuración para forzar la descarga
             
                header('Content-Type: application/pdf');
                header('Content-Disposition: inline; filename="' . basename($ruta) . '"');
                header('Content-Length: ' . filesize($ruta));
                readfile($ruta);


                exit;
            } else {
                show_404(); // Mostrar error si el archivo no existe
            }
        }
    }
    function descargarRespuesta ($IdCorrespondencia = NULL){
        if(!$this->hasUpdateAccess())
        {
            $this->loadThis();
        }
        else
        {
            if($IdCorrespondencia == null)
            {
                redirect('correspondencia');
            }
            
            $documentos= $this->cm->documentosRespuesta($IdCorrespondencia);
            $ruta = FCPATH . $documentos['ruta']; // Genera la ruta absoluta
        
            if (file_exists($ruta)) {
                // Configuración para forzar la descarga
                header('Content-Type: application/pdf');
                header('Content-Disposition: inline; filename="' . basename($ruta) . '"');
                header('Content-Length: ' . filesize($ruta));
                readfile($ruta);
                exit;
            } else {
                show_404(); // Mostrar error si el archivo no existe
            }
        }
    }
    function descargarDocumento($IdCorrespondencia = NULL)
    {
        if(!$this->hasUpdateAccess())
        {
            $this->loadThis();
        }
        else
        {
            if($IdCorrespondencia == null)
            {
                redirect('correspondencia');
            }
            
            $documentos= $this->cm->documentos($IdCorrespondencia);
            $ruta = FCPATH . $documentos['ruta']; // Genera la ruta absoluta
        
            if (file_exists($ruta)) {
                // Configuración para forzar la descarga
                header('Content-Type: application/pdf');
                header('Content-Disposition: inline; filename="Oficio ' . basename($ruta) . '"');
                header('Content-Length: ' . filesize($ruta));
                readfile($ruta);
                exit;
            } else {
                show_404(); // Mostrar error si el archivo no existe
            }
        }
    }
    function guardar()
    {
        if(!$this->hasUpdateAccess())
        {
            $this->loadThis();
        }
        else
        {
            $this->load->library('form_validation');            
            $cve_correspondencia = $this->input->post('cve_correspondencia');
            $tipo_documento = $this->input->post('tipo_documento');
            $this->form_validation->set_rules('cve_correspondencia','Correspondencia','trim|callback_html_clean|required|max_length[250]');
            $this->form_validation->set_rules('tipo_documento','Tipo de documento','trim|callback_html_clean|required|max_length[1024]');
            
            if($this->form_validation->run() == FALSE)
            {
                $this->adjuntarCorrespondencia($cve_correspondencia);
            }
            else
            {   $extension = pathinfo($_FILES['cve_documento']['name'], PATHINFO_EXTENSION);
            
                $tipo_documento = $this->input->post('tipo_documento');
switch ($tipo_documento) {
    case 1:
        $nombre_archivo = "oficio_".$this->input->post('cve_correspondencia').".".$extension;
        $cve_documento= "oficio";

        break;
    case 2:
        $nombre_archivo = "anexos_".$this->input->post('cve_correspondencia').".".$extension;
        $cve_documento= "anexos";
        break;
    case 3:
        $nombre_archivo = "respuesta_".$this->input->post('cve_correspondencia').".".$extension;
        $cve_documento= "respuesta";
        break;
}
           
                // Configuración para la subida de archivos
                $upload_path= 'assets/pdf/'.$cve_correspondencia."/";// Ejemplo de crear carpeta por fecha 
          
                $config['upload_path'] = $upload_path; // Ruta donde se guardarán los archivos
                $config['allowed_types'] = 'jpg|png|pdf|docx'; // Tipos de archivos permitidos
                $config['max_size'] = 2048; // Tamaño máximo en KB 2MB
                $config['file_name'] = $nombre_archivo; // Nombre único para el archivo             
                // Crear la carpeta si no existe 
                if (!is_dir($upload_path)) { 
                mkdir($upload_path, 0755, true);
                }
          
                $this->load->library('upload', $config);
                   
                $_FILES['cve_documento']['name'] = $nombre_archivo;
                $file_info = array( 
                'cve_correspondencia' => $cve_correspondencia, // Supongamos que tienes un ID de usuario, reemplázalo según sea necesario
                'file_type' => $_FILES['cve_documento']['type'], 
                'user_id'=>$this->vendorId,
                'file_name' => $nombre_archivo, 
                'cve_documento' => $cve_documento, 
                'file_path' => $upload_path . $nombre_archivo,
                'file_ext' =>  $extension,              
                'uploaded_at' => date('Y-m-d H:i:s') );





                $this->upload->initialize($config); // Archivos a subir 

             
                $file='cve_documento';
                if (file_exists($upload_path . $_FILES[$file]['name'])) { 
                    // Mostrar error si el archivo ya existe 
                    $data['error']  = array('error' => 'El archivo ' . $_FILES[$file]['name'] . ' ya existe.'); 
                    
                 $this->loadViews("correspondencia/adjuntar", $this->global, $data, NULL);
                      
                }
                    if (!$this->upload->do_upload($file)) { 
                        // Mostrar errores si la carga falla 
                        $data['error'] = array('error' => $this->upload->display_errors()); 
                        $this->loadViews("correspondencia/adjuntar", $this->global, $data, NULL);
                       
                        return; 
                    } 
                    else{
                        $this->upload->data(); 
                   
                        $this->cm->save_file_info($file_info);

                    }
                // Aquí puedes manejar la lógica para guardar el archivo adjunto
                // Por ejemplo, mover el archivo a una carpeta específica y guardar la información en la base de datos
                redirect('correspondencia');
            }
        }
    }
    //NADA DE NADA
    function adjuntarCorrespondencia($IdCorrespondencia = NULL){
        if(!$this->hasUpdateAccess())
        {
            $this->loadThis();
        }
        else
        {
           // die("aaa");
            $documentos=array(""=>"Seleccione un tipo de documento",1=>"oficio",2=>"anexos",3=>"respuesta");
            $this->global['pageTitle'] = 'SEIEM : Adjuntar Documentos';
            $this->data['cve_correspondencia'] = array(
                'name'  => 'cve_correspondencia',
                'id'    => 'cve_correspondencia',
                'class' => 'form-control',
                'type' => 'hidden',
                'value' => $IdCorrespondencia,
           
            );
            $this->data['cve_documento'] = array(
                'name'  => 'cve_documento',
                'id'    => 'cve_documento',
                'class' => 'form-control',
                'type' => 'file',
                'accept' => 'application/pdf',
            );
            $this->data['tipo_documento'] = array(
            'name'  => 'tipo_documento',
            'id'    => 'tipo_documento',
            'class' => 'form-control',
            'options' => $documentos,
         
        );
        
        $this->loadViews("correspondencia/adjuntar", $this->global, $this->data, NULL);

        }
    }
   function  asignar($IdCorrespondencia = NULL){
    
    if(!$this->hasUpdateAccess())
    {
        $this->loadThis();
    }
    else
    {
   
        if($IdCorrespondencia == null)
        {
            redirect('correspondencia');
        }
          
        $CorrespondenciaInfo = $this->cm->getOficiosInfo($IdCorrespondencia);
       
        $this->data['id'] = $IdCorrespondencia;
       
        $personas = $this->cm->personal();
         $areas = $this->cm->areas();
        $dependencias = $this->cm->dependencias();
        $estados= $this->cm->estados();
           $this->global['pageTitle'] = 'SEIEM : Editar Correspondencia';
      

 $options = array();
                                                                                
        foreach ($dependencias as $dependencia) {   
            $options[$dependencia['cve_dependencia']] = $dependencia['nombre_corto'];
        }
   $dependencias = array('' => 'Seleccione una dependencia') + $options;

   $options1 = array();
   $cve_area_seleccionada = explode(',', $CorrespondenciaInfo->cve_area); // Convierte la cadena en un array                                                                             
   foreach ($areas as $area) {   
       $options1[$area['cve_area']] = $area['des_area'];
   }
$areas = array('' => 'Seleccione una Área') + $options1;
       

$options2 = array();
                                                                                
        foreach ($estados as $estado) {   
            $options2[$estado['cve_estado']] = $estado['des_estado'];
        }
   $estados = array('' => 'Seleccione una Estado') + $options2;

   $options3 = array();
                                                                                
        foreach ($personas as $persona) {   
            $options3[$persona['cve_personal']] = $persona['nombre'];
        }
   $personas = array('' => 'Seleccione una persona') + $options3;

        $this->data['id'] =(int) $CorrespondenciaInfo->id;
            
      
        $this->data['no_oficio'] = array(
            'name'  => 'no_oficio',
            'id'    => 'no_oficio',
            'type'  => 'text',
            'class' => 'form-control',
            'value' => $this->form_validation->set_value('no_oficio', $CorrespondenciaInfo->no_oficio),
            'disabled'=>'disabled',
        );
        
        $this->data['asunto'] = array(
            'name'  => 'asunto',
            'id'    => 'asunto',
            'type'  => 'text',
            'class' => 'form-control',
            'value' => $this->form_validation->set_value('asunto', $CorrespondenciaInfo->asunto),
            'disabled'=>'disabled',
        );
        $this->data['cve_dependencia'] = array(
            'name'  => 'cve_dependencia',
            'id'    => 'cve_dependencia',
            'class' => 'form-control',
            'selected' => $CorrespondenciaInfo->cve_dependencia,
            'options' => $dependencias,
            'disabled'=>'disabled',
        );
     
     
        $this->data['remitido_por'] = array(
            'name'  => 'remitido_por',
            'id'    => 'remitido_por',
            'type'  => 'text',
            'class' => 'form-control',
            'value' => $this->form_validation->set_value('remitido_por', $CorrespondenciaInfo->remitido_por),
            'disabled'=>'disabled',
        );
        $this->data['fecha_registro'] = array(
            'name'  => 'fecha_registro',
            'id'    => 'fecha_registro',
            'type'  => 'date',
            'class' => 'form-control',
            'value' => $this->form_validation->set_value('fecha_registro', $CorrespondenciaInfo->fecha_registro),
            'disabled'=>'disabled',
        );
        $this->data['cve_area'] = array(
            'name'  => 'cve_area[]',
            'id'    => 'cve_area',
            'class' => 'form-control',
            'selected' => $cve_area_seleccionada,
            'options' => $areas,
            'disabled'=>'disabled',
            'multiple'=>"multiple",
        );
        $this->data['no_oficio_respuesta'] = array(
            'name'  => 'no_oficio_respuesta',
            'id'    => 'no_oficio_respuesta',
            'type'  => 'text',
            'class' => 'form-control',
            'value' => $this->form_validation->set_value('no_oficio_respuesta', $CorrespondenciaInfo->no_oficio_respuesta),
       
        );
        $this->data['estado_oficio'] = array(
            'name'  => 'estado_oficio',
            'id'    => 'estado_oficio',
            'class' => 'form-control',
            'selected' => $CorrespondenciaInfo->cve_estado,
            'options' => $estados,
        );
        $this->data['observaciones'] = array(
            'name'  => 'observaciones',
            'id'    => 'observaciones',
            'class' => 'form-control',
            'value' => $this->form_validation->set_value('observaciones', $CorrespondenciaInfo->observaciones),
            'maxlength' => 560,
            'rows' => 3,
        );  $this->data['pdf_respuesta'] = array(
            'name'  => 'pdf_respuesta',
            'id'    => 'pdf_respuesta',
            'class' => 'form-control',
            'type' => 'file',
            'value' => $this->form_validation->set_value('pdf_respuesta', $CorrespondenciaInfo->pdf_respuesta),
            'accept' => 'application/pdf',
        );
        $this->data['asignado_a'] = array(
            'name'  => 'asignado_a',
            'id'    => 'asignado_a',
            'class' => 'form-control',
            'selected' => $CorrespondenciaInfo->asignado_a,
            'options' => $personas,
        );
      
        /*   
        'multiple'=>"multiple"
<input disabled type="text" class="form-control" id="" placeholder="Ingresa Número de Oficio" value="<?php echo $no_oficio; ?>" name="no_oficio" maxlength="200">


       NUEVOS CAMPOS 

          <?php echo form_input($no_oficio);?> 

        $this->data['cveCoordinacionMunicipal'] = array(
            'name'  => 'cveCoordinacionMunicipal',
            'id'    => 'cveCoordinacionMunicipal',
            'type'  => 'text',
            'class' => 'form-control',
            'value' => $this->form_validation->set_value('cveCoordinacionMunicipal'),
        );
      
    
*/




//die("llegando a asignar");
        $this->loadViews("correspondencia/asignar", $this->global, $this->data, NULL);
    }




   }
   function Seguimiento(){
    if(!$this->hasUpdateAccess())
    {
        $this->loadThis();
    }
    else
    {
        $cve_correspondencia = $this->input->post('cve_correspondencia');
        $oficioInfo = array(
            'no_oficio_respuesta' => $this->security->xss_clean($this->input->post('no_oficio_respuesta')),
            'cve_estado' => $this->security->xss_clean($this->input->post('estado_oficio')),
            'asignado_a' => $this->security->xss_clean($this->input->post('asignado_a')),
            'observaciones' => $this->security->xss_clean($this->input->post('observaciones')),
            'updatedBy' => $this->vendorId,
            'updatedDtm' => date('Y-m-d H:i:s')
        );
        $result = $this->cm->editarCorrespondencia($oficioInfo, $cve_correspondencia);
                
        if($result == true)
        {
            $this->session->set_flashdata('success', 'Correspondencia Actualizada exitosamente');
        }
        else
        {
            $this->session->set_flashdata('error', 'Fallo la Actualización de la Correspondencia');
        }
        
        redirect('correspondencia');
       
      
       
    }
   }
    function EditarCorrespondencia()
    {
        if(!$this->hasUpdateAccess())
        {
            $this->loadThis();
        }
        else
        {
            $this->load->library('form_validation');            
            $cve_correspondencia = $this->input->post('cve_correspondencia');

            $this->form_validation->set_rules('asunto','Asunto','trim|callback_html_clean|required|max_length[250]');
          //  $this->form_validation->set_rules('quien_lo_turna','Quien lo turna','trim|callback_html_clean|required|max_length[1024]');
          



             
            if($this->form_validation->run() == FALSE)
            {
                $this->Editar($cve_correspondencia);
            }
            else
            {
              
$oficioInfo = array(
    'asunto'=>$this->security->xss_clean($this->input->post('asunto')),
  //  'quien_lo_turna' => $this->security->xss_clean($this->input->post('quien_lo_turna')),
    'fecha_registro' => $this->security->xss_clean($this->input->post('fecha_recibido_academica')),
    'no_oficio_respuesta' => $this->security->xss_clean($this->input->post('no_oficio_respuesta')),
    'asignado_a' => $this->security->xss_clean($this->input->post('asignado_a')),
    'cve_estado' => $this->security->xss_clean($this->input->post('estado_oficio')),
    'observaciones' => $this->security->xss_clean($this->input->post('observaciones')),
    'updatedBy' => $this->vendorId,
    'updatedDtm' => date('Y-m-d H:i:s')
);
                
                 $result = $this->cm->editarCorrespondencia($oficioInfo, $cve_correspondencia);
                
                if($result == true)
                {
                    $this->session->set_flashdata('success', 'Correspondencia Actualizada exitosamente');
                }
                else
                {
                    $this->session->set_flashdata('error', 'Fallo la Actualización de la Correspondencia');
                }
                
                redirect('correspondencia');
            }
        }
    }

    public function html_clean($s, $v)
    {
        return strip_tags((string) $s);
    }
}

?>