<?php

		error_reporting(E_ALL);
		session_start(); 
			
		if ((isset($_SESSION['idsess'])) && (isset($_SESSION['hashpasswd']))){
			header('Location: base.php');
			exit();
		}
		
		
		$login	=	!empty($_POST['login']) ? $_POST['login'] : null;   
		$passwd	=	!empty($_POST['passwd']) ? $_POST['passwd'] : null;  

		
		$info	=	array();		
			 
		$date	=	date("d.m.y"); 
		$dn		=	date("l"); 		

		if (!empty($_POST['ok'])){ // Если кнопка Отправить была нажата
			if(!$login){
				$info[]	=	'Нет имени пользователя.'; 
            }
			if(!$passwd){
				$info[]	=	'Не введен пароль.';
			}
			
			if (count($info) == 0){		// Если замечаний нет и все поля заполнены
			/*
				//защита от SQL-инъекций и вредоносного кода 
			*/
				$login	=	substr($login,0,50);
				$login	=	htmlspecialchars(stripslashes($login));
				$passwd	=	substr($passwd,0,50);
				$passwd	=	htmlspecialchars(stripslashes($passwd));
		

				$link	=	mysqli_connect ('localhost','root','',"db_users");
				if (mysqli_connect_errno()) {
					printf("Соединение не удалось: %s\n", mysqli_connect_error());
					exit();
				}
				mysqli_query($link,'SET NAMES utf8') or exit('SET NAMES Error');	//Отправляем и принимаем данные в UTF-8
			
				$hash_val	=	md5($passwd); // шифрование введенного пароля для сравнения с табличными данными
			
				
				$query1	=	mysqli_query($link,
					"select 
						userid 
					from 
						user_autentificate
					where
						username 		=	'$login' 
						and	password	=	'$hash_val'"	
					) or die("Query1 failed : " . mysqli_error($link));
				
				if (mysqli_num_rows($query1) != 1){			// mysqli_num_rows возвращает количество рядов результата запроса
					$info[]	=	'Доступ запрещен.';
				}	else	{	
					mysqli_field_seek($query1, 0);
					
					$row = mysqli_fetch_row($query1);	// Находим идентификатор пользователя из возвращенного запроса
					$userid	= $row[0];	

					/*
						логин и пароль пользователя записывается в суперглобальный массив _SESSION
						в качестве идентификатора сессии для доступа к базам mySQL
					*/
					$_SESSION['idsess']		=	$login; 
					$_SESSION['hashpasswd']	=	$hash_val;
				
					$link2	=	mysqli_connect ("", "$login", "$hash_val","db_zv"); 	
					if (mysqli_connect_errno()) {
					printf("Соединение не удалось: %s\n", mysqli_connect_error());
					exit();
				}
					mysqli_query($link2,'SET NAMES utf8') or exit('SET NAMES Error');
				
					$query2	=	mysqli_query($link2,
						"select
							name,
							surname 
						from 
							employee
						where
							ID_employee	=	'$userid'"	
						) or die("Query2 failed : " . mysqli_error($link2));
					if (mysqli_num_rows($query2) != 1){
						$info[]	=	'Ошибка в базе.';
					} else{ // Если данные найдены
						while ($row = mysqli_fetch_array($query2, MYSQLI_BOTH)){
							$_SESSION['name']		=	$row["name"];
							$_SESSION['surname']	=	$row["surname"];
						}
						header('Location: base.php');	
						exit();
					}
					mysqli_free_result($query2);	// Освобождние памяти от результата
					mysqli_close($link2); 		
				}
				mysqli_free_result($query1);	
				mysqli_close($link);	
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
    		<h1>Вход для администраторов</h1>
    		<p class="center-element">
    			Введите следующие данные:
    		</p>
    		<form method="post" action="">
    			<table border="0" align="center">
    				<tr>
    					<td align="right">Логин:</td>
    					<td><input type="text" size="30" name="login"/></td>
    				</tr>
    				<tr>
    					<td align="right">Пароль:</td>
    					<td><input type="password" size="30" name="passwd"/></td>
    				</tr>
    			</table>
    			<br/>
    			<p class="center-element">
					<input type="submit" value="Войти!" name="ok"/>
					<input type="reset" value="Очистить"/>
				</p>
			</form>
			<p class="error">
					<!-- Вывод информации об ошибках -->
					<?php
						// Функция implode перечисляет элементы массива через любой разделитель
					echo implode('<br/>', $info)."\n";
					?>
			</p>
			<br/>
		</main>
		<footer class="main-footer">
			<p>По любым вопросам: <a href="mailto:andreybar94@mail.ru">andreybar94@mail.ru</a></p>
		</footer>
	</body>
</html>	
