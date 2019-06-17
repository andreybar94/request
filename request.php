<?php
		error_reporting(E_ALL);
		session_start();

		if (!((isset($_SESSION['idsess'])) && (isset($_SESSION['hashpasswd'])))){
			header('Location: adm.php');
			exit();
		}	else	{
			$succsess	=	true;
			$hello		=	$_SESSION['name']." ".$_SESSION['surname'];
			$exitlink	=	"<a class=\"exitlink\" href=\"exitsess.php\">Выход</a>";
		}
		$tblname	=	"request";		// Объявление имени таблицы
		$tblindex	=	"model"
			.	","	.	"name"
			.	","	.	"surname"
			.	","	.	"number"
			.	","	.	"date_start"
			.	","	.	"date_finish"
			.	","	.	"state"
			
		;	// Объявление индексируемого поля
		$tblKeyField	=	"ID_request";	// Объявление ключевого поля
		require "parametrs.php";
		
		$tableColumns	=	array(
			1,
			2,
			3,
			4,
			5,
			6,
			7
		);
		
		$tableHeaders	=	array(
			'Модель запчасти',
			'Название магазина',
			'Сотрудник',
			'Количество единиц',
			'Дата подачи',
			'Примерная дата исполнения',
			'Статус'
		);
		
		require "functions.php";
		
		//Обработка таблицы

		// Добавление записи в таблицу
		if (isset($_POST['model_add']) && isset($_SESSION['surname'])){
			$_POST['model_add']		=	trim(htmlspecialchars(stripslashes($_POST['model_add'])));
			$_POST['number_add']	=	trim(htmlspecialchars(stripslashes($_POST['number_add'])));
			if ((!empty($_POST['model_add'])) && (!empty($_SESSION['surname']))){
				// Проверка на присутствие аналогичной записи в таблице
				$query_record_add	=	mysqli_query($link,
					"select addRequest (
						'".$_POST['model_add']."',
						'".$_SESSION['surname']."',
						'".$_POST['number_add']."'
					);"
					) or die("query_record_add failed: " . mysqli_error($link));
					
				$query_result = mysqli_fetch_array($query_record_add, MYSQLI_NUM);

				if ($query_result[0] == 0){
					$info[]	=	'Такая запись уже существует!';
				}	elseif ($query_result[0] == 1){
					$info[]	=	'Запись успешно добавлена';
				}
				
			}	else	{
				$info[]	=	'Не заполнены поля!';
			}
		}

		// Изменение записи
		if (isset($_POST['state_upd']) && isset($_POST['name_upd'])){
			$_POST['name_upd']	=	trim(htmlspecialchars(stripslashes($_POST['name_upd'])));
			$_POST['date_start_upd']	=	trim(htmlspecialchars(stripslashes($_POST['date_start_upd'])));
			$_POST['date_finish_upd']	=	trim(htmlspecialchars(stripslashes($_POST['date_finish_upd'])));
			$_POST['state_upd']	=	trim(htmlspecialchars(stripslashes($_POST['state_upd'])));
			if ((!empty($_POST['state_upd'])) && (!empty($_POST['name_upd']))){
				// Проверка на присутствие аналогичной записи в таблице

				$query_record_upd	=	mysqli_query($link,
					"select updRequest (
						'".$_POST['name_upd']."',
						'".$_POST['date_start_upd']."',
						'".$_POST['date_finish_upd']."',
						'".$_POST['state_upd']."',
						'".$_SESSION['updateRow']."'
					);"
					) or die("query_record_add failed: " . mysqli_error($link));
					
				$query_result = mysqli_fetch_array($query_record_upd, MYSQLI_NUM);	
							if ($query_result[0] == 0){
							$info[]	=	'Запись успешно удалена';
								}
				
				mysqli_free_result($query_record_upd);
			}	else	{
				$info[]	=	'Не заполнены поля!';
			}
		}
	?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
	<head>
		<title>Система приема заявок</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
		<meta name="description" content="База данных"/>
		<meta name="keywords" content="База данных, PHP, MySQL, web-программирование"/>
		<link href="css/style.css" rel="stylesheet">
	</head>
	<body>
		<header class="main-header">
            <ul class="user-navigation">
                <li class="main-header-logo">
                	<img src="img/logo.png" alt="Логотип ОАО СаянскХимПласт">
                </li>
                <li class="login-link">
                	<?php
						if ($succsess == true){	
							print $hello." ".$exitlink." ";
						} else	{
							echo "Вы не авторизованы";
						}
					?>				
				</li>
				<li class="date">
					<?php
						echo "Сегодня ".$date.", ".$dn;
					?>	
				</li>
            </ul>
            <nav class="main-navigation">
            	<ul class="site-navigation">
                	<li>
                    	<a href="index.php">Главная</a>
                	</li>
                	<li>
                    	<a href="adm.php">Администрирование</a>
                	</li>
            	</ul>
        	</nav>
    	</header>
    	
    	<main class="container">
			<h1>
				<?php
					print "\"".$getTableName[$tblname]."\"";
				?>
			</h1>
			
			<?php
				if (isset($_POST['input'])){
						if ($_POST['input'] == 'del'){ // Удаление
						if (!isset($_POST['chooseRow'])){
							$info[]	=	'Укажите запись!';
						}	else	{	
							$query_record_del	=	mysqli_query($link,
								"Select delRequest (".$_POST['chooseRow'].")"
							) or die("Query failed : " . mysqli_error($link));
							
							$query_result = mysqli_fetch_array($query_record_del, MYSQLI_NUM);
							
							if ($query_result[0] == 0){
							$info[]	=	'Запись успешно удалена';
								}
						}
					}	else	{
						if ($_POST['input'] == 'add'){ // Добавление		
							drawEditorForm(
							editorList('hardware', 
									array(
										'model',
										'model',
										$tableHeaders[0],
										'order by	model'
									), 
									$_POST['input'],
									NULL,
									1
								)
								.editorField('text', array($tableColumns[3], $tableHeaders[3]), $list_of_fields, $_POST['input'], NULL, 'required', $fields)
							);
						
						}	elseif ($_POST['input'] == 'upd'){ // Изменение			
						
							if (!isset($_POST['chooseRow'])){
								$info[] = 'Укажите запись!';
							}	else	{
								$_SESSION['updateRow'] = $_POST['chooseRow']; // Запоминание ряда

								// Выполнение SQL-запроса для ввода в форму содержимого изменяемого ряда
								$query_form_upd	=	mysqli_query($link,
									"SELECT 
										* 
									FROM 
										".$tblname." 
									WHERE 
										".$tblKeyField." = ".$_POST['chooseRow']
									) or die("Query failed : " . mysqli_error($link));
								$line_content = mysqli_fetch_array($query_form_upd, MYSQLI_NUM);
								
								mysqli_free_result($query_form_upd);
								
							drawEditorForm(
								editorList('shop', 
									array(
										'name',
										'name',
										$tableHeaders[1],
										'order by	name'
									), 
									$_POST['input'],
									NULL,
									1
								)
								.editorField('text', array($tableColumns[4], $tableHeaders[4]),$list_of_fields, $_POST['input'], NULL, 'required',$fields)
								.editorField('text', array($tableColumns[5], $tableHeaders[5]), $list_of_fields, $_POST['input'], NULL, 'required',$fields)
								.editorField('text', array($tableColumns[6], $tableHeaders[6]), $list_of_fields, $_POST['input'], NULL, 'required',$fields)
							);
							}
						}
					}
				}
			?>
		
			<!-- Панель для выбора действия над таблицей -->
			<form method="post" action="">
				<table border="0" align="center">
					<tr>
						<td>
							<input class="buttons add" type="submit" name="input" value="add"/></td>
						<td>
							<input class="buttons upd" type="submit" name="input" value="upd"/></td>
						<td>
							<input class="buttons del" type="submit" name="input" value="del"/></td>
					</tr>
				</table>

				<?php
					drawTable($tblname,	$tblindex,	array($tableColumns, $tableHeaders),	'ID_request  != -1');
				?>

			</form>
			
			<br/>
			<p class="error">
				<!-- Вывод информации об ошибках -->
				<?php
					// Функция implode перечисляет элементы массива через любой разделитель
					echo implode('
					<br/>',
						$info)."\n";
				?>
			</p>
			
		</main>

		<footer class="main-footer">
			<p>По любым вопросам: <a href="mailto:andreybar94@mail.ru">andreybar94@mail.ru</a></p>
		</footer>

	</body>
</html>