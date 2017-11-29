<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
 
class mpdf_lib {
    
    function pdf()
    {
        $CI = & get_instance();
        log_message('Debug', 'mPDF class is loaded.');
    }
 
    function load($mode = '',$format = '',$default_font_size=0,$default_font='',$l=15,$r=15,$t=10,$b=10,$margin_header=9,$margin_footer=9,$orientation='L')
    {
		include_once APPPATH.'/third_party/mpdf/mpdf.php';
		
		return new mPDF($mode,$format,$default_font_size,$default_font,$l,$r,$t,$b,$margin_header,$margin_footer,$orientation);
    }
}

?>