<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Class : Task_model (Task Model)
 * Task model class to get to handle task related data 
 * @author : Kishor Mali
 * @version : 1.5
 * @since : 18 Jun 2022
 */
class Oficios_model extends CI_Model
{
    function incidenciasListingCount1($searchText){
        $this->db->select('*');
        $this->db->from('oficios ');
        if(!empty($searchText)) {
            $likeCriteria = "(asunto LIKE '%".$searchText."%')";
            $this->db->where($likeCriteria);
        }
        $this->db->where('estado', 0);
        $this->db->where('omision>', 0);

        $query = $this->db->get();
        
        return $query->num_rows();

    }
    function incidenciasListingCount($searchText){
        $this->db->select('*');
        $this->db->from('oficios ');
        if(!empty($searchText)) {
            $likeCriteria = "(asunto LIKE '%".$searchText."%')";
            $this->db->where($likeCriteria);
        }
        $this->db->where('estado', 0);
        $this->db->where('omision>', 0);

        $query = $this->db->get();
        
        return $query->num_rows();

        
    }
    function oficiosListingCount($searchText)
    {
        $this->db->select('*');
        $this->db->from('oficios ');
        if(!empty($searchText)) {
            $likeCriteria = "(asunto LIKE '%".$searchText."%')";
            $this->db->where($likeCriteria);
        }
        $this->db->where('estado', 0);
        $query = $this->db->get();
        
        return $query->num_rows();
    }
    public function personal_comisionado(){
         $query = $this->db->get('personas'); return $query->result_array(); 
    }
    
    public function dependencias() { 
        $query = $this->db->get('catalogo_dependencias'); return $query->result_array(); 
    }
    public function tipo_omision() { 
        $query = $this->db->get('catalgo_tipo_omision'); return $query->result_array(); 
    }

         function omisiones($tipo_omision) {
            $this->db->select('cve_omision, des_omision');
            $this->db->where('cve_tipo_omision', $tipo_omision);
            $query = $this->db->get('catalogo_omisiones');
    
            $result []= ["SELEONE UNA OMISIÓN"];
             foreach ($query->result() as $row) {
                $result[$row->cve_omision] = $row->des_omision;
            }
          
            return $result;
        }
    
    public function oficio_creado_por($no_oficio) { 
        $this->db->select('name,userId'); // Selecciona columnas específicas
         $this->db->where('userId', $no_oficio); // Aplica el filtro de búsqueda
            $this->db->from('tbl_users'); // Establece la tabla
            $query = $this->db->get(); // Ejecuta la consulta
         return $query->row(); 
    }
    

    public function jefes_departamento() { 
        /* echo"<pre>";
         print_r($_SESSION['role']);
         echo"</pre>";   
         die;*/
         $this->db->select('cve_jefe, concat(abreviatura_titulo," ",nombre_jefe)nombre_jefe'); // Selecciona columnas específicas
         $this->db->from('jefes_departamento'); // Establece la tabla
         $this->db->where('estado_jefe', "activo"); // Aplica el filtro de búsqueda
         $query = $this->db->get(); // Ejecuta la consulta
         return $query->result_array(); // Retorna el resultado como un arreglo asociativo
     
     
     
         }

function ObtenerInfoOficio1($no_oficio){
            $this->db->select('*');
            $this->db->from('oficios a');
            $this->db->join('jefes_departamento b', 'a.quien_firma=b.cve_jefe' , 'left');
            $this->db->join('tbl_users AS c', 'a.oficio_creado_por = c.userId' , 'left');
            $this->db->join('personas AS d', 'a.personal_comisionado = d.rfc_personal' , 'left');
              $this->db->join('catalogo_dependencias AS e', 'a.destinatario=e.cve_dependencia' , 'left');
         
            $this->db->where('no_oficio', $no_oficio);
            $this->db->where('a.estado', 0);
            $query = $this->db->get();
           
            return $query->row(); // Devuelve los resultados como un array
         }


