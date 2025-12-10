<?php


define ('K_BLANK_IMAGE', '_blank.png');

define ('PDF_PAGE_FORMAT', 'letter ');

/**
 * Page orientation (P=portrait, L=landscape).
 */
define ('PDF_PAGE_ORIENTATION', 'P');

/**
 * Document creator.
 */
define ('PDF_CREATOR', 'HLANDEROS');

/**
 * Document author.
 */
define ('PDF_AUTHOR', 'HLANDEROS');

/**
 * Header title.
 */
define ('PDF_HEADER_TITLE', 'SEIEM');

/**
 * Header description string.
 */
define ('PDF_HEADER_STRING', "Departamento de Formación Profesional");

/**
 * Document unit of measure [pt=point, mm=millimeter, cm=centimeter, in=inch].
 */
define ('PDF_UNIT', 'mm');

/**
 * Header margin.
 */
define ('PDF_MARGIN_HEADER', 5);

/**
 * Footer margin.
 */
define ('PDF_MARGIN_FOOTER', 20);

/**
 * Top margin.
 */
define ('PDF_MARGIN_TOP', 20);

/**
 * Bottom margin.
 */
define ('PDF_MARGIN_BOTTOM', 10);

/**
 * Left margin.
 */
define ('PDF_MARGIN_LEFT', 15);

/**
 * Right margin.
 */
define ('PDF_MARGIN_RIGHT', 15);

/**
 * Default main font name.
 */
define ('PDF_FONT_NAME_MAIN', 'helvetica');

/**
 * Default main font size.
 */
define ('PDF_FONT_SIZE_MAIN', 10);

/**
 * Default data font name.
 */
define ('PDF_FONT_NAME_DATA', 'helvetica');

/**
 * Default data font size.
 */
define ('PDF_FONT_SIZE_DATA', 8);

/**
 * Default monospaced font name.
 */
define ('PDF_FONT_MONOSPACED', 'courier');

/**
 * Ratio used to adjust the conversion of pixels to user units.
 */
define ('PDF_IMAGE_SCALE_RATIO', 1.25);

/**
 * Magnification factor for titles.
 */
define('HEAD_MAGNIFICATION', 1.1);

/**
 * Height of cell respect font height.
 */
define('K_CELL_HEIGHT_RATIO', 1.25);

/**
 * Title magnification respect main font size.
 */
define('K_TITLE_MAGNIFICATION', 1.3);

/**
 * Reduction factor for small font.
 */
define('K_SMALL_RATIO', 2/3);

/**
 * Set to true to enable the special procedure used to avoid the overlappind of symbols on Thai language.
 */
define('K_THAI_TOPCHARS', true);

/**
 * If true allows to call TCPDF methods using HTML syntax
 * IMPORTANT: For security reason, disable this feature if you are printing user HTML content.
 */
define('K_TCPDF_CALLS_IN_HTML', false);

/**
 * List of TCPDF methods that are allowed to be called using HTML syntax.
 * Note: each method name must end with surrounded with | (pipe) character.
 * The constant K_TCPDF_CALLS_IN_HTML must be set to true.
 * IMPORTANT: For security reason, disable this feature if you are allowing user HTML content.
 */
define('K_ALLOWED_TCPDF_TAGS', '');

/**
 * If true and PHP version is greater than 5, then the Error() method throw new exception instead of terminating the execution.
 */
define('K_TCPDF_THROW_EXCEPTION_ERROR', false);

/**
 * Default timezone for datetime functions
 */
define('K_TIMEZONE', 'UTC');

//============================================================+
// END OF FILE
//============================================================+
