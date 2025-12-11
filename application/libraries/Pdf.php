<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

// Incluir la clase TCPDF
require_once(APPPATH.'third_party/tcpdf/tcpdf.php');

class Pdf extends TCPDF {
    protected $ci; // Instancia de CodeIgniter
    function __construct() {
        parent::__construct();
        $this->ci = &get_instance();
        if (!isset($this->ci->db)) {
            $this->ci->load->database();
        }
   
    }

    
public function Header() {



    // 🔹 Si quieres imagen, actívala
     $this->Image(base_url('assets/images/cabeza.png'), 14, 5, 200, 25);
}

    

    // Sobrescribir el método Footer para establecer la imagen de fondo
    public function Footer() {
        // Obtener las dimensiones de la página

        // Establecer la imagen de fondo
        $this->SetY(-20); // Posicionar cerca del final de la página
        $this->Image(base_url('assets/images/pie.png'), 12, $this->GetY(), 200,5); // Imagen en la parte inferior

      
            $lugares="Agripín García Estrada núm. 1306, primer piso, colonia Santa Cruz Atzcapotzaltongo, C. P. 50290,\n\n Toluca, Estado de México. Teléfono: 722 265 12 00, exts.: 1505. Página web: seiem.gob.mx";
        
        
     $this->SetXY(10,-13);
             // Ajustar la posición Y para el texto 
             $this->SetFont('gothambook', '', 6); 
             // Establecer la fuente 
              // Añadir el texto centrado
             $this->writeHTMLCell(180, 10, '', '',$lugares, 0, 1, 0, true, 'C', true); 


    }
}
?>