         function ObtenerInfoIncidencia($cve_incidencia){
            $this->db->select('abreviatura_titulo,cve_incidencia,b.name, c.nombre_jefe, a.fecha_comision, a.omision, a.tipo_omision, d.des_tipo_omision, e.des_omision, a.descripcion_omision');
            $this->db->from('incidencias a');
            $this->db->join('tbl_users b', 'a.oficio_creado_por = b.userId', 'left');
            $this->db->join('jefes_departamento c', 'a.quien_firma = c.cve_jefe', 'left');
            $this->db->join('catalgo_tipo_omision d', 'a.tipo_omision = d.cve_tipo_omision', 'left');
            $this->db->join('catalogo_omisiones e', 'a.omision = e.cve_omision AND a.tipo_omision = e.cve_tipo_omision', 'left');
            $this->db->where('cve_incidencia', $cve_incidencia);

            $this->db->order_by('cve_incidencia', 'DESC');
       
            $query = $this->db->get();
            return $query->row(); // Devuelve los resultados como un array
    

         }
         function incidenciasListing1($searchText, $page, $segment,$vendorId){
            $this->db->select('cve_incidencia,b.name, c.nombre_jefe, a.fecha_comision, a.omision, a.tipo_omision, d.des_tipo_omision, e.des_omision, a.descripcion_omision');
            $this->db->from('incidencias a');
            $this->db->join('tbl_users b', 'a.oficio_creado_por = b.userId', 'left');
            $this->db->join('jefes_departamento c', 'a.quien_firma = c.cve_jefe', 'left');
            $this->db->join('catalgo_tipo_omision d', 'a.tipo_omision = d.cve_tipo_omision', 'left');
            $this->db->join('catalogo_omisiones e', 'a.omision = e.cve_omision AND a.tipo_omision = e.cve_tipo_omision', 'left');

            if (!empty($searchText)) {
                $this->db->like('a.asunto', $searchText);
            }
            $this->db->where('oficio_creado_por', $vendorId);

            $this->db->order_by('cve_incidencia', 'DESC');
        $this->db->limit($page, $segment);
            $query = $this->db->get();
            return $query->result(); // Devuelve los resultados como un array
    

         }
         function incidenciasListing($searchText, $page, $segment){
            $this->db->select("
            a.no_oficio,
            a.fecha_comision,
            c.name AS elaborado,
            a.asunto,
            b.nombre AS destinatario,
            d.nombre AS quien_firma,
            a.estado,
            CASE 
                WHEN a.omision = 1 THEN 'Entrada'
                WHEN a.omision = 2 THEN 'Salida'
                WHEN a.omision = 3 THEN 'Ambos'
                ELSE ''
            END AS omision
        ");
        $this->db->from('oficios AS a');
        $this->db->join('personas AS b', 'a.destinatario = b.cve_personal', 'left');
        $this->db->join('personas AS d', 'a.quien_firma = d.cve_personal', 'left');
        $this->db->join('tbl_users AS c', 'a.oficio_creado_por = c.userId', 'left');
        
        if (!empty($searchText)) {
            $this->db->like('a.asunto', $searchText);
        }
        
        $this->db->where('a.estado', 0);
        $this->db->where('a.omision >', 0);
        $this->db->order_by('a.no_oficio', 'DESC');
        $this->db->limit($page, $segment);
        
        $query = $this->db->get();
        return $query->result();

         }
    function oficiosListing($searchText, $page, $segment)
    {

           $this->db->select(' a.no_oficio,a.fecha_oficio,c.name AS creado_por,a.asunto,a.lugar_comision, a.modalidad,a.fecha_comision,a.motivo_comision,d.nombre AS personal_comisionado,e.des_dependencia,b.nombre_jefe  as quien_firma,a.estado');
            $this->db->from('oficios a');
            $this->db->join('jefes_departamento b', 'a.quien_firma=b.cve_jefe' , 'left');
            $this->db->join('tbl_users AS c', 'a.oficio_creado_por = c.userId' , 'left');
            $this->db->join('personas AS d', 'a.personal_comisionado = d.rfc_personal' , 'left');
             $this->db->join('catalogo_dependencias AS e', 'a.destinatario=e.cve_dependencia' , 'left');
         
          
            $this->db->where('a.estado', 0);
   


        if(!empty($searchText)) {
            $likeCriteria = "(a.asunto LIKE '%".$searchText."%')";
            $this->db->where($likeCriteria);
        }
        
        $this->db->order_by('a.no_oficio', 'DESC');
        $this->db->limit($page, $segment);
        $query = $this->db->get();
        
        $result = $query->result();        
        return $result;
    }
    
    /**
     * This function is used to add new task to system
     * @return number $insert_id : This is last inserted id
     */
    function AgregarNuevoOficio($oficioInfo)
    {
        $this->db->trans_start();
        $this->db->insert('oficios', $oficioInfo);
        
        $insert_id = $this->db->insert_id();
        
        $this->db->trans_complete();
      //  die("guardar oficio");
        return $insert_id;
    }
    
    /**
     * This function used to get task information by id
     * @param number $taskId : This is task id
     * @return array $result : This is task information
     */
    function ObtenerInfoOficio($no_oficio)
    {
        $this->db->select('*');
        $this->db->from('oficios a');
        $this->db->join('personas as b', 'a.destinatario = b.cve_personal' , 'left');
        $this->db->join('personas as d', 'a.quien_firma = d.cve_personal' , 'left');
        $this->db->join('tbl_users as c', 'a.oficio_creado_por = c.userId' , 'left');
        $this->db->join('jefes_departamento e', 'a.quien_firma=e.cve_jefe' , 'left');
        
   
        $this->db->where('no_oficio', $no_oficio);
        $this->db->where('estado', 0);
        $query = $this->db->get();
        
        return $query->row();
    }
    
    
    /**
     * This function is used to update the task information
     * @param array $taskInfo : This is task updated information
     * @param number $taskId : This is task id
     */
    function GuardarOficio($OficioInfo, $no_oficio)
    {
        $this->db->where('no_oficio', $no_oficio);
        $this->db->update('oficios', $OficioInfo);
        
        return TRUE;
    }
    function agregarNuevoIncidencia($oficioInfo)
    {
        $this->db->trans_start();
        $this->db->insert('incidencias', $oficioInfo);
        
        $insert_id = $this->db->insert_id();
        
        $this->db->trans_complete();
        
        return $insert_id;
    }
   
}