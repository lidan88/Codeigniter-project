<?

class Sql_lib
{
	var $sqlToRun; 
	public function __construct()
	{
		$this->sqlToRun = array();
	    $this->ci = &get_instance();
	    $this->ci->load->database();
	}
	
	//function sqlParser(){ $this->sqlToRun = array();}
	
	function parseFile($filename)
	{
		$fp = fopen($filename,"r");
		$sqlText = '';
		while($data = fgets($fp))
				$sqlText .= $data;
		fclose($fp);
		$this->fn_parse_queries($this->sqlToRun, $sqlText);
		$this->clean_parsed_query($this->sqlToRun);
		return $this->sqlToRun;
	}
	
	function processFile($filename,$database_name)
	{
		$this->ci->db->query("USE ".$database_name);
		$this->parseFile(APPPATH."schema/".$filename);
		foreach($this->sqlToRun as $k => $query)
		{
			//
			if(trim($query)!="")
			{
				//echo $query.'<br />';
				$this->ci->db->query($query);
			}
		}
	}
	
	function clean_parsed_query(&$ret)
	{
		foreach($ret as $k => $v)
		{
			$ret[$k] = preg_replace('/(\/\*.*\*\/)/','',str_replace("\r\n",'',$v));
		}
	}
	// Parameters:
	// @ret - reference to array with parsed queries
	// @sql - plain text data
	function fn_parse_queries(&$ret, $sql)
	{
		$sql_len      = strlen($sql);
		$char         = '';
		$string_start = '';
		$in_string    = FALSE;
		$time0        = time();
	
		$i = -1;
		while ($i < $sql_len) {
			$i++;
			if (!isset($sql[$i])) {
				return $sql;
			}
			$char = $sql[$i];
	
	
			// We are in a string, check for not escaped end of strings except for
			// backquotes that can't be escaped
			if ($in_string) {
				for (;;) {
					$i         = strpos($sql, $string_start, $i);
					// No end of string found -> add the current substring to the
					// returned array
					if (!$i) {
	//                    $ret[] = $sql;
						return $sql;
					}
					// Backquotes or no backslashes before quotes: it's indeed the
					// end of the string -> exit the loop
					else if ($string_start == '`' || $sql[$i - 1] != '\\') {
						$string_start      = '';
						$in_string         = FALSE;
						break;
					}
					// one or more Backslashes before the presumed end of string...
					else {
						// ... first checks for escaped backslashes
						$j                     = 2;
						$escaped_backslash     = FALSE;
						while ($i- $j > 0 && $sql[$i - $j] == '\\') {
							$escaped_backslash = !$escaped_backslash;
							$j++;
						}
						// ... if escaped backslashes: it's really the end of the
						// string -> exit the loop
						if ($escaped_backslash) {
							$string_start  = '';
							$in_string     = FALSE;
							break;
						}
						// ... else loop
						else {
							$i++;
						}
					} // end if...elseif...else
				} // end for
			} // end if (in string)
	
			// We are not in a string, first check for delimiter...
			else if ($char == ';') {
				// if delimiter found, add the parsed part to the returned array
				$ret[]      = substr($sql, 0, $i);
				$sql        = ltrim(substr($sql, min($i + 1, $sql_len)));
				$sql_len    = strlen($sql);
				if ($sql_len) {
					$i = -1;
				} else {
					// The submited statement(s) end(s) here
					return '';
				}
			} // end else if (is delimiter)
	
			// ... then check for start of a string,...
			else if (($char == '"') || ($char == '\'') || ($char == '`')) {
				$in_string    = TRUE;
				$string_start = $char;
			} // end else if (is start of string)
	
			// ... for start of a comment (and remove this comment if found)...
			else if ($char == '#' || ($i > 1 && $sql[$i - 2] . $sql[$i - 1] == '--')) {
				$sql = substr($sql, strpos($sql,"\n") + 1);
				$sql_len = strlen($sql);
				$i = -1;
			} // end else if (is comment)
		} // end for
	
		// add any rest to the returned array
		if (!empty($sql) && ereg('[^[:space:]]+', $sql)) {
			return $sql;
		}
		return '';
	}


}


?>