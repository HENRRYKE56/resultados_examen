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
   $this->db->select("*", FALSE);

$this->db->from('evaluacion_conocimientos_respuestas_2025 a');
$this->db->join('cat_ies b2', 'a.cve_ies = b2.cve_ies', 'left');
$this->db->join('catalogo_sede c', 'a.cve_sede = c.cve_sede', 'left');
$this->db->join('cat_programas d', 'a.cve_programa = d.cve_programa', 'left');
//$this->db->join('catologo_rubro b', 'a.cve_rubro = b.cve_rubro', 'inner'); // <-- este es de tu consulta MySQL

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
    
]);

$this->db->order_by('a.nombre_alumno', 'ASC');
$this->db->order_by('a.cve_sede', 'ASC');


$query = $this->db->get();

    return $query->result_array();
}
public function get_all_resultados_for_sedes($ies = null, $sede = null)
{
    /////
 $this->db->select("
    a.examen, 
    a.cve_semestre, 
    ROUND(AVG(a.grammar), 2) AS grammar, 
    ROUND(AVG(a.vocabulary), 2) AS vocabulary, 
    ROUND(AVG(a.reading), 2) AS reading, 
    ROUND(AVG(a.promedio), 2) AS promedio, 
    b2.ies, 
    c.sede, 
    d.programa
", FALSE); // El FALSE es importante para no escapar las funciones SQL

 $this->db->from('resultados_x_secciones a');
 $this->db->join('cat_ies b2', 'a.cve_ies = b2.cve_ies', 'left');
 $this->db->join('catalogo_sede c', 'a.cve_sede = c.cve_sede', 'left');
 $this->db->join('cat_programas d', 'a.cve_programa = d.cve_programa', 'left');

// FILTROS OPCIONALES (solo se agregan si existe valor)
if (!empty($ies)) {
    $this->db->where('a.cve_ies', $ies);
}

// Agrupamos por sede y examen, como solicitaste
 $this->db->group_by(['a.cve_sede', 'a.examen']);

// Opcional: Ordenar los resultados para que sean consistentes
 $this->db->order_by('a.cve_sede', 'ASC');


 $query = $this->db->get();

// Devuelves los resultados
return $query->result_array();



   
}
public function get_all_resultados_for_ies()
{
     $this->db->select("a.cve_sede,
    a.examen, 
    a.cve_semestre, 
    ROUND(AVG(a.grammar), 2) AS grammar, 
    ROUND(AVG(a.vocabulary), 2) AS vocabulary, 
    ROUND(AVG(a.reading), 2) AS reading, 
    ROUND(AVG(a.promedio), 2) AS promedio, 
    b2.ies, 
    c.sede, nivel,
    d.programa
", FALSE); // El FALSE es importante para no escapar las funciones SQL

$this->db->from('resultados_x_secciones a');
$this->db->join('cat_ies b2', 'a.cve_ies = b2.cve_ies', 'left');
$this->db->join('catalogo_sede c', 'a.cve_sede = c.cve_sede', 'left');
$this->db->join('cat_programas d', 'a.cve_programa = d.cve_programa', 'left');
//$this->db->join('catologo_rubro b', 'a.cve_rubro = b.cve_rubro', 'inner'); // <-- este es de tu consulta MySQL

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
 
    'a.cve_sede'
    
]);

$this->db->order_by('a.nombre_alumno', 'ASC');
$this->db->order_by('a.cve_sede', 'ASC');
//$this->db->order_by('b.cve_rubro', 'ASC');

$query = $this->db->get();

    return $query->result_array();
   
}
public function get_all_resultados_for_planes($ies = null, $sede = null)
{
     $this->db->select("a.cve_sede,
    a.examen, 
    a.cve_semestre, 
    ROUND(AVG(a.grammar), 2) AS grammar, 
    ROUND(AVG(a.vocabulary), 2) AS vocabulary, 
    ROUND(AVG(a.reading), 2) AS reading, 
    ROUND(AVG(a.promedio), 2) AS promedio, 
    b2.ies, 
    c.sede, 
    d.programa
", FALSE); // El FALSE es importante para no escapar las funciones SQL


$this->db->from('resultados_x_secciones a');
$this->db->join('cat_ies b2', 'a.cve_ies = b2.cve_ies', 'left');
$this->db->join('catalogo_sede c', 'a.cve_sede = c.cve_sede', 'left');
$this->db->join('cat_programas d', 'a.cve_programa = d.cve_programa', 'left');
//$this->db->join('catologo_rubro b', 'a.cve_rubro = b.cve_rubro', 'inner'); // <-- este es de tu consulta MySQL
// FILTROS OPCIONALES (solo se agregan si existe valor)
if (!empty($ies)) {
    $this->db->where('a.cve_ies', $ies);
}

if (!empty($sede)) {
    $this->db->where('a.cve_sede', $sede);
}


 $this->db->group_by(['a.cve_sede', 'a.examen','a.cve_programa']);


$this->db->order_by('a.cve_sede', 'ASC');

$query = $this->db->get();

    return $query->result_array();

   
}
// Obtener resultados por estudiante ingles 2025
public function get_all_resultados_for_report($ies = null, $sede = null, $programa = null, $pass = null)
{
  $this->db->select("nivel,
    a.cve_sede,
    a.examen, 
    a.cve_semestre,a.nombre_alumno,b2.ies,c.sede,d.programa,

    tg.total AS total_grammar_examen,
    tr.total AS total_reading_examen,
    tv.total AS total_vocabulary_examen,promedio,a.total_grammar,a.total_reading,a.total_vocabulary,
", FALSE);

$this->db->from('resultados_x_secciones a');

$this->db->join('cat_examen_secciones tg', "tg.examen = a.examen AND tg.seccion = 'Grammar'", 'left');
$this->db->join('cat_examen_secciones tr', "tr.examen = a.examen AND tr.seccion = 'Reading'", 'left');
$this->db->join('cat_examen_secciones tv', "tv.examen = a.examen AND tv.seccion = 'Vocabulary'", 'left');

$this->db->join('cat_ies b2', 'a.cve_ies = b2.cve_ies', 'left');
$this->db->join('catalogo_sede c', 'a.cve_sede = c.cve_sede', 'left');
$this->db->join('cat_programas d', 'a.cve_programa = d.cve_programa', 'left');

if (!empty($ies)) {
    $this->db->where('a.cve_ies', $ies);
}
if (!empty($sede)) {
    $this->db->where('a.cve_sede', $sede);
}
if (!empty($programa)) {
    $this->db->where('a.cve_programa', $programa);
}
if (!empty($pass)) {
    $this->db->where('a.nivel', $pass);
}

$this->db->group_by('a.nombre_alumno');
$this->db->order_by('a.nombre_alumno', 'ASC');

$query = $this->db->get();
return $query->result_array();

}


}
