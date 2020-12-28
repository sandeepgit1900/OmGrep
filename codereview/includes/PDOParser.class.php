<?php
class PDOParser
{
	private $bForDebug = true;
	private $bScanForNonPdo = true;
	private $sqlRegularExp = "/[\'\"]\s*(select|update|insert|delete|alter)\s+/i";
	/*
	Function for baking query fron the given block of code
	@param
	$szBlock : String continig chunk of code 
	$szVar_name : Variable Name For which we have to bake query

	*/
	private function BakeQuery(&$szBlock,$szVar_name)
	{
		//Look for function Definition Scope
		if($iScope = strripos($szBlock,"function"))
		{
			//Starting position of scope
			$szBlock = substr($szBlock,$iScope);
			$iScope = stripos($szBlock,"{");
			
			$szBlock = substr($szBlock,$iScope+1);
			$szBlock = ltrim($szBlock);
		}	

		//Search for all occurence of $szVar_name in given block
		$start = strstr($szBlock,$szVar_name);
		
		
		$statements = explode(";",$start);
		
		if(count($statements) === 1)
		{
			// If not occurrence of Varibale Name exist then return
			return;	
		}

		$sql_statement = array();
		$count =0;
		
		//Fill Sql statement array
		foreach($statements as $val)
		{	

			if($str = strstr($val,$szVar_name))
			{
				$sql_statement[$count]= $str;	
				$count++;
			}
		}
		//Now Bake Query
		$query = "";

		foreach($sql_statement as $qu)
		{
			$iEqual_pos = stripos($qu,"=");
			$iDot_pos  = stripos($qu,".");

			if(($iEqual_pos and $iDot_pos == false))
			{
				if(($ch = substr($qu,$iEqual_pos+1,1)) == "=")
                                        continue;
                                $query = $query . substr($qu,$iEqual_pos+1);
			}

			$recStat = explode(".",$qu);
			
			if(count($recStat)>1 and ($sym = substr($recStat[1],0,1)) and $sym != "="){
				$iCtr =0;
				$recStat[0] = strstr($recStat[0],'"') . ".";
			}
			else
				$iCtr =1;
			for(;$iCtr < count($recStat);$iCtr++)
			{
				$exp = $recStat[$iCtr];
				trim($exp);
				$iOffSetPos = 0;
				
				if(($pos = stripos($exp,"=")) !== false and $pos === 0 )
				{
					if(($ch = substr($exp,$pos+1,1)) == "=")
        	                                continue;
					$iOffSetPos = $pos+1;
				}
					
				$query = $query . substr($exp,$iOffSetPos);
			}
		}
		
		return ltrim($query);
	}
	
	/*
	Helper Function to Extract Variable Name of Kind :X1, :X2 from SQL Query
	getVariableName()
	@param IN
	$szQuery : Sql Query or string contining variable name
	$iSt	 : Integer representing starting position of variable name
	OUT
	Variable Name
	*/
	private function getVariableName(&$szQuery,$iSt)
	{
		
		//search for end token like "}" , "," , " "
		$arrToken = array(")",","," ",'"',"=","'");
		$iVar_EndPos = 0;
		$iSt_Pos = $iSt;

		//Loop through string character by character
		while($iSt_Pos < strlen($szQuery))
		{
			$ch = substr($szQuery,$iSt_Pos,1);
			if(in_array($ch,$arrToken))
			{
				$iVar_EndPos = $iSt_Pos;
				break;
			}
			++$iSt_Pos;
		}
		if($iVar_EndPos !== 0)
		{
			return substr($szQuery,$iSt,($iVar_EndPos - $iSt));
		}
	}
	
