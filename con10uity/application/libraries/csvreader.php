<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class CSVReader {

    var $fields;            /** columns names retrieved after parsing */ 
    var $separator = ';';    /** separator used to explode each line */
    var $enclosure = '"';    /** enclosure used to decorate each field */

    var $max_row_size = 4096;    /** maximum row size to be used for decoding */

    function get_csv_header($p_Filepath) {
    
        $file = fopen($p_Filepath, 'r');
        $this->fields = fgetcsv($file, $this->max_row_size, $this->separator, $this->enclosure);
        $keys_values = explode(',',$this->fields[0]);
		fclose($file);
		$content    =   array();
		$keys   =   $this->escape_string($keys_values);
		
		return  $keys;
    }
    
    function parse_file($p_Filepath) {
		ini_set("auto_detect_line_endings", "1");

        $file = fopen($p_Filepath, 'r');
        $this->fields = fgetcsv($file, $this->max_row_size, $this->separator, $this->enclosure);
        $keys_values = explode(',',$this->fields[0]);

        $content    =   array();
        $keys   =   $this->escape_string($keys_values);

        $i  =   1;
        while( ($row = fgetcsv($file, $this->max_row_size, $this->separator, $this->enclosure)) != false ) {            
            if( $row != null ) { // skip empty lines
                
                // convert , within " into some _COMMA_
                
                $encodedRow = '';
                $alphabets = str_split($row[0]);
                $openquot = false;
               
                foreach ($alphabets as $a) {
                	if($a=='"')
                	{
                		if($openquot==false)
                		{
                			$openquot=true;
                		}else {
	               			$openquot=false;
	            		}
                	}
                	
            		if($a==',' and $openquot==true)
            			$a = '__COMMA__';
            	
            		$encodedRow .= $a;
                }
                
                $values = explode(',',$encodedRow);
                //$values =   explode(',',$row[0]);
               	
               	if(is_array($values))
               	{
               		foreach ($values as $vk => $vv) {
               			$values[$vk] = str_replace('__COMMA__', ',', $vv);
               		}
               	}
               	
               //	echo count($keys)." - ".count($values).'<br />';
                if(count($keys) == count($values)){
                
                    $arr    =   array();
                    $new_values =   array();
                    $new_values =   $this->escape_string($values);
                    for($j=0;$j<count($keys);$j++){
                        if($keys[$j] != ""){
                            $arr[$keys[$j]] =   $new_values[$j];
                        }
                    }

                    $content[$i]=   $arr;
                    $i++;
                }
            }
        }
        
        fclose($file);
        
        return $content;
    }

    function escape_string($data){
        $result =   array();
        foreach($data as $row){
            $result[]   =   str_replace('"', '',$row);
        }
        return $result;
    }   
}
?>