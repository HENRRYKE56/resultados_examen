<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class Exam_model_docente extends CI_Model
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
         case 15://nromero
        $resultado =13;
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
/*public function get_resultados($ies = null, $sede = null, $programa = null)
{
$this->db->select("
    b.ies AS institucion,
    c.sede AS sede,
     d.programa AS programa,
    a.grado AS grado,
    a.grupo AS grupo,
    a.asignatura AS asignatura,
    a.nombre_docente AS nombre_docente,

    ROUND((p1+p2+p3+p4+p5+p6)/30*10.0,2) AS planeacion,
    ROUND((p7+p8+p9+p10+p11+p12+p13)/35*10.0,2) AS saberes,
    ROUND((p14+p15+p16+p17+p18)/25*10.0,2) AS habilidades,
    ROUND((p19+p20+p21+p22+p23)/25*10.0,2) AS recursos,
    ROUND((p24+p25+p26+p27+p28+p29)/30*10.0,2) AS etica,
    ROUND((p30+p31+p32+p33+p34+p35)/30*10.0,2) AS evaluacion,

    a.ponderacion AS total,
    ROUND(a.ponderacion/175*10.0,2) AS promedio
", FALSE);


$this->db->from('evaluacion_docente_diciembre_2025 a');
$this->db->join('cat_ies b', 'a.cve_ies = b.cve_ies', 'left');
$this->db->join('catalogo_sede c', 'a.cve_ies = c.cve_ies AND a.cve_sede = c.cve_sede', 'left');
$this->db->join('cat_programas d', 'a.cve_ies = d.cve_ies AND a.cve_sede = d.cve_sede AND a.cve_programa = d.cve_programa', 'left');



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
    'a.asignatura',
    'a.nombre_docente'
]);

//a., a., a.
$this->db->order_by('a.cve_ies', 'ASC');
$this->db->order_by('a.cve_sede', 'ASC');
$this->db->order_by('a.cve_programa', 'ASC');
$this->db->order_by('a.grado', 'ASC');


$query = $this->db->get();

    return $query->result_array();
}*/
public function get_all_resultados_for_sedes($ies = null, $sede = null)
{
  $this->db->select("
    b.ies AS institucion,
    c.sede AS sede,
     d.programa AS programa,
    a.grado AS grado,
    a.grupo AS grupo,
    a.asignatura AS asignatura,
    a.nombre_docente AS nombre_docente,

    ROUND((p1+p2+p3+p4+p5+p6)/30*10.0,2) AS planeacion,
    ROUND((p7+p8+p9+p10+p11+p12+p13)/35*10.0,2) AS saberes,
    ROUND((p14+p15+p16+p17+p18)/25*10.0,2) AS habilidades,
    ROUND((p19+p20+p21+p22+p23)/25*10.0,2) AS recursos,
    ROUND((p24+p25+p26+p27+p28+p29)/30*10.0,2) AS etica,
    ROUND((p30+p31+p32+p33+p34+p35)/30*10.0,2) AS evaluacion,

    a.ponderacion AS total,
    ROUND(a.ponderacion/175*10.0,2) AS promedio
", FALSE);



$this->db->from('evaluacion_docente_diciembre_2025 a');
$this->db->join('cat_ies b', 'a.cve_ies = b.cve_ies', 'left');
$this->db->join('catalogo_sede c', 'a.cve_ies = c.cve_ies AND a.cve_sede = c.cve_sede', 'left');
$this->db->join('cat_programas d', 'a.cve_ies = d.cve_ies AND a.cve_sede = d.cve_sede AND a.cve_programa = d.cve_programa', 'left');



// FILTROS OPCIONALES (solo se agregan si existe valor)
if (!empty($ies)) {
    $this->db->where('a.cve_ies', $ies);
}


$this->db->group_by([
    'a.cve_sede'
    
]);

//a., a., a.
$this->db->order_by('a.cve_ies', 'ASC');
$this->db->order_by('a.cve_sede', 'ASC');
$this->db->order_by('a.cve_programa', 'ASC');
$this->db->order_by('a.grado', 'ASC');


 $query = $this->db->get();

// Devuelves los resultados
return $query->result_array();



   
}
public function get_all_resultados_for_ies()
{
    $this->db->select("
    b.ies AS institucion,
   

    ROUND(avg(p1+p2+p3+p4+p5+p6)/30*10.0,2) AS planeacion,
    ROUND(avg(p7+p8+p9+p10+p11+p12+p13)/35*10.0,2) AS saberes,
    ROUND(avg(p14+p15+p16+p17+p18)/25*10.0,2) AS habilidades,
    ROUND(avg(p19+p20+p21+p22+p23)/25*10.0,2) AS recursos,
    ROUND(avg(p24+p25+p26+p27+p28+p29)/30*10.0,2) AS etica,
    ROUND(avg(p30+p31+p32+p33+p34+p35)/30*10.0,2) AS evaluacion,

    a.ponderacion AS total,
    ROUND(avg(a.ponderacion)/175*10.0,2) AS promedio
", FALSE);


$this->db->from('evaluacion_docente_diciembre_2025 a');
$this->db->join('cat_ies b', 'a.cve_ies = b.cve_ies', 'left');
$this->db->join('catalogo_sede c', 'a.cve_ies = c.cve_ies AND a.cve_sede = c.cve_sede 
JOIN catalogo_sede c ON c.cve_sede = a.cve_sede' , 'left');
$this->db->join('cat_programas d', 'a.cve_ies = d.cve_ies AND a.cve_sede = d.cve_sede AND a.cve_programa = d.cve_programa AND d.cve_ies=c.cve_ies AND d.cve_sede=c.cve_sede', 'left');



// FILTROS OPCIONALES (solo se agregan si existe valor)
if (!empty($ies)) {
    $this->db->where('a.cve_ies', $ies);
}



$this->db->group_by([
    'a.cve_ies',
   
]);

//a., a., a.
$this->db->order_by('a.cve_ies', 'ASC');

$query = $this->db->get();

    return $query->result_array();
   
}
public function get_all_resultados_for_planes_esta($ies = null, $sede = null, $programa = null){


        /* =====================================================
           1️⃣ SUBCONSULTA: promedio por ALUMNO (nivel correcto)
        ====================================================== */

        $subquery = "
            SELECT
                b.ies AS institucion,
                c.sede AS sede,
                d.programa AS programa,
                a.grado,
                a.grupo,
                a.asignatura,
                a.nombre_docente,
                a.nombre_alumno,

                ROUND(AVG(a.ponderacion / 175 * 10), 2) AS promedio,

                ROUND(AVG((p1+p2+p3+p4+p5+p6)/30*10),2) AS planeacion,
                ROUND(AVG((p7+p8+p9+p10+p11+p12+p13)/35*10),2) AS saberes,
                ROUND(AVG((p14+p15+p16+p17+p18)/25*10),2) AS habilidades,
                ROUND(AVG((p19+p20+p21+p22+p23)/25*10),2) AS recursos,
                ROUND(AVG((p24+p25+p26+p27+p28+p29)/30*10),2) AS etica_y_valores,
                ROUND(AVG((p30+p31+p32+p33+p34+p35)/30*10),2) AS evaluacion
             FROM evaluacion_docente_diciembre_2025 a
JOIN cat_ies b ON b.cve_ies = a.cve_ies
JOIN catalogo_sede c ON c.cve_sede = a.cve_sede
JOIN cat_programas d ON d.cve_programa = a.cve_programa
            WHERE 1=1
        ";

        /* =====================
           2️⃣ WHERE dinámico
        ====================== */

        if (!empty($ies)) {
            $subquery .= " AND a.cve_ies = " . $this->db->escape($ies);
        }

        if (!empty($sede)) {
            $subquery .= " AND a.cve_sede = " . $this->db->escape($sede);
        }

        if (!empty($programa)) {
            $subquery .= " AND a.cve_programa = " . $this->db->escape($programa);
        }

        /* =====================
           3️⃣ GROUP BY alumno
        ====================== */

        $subquery .= "
            GROUP BY
                b.ies, c.sede, d.programa,
                a.grado, a.grupo,
                a.asignatura,
                a.nombre_docente,
                a.nombre_alumno
        ";

        /* =====================================================
           4️⃣ CONSULTA FINAL: conteos y promedios reales
        ====================================================== */

        $this->db->select("
            institucion,
            sede,
            programa,
            grado,
            grupo,
            asignatura,
            nombre_docente,

            COUNT(*) AS total_alumnos,
            SUM(CASE WHEN promedio >= 7 THEN 1 ELSE 0 END) AS aprobados,
            SUM(CASE WHEN promedio < 7 THEN 1 ELSE 0 END) AS reprobados,

            ROUND(AVG(planeacion),2)  AS planeacion,
            ROUND(AVG(saberes),2)     AS saberes,
            ROUND(AVG(habilidades),2) AS habilidades,
            ROUND(AVG(recursos),2)    AS recursos,
            ROUND(AVG(etica_y_valores),2)       AS etica_y_valores,
            ROUND(AVG(evaluacion),2)  AS evaluacion,
            ROUND(AVG(promedio),2)    AS promedio_general
        ", false);

        $this->db->from("($subquery) AS t");

        $this->db->group_by("
            institucion,
            sede,
            programa,
            grado,
            grupo,
            asignatura,
            nombre_docente
        ");
        $this->db->order_by("grado, grupo, asignatura", "ASC");
        return $this->db->get()->result_array();
    }


public function get_all_resultados_for_planes($ies = null, $sede = null)
{
   $this->db->select("
    b.ies AS institucion,
    c.sede AS sede,
     d.programa AS programa,
    a.grado AS grado,
    a.grupo AS grupo,
    a.asignatura AS asignatura,
    a.nombre_docente AS nombre_docente,

    ROUND(avg(p1+p2+p3+p4+p5+p6)/30*10.0,2) AS planeacion,
    ROUND(avg(p7+p8+p9+p10+p11+p12+p13)/35*10.0,2) AS saberes,
    ROUND(avg(p14+p15+p16+p17+p18)/25*10.0,2) AS habilidades,
    ROUND(avg(p19+p20+p21+p22+p23)/25*10.0,2) AS recursos,
    ROUND(avg(p24+p25+p26+p27+p28+p29)/30*10.0,2) AS etica,
    ROUND(avg(p30+p31+p32+p33+p34+p35)/30*10.0,2) AS evaluacion,

    a.ponderacion AS total,
    ROUND(avg(a.ponderacion)/175*10.0,2) AS promedio
", FALSE);



$this->db->from('evaluacion_docente_diciembre_2025 a');
$this->db->join('cat_ies b', 'a.cve_ies = b.cve_ies', 'left');
$this->db->join('catalogo_sede c', 'a.cve_ies = c.cve_ies AND a.cve_sede = c.cve_sede', 'left');
$this->db->join('cat_programas d', 'a.cve_ies = d.cve_ies AND a.cve_sede = d.cve_sede AND a.cve_programa = d.cve_programa', 'left');


if (!empty($ies)) {
    $this->db->where('a.cve_ies', $ies);
}

if (!empty($sede)) {
    $this->db->where('a.cve_sede', $sede);
}

$this->db->group_by('b.curp');
$this->db->having("TRIM(b.curp) <> ''", null, false);

$this->db->group_by([
    'a.curp'
   
]);

//a., a., a.
$this->db->order_by('a.cve_ies', 'ASC');
$this->db->order_by('a.cve_sede', 'ASC');
$this->db->order_by('a.cve_programa', 'ASC');
$this->db->order_by('a.grado', 'ASC');

$query = $this->db->get();

    return $query->result_array();

   
}
// Obtener resultados por estudiante ingles 2025
   

// Obtener resultados por estudiante ingles 2025
public function get_all_resultados_for_report($ies = null, $sede = null, $programa = null)
{
$this->db->select("
    b.ies AS institucion,
    c.sede AS sede,
     d.programa AS programa,
    a.grado AS grado,
    a.grupo AS grupo,
    a.asignatura AS asignatura,
    a.nombre_docente AS nombre_docente,

    ROUND(avg(p1+p2+p3+p4+p5+p6)/30*10.0,2) AS planeacion,
    ROUND(avg(p7+p8+p9+p10+p11+p12+p13)/35*10.0,2) AS saberes,
    ROUND(avg(p14+p15+p16+p17+p18)/25*10.0,2) AS habilidades,
    ROUND(avg(p19+p20+p21+p22+p23)/25*10.0,2) AS recursos,
    ROUND(avg(p24+p25+p26+p27+p28+p29)/30*10.0,2) AS etica,
    ROUND(avg(p30+p31+p32+p33+p34+p35)/30*10.0,2) AS evaluacion,

    a.ponderacion AS total,
    ROUND(avg(a.ponderacion)/175*10.0,2) AS promedio
", FALSE);


$this->db->from('evaluacion_docente_diciembre_2025 a');
$this->db->join('cat_ies b', 'a.cve_ies = b.cve_ies', 'left');
$this->db->join('catalogo_sede c', 'a.cve_ies = c.cve_ies AND a.cve_sede = c.cve_sede', 'left');
$this->db->join('cat_programas d', 'a.cve_ies = d.cve_ies AND a.cve_sede = d.cve_sede AND a.cve_programa = d.cve_programa', 'left');




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
    'a.asignatura',
    'a.nombre_docente'
]);

//a., a., a.
$this->db->order_by('a.cve_ies', 'ASC');
$this->db->order_by('a.cve_sede', 'ASC');
$this->db->order_by('a.cve_programa', 'ASC');
$this->db->order_by('a.grado', 'ASC');

$query = $this->db->get();

    return $query->result_array();
}

public function formato_docente($ies, $sede){


   $this->db->select("cve_evaluacion,
    b.ies AS institucion,
    c.sede AS sede,
    d.programa,
    a.nombre_docente,

ROUND(AVG(a.ponderacion) / 175 * 10, 2) AS escala_10,
ROUND(AVG(a.ponderacion) / 175 * 5, 2) AS escala_5,
ROUND(AVG(a.ponderacion) / 175 * 35, 2) AS escala_35,

    ROUND(AVG((p1+p2+p3+p4+p5+p6)/30*10),2) AS planeacion,
    ROUND(AVG((p7+p8+p9+p10+p11+p12+p13)/35*10),2) AS saberes,
    ROUND(AVG((p14+p15+p16+p17+p18)/25*10),2) AS habilidades,
    ROUND(AVG((p19+p20+p21+p22+p23)/25*10),2) AS recursos,
    ROUND(AVG((p24+p25+p26+p27+p28+p29)/30*10),2) AS etica_y_valores,
    ROUND(AVG((p30+p31+p32+p33+p34+p35)/30*10),2) AS evaluacion
", FALSE);

$this->db->from('evaluacion_docente_diciembre_2025 a');

$this->db->join('cat_ies b', 'a.cve_ies = b.cve_ies', 'left');
$this->db->join('catalogo_sede c', 'a.cve_sede = c.cve_sede', 'left');
$this->db->join('cat_programas d', 'a.cve_programa = d.cve_programa', 'left');

// FILTROS
if (!empty($ies)) {
    $this->db->where('a.cve_ies', $ies);
}

if (!empty($sede)) {
    $this->db->where('a.cve_sede', $sede);
}

// AGRUPACIÓN
$this->db->group_by([
    'a.cve_sede',
    'a.nombre_docente'
]);

$query = $this->db->get();

$resultados = $query->result_array();




         return $resultados;
}
}