	/*
	Parse SQL Query 
	parseSQLQuery()
	@param IN
	$szBlock : Block contining all bindValue and execute command for correponding Query
	$szQuery : SQL Query
	OUT
	Status of SQL Query. Like Vulnerable Query, Syntax Error, bindValue Statement Missing, Successful Query
	*/
	private function parseSQLQuery(&$szBlock,&$szQuery,&$szResMsg)
	{
		if(strlen($szQuery) <5)
			return -1;
		//IF SELECT Query then SKIP Query till FROM
		if(false !== stripos($szQuery,"SELECT"))
		{
			$szQuery = substr($szQuery,stripos($szQuery,"FROM"));
		}

		if(false !== stripos($szQuery,"$"))
		{
			//Throw Warning or Log
			//Vulnerability Error : Sql Injection Vulnerable Query!!.
			
			$szResMsg = "<strong>Vulnerability Error :</strong> Sql Injection Vulnerable Query!!.<br>";
			if($this->bForDebug)
				echo $szResMsg;
			
			return 1; //	return false;
		}

		//Count the occurrence of ":VAR" in query or push variable name in an array and
		//Search for their corresponding bind command
		$iSize_blk = strlen($szBlock);		
		$iSt_offset = 0;
		$arrVar_name = array();
		$iCount =0;
		$arrDupVaribale = array();
		$bDuplicateStatement = false;
		while($iSt_offset < $iSize_blk)
		{
			$iPos = stripos($szQuery,":",$iSt_offset);

			if($iPos === false)
			{
				break;
			}

			// Get Variable Name 
			$szTempName = self::getVariableName($szQuery,$iPos);
			
			if(in_array($szTempName,$arrVar_name) === FALSE){
				$arrVar_name[$iCount] = $szTempName;
				++$iCount;
			}
			else
			{
				//Duplicate Variable Name in same query
				$bDuplicateStatement = true;
				$arrDupVaribale[] = $szTempName;
			}
			$iSt_offset = $iPos+1;
		}

		//Now explode block for all bind commands
		//and now search var_name 
//		$arrBindStatements = explode("->bindValue(",$szBlock);
		
		$iType = stripos($szBlock,"bindValue");
		$szBind = "->bindValue(";

		if($iType == false)		
		{
			$szBind = "->bindParam(";
		}
		
		$arrBindStatements = explode($szBind,$szBlock);
		
		$iNo_Bind_Cmd = count($arrBindStatements);
		$iNo_Variable = count($arrVar_name);

		//if no variable is 0 but number of bind statements are > 0 
		//then check for ? 

		if($iNo_Variable === 0 and $iNo_Bind_Cmd > 1 )
		{
			//		echo "<br><br>HI<br>";
			$iSt_offset = 0;	
			while($iSt_offset < $iSize_blk)
			{
				$iPos = stripos($szQuery,"?",$iSt_offset);
				if($iPos === false)
				{
					break;
				}
				++$iNo_Variable;
				$iSt_offset = $iPos+ 1;
				//var_dump($szQuery);
			}
			if($iNo_Variable === $iNo_Bind_Cmd-1)
			{	
				$szResMsg = "<strong>Query Successfully Parsed.</strong> <br>";
				if($this->bForDebug)
					echo $szResMsg;
				return 200;//return true;
			}	
		}

//		var_dump($iNo_Bind_Cmd);
//		var_dump($iNo_Variable);
		if(($iNo_Bind_Cmd-1) !== ($iNo_Variable) and $bDuplicateStatement===false)
		{
			//Throw Error or Log Error
			//Number of sql varibale are not equal to Number of Bind statements
			$szResMsg = "<strong>Syntax Error :</strong> Number of sql varibale are not equal to Number of Bind statements<br>";
			if($this->bForDebug)
				echo $szResMsg;
			return 2;//return false;
		}	
		

		$iUniqueEle = 0;
		$arrBindParam = array();
		$NumState = count($arrBindStatements);	
		for($iCtr=1;$iCtr<$NumState;$iCtr++)
		{
//			$arrBindParam[$iCtr-1] = self::getVariableName($arrBindStatements[$iCtr],1);
			
			$colonPos = stripos($arrBindStatements[$iCtr],":");
			$sym = substr($arrBindStatements[$iCtr],$colonPos+1,1);
			if($sym == ':' || $sym == ":")
				continue;
			$arrBindParam[$iCtr-1] = self::getVariableName($arrBindStatements[$iCtr],$colonPos);
		}	

		asort($arrVar_name,SORT_STRING);
		asort($arrBindParam,SORT_STRING);
		//var_dump($arrVar_name);
		//var_dump($arrBindParam);
		
		if(count($arrVar_name) !== count($arrBindParam))
		{
			//
			$szErrorMsg = "<strong>Syntax Error :</strong> Missing Binding of some SQL variables :: ";
			foreach($arrVar_name as $val)
				$szErrorMsg .= " $val ";
			$szErrorMsg .=  "<br>";
			if($this->bForDebug)
				echo "$szErrorMsg";
			$szResMsg = $szErrorMsg;

			return 3;//	return false; 
		}
		
		foreach($arrVar_name as $ele)
		{
			$bFound =false;
			foreach($arrBindParam as $ele1)
			{
				if(strcmp($ele,$ele1)===0)
				{
					$bFound = true;
					break;
				}
			}
			if($bFound==false)
			{
				$szResMsg = "<strong> Syntax Error $ele </strong> bindValue() Statement does not exist. <br>";

				if($this->bForDebug)
					echo $szResMsg;

				return 4;//return false;
			}

		}	
		$szResMsg = "<strong>Query Successfully Parsed.</strong> <br>";
		if($this->bForDebug)
			echo $szResMsg;
		//echo "<strong>Query Successfully Parsed.</strong> <br>";
		return 200;//return true;
	}
	
