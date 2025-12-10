<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

//oki doki
class Exam_model extends CI_Model
{
    function editarOficio($id = FALSE) {
        if ($id === FALSE) {
            $query = $this->db->get('correspondencia');
            return $query->result_array();
        }

        $query = $this->db->get_where('correspondencia', array('id' => $id));
        return $query->row();
    }
    public function getOficiosInfo($id = FALSE) {
        if ($id === FALSE) {
            $query = $this->db->get('correspondencia');
            return $query->result_array();
        }

        $query = $this->db->get_where('correspondencia', array('id' => $id));
        return $query->row();
    }

    public function set_correspondencia($data) {
       
        $cve_area = $this->input->post('cve_area'); // Obtiene el array de áreas seleccionadas

        if (!empty($cve_area)) {
            $cve_area = implode(",", $cve_area); // Une los elementos del array con comas
        } else {
            $cve_area = ""; // Si no hay áreas seleccionadas, asigna un valor vacío
        }
       
       $data['cve_area'] = $cve_area; // Asigna el string de áreas al campo cve_area en el array $data
     
       
       $data['registrado_por']=$_SESSION['userId'];
   
        $this->db->insert('correspondencia', $data);
        return $this->db->insert_id();
    }
function   save_file_info ($file_info) {
        $this->db->insert('uploaded_files', $file_info);
        return $this->db->insert_id();
    }
    function save_file_anexos($file_info_anexos) {
        $this->db->insert('uploaded_files', $file_info_anexos);
        return $this->db->insert_id();
    }
  

  public function personal() { 
   /* echo"<pre>";
    print_r($_SESSION['role']);
    echo"</pre>";   
    die;*/
    $this->db->select('cve_personal, nombre'); // Selecciona columnas específicas
    $this->db->from('personas'); // Establece la tabla
    $this->db->where('roleId', $_SESSION['role']); // Aplica el filtro de búsqueda
    $query = $this->db->get(); // Ejecuta la consulta
    return $query->result_array(); // Retorna el resultado como un arreglo asociativo



        $query = $this->db->get('personas'); return $query->result_array(); 
    }
    public function estados() { 
        $query = $this->db->get('catalogo_estados'); return $query->result_array(); 
    }
    public function areas() { 
        $query = $this->db->get('catalogo_areas'); return $query->result_array(); 
    }
    function documentosAnexos($IdCorrespondencia) { 
        $this->db->select('file_path as ruta'); // Selecciona columnas específicas
        $this->db->from('uploaded_files'); // Establece la tabla
        $this->db->where("cve_correspondencia",$IdCorrespondencia); // Aplica el filtro de búsqueda
        $this->db->where("cve_documento",'anexos'); // Aplica el filtro de búsqueda
        $query = $this->db->get(); // Ejecuta la consulta
        return $query->row_array(); // Retorna el resultado como un arreglo asociativo
    }
    function documentosRespuesta($IdCorrespondencia) { 
        $this->db->select('file_path as ruta'); // Selecciona columnas específicas
        $this->db->from('uploaded_files'); // Establece la tabla
        $this->db->where("cve_correspondencia",$IdCorrespondencia); // Aplica el filtro de búsqueda
         $this->db->where("cve_documento",'respuesta'); // Aplica el filtro de búsqueda
        $query = $this->db->get(); // Ejecuta la consulta
        return $query->row_array(); // Retorna el resultado como un arreglo asociativo
    }
    function documentos($IdCorrespondencia) { 

        $this->db->select('file_path as ruta'); // Selecciona columnas específicas
    $this->db->from('uploaded_files'); // Establece la tabla
    $this->db->where("cve_correspondencia",$IdCorrespondencia); // Aplica el filtro de búsqueda
    $this->db->where("cve_documento",'oficio'); // Aplica el filtro de búsqueda
   
    $query = $this->db->get(); // Ejecuta la consulta
    return $query->row_array(); // Retorna el resultado como un arreglo asociativo

    
    }
    function dependencias() { 

        $this->db->select('cve_dependencia, nombre_corto'); // Selecciona columnas específicas
$this->db->from('catalogo_dependencias'); // Establece la tabla

$query = $this->db->get(); // Ejecuta la consulta
return $query->result_array(); // Retorna el resultado como un arreglo asociativo

      
    }
    function correspondenciaListingCount($searchText)
    {
       
     

        


        $this->db->select('*');
        $this->db->from('correspondencia as BaseTbl');
        $perfil=$_SESSION['role'];
        ///1 y 4 lo ven todo
        switch ($perfil) {
            case 7: // "Control Escolar"
                $likeCriteria1 = "(cve_area LIKE '%2%')";         
                $this->db->where($likeCriteria1);
                break;
            case 9: // "Recursos Humanos"
                $likeCriteria1 = "(cve_area LIKE '%3%')";         
                $this->db->where($likeCriteria1);
                break;
            case 8: // "Planeación"
                $likeCriteria1 = "(cve_area LIKE '%4%')";         
                $this->db->where($likeCriteria1);
                break;
            case 6: // "Académica"
                $likeCriteria1 = "(cve_area LIKE '%1%')";         
                $this->db->where($likeCriteria1);
                break;
            default:
                // Sin filtro para otros perfiles
                break;
        }
        
        // Filtro de búsqueda por texto
        if (!empty($searchText)) {
            $likeCriteria = "(asunto LIKE '%" . $searchText . "%')";
            $this->db->where($likeCriteria);
        }
        
        // Filtro adicional para no mostrar eliminados
        $this->db->where('isDeleted', 0);
        
        // Ejecutar la consulta
        $query = $this->db->get();
        
        return $query->num_rows();
    }
    
