<?php
	$date	=	date("d.m.y");
	$dn		=	date("l");
	
	$link = mysqli_connect("localhost", $_SESSION['idsess'], $_SESSION['hashpasswd'], $dbname);
	if (mysqli_connect_errno()) {
					printf("Соединение не удалось: %s\n", mysqli_connect_error());
					exit();
				}	
	mysqli_query($link,'SET NAMES utf8') or exit('SET NAMES Error');
	
	function drawTable($inputName, $inputIndex, $inputTable, $inputFilter){
		if(count($inputTable[0]) == count($inputTable[1])){
			print "\n
			<table class=\"php-table\">\n
				<tr>\n
					<th class=\"choice\">\n
						&nbsp;
					</th>\n";
			
					// Вывод заголовков
					$end	=	count($inputTable[0]);
					for ($i = 0; $i < $end; $i++){
						print "\n
						<th>\n
						".$inputTable[1][$i].
						"</th>\n";
					}
						
				print "\n
				</tr>\n";
				
				$filter[0]	=	NULL;
				$filter[1]	=	NULL;
				
				if($inputFilter != NULL){
					$filter[0]	=	"WHERE ".$inputFilter;
					$filter[1]	=	"and ".$inputFilter;
				}
				
				$link	=	mysqli_connect ("localhost",$_SESSION['idsess'],$_SESSION['hashpasswd'],"db_zv");
				if (mysqli_connect_errno()) {
					printf("Соединение не удалось: %s\n", mysqli_connect_error());
					exit();
				}	
				
				mysqli_query($link,'SET NAMES utf8') or exit('SET NAMES Error');
		
				// Выполнение SQL-запросов для отображения содержимого таблицы
				$query_content	=	mysqli_query($link,
					"SELECT 
						* 
					FROM 
						".$inputName."
					".$filter[0]."
					ORDER BY 
						".$inputIndex
					) or die("Query (Output_records_from_table [".$inputName."]) failed : " . mysqli_error($link));
				
				while ($line_content	=	mysqli_fetch_array($query_content, MYSQLI_NUM)){	// Вывод содержимого таблицы
					print "\n
					<tr>\n
						<td>\n
							<input type=\"radio\" name=\"chooseRow\" value=\"".$line_content[0]."\"/>\n
						</td>\n";
						
						$j	=	0;	//номер запрашиваемого столбца
						for ($i	=	0; $i < $end; $i++){
							print "\n
							<td>\n"
								.$line_content[$inputTable[0][$i]].
							"</td>\n";
						}
					print "\n
					</tr>\n";
				}
			print "\n
			</table>\n";	
		}	else	{
			die("Error! Count \"Table headers\" not equal \"Table column\"");
		}
	}
	
	
	
		
	function drawEditorForm($inputFields){
		print "\n
		<form method=\"post\" action=\"\">\n
			<table>\n
				".$inputFields."
			</table>\n
			
			<p>\n
				<br>\n
				<input type=\"submit\" name=\"submit\" value=\"Отправить\"/>\n
				<input type=\"reset\" name=\"reset\" value=\"Сброс\"/>\n
			</p>\n
		</form>\n
		
		<br/>\n";
	}	
	
	function editorList($inputTable, $fieldName, $type, $startValue, $propertyLest){
		if($propertyLest == 1){
			$propertyLest	=	'required';
		}
		$result="
			<tr>\n
				<td>\n
					".$fieldName[2]."
				</td>\n
				
				<td>\n
					<select name=\"".$fieldName[1]."_".$type."\" ".$propertyLest.">\n";
		$link = mysqli_connect("localhost", $_SESSION['idsess'], $_SESSION['hashpasswd'], "db_zv");
		if (mysqli_connect_errno()) {
					printf("Соединение не удалось: %s\n", mysqli_connect_error());
					exit();
				}	
	mysqli_query($link,'SET NAMES utf8') or exit('SET NAMES Error');			
		$query_content	=	mysqli_query($link,
			"SELECT 
				".$fieldName[0]."
			FROM 
				".$inputTable."
			 ".$fieldName[3]	
			) or die("Query_content failed : " . mysqli_error($link));
		
		if($type == 'add'){
			$startYes	=	1;
		}	else	{
			$startYes	=	0;
		}
						
		while ($line_content	=	mysqli_fetch_array($query_content, MYSQLI_NUM)){
			if($startYes == 0){
				if($startValue == $line_content[0]){
					$result	=	$result."<option selected=\"selected\">\n";
				}	else	{
					$result	=	$result."<option>\n";
				}
				$result	=	$result.$line_content[0]."</option>";
			}	else	{
				$result	=	$result."
					<option>\n"
						.$line_content[0].
					"</option>";
			}
		}								
		
		$result	=	$result."</select>\n
				</td>
			</tr>\n";
		return $result;
	}
		 
	function editorField($fieldType, $inputTable, $inputFields, $type, $startValue, $property, $fields){
		$field = $fields[$inputTable[0]];
		$result	=	"
			<tr>
				<td>
					".$inputTable[1]."
				</td>
				
				<td>";
				
		if($type == 'add'){
			$startValue[$inputTable[0]]	=	'';
		}
		
		if($property == NULL){
			$property	=	'';
		}
		
		$result	=	$result."
					<input type=\"".$fieldType."\" size=\"50%\" name=\"".$field."_".$type."\" value=\"".$startValue[$inputTable[0]]."\" ".$property."/>
				</td>
			</tr>\n";
		
		return $result;
	}
	
	function haveForeighTables($inputTable, $inputField, $inputKey){
		$query_have	=	mysqli_query($link,
			"SELECT 
				* 
			FROM 
				".$inputTable."
			WHERE
				".$inputField." = ".$inputKey
			) or die("query_haveForeigh_in_".$inputTable." failed : " . mysqli_error($link));
		
		return mysqli_num_rows($query_have);
	}
	
		$sql = "SHOW COLUMNS FROM $tblname";
		$list_of_fields = mysqli_query($link,$sql)
		or die("Query1 failed : " . mysqli_error($link));
		if (mysqli_num_rows($list_of_fields) > 0) {
  		  while ($row = mysqli_fetch_assoc($list_of_fields)) {
        	$fields[] = $row["Field"]; // Получение списка заголовков столбцов таблицы
		  }
		}

	$columns	=	count($fields); // Получение количества столбцов таблиц
								
?>