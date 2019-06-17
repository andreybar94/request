<?php
		error_reporting(E_ALL);
		session_start();
			
		if ((isset($_SESSION['idsess'])) && (isset($_SESSION['hashpasswd']))){
			$succsess	=	true;
			$hello		=	$_SESSION['name']." ".$_SESSION['surname'];
			$exitlink	=	"<a class=\"exitlink\" href=\"exitsess.php\">Выход</a>";
		} else{
			$succsess = false;
		}

		$date	=	date("d.m.y");
		$dn		=	date("l");
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
						echo "Сегодня ".$date.", ".$dn; // вывод даты и дня недели
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
			<h1>Система приема заявок</h1>
			<p>Система предназначена для автоматизации деятельности сотрудников Отдела Сервис Поддержки в части приема и обработки заявок на запчасти.</p>
		</main>
		<footer class="main-footer">
			<p>По любым вопросам: <a href="mailto:andreybar94@mail.ru">andreybar94@mail.ru</a></p>
		</footer>
	</body>
</html>