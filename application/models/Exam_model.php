<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class Exam_model extends CI_Model
{
    function ies() {
          $nombre = strtoupper($_SESSION['name']);
  
switch ($nombre) {

    case 'ACADEMICA':
        $resultado = '';
        break;

    case 'UPN 151':

        $resultado = '3';
        break;
        case 'UPN 152':
           $resultado = '4';
        break;

    case 'UPN 153':
        $resultado = '5';
        break;

    case 'ESCUELA NORMAL SUPERIOR DEL VALLE DE MÉXICO':
        $resultado = '1';
        break;

    case 'ESCUELA NORMAL SUPERIOR DEL VALLE DE TOLUCA':
        $resultado = '2';
        break;

   

    default:
        $resultado = '';
        break;
}
    
   
  $this->db->select('*');
  $this->db->from('cat_ies a'); 
        if (!empty($resultado)) {
    $this->db->where('cve_ies', $resultado);
}  
 $query = $this->db->get();
        
        return $query->result_array();


      
    }
   public function get_sedes_by_ies($cve_ies)
{
  $nombre = strtoupper($_SESSION['userId']);
  
switch ($nombre) {

    case 2://academica
        $resultado = '';
        break;

    case 3://nezahualcoyotl

        $resultado = 1;
      
        break;
        case 4 ://tlalnepantla
           $resultado = 2;
        break;

    case 5://huerta
        $resultado = 3;
        break;

    case 6://acambay
        $resultado = 4;
        break;

   case 7://ixtlahuaca
        $resultado = 5;
        break;
    case 8://JILOTEPEC
        $resultado = 6;
        break;
    case 9://TEJUPILCO
        $resultado = 7;
        break;
    case 10://toluca
        $resultado = 8;
        break;
    case 11://atizapan
        $resultado = 9;
        break;
    case 12://upn nezahualcoyotl
        $resultado = 10;
        break;
    case 13://tultepec
        $resultado = 11;
        break;
    case 14://ecatepec
        $resultado =12;
        break;

    default:
        $resultado = 12;
        break;
}

  $this->db->select('cve_sede, sede')
         ->from('catalogo_sede')
         ->where('cve_ies', $cve_ies)
         ->order_by('sede', 'ASC');

!empty($resultado) && $this->db->where('cve_sede', $resultado);

return $this->db->get()->result_array();

}

public function get_programas_by_sede($cve_ies, $cve_sede)
{

    
    return $this->db->select('cve_programa, programa')
                    ->from('cat_programas')
                    ->where('cve_ies', $cve_ies)
                    ->where('cve_sede', $cve_sede)
                    ->order_by('programa', 'ASC')
                    ->get()
                    ->result_array();
}
public function get_resultados($ies = null, $sede = null, $programa = null)
{
   $this->db->select("
    ies,
    sede,
    programa,
    a.nombre_alumno,
    b.rubro,

    SUM(CASE WHEN a.estado = 'correcto' THEN 1 ELSE 0 END) AS correctos,
    SUM(CASE WHEN a.estado = 'incorrecto' THEN 1 ELSE 0 END) AS incorrectos,

    COUNT(*) AS total_rubro,

    ROUND(
        (SUM(CASE WHEN a.estado = 'correcto' THEN 1 ELSE 0 END) / COUNT(*)) * 2.5,
        2
    ) AS calificacion_rubro
", FALSE);

$this->db->from('evaluacion_conocimientos_respuestas_2025 a');
$this->db->join('cat_ies b2', 'a.cve_ies = b2.cve_ies', 'left');
$this->db->join('catalogo_sede c', 'a.cve_sede = c.cve_sede', 'left');
$this->db->join('cat_programas d', 'a.cve_programa = d.cve_programa', 'left');
$this->db->join('catologo_rubro b', 'a.cve_rubro = b.cve_rubro', 'inner'); // <-- este es de tu consulta MySQL

// FILTROS OPCIONALES (solo se agregan si existe valor)
if (!empty($ies)) {
    $this->db->where('a.cve_ies', $ies);
}

if (!empty($sede)) {
    $this->db->where('a.cve_sede', $sede);
}

if (!empty($programa)) {
    $this->db->where('a.cve_programa', $programa);
}

$this->db->group_by([
    'a.cve_ies',
    'a.cve_sede',
    'a.cve_programa',
    'a.nombre_alumno',
    'b.rubro'
]);

$this->db->order_by('a.nombre_alumno', 'ASC');
$this->db->order_by('a.cve_sede', 'ASC');
$this->db->order_by('b.cve_rubro', 'ASC');

$query = $this->db->get();

    return $query->result_array();
}
public function get_all_resultados_for_sedes($ies = null, $sede = null)
{
     $this->db->select("
   
    b.rubro,
sede,ies,
    SUM(CASE WHEN a.estado = 'correcto' THEN 1 ELSE 0 END) AS correctos,
    SUM(CASE WHEN a.estado = 'incorrecto' THEN 1 ELSE 0 END) AS incorrectos,

    COUNT(*) AS total_rubro,

    ROUND(
        (SUM(CASE WHEN a.estado = 'correcto' THEN 1 ELSE 0 END) / COUNT(*)) * 10,
        2
    ) AS calificacion_rubro
", FALSE);

$this->db->from('evaluacion_conocimientos_respuestas_2025 a');
$this->db->join('cat_ies b2', 'a.cve_ies = b2.cve_ies', 'left');
$this->db->join('catalogo_sede c', 'a.cve_sede = c.cve_sede', 'left');
$this->db->join('cat_programas d', 'a.cve_programa = d.cve_programa', 'left');
$this->db->join('catologo_rubro b', 'a.cve_rubro = b.cve_rubro', 'inner'); // <-- este es de tu consulta MySQL

// FILTROS OPCIONALES (solo se agregan si existe valor)
if (!empty($ies)) {
    $this->db->where('a.cve_ies', $ies);
}



$this->db->group_by([
 
    'a.cve_sede',
    'b.rubro'
]);


$this->db->order_by('a.cve_sede', 'ASC');
$this->db->order_by('b.cve_rubro', 'ASC');

$query = $this->db->get();

    return $query->result_array();

   
}
public function get_all_resultados_for_ies()
{
     $this->db->select("
   
    b.rubro,
sede,ies,
    SUM(CASE WHEN a.estado = 'correcto' THEN 1 ELSE 0 END) AS correctos,
    SUM(CASE WHEN a.estado = 'incorrecto' THEN 1 ELSE 0 END) AS incorrectos,

    COUNT(*) AS total_rubro,

    ROUND(
        (SUM(CASE WHEN a.estado = 'correcto' THEN 1 ELSE 0 END) / COUNT(*)) * 10,
        2
    ) AS calificacion_rubro
", FALSE);

$this->db->from('evaluacion_conocimientos_respuestas_2025 a');
$this->db->join('cat_ies b2', 'a.cve_ies = b2.cve_ies', 'left');
$this->db->join('catalogo_sede c', 'a.cve_sede = c.cve_sede', 'left');
$this->db->join('cat_programas d', 'a.cve_programa = d.cve_programa', 'left');
$this->db->join('catologo_rubro b', 'a.cve_rubro = b.cve_rubro', 'inner'); // <-- este es de tu consulta MySQL




$this->db->group_by([
 
   'a.cve_sede',
    'b.rubro'
]);


$this->db->order_by('a.cve_ies', 'ASC');
$this->db->order_by('b.cve_rubro', 'ASC');

$query = $this->db->get();

    return $query->result_array();

   
}
public function get_all_resultados_for_planes($ies = null, $sede = null)
{
     $this->db->select("
   
    b.rubro,
sede,ies,programa,
    SUM(CASE WHEN a.estado = 'correcto' THEN 1 ELSE 0 END) AS correctos,
    SUM(CASE WHEN a.estado = 'incorrecto' THEN 1 ELSE 0 END) AS incorrectos,

    COUNT(*) AS total_rubro,

    ROUND(
        (SUM(CASE WHEN a.estado = 'correcto' THEN 1 ELSE 0 END) / COUNT(*)) * 10,
        2
    ) AS calificacion_rubro
", FALSE);

$this->db->from('evaluacion_conocimientos_respuestas_2025 a');
$this->db->join('cat_ies b2', 'a.cve_ies = b2.cve_ies', 'left');
$this->db->join('catalogo_sede c', 'a.cve_sede = c.cve_sede', 'left');
$this->db->join('cat_programas d', 'a.cve_programa = d.cve_programa', 'left');
$this->db->join('catologo_rubro b', 'a.cve_rubro = b.cve_rubro', 'inner'); // <-- este es de tu consulta MySQL

// FILTROS OPCIONALES (solo se agregan si existe valor)
if (!empty($ies)) {
    $this->db->where('a.cve_ies', $ies);
}

if (!empty($sede)) {
    $this->db->where('a.cve_sede', $sede);
}


$this->db->group_by([
 'a.cve_programa',
    'a.cve_sede',
    'b.rubro'
]);


$this->db->order_by('a.cve_sede', 'ASC');
$this->db->order_by('b.cve_rubro', 'ASC');

$query = $this->db->get();

    return $query->result_array();

   
}

public function get_all_resultados_for_report($ies = null, $sede = null, $programa = null)
{
   $this->db->select("
   
    a.nombre_alumno,
    b.rubro,
sede,ies,programa,
    SUM(CASE WHEN a.estado = 'correcto' THEN 1 ELSE 0 END) AS correctos,
    SUM(CASE WHEN a.estado = 'incorrecto' THEN 1 ELSE 0 END) AS incorrectos,

    COUNT(*) AS total_rubro,

    ROUND(
        (SUM(CASE WHEN a.estado = 'correcto' THEN 1 ELSE 0 END) / COUNT(*)) * 10,
        2
    ) AS calificacion_rubro
", FALSE);

$this->db->from('evaluacion_conocimientos_respuestas_2025 a');
$this->db->join('cat_ies b2', 'a.cve_ies = b2.cve_ies', 'left');
$this->db->join('catalogo_sede c', 'a.cve_sede = c.cve_sede', 'left');
$this->db->join('cat_programas d', 'a.cve_programa = d.cve_programa', 'left');
$this->db->join('catologo_rubro b', 'a.cve_rubro = b.cve_rubro', 'inner'); // <-- este es de tu consulta MySQL

// FILTROS OPCIONALES (solo se agregan si existe valor)
if (!empty($ies)) {
    $this->db->where('a.cve_ies', $ies);
}

if (!empty($sede)) {
    $this->db->where('a.cve_sede', $sede);
}

if (!empty($programa)) {
    $this->db->where('a.cve_programa', $programa);
}

$this->db->group_by([
 
    'a.nombre_alumno',
    'b.rubro'
]);

$this->db->order_by('a.nombre_alumno', 'ASC');
$this->db->order_by('a.cve_sede', 'ASC');
$this->db->order_by('b.cve_rubro', 'ASC');

$query = $this->db->get();

    return $query->result_array();
}


}