	/**
	 * Print Msg
	 * 
	 */
	private function PrintMsg($iMsgNum)
	{
		switch($iMsgNum)
		{
			case 1:
				echo " \" Vulnerability Error : Sql Injection Vulnerable Query!!. \"<br>";
				break;
			case 200:
				echo " Query Successfully Parsed.  <br>";
				break;
			case 2:
				echo " Syntax Error : Number of sql varibale are not equal to Number of Bind statements<br>";
				break;
			case 3:
				echo " Syntax Error : Missing Binding of some SQL variables :: ";
				break;
			case 4:
				echo " Syntax Error $ele  bindValue() Statement does not exist. <br>";
				break;
		}
	}	
	/*
	Helper Function to Remove Comments from File
	*/
	private function removeCommentFromFile(&$szFileContent)
	{
		$newStr  = '';

		$commentTokens = array(T_COMMENT);

		if (defined('T_DOC_COMMENT'))
			$commentTokens[] = T_DOC_COMMENT; // PHP 5
		if (defined('T_ML_COMMENT'))
			$commentTokens[] = T_ML_COMMENT;  // PHP 4

		$tokens = token_get_all($szFileContent);

		foreach ($tokens as $token) {    
			if (is_array($token)) {
				if (in_array($token[0], $commentTokens))
					continue;

				$token = $token[1];
			}

			$newStr .= $token;
		}
		$szFileContent = $newStr;
	}
	
	/*
	Main Function For Parsing File PDO Vulnerability issues
 	@param IN
	$path - File Path
	*/
	private function parseFile_ForPDO($path)
	{
//var_dump($path);
		$file_content = file_get_contents($path);
		if($file_content == false or $file_content == FALSE){
			//echo "Empty File";
			return;
		}

		//Remove Comment from File
		self::removeCommentFromFile($file_content);
		if(stripos($file_content,"->prepare(") === false ||  count(preg_split($this->sqlRegularExp,$file_content)) === 1  )
		{
	            if($this->bScanForNonPdo)
        	        self::parseFile_ForNonPDO($path,true);
            
			return ;
		}

		if($this->bForDebug)
			echo "<h3><strong> $path</strong></h3>";

		$bPrintFileName = false;
		$flag = false;
		$file_size = strrpos($file_content,"}");//last position of '{'	
		$offset = 0;	
		while($offset<$file_size)
		{	
			$pos = stripos($file_content,"->prepare(",$offset);

			if($pos === false)
				break;

			$pos+= 10;// Adding 10 for skiping call of strlen("->prepare("); which will output 10 always
			
			$sym = substr($file_content,$pos ,1);
			$diff = $pos - $offset;
			$blk = substr($file_content,$offset,$diff);
			$query = "";
		
			if($sym === "$" )
			{	
                //$var_name = strstr(strstr($file_content,"->prepare("),"$");
                $var_name = substr($file_content,$pos,50);
				$var_name = substr($var_name,0,stripos($var_name,");"));
				$query = self::BakeQuery($blk,$var_name);
			}			
			elseif($sym !=="\\") // SQL Statement in pdo->prepare() 
			{
				$iEndPos = stripos($file_content,");",$pos);
				$query = substr($file_content,$pos,$iEndPos-$pos);
			}
			else
			{
				return;
			}
			//IF Baked Query or Actual Query have length less then 5 then don't parse this query
			// 
			if(strlen($query) <5)
			{
				$offset +=$diff;
				continue;	
			}
			
			//echo "<strong>SQL Query :</strong> $query. ";
			if($this->bForDebug)
				echo "<strong> \" SQL Query : \" ` \" </strong> $query .\" `";
			
			//Block of code contining BindValue and Excute 
			$iExcuteCmd_Pos = stripos($file_content,"->execute(",$pos);
			$iExcuteCmd_Pos = stripos($file_content,";",$iExcuteCmd_Pos);
			$szBind_Excute_Blk = substr($file_content,$pos,($iExcuteCmd_Pos-$pos));
			//End of Block
			$szOutMsg = "";
			$res = self::parseSQLQuery($szBind_Excute_Blk,$query,$szOutMsg);
			$offset += $diff;
			if($res >0 && $res != 200 and $flag == false)
				$flag = true;
			if($res >0 && $res !=200 && !$this->bForDebug)
			{
				if($bPrintFileName == false)
				{
					$bPrintFileName = true;
					echo "<br><strong>`$path`</strong><br>";
				}

				
	
			
				echo "<strong> SQL Query : </strong> ` " . $query . " ` $szOutMsg `";
				//self::PrintMsg($res);
			}

		}
		if($this->bScanForNonPdo)
			self::parseFile_ForNonPDO($path);
		if($flag)
			return 1;
		else
			return 0;

	}
	