    /**
     * This function is used to get the booking listing count
     * @param string $searchText : This is optional search text
     * @param number $page : This is pagination offset
     * @param number $segment : This is pagination limit
     * @return array $result : This is result
     */
    function CorrespondenciaListing($searchText, $page, $segment)
    {
       
        $this->db->select('
        a.*, 
        c.*, 
        b.*, 
        GROUP_CONCAT(b.cve_documento SEPARATOR ",") as documento, 
        a.id as correspondencia,
          GROUP_CONCAT(des_area SEPARATOR ", ") as nombre_area,
          e.*,f.*
    ');
    $this->db->from('correspondencia as a');
    $this->db->join('uploaded_files as b', 'a.id = b.cve_correspondencia', 'left');
    $this->db->join('personas as c', 'a.asignado_a = c.cve_personal', 'left');
    $this->db->join('catalogo_areas as d', 'FIND_IN_SET(d.cve_area, a.cve_area)', 'left'); // Para manejar "2,3"
    $this->db->join('catalogo_dependencias as e', 'a.cve_dependencia = e.cve_dependencia', 'left');
    $this->db->join('catalogo_estados as f', 'a.cve_estado = f.cve_estado', 'left');
    $perfil=$_SESSION['role'];
    ///1 y 4 lo ven todo
    switch ($perfil) {
        case 7: // "Control Escolar"
            $likeCriteria1 = "(d.cve_area LIKE '%2%')";         
            $this->db->where($likeCriteria1);
            break;
        case 9: // "Recursos Humanos"
            $likeCriteria1 = "(d.cve_area LIKE '%3%')";         
            $this->db->where($likeCriteria1);
            break;
        case 8: // "Planeación"
            $likeCriteria1 = "(d.cve_area LIKE '%4%')";         
            $this->db->where($likeCriteria1);
            break;
        case 6: // "Académica"
            $likeCriteria1 = "(d.cve_area LIKE '%1%')";         
            $this->db->where($likeCriteria1);
            break;
        default:
            // Sin filtro para otros perfiles
            break;
    }    





        if(!empty($searchText)) {
            $likeCriteria = "(a.asunto  LIKE '%".$searchText."%'
                            OR  a.no_oficio  LIKE '%".$searchText."%'
                            OR  a.asignado_a  LIKE '%".$searchText."%')";
            $this->db->where($likeCriteria);
        }
        $this->db->where('isDeleted', 0);
        $this->db->group_by('a.id'); // Agrupar por ID de correspondencia
        $this->db->order_by('a.id', 'DESC');
    
   $this->db->limit($page, $segment);
        $query = $this->db->get();
        
        $result = $query->result();        
        return $result;
    }
   
    function editarCorrespondencia($oficioInfo, $cve_correspondencia)
    {
        
        $this->db->where('id', $cve_correspondencia);
        $this->db->update('correspondencia', $oficioInfo);
        
        return TRUE;
    }
   
}