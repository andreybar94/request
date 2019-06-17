<?php
		error_reporting(E_ALL); 
		session_start();
	
		if ((!isset($_SESSION['idsess'])) || (!isset($_SESSION['hashpasswd']))){
			header('Location: adm.php');
			exit();
		}	else{ 
			$succsess	=	true;
			$hello		=	$_SESSION['name']." ".$_SESSION['surname'];
			$exitlink	=	"<a class=\"exitlink\" href=\"exitsess.php\">Выход</a>";
		}
		
		$list = array();			// Массив для хранения списка таблиц
		$dbname = "db_zv";	// имя основной базы данных
		
		require "parametrs.php";
     
		$date	=	date("d.m.y");		
		$dn		=	date("l");
		
		$link	=	mysqli_connect ("localhost",$_SESSION['idsess'],$_SESSION['hashpasswd'],$dbname);
		if (mysqli_connect_errno()) {
					printf("Соединение не удалось: %s\n", mysqli_connect_error());
					exit();
				}
		mysqli_query($link,'SET NAMES utf8') or exit('SET NAMES Error');
		$sql = "SHOW TABLES FROM $dbname";
		$list_of_tables = mysqli_query($link,$sql)
		or die("Query1 failed : " . mysqli_error($link));
		while ($row = mysqli_fetch_row($list_of_tables)) {
		$list[] = $row[0];
		}
		mysqli_free_result($list_of_tables);
		
		mysqli_close($link);
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
        		<h1>Таблицы</h1>
        		<p class="centr">
        			Выберите таблицу:
        		</p>
        		<ol class="table-list">
        			<?php
        			$end=count($getTableName);
        			foreach ($list as $col_value){
        				if(isset($getTableName[$col_value])){
							print "<li><a href=\"".$col_value.".php\">".$getTableName[$col_value]."</a>\n";	// Формирмирование ссылки и её названия
						}
					}
					?>
				</ol>
				<br/>
		</main>
		
		<footer class="main-footer">
			<p>По любым вопросам: <a href="mailto:andreybar94@mail.ru">andreybar94@mail.ru</a></p>
		</footer>
	</body>
</html>