	/*
	Main Function For Parsing File For Non PDO Sqls
 	@param IN
	$path - File Path
	*/
	private function parseFile_ForNonPDO($path,$bPrintFileName=false)
	{
		$file_content = file_get_contents($path);
		if($file_content == false or $file_content == FALSE){
			//echo "Empty File";
			return;
		}

		//Remove Comment from File
		self::removeCommentFromFile($file_content);
		//~ if($this->bForDebug)
			//~ echo "<h3><strong> $path</strong></h3>";
		
		
		$arrSqlQuery =  preg_split($this->sqlRegularExp,$file_content);
		if(count($arrSqlQuery)>1)
		{
			$aboveScope = $arrSqlQuery[0];
			unset($arrSqlQuery[0]);
		}
		else
		{
			return false;
		}
		if($bPrintFileName)
                	echo "<h3><strong> $path</strong></h3>";	
		$arrBadPractise = array();
		foreach($arrSqlQuery as $key=>$query)
		{
			$pos = stripos($query,"->prepare(");
			if($pos !== false)
			{
				//PDO Used
				continue;
			}
			else
			{
				$arrBadPractise[] = $query;
			}
		}
		
		
		if(count($arrBadPractise))
		{
			//List all Bad Practise Code Where Pdo Not Used
			echo "<h4><strong>PDO Not used in ", count($arrBadPractise), " queries, which are as follows : </strong><br></h4>";
			$arrQuery = array();			
			$count = 0;
			foreach($arrBadPractise as $key=>$query)
			{
				$str = substr(stristr($file_content,$query,true),strlen($str)-100);
				
				//$arrStat = preg_split('/[\s*w+]\s*=/i',$str);
				$arrStat = explode('=',$str);
				$sqlQuery = $arrStat[count($arrStat)-1];
				
				$pos = stripos($query,";");
				$sqlQuery= $sqlQuery.substr($query,0,$pos);
				$arrQuery[] = $sqlQuery;
				echo ++$count,".  ",$sqlQuery," <br>";	
				
			}
		}	
		return;
	}
	/**
	 * Get Directory Content
	 */
	private function getDir($path='.',$level=0)
	{
		$ignore = array( 'cgi-bin', '.', '..' );
		$dh = opendir( $path );
		// Open the directory to the handle $dh 
		$count =0;
		while( false !== ( $file = readdir( $dh ) ) ){
			// Loop through the directory 
			//var_dump($file);
			if( !in_array( $file, $ignore ) ){

				$spaces = str_repeat( '&nbsp;', ( $level * 4 ) );
				// Just to add spacing to the list, to better 
				// show the directory tree. 

				if( is_dir( "$path/$file" ) ){
					// Its a directory, so we need to keep reading down... 

					//                    echo "<strong>$spaces $file</strong><br />";
					$count += self::getDir( "$path/$file", ($level+1) );
					// Re-call this same function but on a new directory. 
					// this is what makes function recursive. 

				} elseif($found = stristr($file,'.php') and  $len=strlen($found) and  $len==4 ) {

					//			echo " : $path    :  ";
					//           echo "$spaces $file<br />";
					// Just print out the filename 

					$filename = $path.'/'.$file;
					//	self::parseFile($filename);

					$count += self::parseFile_ForPDO($filename);
				}

			}

		}

		closedir( $dh );
		// Close the directory handle
		if($count) 
		{
			//echo "Count of File : $count <br>";
			return $count;
		}
		
	}

	/**
	 * Parse List of Files
	 * Read the give file and process  the all file specified 
	 * if relative path is given the specify the basepath for file
	 * and if directory is given the traverse the directory content
	 */
	public function parseFiles($InputFilePath,$basePath='')
	{
		//echo "List Of Files <br>";
		//To read list of file from a file
		$InputFilePath = trim($InputFilePath);
		$basePath = "/tmp/sandeep";
		//$basePath = "/home/kunal";
	
		if(is_dir($basePath))
		{
			chdir($basePath);
		}
//var_dump($InputFilePath);
//var_dump(is_dir($InputFilePath));
//var_dump(is_dir('/tmp/sandeep'));	
		$bfile_exists = file_exists($InputFilePath);
		
		if($bfile_exists && !is_dir($InputFilePath))
		{
			$file_content = file_get_contents($InputFilePath);
			if($file_content == false or $file_content == FALSE){
			echo "Empty File";
			return;
			}
			$count =0;
			$arrFileList = explode("\n",$file_content);
			foreach($arrFileList as $filePath)
			{
				if(strlen($filePath) && (stristr($filePath,".php") || stristr($filePath,".inc")))
				{
					++$i;
					$count += self::parseFile_ForPDO($filePath);
				}
				if(is_dir($filePath))
				{
					$count += self::getDir($filePath);
				}
			}
			
			//echo "<br> Number of files scanned <strong> $i </strong> <br>";
			return $count;
		}
		else if(is_dir($InputFilePath))
		{
			$count = 0;
			$count = self::getDir($InputFilePath);
			return $count;
		}

		return 0;
	}
}
?>
